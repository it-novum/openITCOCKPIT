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

use itnovum\openITCOCKPIT\Grafana\GrafanaDashboard;
use itnovum\openITCOCKPIT\Grafana\GrafanaPanel;
use itnovum\openITCOCKPIT\Grafana\GrafanaRow;
use itnovum\openITCOCKPIT\Grafana\GrafanaSeriesOverrides;
use itnovum\openITCOCKPIT\Grafana\GrafanaTarget;
use itnovum\openITCOCKPIT\Grafana\GrafanaTargetCollection;
use itnovum\openITCOCKPIT\Grafana\GrafanaTargetUnit;
use itnovum\openITCOCKPIT\Grafana\GrafanaYAxes;

class TestShell extends AppShell {
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
        'Rrd'
    ];

    public function main() {
        //debug($this->Aro->find('all'));
        //debug($this->Aco->find('all', ['recursive' => -1]));
        /*
         * Lof of space for your experimental code :)
         */
        $host = $this->Host->find('first', [
            'conditions' => [
                'Host.id' => 1,
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

        $servicestatus = $this->Servicestatus->byUuid(Hash::extract($host, 'Service.{n}.uuid'));

        $grafanaDashboard = new GrafanaDashboard();
        $grafanaDashboard->setTitle($host['Host']['uuid']);
        $grafanaDashboard->setEditable(true);
        $grafanaDashboard->setHideControls(false);

        $panelId = 1;

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

                $grafanaTargetCollection->addTarget(
                    new GrafanaTarget(
                        sprintf(
                            '%s.%s.%s.%s',
                            'ibering',
                            $host['Host']['uuid'],
                            $service['uuid'],
                            $label
                        ),
                        new GrafanaTargetUnit($gauge['unit']),
                        $label
                    )
                );
            }

            $grafanaPanel->addTargets(
                $grafanaTargetCollection,
                new GrafanaSeriesOverrides($grafanaTargetCollection),
                new GrafanaYAxes($grafanaTargetCollection)
            );
            if($grafanaRow->getNumberOfPanels() === 2 && ($panelId % 2 === 0)){
                //Row is full, create a new one
                $grafanaRow = new GrafanaRow();
                $grafanaRow->addPanel($grafanaPanel);
                $isRowFull=true;
            }else{
                $grafanaRow->addPanel($grafanaPanel);
            }

            $panelId++;
            if(sizeof($host['Service']) == $panelId && $isRowFull === false){
                $grafanaDashboard->addRow($grafanaRow);
            }
        }

        debug($grafanaDashboard->getGrafanaDashboardJson());

    }

    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'type' => ['short' => 't', 'help' => __d('oitc_console', 'Type of the notification host or service')],
            'hostname' => ['help' => __d('oitc_console', 'The uuid of the host')],
        ]);

        return $parser;
    }
}
