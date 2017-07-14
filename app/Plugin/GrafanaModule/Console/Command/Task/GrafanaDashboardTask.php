<?php

use GuzzleHttp\Client;
use \itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\BadResponseException;
use itnovum\openITCOCKPIT\Core\ValueObjects\Perfdata;
use itnovum\openITCOCKPIT\Grafana\GrafanaDashboard;
use itnovum\openITCOCKPIT\Grafana\GrafanaPanel;
use itnovum\openITCOCKPIT\Grafana\GrafanaRow;
use itnovum\openITCOCKPIT\Grafana\GrafanaSeriesOverrides;
use itnovum\openITCOCKPIT\Grafana\GrafanaTarget;
use itnovum\openITCOCKPIT\Grafana\GrafanaTargetCollection;
use itnovum\openITCOCKPIT\Grafana\GrafanaTargetUnit;
use itnovum\openITCOCKPIT\Grafana\GrafanaThresholdCollection;
use itnovum\openITCOCKPIT\Grafana\GrafanaThresholds;
use itnovum\openITCOCKPIT\Grafana\GrafanaYAxes;
use \itnovum\openITCOCKPIT\Grafana\GrafanaApiConfiguration;

class GrafanaDashboardTask extends AppShell implements CronjobInterface {

    public $uses = [
        'Host',
        'Servicetemplate',
        'Service',
        MONITORING_HOSTSTATUS,
        MONITORING_SERVICESTATUS,
        'Rrd',
        'GrafanaModule.GrafanaConfiguration',
        'GrafanaModule.GrafanaConfigurationHostgroupMembership',
        'GrafanaModule.GrafanaDashboard'
    ];

    public $client = [];

    /**
     * @var GrafanaConfiguration
     */
    public $GrafanaApiConfiguration;

    public function execute($quiet = false) {
        $grafanaConfiguration = $this->GrafanaConfiguration->find('first', [
            'recursive' => -1,
            'contain' => [
                'GrafanaConfigurationHostgroupMembership'
            ]
        ]);
        if (empty($grafanaConfiguration)) {
            $this->out('<error>No Grafana configuration found</error>');
        }
        $this->GrafanaApiConfiguration = GrafanaApiConfiguration::fromArray($grafanaConfiguration);
        $this->out('Check Connection to Grafana');
        $this->client = $this->GrafanaConfiguration->testConnection($this->GrafanaApiConfiguration);

        if ($this->client instanceof Client) {
            $this->out('<success>Connection check successful</success>');
            $this->createDashboard();
        } else {
            $this->out('<error>' . $this->client . '</error>');
            $this->out('<error>Connection check failed</error>');
        }
        $this->out('Done');
    }

    public function createDashboard() {
        $hosts = $this->Host->find('all', [
            'recursive' => -1,
            'fields' => [
                'Host.id',
                'Host.uuid'
            ],
            'contain' => [
                'Hostgroup' => [
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
                    $this->GrafanaDashboard->create();
                    $this->GrafanaDashboard->save([
                        'GrafanaDashboard' => [
                            'configuration_id' => 1,
                            'host_id'=> $id,
                            'host_uuid' => $hostData['uuid']
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
            'fields' => [
                'Host.id',
                'Host.name',
                'Host.uuid',
                'Host.address'
            ],
            'contain' => [
                'Service' => [
                    'fields' => [
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
        $servicestatus = $this->Servicestatus->byUuid(Hash::extract($host, 'Service.{n}.uuid'), [
            'conditions' => [
                'Servicestatus.perfdata IS NOT NULL'
            ]
        ]);
        if (empty($servicestatus)) {
            return false;
        }
        $grafanaDashboard = new GrafanaDashboard();
        $grafanaDashboard->setTitle($host['Host']['uuid']);
        $grafanaDashboard->setEditable(false);
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

            $perfdata = $this->Rrd->parsePerfData($servicestatus[$service['uuid']]['Servicestatus']['perfdata']);
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
}
