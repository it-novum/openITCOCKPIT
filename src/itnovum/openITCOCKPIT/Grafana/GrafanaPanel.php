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


namespace itnovum\openITCOCKPIT\Grafana;


use App\itnovum\openITCOCKPIT\Grafana\GrafanaFieldConfigDefaults;
use App\itnovum\openITCOCKPIT\Grafana\GrafanaPanelOverrides;

class GrafanaPanel {

    /**
     * @var string|null
     */
    private $datasource = null;

    /**
     * @var string
     */
    private $title;

    /**
     * @var array
     */
    private $targets = [];

    /**
     * @var int
     */
    private $panelId;

    /**
     * @var GrafanaSeriesOverrides
     */
    private $SeriesOverrides;

    /**
     * @var GrafanaYAxes
     */
    private $YAxes;

    /**
     * @var int
     */
    private $span = 6;

    /**
     * @var array
     */
    private $color = [];

    /**
     * @var array
     */
    private $panelOverrides = [];

    /**
     * @var array
     */
    private $GrafanaFieldConfigDefaults = [];

    /**
     * Definition of the panel graph type
     * @var string
     */
    private $type = 'timeseries';

    /**
     * @var array
     * @deprecated this is the graph(old) format of grafana which is marked as deprecated
     * see: https://grafana.com/docs/grafana/latest/visualizations/graph-panel/
     */
    private $panel_old = [
        "aliasColors"     => [
            //Insert colors here
        ],
        "bars"            => false,
        //"datasource"      => null,
        "datasource"      => '-- Mixed --',
        "fill"            => 1,
        "id"              => null,
        "legend"          => [
            "alignAsTable" => true,
            "avg"          => true,
            "current"      => true,
            "hideEmpty"    => false,
            "hideZero"     => false,
            "max"          => true,
            "min"          => true,
            "show"         => true,
            "total"        => false,
            "values"       => true
        ],
        "lines"           => true,
        "linewidth"       => 1,
        "links"           => [],
        "nullPointMode"   => "connected",
        "percentage"      => false,
        "pointradius"     => 5,
        "points"          => false,
        "renderer"        => "flot",
        "seriesOverrides" => [],
        "span"            => 6,
        "stack"           => false,
        "steppedLine"     => false,
        "targets"         => [
            //Insert targets here
        ],
        "thresholds"      => [
            //Insert thresholds here
        ],
        "timeFrom"        => null,
        "timeShift"       => null,
        "title"           => "",
        "tooltip"         => [
            "shared"     => true,
            "sort"       => 0,
            "value_type" => "individual"
        ],
        "type"            => "graph",
        "xaxis"           => [
            "mode"   => "time",
            "name"   => null,
            "show"   => true,
            "values" => []
        ],
        "yaxes"           => [
            //Insert yaxes here
        ]
    ];

    /**
     * Hybrid structure. Contains the old one with elements from new structure.
     * @var array
     */
    private $panel_merged = [
        "aliasColors"     => [ // old setter color of the metric, not working anymore
            //Insert colors here
        ],
        "bars"            => false,
        //"datasource"      => null,
        "datasource"      => '-- Mixed --',
        "fill"            => 1,
        "id"              => null,
        "legend"          => [ // may obsolete -> see: legend->calcs
            "alignAsTable" => true,
            "avg"          => true,
            "current"      => true,
            "hideEmpty"    => false,
            "hideZero"     => false,
            "max"          => true,
            "min"          => true,
            "show"         => true,
            "total"        => false,
            "values"       => true
        ],
        "lines"           => true,
        "linewidth"       => 1,
        "links"           => [],
        "nullPointMode"   => "connected",
        "percentage"      => false,
        "pointradius"     => 5,
        "points"          => false,
        "renderer"        => "flot",
        "seriesOverrides" => [],
        "span"            => 6,
        "stack"           => false, // old, not working
        "steppedLine"     => false,
        "targets"         => [
            //Insert targets here
        ],
        "thresholds"      => [
            //Insert thresholds here
        ],
        "timeFrom"        => null,
        "timeShift"       => null,
        "title"           => "",
        "tooltip"         => [
            "shared"     => true,
            "sort"       => 0,
            "value_type" => "individual"
        ],
        "type"            => "", // graph type definiton
        "xaxis"           => [
            "mode"   => "time",
            "name"   => null,
            "show"   => true,
            "values" => []
        ],
        "yaxes"           => [
            //Insert yaxes here
        ],
        //below here are the new options
        "fieldConfig"     => [
            "defaults" => [
                "custom" => [
                    "spanNulls"   => true, //connect null values
                    "showPoints"  => "never",
                    "fillOpacity" => 10,
                    /*
                      "stacking"=> [
                          "group"=> "A",
                          "mode"=> "normal" // or "none"
                        ],
                     */
                ],
                "unit"   => "", // new unit definition - old one was part of "yaxes":[{"format":"hertz"}]
            ],
            /*"overrides" => [
                [
                    "matcher"    => [
                        "id"      => "byName",
                        "options" => "default host.LinuxLoad.load1"
                    ],
                    "properties" => [
                        [
                            "id"    => "color",
                            "value" => [
                                "fixedColor" => "rgb(17, 36, 214)", // color of the metric
                                "mode"       => "fixed"
                            ]
                        ],
                        [
                            "id" => "custom.axisPlacement", // Y-Axis placement (in this case to the right side)
                            "value" => "right"
                        ]
                    ]
                ],
                //next custom override for another metric...
            ]*/
        ],
        "options"         => [
            "legend"  => [
                "calcs"       => [
                    "min",
                    "max",
                    "mean",
                    "lastNotNull",
                ],
                "displayMode" => "table",
                "placement"   => "bottom"
            ],
            "tooltip" => [
                "mode" => "multi",
                "sort" => "asc"
            ]
        ],
    ];

    /**
     * GrafanaPanel constructor.
     * @param $panelId
     * @param int $span
     */
    public function __construct($panelId, $span = 6) {
        $this->panelId = $panelId;
        $span = (int)$span;
        if ($span <= 0) {
            $span = 1;
        }

        if ($span > 12) {
            $span = 12;
        }

        $this->span = $span;
    }

    /**
     * @return array
     */
    public function getPanelAsArray() {
        // MERGED HYBRID STRUCTURE
        $this->panel_merged['id'] = $this->panelId;
        $this->panel_merged['title'] = $this->title;
        $this->panel_merged['targets'] = $this->targets;
        $this->panel_merged['fieldConfig']['overrides'] = $this->panelOverrides;
        $this->panel_merged['fieldConfig']['defaults'] = $this->GrafanaFieldConfigDefaults;
        $this->panel_merged['span'] = $this->span; // stretches panel to full size
        $this->panel_merged['type'] = $this->type;

        if ($this->SeriesOverrides->hasOverrides()) {
            /*
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
             */
            $this->panel_merged['seriesOverrides'] = $this->SeriesOverrides->getOverrides(); // defines the yaxis units but not the placement of the axis
        }

        $this->panel_merged['yaxes'] = $this->YAxes->getAxesAsArray(); // defines the format (eg. ms, percent etc.) of the yaxis
        return $this->panel_merged;
    }

    /**
     * @param $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @param GrafanaTargetCollection $grafanaTargetCollection
     * @param GrafanaSeriesOverrides $SeriesOverrides
     * @param GrafanaYAxes $YAxes
     */
    public function addTargets(
        GrafanaTargetCollection    $grafanaTargetCollection,
        GrafanaSeriesOverrides     $SeriesOverrides,
        GrafanaYAxes               $YAxes,
        GrafanaPanelOverrides      $GrafanaPanelOverrides,
        GrafanaFieldConfigDefaults $GrafanaFieldConfigDefaults
    ) {

        $this->targets = $grafanaTargetCollection->getTargetsAsArray();
        $this->SeriesOverrides = $SeriesOverrides;
        $this->YAxes = $YAxes;

        // Adaptions to new structure
        $this->panelOverrides = $GrafanaPanelOverrides->getOverrides();
        $this->GrafanaFieldConfigDefaults = $GrafanaFieldConfigDefaults->getFieldConfigDefaults();
    }
}
