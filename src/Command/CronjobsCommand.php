<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
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

use App\Model\Table\CronjobsTable;
use App\Model\Table\CronschedulesTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

/**
 * Cronjobs command.
 */
class CronjobsCommand extends Command {

    /**
     * @var bool
     */
    private $force = false;

    /**
     * @var bool
     */
    private $quiet = false;

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

        $parser->addOption('force', [
            'short'   => 'f',
            'help'    => __d('oitc_console', 'All cronjobs will be forced to execute!'),
            'boolean' => true
        ]);

        $parser->addOption('list', [
            'short'   => 'l',
            'help'    => __d('oitc_console', 'List all available cronjobs!'),
            'boolean' => true
        ]);

        $parser->addOption('task', [
            'short' => 't',
            'help'  => __d('oitc_console', 'Only execute the given cronjob by Task name'),
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
        $listOnly = $args->getOption('list');
        if ($listOnly === true) {
            // Only list available cronjobs with some information and exit
            /** @var CronjobsTable $CronjobsTable */
            $CronjobsTable = TableRegistry::getTableLocator()->get('Cronjobs');
            $cronjobs = $CronjobsTable->getCronjobs();

            $tableData = [
                [
                    'Task', 'Plugin', 'Interval', 'Last scheduled', 'Is currently running', 'Enabled'
                ]
            ];
            foreach ($cronjobs as $cronjob) {
                $isRunning = 'No';
                if (isset($cronjob['Cronschedule']['is_running']) && $cronjob['Cronschedule']['is_running']) {
                    $isRunning = 'Yes';
                }

                $enabled = '<error>✗</error>';
                if ($cronjob['Cronjob']['enabled'] === true) {
                    $enabled = '<success>✓</success>';
                }

                $tableData[] = [
                    $cronjob['Cronjob']['task'],
                    $cronjob['Cronjob']['plugin'],
                    $cronjob['Cronjob']['interval'],
                    $cronjob['Cronschedule']['start_time'] ?? 'n/a',
                    $isRunning,
                    $enabled,
                ];
            }

            $io->helper('Table')->output($tableData);
            exit(0);
        }

        $task = $args->getOption('task');

        // Execute the cronjobs
        $io->info('Start openITCOCKPIT cronjobs...');
        $io->hr();

        $fp = fopen('/var/run/oitc_cronjob.lock', 'wb');
        if (!$fp || !flock($fp, LOCK_EX | LOCK_NB)) {
            Log::error('Cronjob: Cronjob already running!');
            fclose($fp);
            exit(1);
        }

        $this->force = $args->getOption('force');
        $this->quiet = $args->getOption('quiet');

        try {
            /** @var CronjobsTable $CronjobsTable */
            $CronjobsTable = TableRegistry::getTableLocator()->get('Cronjobs');
            $cronjobs = $CronjobsTable->getEnabledCronjobs();
        } catch (\Exception $e) {
            dump($e->getMessage());
            exit(0);
        }

        $this->cronjobsToExecute = [];
        foreach ($cronjobs as $cronjob) {
            if (!empty($task)) {
                // Only execute the given cronjob
                if ($cronjob['Cronjob']['task'] !== $task) {
                    $io->info(sprintf('Skipping cronjob %s.%s', $cronjob['Cronjob']['plugin'], $cronjob['Cronjob']['task']));
                    continue;
                }
            }

            if (
                !(isset($cronjob['Cronschedule']['start_time'])) ||
                (time() >= (strtotime($cronjob['Cronschedule']['start_time']) + $this->m2s($cronjob['Cronjob']['interval'])) && $cronjob['Cronschedule']['is_running'] == 0) ||
                $this->force === true
            ) {
                $this->scheduleCronjob($cronjob);
            }

        }
        fclose($fp);
    }


    public function scheduleCronjob($cronjob) {
        /** @var CronschedulesTable $CronschedulesTable */
        $CronschedulesTable = TableRegistry::getTableLocator()->get('Cronschedules');

        $cronjobId = (int)$cronjob['Cronjob']['id'];
        $scheduleEntity = $CronschedulesTable->getSchedulByCronjobId($cronjobId);

        //Flag the cronjob as is_running in DB and set start_time
        $scheduleEntity->set('start_time', date('Y-m-d H:i:s'));
        $scheduleEntity->set('is_running', 1);

        $CronschedulesTable->save($scheduleEntity);

        if ($cronjob['Cronjob']['plugin'] === 'Core') {
            // Core cronjob
            $commandClassName = sprintf('%sCommand', $cronjob['Cronjob']['task']);
            $fullClassName = sprintf('App\Command\%s', $commandClassName);
        } else {
            // Cronjob of a Module
            $commandClassName = sprintf('%sCommand', $cronjob['Cronjob']['task']);
            $fullClassName = sprintf('%s\Command\%s', $cronjob['Cronjob']['plugin'], $commandClassName);
        }

        if (!class_exists($fullClassName)) {
            Log::error(sprintf('Cronjob: Class "%s" not found!', $fullClassName));
            return false;
        }

        //Executing the cron
        try {
            $CronjobTask = new $fullClassName();
            $taskArgs = [];
            if ($this->quiet === true) {
                $taskArgs[] = '--quiet';
            }

            $this->executeCommand($CronjobTask, $taskArgs);
        } catch (\Exception $e) {
            dump($e->getMessage());
        }

        try {
            //Cronjob is done, set is_running back to 0 and the end_time
            $scheduleEntity->set('end_time', date('Y-m-d H:i:s'));
            $scheduleEntity->set('is_running', 0);

            $CronschedulesTable->save($scheduleEntity);
        } catch (\PDOException $e) {
            // Thanks to https://github.com/statusengine/worker/blob/e20d6b5c83c6b3c6a2030c9506542fa59dcbb551/src/Backends/MySQL/MySQL.php#L296C17-L298C92
            $sqlstateErrorCode = $e->errorInfo[0]; // SQLSTATE error code (a five characters alphanumeric identifier defined in the ANSI SQL standard).
            $errorNo = $e->errorInfo[1]; //  Driver-specific error code.
            $errorString = $e->errorInfo[2]; //  Driver-specific error message.

            Log::error(sprintf(
                'Catch MySQL Error: %s %s %s',
                $sqlstateErrorCode,
                $errorNo,
                $errorString
            ));

            // $sqlstateErrorCode = HY000
            // $errorNo = 2006
            if ($errorString == 'MySQL server has gone away') {

                // This can happen if MySQL terminates the connection because the Job was running for too long
                // Or if the MySQL Server got restarted
                $connection = $CronschedulesTable->getConnection();
                $connection->disconnect();
                $connection->connect();

                // Retry
                $CronschedulesTable->save($scheduleEntity);
            }
        }

        return !$scheduleEntity->hasErrors();
    }

    public function m2s($minutes) {
        return $minutes * 60;
    }
}
