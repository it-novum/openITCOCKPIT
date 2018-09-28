<?php


namespace itnovum\openITCOCKPIT\Grafana;

use Configure;
use GrafanaConfiguration;
use GuzzleHttp\Client;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\ServicestatusFields;
use itnovum\openITCOCKPIT\Core\ValueObjects\Perfdata;
use itnovum\openITCOCKPIT\Grafana\GrafanaApiConfiguration;
use itnovum\openITCOCKPIT\Grafana\GrafanaPanel;
use itnovum\openITCOCKPIT\Grafana\GrafanaRow;
use itnovum\openITCOCKPIT\Grafana\GrafanaSeriesOverrides;
use itnovum\openITCOCKPIT\Grafana\GrafanaTag;
use itnovum\openITCOCKPIT\Grafana\GrafanaTarget;
use itnovum\openITCOCKPIT\Grafana\GrafanaTargetCollection;
use itnovum\openITCOCKPIT\Grafana\GrafanaTargetUnit;
use itnovum\openITCOCKPIT\Grafana\GrafanaThresholdCollection;
use itnovum\openITCOCKPIT\Grafana\GrafanaThresholds;
use itnovum\openITCOCKPIT\Grafana\GrafanaYAxes;
use Statusengine\PerfdataParser;

class GrafanaUserdashboard {


    public $uses = [
        'Systemsetting',
        MONITORING_CORECONFIG_MODEL,
        'Host',
        'Servicetemplate',
        'Hosttemplate',
        'Service',
        'Hostgroup',
        MONITORING_HOSTSTATUS,
        MONITORING_SERVICESTATUS,
        'Servicetemplateeventcommandargumentvalue',
        'Serviceeventcommandargumentvalue',
        'Command',
        'Contact',
        'Contactgroup',
        'Servicegroup',
        'Timeperiod',
        'Macro',
        'Hostescalation',
        'Hostcommandargumentvalue',
        'Servicecommandargumentvalue',
        'Aro',
        'Aco',
        'Calendar',
        'GrafanaModule.GrafanaConfiguration'
    ];

    private $rows = [];

    private $title = 'User Generated Dashboard';

    private $editable = true;

    private $hideControls = true;

    public function createUserdashboard() {

        //Load DbBackend to Testing Shell
        Configure::load('dbbackend');
        $this->DbBackend = new DbBackend(Configure::read('dbbackend'));


        //test

       // $grafanaUserdashboardConfig = $this->creationTestData();

        //test end

        $grafanaUserdashboardConfig = $this->rows;


        //Remove loadModel testing stuff
      /*  $this->loadModel('Host');
        $this->loadModel('Service');
        $this->loadModel('Proxy');
        $this->loadModel(MONITORING_SERVICESTATUS);
        $this->loadModel('GrafanaModule.GrafanaConfiguration');
*/

        $this->GrafanaConfiguration = new GrafanaConfiguration();
        $grafanaConfiguration = $this->GrafanaConfiguration->find('first', [
            'recursive' => -1,
            'contain'   => [
                'GrafanaConfigurationHostgroupMembership'
            ]
        ]);

        if (empty($grafanaConfiguration)) {
            throw new RuntimeException('No Grafana configuration found');
        }

        $GrafanaApiConfiguration = GrafanaApiConfiguration::fromArray($grafanaConfiguration);
        $client = $this->GrafanaConfiguration->testConnection($GrafanaApiConfiguration, $this->Proxy->getSettings());

        $panelId = 1;
        if ($client instanceof Client) {
            $this->out('<success>Connection check successful</success>');
            $tag = new GrafanaTag();

            $GrafanaDashboard = new \itnovum\openITCOCKPIT\Grafana\GrafanaDashboard();
            $GrafanaDashboard->setTitle($this->title);
            $GrafanaDashboard->setEditable($this->editable); //Set to false for production
            $GrafanaDashboard->setTags($tag->getTag());
            $GrafanaDashboard->setHideControls($this->hideControls);

            foreach ($grafanaUserdashboardConfig as $row) {
                $GrafanaRow = new GrafanaRow();
                foreach ($row as $panel) {
                    $GrafanaTargetCollection = new GrafanaTargetCollection();
                    $SpanSize = 6;
                    $GrafanaPanel = new GrafanaPanel($panelId, $SpanSize);
                    $GrafanaPanel->setTitle($panelId . 'User entered panel title');

                    foreach ($panel as $metrics) {
                        $service = $this->Service->find('first', [
                            'recursive'  => -1,
                            'contain'    => [
                                'Servicetemplate' => [
                                    'fields' => [
                                        'Servicetemplate.name'
                                    ],
                                ],
                            ],
                            'joins'      => [
                                [
                                    'table'      => 'hosts',
                                    'alias'      => 'Host',
                                    'type'       => 'INNER',
                                    'conditions' => [
                                        'Host.id = Service.host_id'
                                    ]
                                ]
                            ],
                            'fields'     => [
                                'Service.id',
                                'Service.name',
                                'Service.uuid',
                                'Host.id',
                                'Host.name',
                                'Host.uuid'
                            ],
                            'conditions' => [
                                'Service.id' => $metrics['Service']['id'],

                            ],
                        ]);

                        $Host = new \itnovum\openITCOCKPIT\Core\Views\Host($service);
                        $Service = new \itnovum\openITCOCKPIT\Core\Views\Service($service);
                        $ServicestatusFields = new ServicestatusFields($this->DbBackend);
                        $ServicestatusFields->perfdata();


                        $servicestatus = $this->Servicestatus->byUuid($Service->getUuid(), $ServicestatusFields);
                        $Servicestatus = new \itnovum\openITCOCKPIT\Core\Servicestatus($servicestatus['Servicestatus']);
                        $PerfdataParser = new PerfdataParser($Servicestatus->getPerfdata());
                        $parsedPerfdata = $PerfdataParser->parse();


                        $Perfdata = Perfdata::fromArray($metrics['Service']['metric'], $parsedPerfdata[$metrics['Service']['metric']]);


                        $GrafanaTargetCollection->addTarget(
                            new GrafanaTarget(
                                sprintf(
                                    '%s.%s.%s.%s',
                                    $GrafanaApiConfiguration->getGraphitePrefix(),
                                    $Host->getUuid(),
                                    $Service->getUuid(),
                                    $Perfdata->getReplacedLabel()
                                ),
                                new GrafanaTargetUnit($Perfdata->getUnit()),
                                new GrafanaThresholds($Perfdata->getWarning(), $Perfdata->getCritical()),
                                $Perfdata->getLabel()
                            ));
                    } //End of metrics foreach

                    //Create current panel with multiple metrics
                    $GrafanaPanel->addTargets(
                        $GrafanaTargetCollection,
                        new GrafanaSeriesOverrides($GrafanaTargetCollection),
                        new GrafanaYAxes($GrafanaTargetCollection),
                        new GrafanaThresholdCollection($GrafanaTargetCollection)
                    );
                    $GrafanaRow->addPanel($GrafanaPanel);
                    $panelId++;
                } //End of panel foreach
                $GrafanaDashboard->addRow($GrafanaRow);
            } //End Grafana Row

            $json = $GrafanaDashboard->getGrafanaDashboardJson();

            //print_r(json_decode($json, true)); return;
debug($json);
            if ($json) {
                $request = new \GuzzleHttp\Psr7\Request('POST', $GrafanaApiConfiguration->getApiUrl() . '/dashboards/db', ['content-type' => 'application/json'], $json);
                try {
                    $response = $client->send($request);
                } catch (BadRequestException $e) {
                    $response = $e->getResponse();
                    $responseBody = $response->getBody()->getContents();
                    $this->out('<error>' . $responseBody . '</error>');
                }

                if ($response->getStatusCode() == 200) {
                    $this->out('<success>Dashboard created</success>');
                }
            }


        } else {
            $this->out('<error>' . $client . '</error>');
            $this->out('<error>Connection check failed</error>');
        }

    }

    public function setRows($rows){
        $this->rows = $rows;
    }

    public function setTitle($title){
        $this->title = $title;
    }

    public function setHideControls($hideControls){
        $this->hideControls = $hideControls;
    }

    public function setEditable($editable){
        $this->editable = $editable;
    }

    public function creationTestData(){
        return [
            [ //Row1
                [ //Panel 1
                    [ //Metric 1 in Panel 1
                        'Host'    => [
                            'id' => 1,
                        ],
                        'Service' => [
                            'id'     => 2,
                            'metric' => '/'
                        ],
                    ],
                    [ //Metric 2 in Panel 1
                        'Host'    => [
                            'id' => 151,
                        ],
                        'Service' => [
                            'id'     => 798,
                            'metric' => '/'
                        ],
                    ],
                ],

                [ //Panel 2
                    [ //Metric 1 in Panel 1
                        'Host'    => [
                            'id' => 1,
                        ],
                        'Service' => [
                            'id'     => 2,
                            'metric' => '/'
                        ],
                    ],
                    [ //Metric 2 in Panel 1
                        'Host'    => [
                            'id' => 151,
                        ],
                        'Service' => [
                            'id'     => 798,
                            'metric' => '/'
                        ],
                    ],
                ]
            ],

            [ //Row2
                [ //Panel 1
                    [ //Metric 1 in Panel 1 in Row2
                        'Host'    => [
                            'id' => 1,
                        ],
                        'Service' => [
                            'id'     => 2,
                            'metric' => '/'
                        ],
                    ],
                    [ //Metric 2 in Panel 1 in Row2
                        'Host'    => [
                            'id' => 151,
                        ],
                        'Service' => [
                            'id'     => 798,
                            'metric' => '/'
                        ],
                    ],
                ],

                [ //Panel 2
                    [ //Metric 1 in Panel 1 in Row2
                        'Host'    => [
                            'id' => 1,
                        ],
                        'Service' => [
                            'id'     => 2,
                            'metric' => '/'
                        ],
                    ],
                    [ //Metric 2 in Panel 1 in Row2
                        'Host'    => [
                            'id' => 151,
                        ],
                        'Service' => [
                            'id'     => 798,
                            'metric' => '/'
                        ],
                    ],
                ]
            ]
        ];
    }

}