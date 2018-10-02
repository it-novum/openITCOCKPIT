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


class GrafanaDashboard {

    /**
     * @var string
     */
    private $title;

    /**
     * @var array
     */
    private $rows = [];

    /**
     * @var bool
     */
    private $editable = false;

    /**
     * @var bool
     */
    private $hideControls = true;

    /**
     * @var array
     */
    private $tags = [];

    /**
     * @var array
     */
    private $grafanaDashboardDataArray = [
        'annotations'   => [
            'list' => []
        ],
        "editable"      => false, // testing true
        "gnetId"        => null,
        "graphTooltip"  => 0,
        "hideControls"  => true, // testing false
        "id"            => null,
        "links"         => [],
        "rows"          => [
            //Insert rows here
        ],
        "schemaVersion" => 14,
        //"style" => "light",
        "tags"          => [],
        "templating"    => [
            "list" => []
        ],
        "time"          => [
            "from" => "now-6h",
            "to"   => "now"
        ],

        "timepicker" => [
            "refresh_intervals" => [
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
            "time_options"      => [
                "5m",
                "15m",
                "1h",
                "6h",
                "12h",
                "24h",
                "2d",
                "7d",
                "30d"
            ],
        ],

        "timezone" => "browser",
        "title"    => "",
        "version"  => 0,
    ];

    /**
     * @return string
     */
    public function getGrafanaDashboardJson() {
        $this->grafanaDashboardDataArray['title'] = $this->title;
        $this->grafanaDashboardDataArray['rows'] = $this->rows;
        $this->grafanaDashboardDataArray['editable'] = $this->editable;
        $this->grafanaDashboardDataArray['hideControls'] = $this->hideControls;
        $this->grafanaDashboardDataArray['tags'] = $this->tags;

        return json_encode(['dashboard' => $this->grafanaDashboardDataArray, 'overwrite' => true /*'inputs' => $additional*/]/*, JSON_PRETTY_PRINT*/);
    }

    /**
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    public function addRow(GrafanaRow $grafanaRow) {
        $this->rows[] = $grafanaRow->getRowAsArray();
    }

    public function setTags($tags) {
        $this->tags[] = $tags;
    }

    public function setAutoRefresh($value) {
        //Possible values: 5s, 10s, 30s, 1m, 5m 15m, 30m, 1h, 2h, 1d
        $this->grafanaDashboardDataArray['refresh'] = $value;
    }

    public function setTimeInHours($value) {
        $this->grafanaDashboardDataArray['time'] = [
            "from" => sprintf("now-%sh", $value),
            "to"   => "now"
        ];
    }

    /**
     * @param bool $editable
     */
    public function setEditable($editable) {
        $this->editable = $editable;
    }

    /**
     * @param bool $hideControls
     */
    public function setHideControls($hideControls) {
        $this->hideControls = $hideControls;
    }
}
