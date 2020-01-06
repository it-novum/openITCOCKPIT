<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Table\ConfigurationFilesTable;
use App\Model\Table\SystemsettingsTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\ConfigGenerator\ConfigInterface;
use itnovum\openITCOCKPIT\ConfigGenerator\GeneratorRegistry;
use SebastianBergmann\Environment\Console;

/**
 * ConfigGeneratorShell command.
 */
class ConfigGeneratorShellCommand extends Command {

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
            'generate' => ['help' => "Will generate all configuration files from database", 'boolean' => true, 'default' => false],
            'reload'   => ['help' => "Reload services, where a new configuration file was generated for", 'boolean' => true, 'default' => false],
            'migrate'  => ['help' => 'Will migrate existing configuration files to database', 'boolean' => true, 'default' => false]
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
        $io->setStyle('red', ['text' => 'red', 'blink' => false]);
        $hadJob = false;
        if ($args->getOption('migrate')) {
            $hadJob = true;
            try {
                $this->migrate($io);
            } catch (\Exception $e) {
                $io->out('<red>' . $e->getMessage() . '</red>');
            }
        }

        if ($args->getOption('generate')) {
            $hadJob = true;
            if ($args->getOption('reload')) {
                $this->generateAndReload($io);
            } else {
                $this->generate($io);
            }
        }

        if (!$hadJob) {
            $this->displayHelp($this->getOptionParser(), $args, $io);
        }
    }

    /**
     * @param ConsoleIo $io
     */
    private function generate(ConsoleIo $io) {
        $io->out('Generate all configuration files...    ');
        /** @var ConfigurationFilesTable $ConfigurationFilesTable */
        $ConfigurationFilesTable = TableRegistry::getTableLocator()->get('ConfigurationFiles');

        $GeneratorRegistry = new GeneratorRegistry();

        foreach ($GeneratorRegistry->getAllConfigFiles() as $ConfigFileObject) {
            /** @var ConfigInterface $ConfigFileObject */
            $io->out(sprintf('Generate %s   ', $ConfigFileObject->getLinkedOutfile()), 0);
            $ConfigFileObject->writeToFile($ConfigurationFilesTable->getConfigValuesByConfigFile($ConfigFileObject->getDbKey()));
            $io->success('Ok');
        }
    }

    /**
     * @param ConsoleIo $io
     */
    private function generateAndReload(ConsoleIo $io) {
        $io->out('Generate all configuration files...    ');

        /** @var SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $systemsettings = $SystemsettingsTable->findAsArray();

        /** @var $ConfigurationFilesTable ConfigurationFilesTable */
        $ConfigurationFilesTable = TableRegistry::getTableLocator()->get('ConfigurationFiles');

        $GeneratorRegistry = new GeneratorRegistry();

        foreach ($GeneratorRegistry->getAllConfigFiles() as $ConfigFileObject) {
            /** @var ConfigInterface $ConfigFileObject */
            $io->out(sprintf('Generate %s', $ConfigFileObject->getLinkedOutfile()));
            $ConfigFileObject->writeToFile($ConfigurationFilesTable->getConfigValuesByConfigFile($ConfigFileObject->getDbKey()));
            $this->restartByConfigFile($ConfigFileObject->getDbKey(), $systemsettings, $io);
        }
    }


    /**
     * @param ConsoleIo $io
     * @throws \Exception
     */
    private function migrate(ConsoleIo $io) {
        $io->out('Migrate existing configuration files to database...    ');
        /** @var $ConfigurationFilesTable ConfigurationFilesTable */
        $ConfigurationFilesTable = TableRegistry::getTableLocator()->get('ConfigurationFiles');

        $GeneratorRegistry = new GeneratorRegistry();
        foreach ($GeneratorRegistry->getAllConfigFiles() as $ConfigFileObject) {
            /** @var ConfigInterface $ConfigFileObject */
            $io->out(sprintf('Processing %s   ', $ConfigFileObject->getLinkedOutfile()), 0);

            $dbConfig = $ConfigurationFilesTable->getConfigValuesByConfigFile($ConfigFileObject->getDbKey());
            $config = $ConfigFileObject->migrate($dbConfig);
            if (is_array($config)) {
                $configFileForDatabase = $ConfigFileObject->convertRequestForSaveAll($config);
                $ConfigurationFilesTable->saveConfigurationValuesForConfigFile($ConfigFileObject->getDbKey(), $configFileForDatabase);
                $io->success('Ok');
            } else {
                $io->info('Skipping');
            }
        }
    }

    /**
     * @param $configFile
     * @param $systemsettings
     * @param ConsoleIo $io
     */
    private function restartByConfigFile($configFile, $systemsettings, ConsoleIo $io) {
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
