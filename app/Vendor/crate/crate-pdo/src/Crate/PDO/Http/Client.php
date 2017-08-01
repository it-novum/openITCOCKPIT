<?php
/**
 * Licensed to CRATE Technology GmbH("Crate") under one or more contributor
 * license agreements.  See the NOTICE file distributed with this work for
 * additional information regarding copyright ownership.  Crate licenses
 * this file to you under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.  You may
 * obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.  See the
 * License for the specific language governing permissions and limitations
 * under the License.
 *
 * However, if you have executed another commercial license agreement
 * with Crate these terms will supersede the license and you may use the
 * software solely pursuant to the terms of the relevant commercial agreement.
 */

namespace Crate\PDO\Http;

use Crate\PDO\Exception\RuntimeException;
use Crate\PDO\Exception\UnsupportedException;
use Crate\Stdlib\Collection;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ParseException;

class Client implements ClientInterface
{

    const DEFAULT_SERVER = "localhost:4200";

    /**
     * @var array
     */
    private $availableServers = [];

    /**
     * @var array
     */
    private $serverPool = [];

    /**
     * Client constructor.
     * @param array $servers
     * @param array $options
     */
    public function __construct(array $servers, array $options)
    {
        if ($servers == null || count($servers) == 0) {
            $this->serverPool[self::DEFAULT_SERVER] = new Server(self::DEFAULT_SERVER, $options);
            $this->availableServers[] = self::DEFAULT_SERVER;
        } else {
            foreach ($servers as &$server) {
                $this->serverPool[$server] = new Server($server, $options);
                $this->availableServers[] = $server;
            }
        }
    }

    /**
     * {@Inheritdoc}
     */
    public function execute($query, array $parameters)
    {
        $body = [
            'stmt' => $query,
            'args' => $parameters
        ];

        while (true) {
            $nextServer = $this->nextServer();
            /**
             * @var Server $s
             */
            $s = $this->serverPool[$nextServer];

            try {

                $response = $s->doRequest($body);
                $responseBody = json_decode($response->getBody(), true);

                return new Collection(
                    $responseBody['rows'],
                    $responseBody['cols'],
                    $responseBody['duration'],
                    $responseBody['rowcount']
                );

            } catch (ConnectException $exception) {
                // drop the server from the list of available servers
                $this->dropServer($nextServer);
                // break the loop if no more servers are available
                $this->raiseIfNoMoreServers($exception);
            } catch (BadResponseException $exception) {

                try {

                    $json = json_decode($exception->getResponse()->getBody(), true);

                    $errorCode    = $json['error']['code'];
                    $errorMessage = $json['error']['message'];

                    throw new RuntimeException($errorMessage, $errorCode, $exception);

                } catch (ParseException $e) {
                    throw new RuntimeException('Server returned non-JSON response.', 0, $exception);
                }

            }
        }
        return null;
    }

    /**
     * {@Inheritdoc}
     */
    public function getServerInfo()
    {
        return $this->getServerVersion();
    }

    /**
     * {@Inheritdoc}
     */
    public function getServerVersion()
    {
        return $this->execute("select version['number'] from sys.nodes limit 1", []);
    }

    /**
     * {@Inheritdoc}
     */
    public function setTimeout($timeout)
    {
        foreach ($this->serverPool as $k => &$s) {
            /**
             * @var $s Server
             */
            $s->setTimeout($timeout);
        }
    }

    /**
     * {@Inheritdoc}
     */
    public function setHttpBasicAuth($username, $passwd)
    {
        foreach ($this->serverPool as $k => &$s) {
            /**
             * @var $s Server
             */
            $s->setHttpBasicAuth($username, $passwd);
        }
    }

    /**
     * {@Inheritdoc}
     */
    public function setHttpHeader($name, $value)
    {
        foreach ($this->serverPool as $k => &$s) {
            /**
             * @var $s Server
             */
            $s->setHttpHeader($name, $value);
        }
    }

    /**
     * {@Inheritdoc}
     */
    public function setDefaultSchema($schemaName)
    {
        $this->setHttpHeader("Default-Schema", $schemaName);
    }

    /**
     * @return string The next available server instance
     */
    private function nextServer()
    {
        $server = $this->availableServers[0];
        $this->roundRobin();
        return $server;
    }

    /**
     * Very simple round-robin implementation
     * Pops the first item of the availableServers array and appends it at the end.
     *
     * @return void
     */
    private function roundRobin()
    {
        /**
         * Performing round robin on the array only makes sense
         * if there are more than 1 available servers.
         */
        if (count($this->availableServers) > 1) {
            $this->availableServers[] = array_shift($this->availableServers);
        }
    }

    /**
     * @param string           $server
     */
    private function dropServer($server)
    {
        if (($idx = array_search($server, $this->availableServers)) !== false) {
            unset($this->availableServers[$idx]);
        }
    }

    /**
     * @param ConnectException $exception
     */
    private function raiseIfNoMoreServers($exception)
    {
        if (count($this->availableServers) == 0) {
            throw new ConnectException(
                "No more servers available, exception from last server: " . $exception->getMessage(),
                $exception->getRequest()
            );
        }
    }
}
