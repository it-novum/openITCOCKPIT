<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

use itnovum\openITCOCKPIT\Core\System\QueryLogMessageInterface;
use itnovum\openITCOCKPIT\Ratchet\Overwrites\HttpServerSize;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\Socket\Server as Reactor;
use Symfony\Component\Process\Process;

class QueryLogShell extends AppShell {
    /**
     * @var string
     */
    private $logfile;

    /**
     * @var bool
     */
    private $prettyPrint = false;

    /**
     * @var bool
     */
    private $hidePermissionQueries = false;

    public function main() {
        App::uses('SqlFormatter', 'Lib');

        $this->logfile = LOGS . 'query.log';

        $this->parser = $this->getOptionParser();

        if (array_key_exists('pretty', $this->params)) {
            $this->prettyPrint = true;
        }

        if (array_key_exists('hide-acl', $this->params)) {
            $this->hidePermissionQueries = true;
        }

        if (array_key_exists('websocket-server', $this->params)) {
            $this->fireUpWebSocketServer();
        }

        $this->tailf();
    }

    public function tailf() {
        $options = [
            'cwd' => '/tmp',
            'env' => [
                'LANG'     => 'C',
                'LANGUAGE' => 'en_US.UTF-8',
                'LC_ALL'   => 'C',
                'PATH'     => '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin'
            ],
        ];

        $descriptorspec = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "r"],
        ];

        $process = new Process('/usr/bin/tail -f -n 10 ' . $this->logfile);
        $process->start();
        while ($process->isRunning()) {
            $output = $process->getIncrementalOutput();
            if ($output === '') {
                sleep(0.01);
                continue;
            }

            $queries = json_decode($output);

            if ($queries === null) {
                continue;
            }

            $this->out(sprintf(
                '<info>Database "%s" %s queries took %s ms</info>',
                $queries->datasource,
                $queries->count,
                $queries->time
            ));

            foreach ($queries->queries as $i => $query) {

                if ($this->hidePermissionQueries) {
                    $acoQuery = 'SELECT `Aco`.`id`, `Aco`.`parent_id`';
                    $aroQuery = 'SELECT `Aro`.`id`, `Aro`.`parent_id`';
                    $permissionQuery = 'SELECT `Permission`.`id`, `Permission`.`aro_id`';

                    if (strstr($query->query, $acoQuery) || strstr($query->query, $aroQuery) || strstr($query->query, $permissionQuery)) {
                        continue;
                    }
                }

                $this->out(sprintf(
                    '-- [%s] Affected %s, num. Rows %s, %s ms',
                    $i,
                    $query->affected,
                    $query->numRows,
                    $query->took
                ));

                if ($this->prettyPrint) {
                    $this->out(SqlFormatter::format($query->query), false);
                } else {
                    $this->out(SqlFormatter::highlight($query->query), false);
                }
                $this->out();
            }
            $this->out('------------');
            $this->out();
        }
    }

    private function fireUpWebSocketServer() {
        $MessageInterface = new QueryLogMessageInterface($this, $this->logfile, $this->prettyPrint, $this->hidePermissionQueries);
        $MessageInterface->startTailf();

        $loop = React\EventLoop\Factory::create();
        $loop->addPeriodicTimer(0.01, [$MessageInterface, 'eventLoop']);

        $Server = new IoServer(
            new HttpServerSize(
                new WsServer($MessageInterface)
            ),
            new Reactor(sprintf('%s:%s', '0.0.0.0', 8082), $loop),
            $loop
        );

        try {
            $Server->run();
        } catch (Exception $e) {
            debug($e);
        }

    }

    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'pretty'           => ['short' => 'p', 'help' => __d('oitc_console', 'Pretty print sql queries')],
            'hide-acl'         => ['short' => 'a', 'help' => __d('oitc_console', 'Hide ARO and ACO queries')],
            'websocket-server' => ['short' => 'w', 'help' => __d('oitc_console', 'Start a WebSocket server to make the query log accessible from openITCOCKPIT web interface')],
            'hostname'         => ['help' => __d('oitc_console', 'The uuid of the host')],
        ]);

        return $parser;
    }
}
