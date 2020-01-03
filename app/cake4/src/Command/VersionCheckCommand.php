<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Table\ProxiesTable;
use App\Model\Table\RegistersTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\Http;
use itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;
use itnovum\openITCOCKPIT\Core\PackagemanagerRequestBuilder;
use itnovum\openITCOCKPIT\Core\ValueObjects\License;

/**
 * VersionCheck command.
 */
class VersionCheckCommand extends Command implements CronjobInterface {
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
        $io->out('Checking for new openITCOCKPIT Version', 0);
        $availableVersion = $this->getNewVersion($io);
        $this->saveNewVersion($availableVersion);
        $io->success('   Ok');
        $io->hr();
    }

    /**
     * @param ConsoleIo $io
     * @return string|null  Version as string or null
     */
    private function getNewVersion(ConsoleIo $io) {
        /** @var ProxiesTable $ProxiesTable */
        $ProxiesTable = TableRegistry::getTableLocator()->get('Proxies');
        /** @var RegistersTable $RegistersTable */
        $RegistersTable = TableRegistry::getTableLocator()->get('Registers');

        $License = $RegistersTable->getLicense();
        $License = new License($License);
        $packagemanagerRequestBuilder = new PackagemanagerRequestBuilder(ENVIRONMENT, $License->getLicense());
        $http = new Http(
            $packagemanagerRequestBuilder->getUrl(),
            $packagemanagerRequestBuilder->getOptions(),
            $ProxiesTable->getSettings()
        );
        //Send https request
        $http->sendRequest();
        $availableVersion = '???';
        if (!$http->error) {
            $data = json_decode($http->data);
            if (property_exists($data, 'version')) {
                $availableVersion = $data->version;
            }
        } else {
            //Force new line
            $io->out('');
            $io->error($http->getLastError()['error']);
        }
        return $availableVersion;
    }

    /**
     * @param string $availableVersion
     */
    private function saveNewVersion(string $availableVersion) {
        $newConfig = sprintf($this->getConfigTemplate(), $availableVersion);
        $fileName = APP . 'Lib' . DS . 'openITCOCKPIT_AvailableVersion.php';
        file_put_contents($fileName, $newConfig);
    }

    /**
     * @return string
     */
    private function getConfigTemplate() {
        $fileName = APP . 'itnovum' . DS . 'openITCOCKPIT' . DS . 'Core' . DS . 'AvailableVersionTemplate.txt';
        return file_get_contents($fileName);
    }
}
