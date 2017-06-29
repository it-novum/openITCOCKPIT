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

use Crate\PDO\Exception\UnsupportedException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\ResponseInterface;

class Server implements InternalClientInterface
{

    const PROTOCOL = 'http';
    const SQL_PATH = '/_sql';

    /**
     * @var HttpClient
     */
    private $client;

    private $opts = [
        'headers' => []
    ];

    /**
     * @param string $uri
     * @param array  $options
     */
    public function __construct($uri, array $options)
    {
        $uri = self::computeURI($uri);
        $this->client = new HttpClient([
            'base_uri' => $uri
        ]);
        $this->opts += $options;
    }

    public function setTimeout($timeout)
    {
        $this->opts['timeout'] = (float)$timeout;
    }

    public function setHttpBasicAuth($username, $passwd)
    {
        $this->opts['auth'] = [$username, $passwd];
    }

    public function setHttpHeader($name, $value)
    {
        $this->opts['headers'][$name] = $value;
    }

    public function getServerInfo()
    {
        // TODO: Implement getServerInfo() method.
        throw new UnsupportedException('Not yet implemented');
    }

    public function getServerVersion()
    {
        // TODO: Implement getServerVersion() method.
        throw new UnsupportedException('Not yet implemented');
    }

    /**
     * Execute a HTTP/1.1 POST request with JSON body
     *
     * @param array $body

     * @return ResponseInterface
     * @throws RequestException When an error is encountered
     */
    public function doRequest(array $body)
    {
        $args = ['json' => $body] + $this->opts;
        return $this->client->post(null, $args);
    }

    /**
     * Compute a URI for usage with the HTTP client
     *
     * @param string $server A host:port string
     *
     * @return string An URI which can be used by the HTTP client
     */
    private static function computeURI($server)
    {
        return self::PROTOCOL .'://' . $server . self::SQL_PATH;
    }
}
