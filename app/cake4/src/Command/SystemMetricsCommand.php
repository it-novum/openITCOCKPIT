<?php
declare(strict_types=1);

namespace App\Command;

use App\Model\Table\HostsTable;
use App\Model\Table\ProxiesTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\SystemsettingsTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;
use itnovum\openITCOCKPIT\Core\System\Health\StatisticsCollector;
use GuzzleHttp\Client;

/**
 * SystemMetrics command.
 */
class SystemMetricsCommand extends Command implements CronjobInterface {
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
        $record = $SystemsettingsTable->getSystemsettingByKeyAsCake2('SYSTEM.ANONYMOUS_STATISTICS');

        if (empty($record)) {
            return;
        }

        if ((int)$record['Systemsetting']['value'] !== 1) {
            $io->out('Anonymous statistic are disabled.', 0);
            $io->success('   Ok');
            $io->hr();
            return;
        }

        $io->out('Sending anonymous statistic information...', 0);

        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        $StatisticsCollector = new StatisticsCollector($HostsTable, $ServicesTable);
        $dataToSend = $StatisticsCollector->getData();

        $params = [
            'form_params' => $dataToSend,
            'proxy'       => [
                'http'  => false,
                'https' => false
            ]
        ];

        /** @var ProxiesTable $ProxiesTable */
        $ProxiesTable = TableRegistry::getTableLocator()->get('Proxies');
        $proxySettings = $ProxiesTable->getSettings();
        if ($proxySettings['enabled']) {
            $params['proxy']['http'] = sprintf('%s:%s', $proxySettings['ipaddress'], $proxySettings['port']);
            $params['proxy']['https'] = $params['proxy']['http'];
        }

        $client = new Client();
        $response = $client->request('POST', 'https://packagemanager.it-novum.com/statistics/submit', $params);
        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException($response->getBody()->getContents());
        }

        $io->success('   Ok');
        $io->hr();
    }
}
