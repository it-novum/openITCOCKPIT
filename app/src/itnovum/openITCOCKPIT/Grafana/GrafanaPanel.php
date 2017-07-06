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
     * @var array
     */
    private $panel = [
        "aliasColors" => [],
        "bars" => false,
        "datasource" => null,
        "fill" => 1,
        "id" => null,
        "legend" => [
            "alignAsTable" => true,
            "avg" => true,
            "current" => true,
            "hideEmpty" => false,
            "hideZero" => false,
            "max" => true,
            "min" => true,
            "show" => true,
            "total" => false,
            "values" => true
        ],
        "lines" => true,
        "linewidth" => 1,
        "links" => [],
        "nullPointMode" => "connected",
        "percentage" => false,
        "pointradius" => 5,
        "points" => false,
        "renderer" => "flot",
        "seriesOverrides" => [],
        "span" => 6,
        "stack" => false,
        "steppedLine" => false,
        "targets" => [
            //Insert targets here
        ],
        "thresholds" => [],
        "timeFrom" => null,
        "timeShift" => null,
        "title" => "",
        "tooltip" => [
            "shared" => true,
            "sort" => 0,
            "value_type" => "individual"
        ],
        "type" => "graph",
        "xaxis" => [
            "mode" => "time",
            "name" => null,
            "show" => true,
            "values" => []
        ],
        "yaxes" => [
            //Insert yaxes here
        ]
    ];

    public function __construct($panelId) {
        $this->panelId = $panelId;
    }

    /**
     * @return array
     */
    public function getPanelAsArray() {
        $this->panel['id'] = $this->panelId;
        $this->panel['title'] = $this->title;
        $this->panel['targets'] = $this->targets;

        if($this->SeriesOverrides->hasOverrides()){
            $this->panel['seriesOverrides'] = $this->SeriesOverrides->getOverrides();
        }

        $this->panel['yaxes'] = $this->YAxes->getAxesAsArray();

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
     * @param GrafanaSeriesOverrides $SeriesOverrides
     * @param GrafanaYAxes $YAxes
     */
    public function addTargets(
        GrafanaTargetCollection $grafanaTargetCollection,
        GrafanaSeriesOverrides $SeriesOverrides,
        GrafanaYAxes $YAxes
    ) {
        $this->targets = $grafanaTargetCollection->getTargetsAsArray();
        $this->SeriesOverrides = $SeriesOverrides;
        $this->YAxes = $YAxes;
    }
}
