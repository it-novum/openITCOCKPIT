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
     * @var GrafanaThresholdCollection
     */
    private $ThresholdCollection;

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
    private $panel = [
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
        $this->panel['id'] = $this->panelId;
        $this->panel['title'] = $this->title;
        $this->panel['targets'] = $this->targets;
        $this->panel['aliasColors'] = $this->color;
        $this->panel['span'] = $this->span;

        if ($this->SeriesOverrides->hasOverrides()) {
            $this->panel['seriesOverrides'] = $this->SeriesOverrides->getOverrides();
        }

        $this->panel['yaxes'] = $this->YAxes->getAxesAsArray();

        $this->panel['thresholds'] = $this->ThresholdCollection->getThresholdsAsArray();

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
     * @param GrafanaThresholdCollection $ThresholdCollection
     */
    public function addTargets(
        GrafanaTargetCollection $grafanaTargetCollection,
        GrafanaSeriesOverrides $SeriesOverrides,
        GrafanaYAxes $YAxes,
        GrafanaThresholdCollection $ThresholdCollection
    ) {
        $this->targets = $grafanaTargetCollection->getTargetsAsArray();
        $this->color = $grafanaTargetCollection->getColorsAsArray();
        $this->SeriesOverrides = $SeriesOverrides;
        $this->YAxes = $YAxes;
        $this->ThresholdCollection = $ThresholdCollection;
    }
}
