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

class GrafanaDashboardTask extends AppShell implements CronjobInterface {

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

    public $grafanaHost = 'https://metrics.oitc.itn/api';
    public $grafanaApiKey = 'eyJrIjoiMnYxdUlnWEQyazdPSVg4aEVxeVhycmVlUlAzSW1zMDIiLCJuIjoib3BlbklUQyIsImlkIjoxfQ==';
    public $graphitePrefix = 'mp_grafana';
    public $https = true;
    public $verifySSL = false;
    public $hostgroups = [6];
    public $hostgroupsExclude = [];

    public $client = [];

    public function execute($quiet = false) {

        //wenn hostgroup leer dann für alle Hosts dashboard erstellen.
        //wenn hostgruppe dann auflösen auf hosts und hostgroup
        //wenn exclude hostgruppe dann schnittmenge aus hostgruppen
        $this->out('Check Connection to Grafana');
        if ($this->testConnection()) {
            $this->out('<success>Connection check successful</success>');
            $this->createDashboard();
        } else {
            $this->out('<error>Connection check failed</error>');
        }
        $this->out('Done');
    }

    public function testConnection() {
        $this->client = new Client(['headers' => ['authorization' => 'Bearer ' . $this->grafanaApiKey], 'verify' => $this->verifySSL]);
        $request = new Request('GET', $this->grafanaHost . '/org');
        try {
            $response = $this->client->send($request);
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
            $responseBody = $response->getBody()->getContents();
            $this->out('<error>' . $responseBody . '</error>');
            return false;
        }

        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $response = json_decode($body->getContents());
            return true;
        }
    }

    public function createDashboard() {
        if (empty($hostgroups)) {
            //take all hosts
            $hostIds = $this->Host->find('list');
            //override for testing purposes cuz we dont want to cleanup all the dashboards yet :D
            $hostids = [1 => 1];
        }

        foreach ($hostids as $id => $name) {
            $json = $this->getJsonForImport($id);
            $request = new Request('POST', $this->grafanaHost . '/dashboards/db', ['content-type' => 'application/json'], $json);
            try {
                $response = $this->client->send($request);
            } catch (BadResponseException $e) {
                $response = $e->getResponse();
                $responseBody = $response->getBody()->getContents();
                $this->out('<error>' . $responseBody . '</error>');
            }

            if ($response->getStatusCode() == 200) {
                $this->out('<success>Dashboard for host with id ' . $id . ' created</success>');
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
        $servicestatus = $this->Servicestatus->byUuid(Hash::extract($host, 'Service.{n}.uuid'));

        $grafanaDashboard = new GrafanaDashboard();
        $grafanaDashboard->setTitle($host['Host']['uuid']);
        $grafanaDashboard->setEditable(false);
        $grafanaDashboard->setHideControls(true);

        $panelId = 1;
        $grafanaRow = new GrafanaRow();
        foreach ($host['Service'] as $service) {
            $isRowFull = false;
            $serviceName = $service['name'];
            if ($serviceName === null || $serviceName === '') {
                $serviceName = $service['Servicetemplate']['name'];
            }
            if (!isset($servicestatus[$service['uuid']]['Servicestatus']['perfdata'])) {
                $panelId++;
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
                            'mp_grafana',
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
            if ($grafanaRow->getNumberOfPanels() === 2 && ($panelId % 2 === 0)) {
                //Row is full, create a new one
                $grafanaRow = new GrafanaRow();
                $grafanaRow->addPanel($grafanaPanel);
                $isRowFull = true;
            } else {
                $grafanaRow->addPanel($grafanaPanel);
            }

            if ((sizeof($host['Service']) == $panelId && $isRowFull === false)) {
                $grafanaDashboard->addRow($grafanaRow);
            }
            $panelId++;
        }
        return $grafanaDashboard->getGrafanaDashboardJson();
    }

}