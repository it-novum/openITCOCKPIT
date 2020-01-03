<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Table\ConfigurationFilesTable;
use App\Model\Table\ConfigurationQueueTable;
use App\Model\Table\SystemsettingsTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\ConfigGenerator\ConfigInterface;
use itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;

/**
 * ConfigGenerator command.
 */
class ConfigGeneratorCommand extends Command implements CronjobInterface {
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
        $io->setStyle('red', ['text' => 'red', 'blink' => false]);

        $io->out('Check for pending configuration files...');

        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $systemsettings = $SystemsettingsTable->findAsArray();

        /** @var ConfigurationQueueTable $ConfigurationQueueTable */
        $ConfigurationQueueTable = TableRegistry::getTableLocator()->get('ConfigurationQueue');

        /** @var ConfigurationFilesTable $ConfigurationFilesTable */
        $ConfigurationFilesTable = TableRegistry::getTableLocator()->get('ConfigurationFiles');

        $configFilesToGenerate = $ConfigurationQueueTable->getConfigFilesToGenerate();

        foreach ($configFilesToGenerate as $record) {
            $configFile = $record['data'];
            $className = sprintf('itnovum\openITCOCKPIT\ConfigGenerator\%s', $configFile);
            if (!class_exists($className)) {
                throw new NotFoundException('Config file not found');
            }

            /** @var ConfigInterface $ConfigFileObject */
            $ConfigFileObject = new $className();
            $config = $ConfigurationFilesTable->getConfigValuesByConfigFile($ConfigFileObject->getDbKey());

            $io->out(sprintf('Generate %s   ', $ConfigFileObject->getLinkedOutfile()), 0);
            $ConfigFileObject->writeToFile($config);
            $io->success('    Ok');

            $this->restartByConfigFile($configFile, $systemsettings, $io);

            $ConfigurationQueueTable->delete($ConfigurationQueueTable->get($record['id']));
        }
        $io->success('Ok');
        $io->hr();
    }

    /**
     * @param $configFile
     * @param $systemsettings
     * @param ConsoleIo $io
     */
    public function restartByConfigFile($configFile, $systemsettings, ConsoleIo $io) {
        switch ($configFile) {
            case 'NagiosCfg':
                $command = $systemsettings['MONITORING']['MONITORING.RESTART'];
                $this->restartService($command, 'Restart Nagios/Naemon core', $io);
                break;

            case 'AfterExport':
                $command = $systemsettings['INIT']['INIT.GEARMAN_WORKER_RESTART'];
                $this->restartService($command, 'Restart gearman_worker service', $io);
                break;

            case 'phpNSTAMaster':
                $command = $systemsettings['INIT']['INIT.PHPNSTA_RESTART'];
                $this->restartService($command, 'Restart phpNSTA service', $io);
                break;

            case 'GraphingDocker':
                $command = $systemsettings['INIT']['INIT.OITC_GRAPHING_RESTART'];
                $this->restartService($command, 'Restart and rebuild openITCOCKPIT-Graphing Docker Containers', $io);
                break;

            case 'StatusengineCfg':
                $command = $systemsettings['INIT']['INIT.STATUSENGINE_RESTART'];
                $this->restartService($command, 'Restart Statusengine service', $io);
                break;

            default:
                break;
        }
    }

    /**
     * @param $command
     * @param $outputTxt
     * @param ConsoleIo $io
     */
    private function restartService($command, $outputTxt, ConsoleIo $io) {
        $io->out($outputTxt . '   ', 0);
        exec(escapeshellcmd($command), $output, $returncode);
        if ($returncode > 0) {
            $io->out('<red>Error</red>');
        } else {
            $io->success('Ok');
        }
    }

}
