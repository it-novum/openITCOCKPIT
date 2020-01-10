<?php
declare(strict_types=1);

namespace GrafanaModule\Command;

use App\Model\Table\ProxiesTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use GrafanaModule\Model\Table\GrafanaConfigurationsTable;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;
use itnovum\openITCOCKPIT\Grafana\GrafanaApiConfiguration;
use itnovum\openITCOCKPIT\Grafana\GrafanaTag;

/**
 * GrafanaDashboard command.
 */
class GrafanaDashboardCommand extends Command implements CronjobInterface {

    /**
     * @var GrafanaApiConfiguration
     */
    private $GrafanaApiConfiguration;


    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $tag;

    /**
     * @var ConsoleIo
     */
    private $io;

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
        $this->io = $io;

        /** @var GrafanaConfigurationsTable $GrafanaConfigurationsTable */
        $GrafanaConfigurationsTable = TableRegistry::getTableLocator()->get('GrafanaModule.GrafanaConfigurations');

        $grafanaConfiguration = $GrafanaConfigurationsTable->getGrafanaConfiguration();
        $this->GrafanaApiConfiguration = GrafanaApiConfiguration::fromArray($grafanaConfiguration);
        $hasGrafanaConfig = $grafanaConfiguration['api_url'] !== '';

        if (!$hasGrafanaConfig) {
            throw new \RuntimeException('No Grafana configuration found');
        }

        /** @var ProxiesTable $ProxiesTable */
        $ProxiesTable = TableRegistry::getTableLocator()->get('Proxies');


        $io->out('Check Connection to Grafana');
        $this->client = $GrafanaConfigurationsTable->testConnection($this->GrafanaApiConfiguration, $ProxiesTable->getSettings());

        if ($this->client instanceof Client) {
            $io->success('Connection check successful');
            //get Tag name
            $tag = new GrafanaTag();
            $this->tag = $tag->getTag();
            //delete previous dashboards
            $this->deleteDashboards($this->tag);
            $this->createDashboard();
        } else {
            Log::error('GrafanaDashboardCommand: ' . $this->client);
            Log::error('GrafanaDashboardCommand: Connection check failed');
        }
        $io->out('Done');
    }

    public function createDashboard() {
        $hosts = $this->Host->find('all', [
            'recursive' => -1,
            'fields'    => [
                'Host.id',
                'Host.uuid'
            ],
            'contain'   => [
                'Hostgroup'    => [
                    'fields' => [
                        'Hostgroup.id'
                    ]
                ],
                'Hosttemplate' => [
                    'Hostgroup' => [
                        'fields' => [
                            'Hostgroup.id'
                        ]
                    ]
                ]
            ]
        ]);
        $filteredHosts = [];
        if (!empty($hosts)) {
            $filteredHosts = $this->GrafanaConfiguration->filterResults(
                $hosts,
                $this->GrafanaApiConfiguration->getIncludedHostgroups(),
                $this->GrafanaApiConfiguration->getExcludedHostgroups()
            );
        }
        if (!empty($filteredHosts)) {
            $this->GrafanaDashboard->deleteAll(true);
        }
        foreach ($filteredHosts as $id => $hostData) {
            $json = $this->getJsonForImport($id);

            if ($json) {
                $request = new Request('POST', $this->GrafanaApiConfiguration->getApiUrl() . '/dashboards/db', ['content-type' => 'application/json'], $json);
                try {
                    $response = $this->client->send($request);
                } catch (BadResponseException $e) {
                    $response = $e->getResponse();
                    $responseBody = $response->getBody()->getContents();
                    $this->out('<error>' . $responseBody . '</error>');
                }

                if ($response->getStatusCode() == 200) {
                    $this->out('<success>Dashboard for host with id ' . $id . ' created</success>');

                    $responseBody = $response->getBody()->getContents();
                    $responseBody = json_decode($responseBody, true);

                    $this->GrafanaDashboard->create();
                    $this->GrafanaDashboard->save([
                        'GrafanaDashboard' => [
                            'configuration_id' => 1,
                            'host_id'          => $id,
                            'host_uuid'        => $hostData['uuid'],
                            'grafana_uid'      => $responseBody['uid']
                        ]
                    ]);
                }
            }
        }
    }

    public function getJsonForImport($hostId) {
        $host = $this->Host->find('first', [
            'conditions' => [
                'Host.id' => $hostId,
            ],
            'fields'     => [
                'Host.id',
                'Host.name',
                'Host.uuid',
                'Host.address'
            ],
            'contain'    => [
                'Service' => [
                    'fields'          => [
                        'Service.id',
                        'Service.name',
                        'Service.uuid',
                        'Service.servicetemplate_id',
                        'Service.process_performance_data'
                    ],
                    'Servicetemplate' => [
                        'fields' => [
                            'Servicetemplate.id',
                            'Servicetemplate.name',
                            'Servicetemplate.process_performance_data'
                        ]
                    ]
                ]
            ]
        ]);

        $DbBackend = new DbBackend();
        $ServicestatusFields = new ServicestatusFields($DbBackend);
        $ServicestatusFields->perfdata();
        $ServicestatusConditions = new ServicestatusConditions($DbBackend);
        $ServicestatusConditions->perfdataIsNotNull();
        $servicestatus = $this->Servicestatus->byUuid(
            Hash::extract($host, 'Service.{n}.uuid'),
            $ServicestatusFields,
            $ServicestatusConditions
        );

        if (empty($servicestatus)) {
            return false;
        }
        $grafanaDashboard = new GrafanaDashboard();
        $grafanaDashboard->setTitle($host['Host']['uuid']);
        $grafanaDashboard->setEditable(false);
        $grafanaDashboard->setTags($this->tag);
        $grafanaDashboard->setHideControls(true);
        $panelId = 1;
        $internalServiceId = 0;
        $grafanaRow = new GrafanaRow();


        foreach ($host['Service'] as $service) {
            $isRowFull = false;
            $serviceName = $service['name'];
            if ($serviceName === null || $serviceName === '') {
                $serviceName = $service['Servicetemplate']['name'];
            }
            if (!isset($servicestatus[$service['uuid']]['Servicestatus']['perfdata'])) {
                continue;
            }

            $PerfdataParser = new PerfdataParser($servicestatus[$service['uuid']]['Servicestatus']['perfdata']);
            $perfdata = $PerfdataParser->parse();
            $grafanaPanel = new GrafanaPanel($panelId);
            $grafanaPanel->setTitle(sprintf('%s - %s', $host['Host']['name'], $serviceName));
            $grafanaTargetCollection = new GrafanaTargetCollection();
            foreach ($perfdata as $label => $gauge) {
                $Perfdata = Perfdata::fromArray($label, $gauge);
                $grafanaTargetCollection->addTarget(
                    new GrafanaTarget(
                        sprintf(
                            '%s.%s.%s.%s',
                            $this->GrafanaApiConfiguration->getGraphitePrefix(),
                            $host['Host']['uuid'],
                            $service['uuid'],
                            $Perfdata->getReplacedLabel()
                        ),
                        new GrafanaTargetUnit($Perfdata->getUnit()),
                        new GrafanaThresholds($Perfdata->getWarning(), $Perfdata->getCritical()),
                        $Perfdata->getLabel()
                    )
                );
            }
            $grafanaPanel->addTargets(
                $grafanaTargetCollection,
                new GrafanaSeriesOverrides($grafanaTargetCollection),
                new GrafanaYAxes($grafanaTargetCollection),
                new GrafanaThresholdCollection($grafanaTargetCollection)
            );

            if ($panelId % 2 === 0) {
                //Row is full, create a new one
                $grafanaRow->addPanel($grafanaPanel);
                $grafanaDashboard->addRow($grafanaRow);
                $grafanaRow = new GrafanaRow();
                $isRowFull = true;
            } else {
                $grafanaRow->addPanel($grafanaPanel);
            }


            $panelId++;
        }
        if ($grafanaRow->getNumberOfPanels() > 0 && $isRowFull === false) {
            $grafanaDashboard->addRow($grafanaRow);
        }
        return $grafanaDashboard->getGrafanaDashboardJson();
    }


    private function getGrafanaDashboardsByTag($tag) {
        try {
            $request = new Request('GET', $this->GrafanaApiConfiguration->getApiUrl() . '/search?tag=' . $tag);
            $response = $this->client->send($request);
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
            $responseBody = $response->getBody()->getContents();
            $this->out('<error>' . $responseBody . '</error>');
        }
        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $response = json_decode($body->getContents());
            return $response;
        }
    }

    /**
     * Delete all Dashboards with the given tag
     * @param $tag
     */
    private function deleteDashboards($tag) {
        try {
            if (empty($tag)) {
                throw new Exception('No Tag given');
            }

            //Only delete auto generated dashboards
            $dashboards = $this->GrafanaDashboard->find('all', [
                'recursive' => -1,
                'fields'    => [
                    'GrafanaDashboard.host_uuid'
                ]
            ]);


            foreach ($dashboards as $dashboard) {
                $hostUuid = $dashboard['GrafanaDashboard']['host_uuid'];
                $request = new Request('DELETE', $this->GrafanaApiConfiguration->getApiUrl() . '/dashboards/db/' . $hostUuid);
                $response = $this->client->send($request);

                if ($response->getStatusCode() == 200) {
                    $body = $response->getBody();
                    $response = json_decode($body->getContents());
                    $this->out('<success>Dashboard ' . $hostUuid . ' deleted!</success>');
                }
            }
        } catch (Exception $e) {
            $this->out('<error>' . $e->getMessage() . '</error>');
        } catch (BadRequestException $e) {
            $response = $e->getResponse();
            $responseBody = $response->getBody()->getContents();
            $this->out('<error>' . $responseBody . '</error>');
        }
    }
}
