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
            debug($id);
            $json = $this->getJsonForImport($id);

           // $json = $this->json2;
            debug($json);
            $request = new Request('POST', $this->grafanaHost . '/dashboards/db', ['content-type' => 'application/json'], $json);
            try {
                $response = $this->client->send($request);
            } catch (BadResponseException $e) {
                $response = $e->getResponse();

                $responseBody = $response->getBody()->getContents();
                debug($response->getStatusCode());
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

    public $json2 = '{  
   "dashboard":{  
      "annotations":{  
         "list":[  

         ]
      },
      "editable":true,
      "gnetId":null,
      "graphTooltip":0,
      "hideControls":false,
      "id":null,
      "links":[  

      ],
      "rows":[  
         {  
            "collapse":false,
            "height":"250px",
            "panels":[  

            ],
            "repeat":null,
            "repeatIteration":null,
            "repeatRowId":null,
            "showTitle":false,
            "title":"Dashboard Row",
            "titleSize":"h6"
         }
      ],
      "schemaVersion":14,
      "style":"dark",
      "tags":[  

      ],
      "templating":{  
         "list":[  

         ]
      },
      "time":{  
         "from":"now-6h",
         "to":"now"
      },
      "timepicker":{  
         "refresh_intervals":[  
            "5s",
            "10s",
            "30s",
            "1m",
            "5m",
            "15m",
            "30m",
            "1h",
            "2h",
            "1d"
         ],
         "time_options":[  
            "5m",
            "15m",
            "1h",
            "6h",
            "12h",
            "24h",
            "2d",
            "7d",
            "30d"
         ]
      },
      "timezone":"browser",
      "title":"test 123123123",
      "version":0
   },
   "overwrite":false
   }';

    public $json = '{
  "__inputs": [
    {
      "name": "DS_GRAPHITE",
      "label": "Graphite",
      "description": "",
      "type": "datasource",
      "pluginId": "graphite",
      "pluginName": "Graphite"
    }
],
"__requires": [
    {
        "type": "grafana",
      "id": "grafana",
      "name": "Grafana",
      "version": "4.1.2"
    },
    {
        "type": "panel",
      "id": "graph",
      "name": "Graph",
      "version": ""
    },
    {
        "type": "datasource",
      "id": "graphite",
      "name": "Graphite",
      "version": "1.0.0"
    }
  ],
  "annotations": {
    "list": []
  },
  "editable": true,
  "gnetId": null,
  "graphTooltip": 0,
  "hideControls": false,
  "id": null,
  "links": [],
  "rows": [
    {
        "collapse": false,
      "height": "250px",
      "panels": [
        {
            "aliasColors": [],
          "bars": false,
          "datasource": "${DS_GRAPHITE}",
          "fill": 1,
          "id": 1,
          "legend": {
            "alignAsTable": true,
            "avg": true,
            "current": true,
            "hideEmpty": false,
            "hideZero": false,
            "max": true,
            "min": true,
            "show": true,
            "total": false,
            "values": true
          },
          "lines": true,
          "linewidth": 1,
          "links": [],
          "nullPointMode": "connected",
          "percentage": false,
          "pointradius": 5,
          "points": false,
          "renderer": "flot",
          "seriesOverrides": [
            {
                "alias": "rta",
              "yaxis": 1
            },
            {
                "alias": "pl",
              "yaxis": 2
            }
          ],
          "span": 6,
          "stack": false,
          "steppedLine": false,
          "targets": [
            {
                "refId": "A",
              "target": "alias(mp_grafana.c36b8048-93ce-4385-ac19-ab5c90574b77.74fd8f59-1348-4e16-85f0-4a5c57c7dd62.rta, "rta")"
            },
            {
                "refId": "B",
              "target": "alias(mp_grafana.c36b8048-93ce-4385-ac19-ab5c90574b77.74fd8f59-1348-4e16-85f0-4a5c57c7dd62.pl, "pl")"
            }
          ],
          "thresholds": [],
          "timeFrom": null,
          "timeShift": null,
          "title": "default host - Ping",
          "tooltip": {
            "shared": true,
            "sort": 0,
            "value_type": "individual"
          },
          "type": "graph",
          "xaxis": {
            "mode": "time",
            "name": null,
            "show": true,
            "values": []
          },
          "yaxes": [
            {
                "format": "ms",
              "label": null,
              "logBase": 1,
              "max": null,
              "min": null,
              "show": true
            },
            {
                "format": "percent",
              "label": null,
              "logBase": 1,
              "max": 100,
              "min": 0,
              "show": true
            }
          ]
        },
        {
            "aliasColors": [],
          "bars": false,
          "datasource": "${DS_GRAPHITE}",
          "fill": 1,
          "id": 2,
          "legend": {
            "alignAsTable": true,
            "avg": true,
            "current": true,
            "hideEmpty": false,
            "hideZero": false,
            "max": true,
            "min": true,
            "show": true,
            "total": false,
            "values": true
          },
          "lines": true,
          "linewidth": 1,
          "links": [],
          "nullPointMode": "connected",
          "percentage": false,
          "pointradius": 5,
          "points": false,
          "renderer": "flot",
          "seriesOverrides": [],
          "span": 6,
          "stack": false,
          "steppedLine": false,
          "targets": [
            {
                "refId": "A",
              "target": "alias(mp_grafana.c36b8048-93ce-4385-ac19-ab5c90574b77.74f14950-a58f-4f18-b6c3-5cfa9dffef4e._, "/")"
            }
          ],
          "thresholds": [
            {
                "colorMode": "critical",
              "fill": true,
              "line": true,
              "op": "lt",
              "value": 10936
            },
            {
                "colorMode": "warning",
              "fill": true,
              "line": true,
              "op": "lt",
              "value": 10946
            }
          ],
          "timeFrom": null,
          "timeShift": null,
          "title": "default host - CHECK_LOCAL_DISK",
          "tooltip": {
            "shared": true,
            "sort": 0,
            "value_type": "individual"
          },
          "type": "graph",
          "xaxis": {
            "mode": "time",
            "name": null,
            "show": true,
            "values": []
          },
          "yaxes": [
            {
                "format": "mbytes",
              "label": null,
              "logBase": 1,
              "max": null,
              "min": null,
              "show": true
            },
            {
                "format": "short",
              "label": null,
              "logBase": 1,
              "max": null,
              "min": null,
              "show": true
            }
          ]
        },
        {
            "aliasColors": [],
          "bars": false,
          "datasource": "${DS_GRAPHITE}",
          "fill": 1,
          "id": 3,
          "legend": {
            "alignAsTable": true,
            "avg": true,
            "current": true,
            "hideEmpty": false,
            "hideZero": false,
            "max": true,
            "min": true,
            "show": true,
            "total": false,
            "values": true
          },
          "lines": true,
          "linewidth": 1,
          "links": [],
          "nullPointMode": "connected",
          "percentage": false,
          "pointradius": 5,
          "points": false,
          "renderer": "flot",
          "seriesOverrides": [],
          "span": 6,
          "stack": false,
          "steppedLine": false,
          "targets": [
            {
                "refId": "A",
              "target": "alias(mp_grafana.c36b8048-93ce-4385-ac19-ab5c90574b77.1c045407-5502-4468-aabc-7781f6cf3dec.load1, "load1")"
            },
            {
                "refId": "B",
              "target": "alias(mp_grafana.c36b8048-93ce-4385-ac19-ab5c90574b77.1c045407-5502-4468-aabc-7781f6cf3dec.load5, "load5")"
            },
            {
                "refId": "C",
              "target": "alias(mp_grafana.c36b8048-93ce-4385-ac19-ab5c90574b77.1c045407-5502-4468-aabc-7781f6cf3dec.load15, "load15")"
            }
          ],
          "thresholds": [],
          "timeFrom": null,
          "timeShift": null,
          "title": "default host - CHECK_LOCAL_LOAD",
          "tooltip": {
            "shared": true,
            "sort": 0,
            "value_type": "individual"
          },
          "type": "graph",
          "xaxis": {
            "mode": "time",
            "name": null,
            "show": true,
            "values": []
          },
          "yaxes": [
            {
                "format": "none",
              "label": null,
              "logBase": 1,
              "max": null,
              "min": null,
              "show": true
            },
            {
                "format": "short",
              "label": null,
              "logBase": 1,
              "max": null,
              "min": null,
              "show": true
            }
          ]
        },
        {
            "aliasColors": [],
          "bars": false,
          "datasource": "${DS_GRAPHITE}",
          "fill": 1,
          "id": 4,
          "legend": {
            "alignAsTable": true,
            "avg": true,
            "current": true,
            "hideEmpty": false,
            "hideZero": false,
            "max": true,
            "min": true,
            "show": true,
            "total": false,
            "values": true
          },
          "lines": true,
          "linewidth": 1,
          "links": [],
          "nullPointMode": "connected",
          "percentage": false,
          "pointradius": 5,
          "points": false,
          "renderer": "flot",
          "seriesOverrides": [],
          "span": 6,
          "stack": false,
          "steppedLine": false,
          "targets": [
            {
                "refId": "A",
              "target": "alias(mp_grafana.c36b8048-93ce-4385-ac19-ab5c90574b77.7391f1aa-5e2e-447a-8a9b-b23357b9cd2a.users, "users")"
            }
          ],
          "thresholds": [
            {
                "colorMode": "warning",
              "fill": true,
              "line": true,
              "op": "gt",
              "value": 3
            },
            {
                "colorMode": "critical",
              "fill": true,
              "line": true,
              "op": "gt",
              "value": 7
            }
          ],
          "timeFrom": null,
          "timeShift": null,
          "title": "default host - CHECK_LOCAL_USERS",
          "tooltip": {
            "shared": true,
            "sort": 0,
            "value_type": "individual"
          },
          "type": "graph",
          "xaxis": {
            "mode": "time",
            "name": null,
            "show": true,
            "values": []
          },
          "yaxes": [
            {
                "format": "none",
              "label": null,
              "logBase": 1,
              "max": null,
              "min": null,
              "show": true
            },
            {
                "format": "short",
              "label": null,
              "logBase": 1,
              "max": null,
              "min": null,
              "show": true
            }
          ]
        },
        {
            "aliasColors": [],
          "bars": false,
          "datasource": "${DS_GRAPHITE}",
          "fill": 1,
          "id": 6,
          "legend": {
            "alignAsTable": true,
            "avg": true,
            "current": true,
            "hideEmpty": false,
            "hideZero": false,
            "max": true,
            "min": true,
            "show": true,
            "total": false,
            "values": true
          },
          "lines": true,
          "linewidth": 1,
          "links": [],
          "nullPointMode": "connected",
          "percentage": false,
          "pointradius": 5,
          "points": false,
          "renderer": "flot",
          "seriesOverrides": [
            {
                "alias": "rta",
              "yaxis": 1
            },
            {
                "alias": "pl",
              "yaxis": 2
            }
          ],
          "span": 6,
          "stack": false,
          "steppedLine": false,
          "targets": [
            {
                "refId": "A",
              "target": "alias(mp_grafana.c36b8048-93ce-4385-ac19-ab5c90574b77.54b9573b-bee2-4635-a756-e439d457e872.rta, "rta")"
            },
            {
                "refId": "B",
              "target": "alias(mp_grafana.c36b8048-93ce-4385-ac19-ab5c90574b77.54b9573b-bee2-4635-a756-e439d457e872.pl, "pl")"
            }
          ],
          "thresholds": [],
          "timeFrom": null,
          "timeShift": null,
          "title": "default host - PZE_PING",
          "tooltip": {
            "shared": true,
            "sort": 0,
            "value_type": "individual"
          },
          "type": "graph",
          "xaxis": {
            "mode": "time",
            "name": null,
            "show": true,
            "values": []
          },
          "yaxes": [
            {
                "format": "ms",
              "label": null,
              "logBase": 1,
              "max": null,
              "min": null,
              "show": true
            },
            {
                "format": "percent",
              "label": null,
              "logBase": 1,
              "max": 100,
              "min": 0,
              "show": true
            }
          ]
        },
        {
            "aliasColors": [],
          "bars": false,
          "datasource": "${DS_GRAPHITE}",
          "fill": 1,
          "id": 8,
          "legend": {
            "alignAsTable": true,
            "avg": true,
            "current": true,
            "hideEmpty": false,
            "hideZero": false,
            "max": true,
            "min": true,
            "show": true,
            "total": false,
            "values": true
          },
          "lines": true,
          "linewidth": 1,
          "links": [],
          "nullPointMode": "connected",
          "percentage": false,
          "pointradius": 5,
          "points": false,
          "renderer": "flot",
          "seriesOverrides": [],
          "span": 6,
          "stack": false,
          "steppedLine": false,
          "targets": [
            {
                "refId": "A",
              "target": "alias(mp_grafana.c36b8048-93ce-4385-ac19-ab5c90574b77.b1996fcb-4262-47fb-8228-66c7d46110fd._select_, "select")"
            }
          ],
          "thresholds": [
            {
                "colorMode": "warning",
              "fill": true,
              "line": true,
              "op": "gt",
              "value": 200
            },
            {
                "colorMode": "critical",
              "fill": true,
              "line": true,
              "op": "gt",
              "value": 300
            }
          ],
          "timeFrom": null,
          "timeShift": null,
          "title": "default host - perfdataQuoteOutput",
          "tooltip": {
            "shared": true,
            "sort": 0,
            "value_type": "individual"
          },
          "type": "graph",
          "xaxis": {
            "mode": "time",
            "name": null,
            "show": true,
            "values": []
          },
          "yaxes": [
            {
                "format": "none",
              "label": null,
              "logBase": 1,
              "max": null,
              "min": null,
              "show": true
            },
            {
                "format": "short",
              "label": null,
              "logBase": 1,
              "max": null,
              "min": null,
              "show": true
            }
          ]
        }
      ],
      "repeat": null,
      "repeatIteration": null,
      "repeatRowId": null,
      "showTitle": false,
      "title": "Dashboard Row",
      "titleSize": "h6"
    }
  ],
  "schemaVersion": 14,
  "style": "dark",
  "tags": [],
  "templating": {
    "list": []
  },
  "time": {
    "from": "now-6h",
    "to": "now"
  },
  "timepicker": {
    "refresh_intervals": [
        "5s",
        "10s",
        "30s",
        "1m",
        "5m",
        "15m",
        "30m",
        "1h",
        "2h",
        "1d"
    ],
    "time_options": [
        "5m",
        "15m",
        "1h",
        "6h",
        "12h",
        "24h",
        "2d",
        "7d",
        "30d"
    ]
  },
  "timezone": "browser",
  "title": "c36b8048-93ce-4385-ac19-ab5c90574b77",
  "version": 0
}';

}