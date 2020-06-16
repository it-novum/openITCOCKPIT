<?php
// Copyright (C) <2020>  <it-novum GmbH>
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

declare(strict_types=1);

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\ConnectionManager;
use itnovum\openITCOCKPIT\Core\System\QueryLogMessageInterface;
use itnovum\openITCOCKPIT\Ratchet\Overwrites\HttpServerSize;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server as Reactor;
use Symfony\Component\Process\Process;

/**
 * Dump command.
 */
class QueryLogCommand extends Command {

    /**
     * @var ConsoleIo
     */
    public $io;

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


    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser {
        $parser = parent::buildOptionParser($parser);

        $parser->addOptions([
            'pretty'           => ['boolean' => true, 'help' => __d('oitc_console', 'Pretty print sql queries')],
            'hide-acl'         => ['boolean' => true, 'help' => __d('oitc_console', 'Hide (ARO/ACO) permission queries')],
            'websocket-server' => ['boolean' => true, 'help' => __d('oitc_console', 'Start a WebSocket server to make the query log accessible from openITCOCKPIT web interface')],
        ]);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io) {
        $this->io = $io;
        $this->logfile = LOGS . 'queries.log';

        $dataSource = ConnectionManager::getConfig('default');
        if($dataSource['log'] != 1) {
            $io->setStyle('red_bold', ['text' => 'red', 'bold' => true]);
            $io->out('<red_bold>Setting "\'log\' => true" in "' . ROOT . '/config/datasource.php' . '" required!</red_bold>');
        }

        if ($args->getOption('pretty')) {
            $this->prettyPrint = true;
        }
        if ($args->getOption('hide-acl')) {
            $this->hidePermissionQueries = true;
        }
        if ($args->getOption('websocket-server')) {
            $this->fireUpWebSocketServer();
        }
        $this->tailf();
    }

    private function tailf() {
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
        pcntl_signal(SIGTERM, function ($signal) use ($process) {
            $process->stop();
        });
        while ($process->isRunning()) {
            $output = $process->getIncrementalOutput();
            if ($output === '') {
                sleep(1);
                continue;
            }

            $queries = $this->parseLogOutputToSourceQueries($output);

            if ($queries === null) {
                continue;
            }

            $time = 0;
            foreach ($queries as $i => $query) {
                if ($this->hidePermissionQueries) {
                    $acoQuery = 'SELECT `Acos`.`id`, `Acos`.`parent_id`';
                    $aroQuery = 'SELECT `Aros`.`id`, `Aros`.`parent_id`';
                    $permissionQuery = 'SELECT `Permissions`.`id`, `Permissions`.`aro_id`';

                    if (strstr($query['query'], $acoQuery) || strstr($query['query'], $aroQuery) || strstr($query['query'], $permissionQuery)) {
                        continue;
                    }
                }
                $time += $query['took'];
            }

            $this->io->out(sprintf(
                '<info>Database "%s" %s queries took %s ms</info>',
                'openitcockpit',
                sizeof($queries),
                $time
            ));

            foreach ($queries as $i => $query) {

                if ($this->hidePermissionQueries) {
                    $acoQuery = 'SELECT `Acos`.`id`, `Acos`.`parent_id`';
                    $aroQuery = 'SELECT `Aros`.`id`, `Aros`.`parent_id`';
                    $permissionQuery = 'SELECT `Permissions`.`id`, `Permissions`.`aro_id`';

                    if (strstr($query['query'], $acoQuery) || strstr($query['query'], $aroQuery) || strstr($query['query'], $permissionQuery)) {
                        continue;
                    }
                }

                $this->io->out(sprintf(
                    '-- [%s] Affected %s, num. Rows %s, %s ms',
                    $i,
                    $query['affected'],
                    $query['numRows'],
                    $query['took']
                ));

                if ($this->prettyPrint) {
                    $this->io->out(\SqlFormatter::format($query['query']), 0);
                } else {
                    $this->io->out(\SqlFormatter::highlight($query['query']), 0);
                }
                $this->io->out();
            }
            $this->io->out('------------');
            $this->io->out();
        }
    }

    private function fireUpWebSocketServer() {
        $MessageInterface = new QueryLogMessageInterface($this, $this->logfile, $this->prettyPrint, $this->hidePermissionQueries);
        $MessageInterface->startTailf();
        pcntl_signal(SIGTERM, function ($signal) use ($MessageInterface) {
            $MessageInterface->stopTailf();
        });

        $loop = Factory::create();
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
        } catch (\Exception $e) {
            debug($e);
        }

    }

    /**
     * @param string $output
     * @return array
     */
    public function parseLogOutputToSourceQueries(string $output) {
        $sourceQueries = [];
        $tmpLine = '';
        $tmpOptions = [
            'affected' => '?',
            'rows'     => 0,
            'duration' => 0,
        ];
        $startReading = false;
        foreach (explode(PHP_EOL, $output) as $line) {
            if (trim($line) === '') {
                continue;
            }

            if (strstr($line, 'Debug:')) {
                $startReading = true;
                if ($tmpLine !== '') {
                    $sourceQueries[] = [
                        'query'    => $tmpLine,
                        'affected' => $tmpOptions['affected'],
                        'numRows'  => intval($tmpOptions['rows']),
                        'took'     => intval($tmpOptions['duration'])
                    ];
                    $tmpLine = '';
                    $tmpOptions['duration'] = 0;
                    $tmpOptions['rows'] = 0;
                }
                $lineArr = explode('rows=', $line);
                $newLine = $lineArr[1];

                $tmpLine = trim(substr($newLine, strpos($newLine, ' ')));
                $tmpOptions['duration'] = explode('duration=', $lineArr[0])[1];
                $tmpOptions['rows'] = substr($newLine, 0, strpos($newLine, ' '));
            } else if ($startReading) {
                $tmpLine .= $line;
            }
        }
        if ($tmpLine !== '') {
            $sourceQueries[] = [
                'query'    => $tmpLine,
                'affected' => $tmpOptions['affected'],
                'numRows'  => intval($tmpOptions['rows']),
                'took'     => intval($tmpOptions['duration'])
            ];
        }

        return $sourceQueries;
    }

}
