<?php
declare(strict_types=1);

namespace App\Command;

use App\Lib\Exceptions\MissingDbBackendException;
use App\Model\Table\SystemsettingsTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Log\Log;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use itnovum\openITCOCKPIT\Core\DbBackend;

/**
 * DatabaseCleanup command.
 */
class DatabaseCleanupCommand extends Command {

    /**
     * @var array
     */
    private $_systemsettings;

    /**
     * @var DbBackend
     */
    private $DbBackend;

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
        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $this->_systemsettings = $SystemsettingsTable->findAsArray();

        $this->DbBackend = new DbBackend();

        if ($this->DbBackend->isNdoUtils()) {
            // NdoUtils or Statusengine 2
            $tables = [
                $this->DbBackend->getServicechecksTable(),
                $this->DbBackend->getHostchecksTable(),
                $this->DbBackend->getStatehistoryHostsTable(),
                //$this->DbBackend->getStatehistoryServicesTable(), //There is only one table in NDO schema
                $this->DbBackend->getLogentriesTable(),
                $this->DbBackend->getNotificationHostsTable(),
                //$this->DbBackend->getNotificationServicesTable(), //There is only one table in NDO schema
                TableRegistry::getTableLocator()->get('Statusengine2Module.Contactnotifications'), //NDO only tables
                TableRegistry::getTableLocator()->get('Statusengine2Module.Contactnotificationmethods') //NDO only tables
            ];

            $this->checkAndCreatePartitionsMySQL($tables, $io);
        }

        if ($this->DbBackend->isStatusengine3()) {
            $tables = [
                $this->DbBackend->getServicechecksTable(),
                $this->DbBackend->getHostchecksTable(),
                $this->DbBackend->getStatehistoryHostsTable(),
                $this->DbBackend->getStatehistoryServicesTable(),
                $this->DbBackend->getLogentriesTable(),
                $this->DbBackend->getNotificationHostsTable(),
                $this->DbBackend->getNotificationServicesTable(),
            ];

            $this->checkAndCreatePartitionsMySQL($tables, $io);
        }

        if ($this->DbBackend->isCrateDb()) {
            throw new MissingDbBackendException('CrateDB is not implemented yet!');

            $tables = [
                $this->DbBackend->getServicechecksTable(),
                $this->DbBackend->getHostchecksTable(),
                $this->DbBackend->getStatehistoryHostsTable(),
                $this->DbBackend->getStatehistoryServicesTable(),
                $this->DbBackend->getLogentriesTable(),
                $this->DbBackend->getNotificationHostsTable(),
                $this->DbBackend->getNotificationServicesTable(),
            ];

            $this->cleanupCrateDb($tables);
        }

    }

    public function checkAndCreatePartitionsMySQL(array $tables, ConsoleIo $io) {
        foreach ($tables as $Table) {
            /** @var Table $Table */

            $Connection = $Table->getConnection();
            //debug($Connection->execute('SELECT partition_name FROM information_schema.partitions')->fetchAll('assoc'));

            //Get existing partitions for this table out of MySQL's information_schema
            $query = $Connection->execute("
                SELECT partition_name
			    FROM information_schema.partitions
			    WHERE TABLE_SCHEMA = :databaseName
			    AND TABLE_NAME = :tableName", [
                'databaseName' => $Connection->config()['database'],
                'tableName'    => $Table->getTable()
            ]);
            $result = $query->fetchAll('assoc');

            $partitions = Hash::extract($result, '{n}.partition_name');

            //Check if partition for current week exists
            $currentMysqlPartitionStartDate = date('Y-m-d H:i:s', strtotime('00:00:00 next monday'));
            $currentPartitionName = 'p_' . date('o_W');

            $io->out('Checking for partition ' . $currentPartitionName . ' in Table "' . $Table->getTable() . '"...', 0);
            if (!in_array($currentPartitionName, $partitions, true)) {
                try {
                    $Connection->execute("ALTER TABLE " . $Connection->config()['database'] . "." . $Table->getTable() . " REORGANIZE PARTITION p_max INTO (PARTITION " . $currentPartitionName . " VALUES LESS THAN (TO_DAYS('" . $currentMysqlPartitionStartDate . "')), PARTITION p_max values LESS THAN (MAXVALUE));");
                    $io->success('   Ok');
                } catch (\Exception $e) {
                    Log::error('DatabaseCleanupCommand: MySQL Error: ' . $e->getMessage());
                }
            } else {
                $io->success('   Ok');
            }


            // Attention: look close before you change the date parameters!!!
            // Think that there's coming a new year on 31.12 !!!
            // TO_DAYS('2014-12-29 00:00:00')
            $mysqlPartitionStartDate = date('Y-m-d H:i:s', strtotime('00:00:00 next monday +1 week'));
            //p_2015_01
            $newPartitionName = 'p_' . date('o_W', strtotime('next monday'));

            $io->out('Checking for partition ' . $newPartitionName . ' in Table "' . $Table->getTable() . '"...', 0);

            //Checking if this partition already exists
            if (!in_array($newPartitionName, $partitions, true)) {
                //This partition does not exist and we need to create it
                $io->out('');
                $io->info('Partition does not exists, will create it....', 0);

                $Connection->execute("ALTER TABLE " . $Connection->config()['database'] . "." . $Table->getTable() . " REORGANIZE PARTITION p_max INTO (PARTITION " . $newPartitionName . " VALUES LESS THAN (TO_DAYS('" . $mysqlPartitionStartDate . "')), PARTITION p_max values LESS THAN (MAXVALUE));");
                $io->success('   Ok');
                //ALTER TABLE ptn_test DROP PARTITION p_2015_01
            } else {
                $io->success('   Ok');
            }
            $this->cleanupPartition($Table, $partitions, $io);
            $io->hr();
        }
    }

    /**
     * @param Table $Table
     * @param array $partitions
     * @param ConsoleIo $io
     */
    public function cleanupPartition(Table $Table, $partitions, ConsoleIo $io) {
        $io->out('Checking age of partitions...');

        $systemsettingsKey = '';
        switch ($Table->getAlias()) {
            case 'StatehistoryHosts':
            case 'StatehistoryServices':
                $systemsettingsKey = 'STATEHISTORIES';
                break;

            case 'Hostchecks':
                $systemsettingsKey = 'HOSTCHECKS';
                break;
            case 'Servicechecks':
                $systemsettingsKey = 'SERVICECHECKS';
                break;
            case 'Logentries':
                $systemsettingsKey = 'LOGENTRIES';
                break;
            case 'NotificationHosts':
            case 'NotificationService':
            case 'Contactnotifications': // NDO only
            case 'Contactnotificationmethods': // NDO only
                $systemsettingsKey = 'NOTIFICATIONS';
                break;
        }

        if (!isset($this->_systemsettings['ARCHIVE']['ARCHIVE.AGE.' . $systemsettingsKey])) {
            throw new \RuntimeException('ARCHIVE.AGE.' . $systemsettingsKey . ' key not found in Systemsettings for Table "' . $Table->getAlias() . '"!');
        }

        $maxAgeInWeeks = $this->_systemsettings['ARCHIVE']['ARCHIVE.AGE.' . $systemsettingsKey];

        $maxAgeTimestamp = strtotime('00:00:00 last monday -' . ($maxAgeInWeeks + 1) . ' week');

        //Valide date
        if ($maxAgeTimestamp) {
            $Connection = $Table->getConnection();

            //$partitions = ['p_2014_48', 'p_2014_49', 'p_2014_50', 'p_2014_51', 'p_2015_01', 'p_2015_02', 'p_2015_03'];
            $comparePartitionName = 'p_' . date('o_W', $maxAgeTimestamp);

            if (is_array($partitions)) {
                foreach ($partitions as $partition) {
                    //NEVER EVER DROP THIS PARTITION!!!
                    if ($partition == 'p_max') {
                        continue;
                    }
                    if (!version_compare($comparePartitionName, $partition, '<')) {
                        $io->setStyle('red', ['text' => 'red', 'blink' => false]);

                        $io->out('<red>Drop partition ' . $partition . '</red>');
                        $Connection->execute("ALTER TABLE " . $Connection->config()['database'] . "." . $Table->getTable() . " DROP PARTITION " . $partition . ";");
                    }
                }
            }

            $io->success('Cleanup done');
        }
    }


    public function cleanupCrateDb(array $tables) {
        $this->out('Checking age of partitions...');

        $this->deletePartitionsFromCrateDb($this->StatehistoryHost, 'STATEHISTORIES');
        $this->deletePartitionsFromCrateDb($this->StatehistoryService, 'STATEHISTORIES');
        $this->deletePartitionsFromCrateDb($this->Hostcheck, 'HOSTCHECKS');
        $this->deletePartitionsFromCrateDb($this->Servicecheck, 'SERVICECHECKS');
        $this->deletePartitionsFromCrateDb($this->Logentry, 'LOGENTRIES');
        $this->deletePartitionsFromCrateDb($this->NotificationHost, 'NOTIFICATIONS');
        $this->deletePartitionsFromCrateDb($this->NotificationService, 'NOTIFICATIONS');
        //$this->deletePartitionsFromCrateDb($this->NotificationService, 'NOTIFICATIONS');

        $this->out('<green>Cleanup done</green>');
        $this->hr();
    }

    /**
     * @param Model $Model
     * @param string $systemsettingsKey
     */
    public function deletePartitionsFromCrateDb(Model $Model, $systemsettingsKey) {
        $maxAgeInWeeks = $this->_systemsettings['ARCHIVE']['ARCHIVE.AGE.' . $systemsettingsKey];

        $maxAgeTimestamp = strtotime('00:00:00 last monday -' . ($maxAgeInWeeks + 1) . ' week');
        $maxAgeTimestamp = $maxAgeTimestamp * 1000; //Java timestamp CrateDB


        $partitionsToDelete = [];
        foreach ($Model->getPartitions() as $record) {
            if (isset($record['values']['day']) && $record['values']['day'] < $maxAgeTimestamp) {
                $partitionsToDelete[] = $record['values']['day'];
            }
        }

        foreach ($partitionsToDelete as $partitionConditionTimestamp) {
            $this->out(sprintf(
                '<red>Drop partition %s from table %s%s</red>',
                $partitionConditionTimestamp,
                $Model->tablePrefix,
                $Model->useTable
            ));
            if (!$Model->dropPartition($partitionConditionTimestamp)) {
                $this->out('<error>Error while dropping partition</error>');
            }
        }
    }
}
