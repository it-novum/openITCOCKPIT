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


use App\itnovum\openITCOCKPIT\Grafana\GrafanaColorOverrides;

class GrafanaPanel {

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
     * @var GrafanaOverrides
     */
    private $Overrides;
    /**
     * @var GrafanaColorOverrides
     */
    private $ColorOverrides;

    /**
     * @var GrafanaYAxes
     */
    private $YAxes;

    /**
     * @var GrafanaThresholdCollection
     */
    private $ThresholdCollection;

    /**
     * @var int
     */
    private $span = 6;

    /**
     * @var int
     */
    private $metricCount = 1;


    /**
     * @var array
     */
    private $panel = [
        "id"         => null,
        "type"       => "timeseries",
        "title"      => "",
        "datasource" => [
            "type" => "datasource",
            "uid"  => "-- Mixed --"
        ],
        "links"      => [],
        "targets"    => [
            //Insert targets here
        ],
        "options"    => [
            "tooltip" => [
                "mode" => "multi",
                "sort" => "none"
            ],
            "legend"  => [
                "displayMode" => "table",
                "placement"   => "bottom",
                "calcs"       => [
                    "mean",
                    "lastNotNull",
                    "max",
                    "min",
                ]
            ]
        ],

        "thresholds"    => [
            //Insert thresholds here
        ],
        "timeFrom"      => null,
        "timeShift"     => null,
        "fieldConfig"   => [
            "defaults"  => [
                "custom"     => [
                    "drawStyle"         => "line",
                    "lineInterpolation" => "smooth",
                    "barAlignment"      => 0,
                    "lineWidth"         => 1,
                    "fillOpacity"       => 50,
                    "gradientMode"      => "opacity",
                    "spanNulls"         => true,
                    "showPoints"        => "never",
                    "pointSize"         => 5,
                    "stacking"          => [
                        "mode"  => "none",
                        "group" => "A"
                    ],

                    "axisPlacement"     => "auto",
                    "axisLabel"         => "",
                    "axisColorMode"     => "text",
                    "scaleDistribution" => [
                        "type" => "linear"
                    ],
                    "axisCenteredZero"  => false,
                    "hideFrom"          => [
                        "tooltip" => false,
                        "viz"     => false,
                        "legend"  => false
                    ],
                    "thresholdsStyle"   => [
                        "mode" => "off"
                    ]
                ],
                "color"      => [
                    "mode" => "palette-classic"
                ],
                "thresholds" => [
                    "steps" => []
                ]
            ],
            "overrides" => [],
            "mappings"  => []

        ],
        "pluginVersion" => "9.0.2"
    ];

    /**
     * GrafanaPanel constructor.
     * @param $panelId
     * @param int $span
     */
    public function __construct($panelId, int $span = 6, $metricCount = 1) {
        $this->panelId = $panelId;
        $span = (int)$span;
        if ($span <= 0) {
            $span = 1;
        }

        if ($span > 12) {
            $span = 12;
        }

        $this->span = $span;
        $this->metricCount = $metricCount;
    }

    /**
     * @return array
     */
    public function getPanelAsArray() {
        $this->panel['id'] = $this->panelId;
        $this->panel['title'] = $this->title;
        $this->panel['targets'] = $this->targets;
        $this->panel['span'] = $this->span;

        if ($this->Overrides->hasOverrides()) {
            $this->panel['fieldConfig']['overrides'] = $this->Overrides->getOverrides();
        }

        $thresholdsAsArray = $this->ThresholdCollection->getThresholdsAsArray();
        if (!empty($thresholdsAsArray) && empty($this->panel['fieldConfig']['overrides']) && sizeof($this->panel['targets']) === 1) {

            $this->panel['fieldConfig']['defaults']['thresholds'] = [
                'steps' => $thresholdsAsArray
            ];

            if ($this->getMetricCount() > 1) {
                // show threshold lines in chart with more than one metric - can be used for all charts if needed
                $this->panel['fieldConfig']['defaults']['custom']['thresholdsStyle'] = [
                    'mode' => 'line'
                ];
            } else {
                $this->panel['fieldConfig']['defaults']['custom']['gradientMode'] = 'scheme';
                $this->panel['fieldConfig']['defaults']['color']['mode'] = 'thresholds';
            }
        }
        if ($this->ColorOverrides->hasOverrides()) {
            $this->panel['fieldConfig']['overrides'] = $this->ColorOverrides->getOverrides();
        }

        return $this->panel;
    }

    /**
     * @param $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @param GrafanaTargetCollection $grafanaTargetCollection
     * @param GrafanaOverrides $Overrides
     * @param GrafanaYAxes $YAxes
     * @param GrafanaThresholdCollection $ThresholdCollection
     */
    public function addTargets(
        GrafanaTargetCollection    $grafanaTargetCollection,
        GrafanaOverrides           $Overrides,
        GrafanaColorOverrides      $ColorOverrides,
        GrafanaYAxes               $YAxes,
        GrafanaThresholdCollection $ThresholdCollection
    ) {
        $this->targets = $grafanaTargetCollection->getTargetsAsArray();
        $this->Overrides = $Overrides;
        $this->ColorOverrides = $ColorOverrides;
        $this->YAxes = $YAxes;
        $this->ThresholdCollection = $ThresholdCollection;
    }

    /**
     * @param $metricCount
     * @return void
     */
    public function setMetricCount($metricCount) {
        $this->metricCount = $metricCount;
    }

    /**
     * @return int|mixed
     */
    private function getMetricCount() {
        return $this->metricCount;
    }
}
