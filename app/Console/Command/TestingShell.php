<?php
// Copyright (C) <2015>  <it-novum GmbH>
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

class TestingShell extends AppShell {
    /*
     * This is a test and debuging shell for development purposes
     */
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
        'Calendar'
    ];

    public function main() {
        //debug($this->Aro->find('all'));
        //debug($this->Aco->find('all', ['recursive' => -1]));
        /*
         * Lof of space for your experimental code :)
         */

        //Load DbBackend to Testing Shell
        Configure::load('dbbackend');
        $this->DbBackend = new DbBackend(Configure::read('dbbackend'));

        $hardcodedExampleConfig = [
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

        //Remove loadModel testing stuff
        $this->loadModel('Host');
        $this->loadModel('Service');
        $this->loadModel('Proxy');
        $this->loadModel(MONITORING_SERVICESTATUS);
        $this->loadModel('GrafanaModule.GrafanaConfiguration');


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
            $GrafanaDashboard->setTitle('Hier ein toller tittel vom user?');
            $GrafanaDashboard->setEditable(true); //Set to false for production
            $GrafanaDashboard->setTags($tag->getTag());
            $GrafanaDashboard->setHideControls(true);

            foreach ($hardcodedExampleConfig as $row) {
                $GrafanaRow = new GrafanaRow();
                foreach ($row as $panel) {
                    $GrafanaTargetCollection = new GrafanaTargetCollection();
                    $SpanSize = 6;
                    $GrafanaPanel = new GrafanaPanel($panelId, $SpanSize);
                    $GrafanaPanel->setTitle($panelId.'User entered panel title');

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

    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'type'     => ['short' => 't', 'help' => __d('oitc_console', 'Type of the notification host or service')],
            'hostname' => ['help' => __d('oitc_console', 'The uuid of the host')],
        ]);

        return $parser;
    }
}
