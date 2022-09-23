<?php

namespace App\itnovum\openITCOCKPIT\Grafana;

use itnovum\openITCOCKPIT\Grafana\GrafanaTargetCollection;

/**
 * build up the fieldConfig structure of the new Grafana Panels
 * this includes also the fieldConfig["defaults"]["custom"] values
 */
class GrafanaFieldConfigDefaults {
    /**
     * @var array
     */
    private $fieldConfigDefaults = [];

    /**
     * @var string
     */
    private $unit = '';

    /**
     * @var string
     */
    private $drawStyle = "line";

    /**
     * connect datapoints to each other to get a line graph instead of points only
     * @var bool
     */
    private $spanNulls = true;

    /**
     * @var string
     */
    private $showPoints = "never";

    /**
     * @var int
     */
    private $fillOpacity = 10;

    /**
     * @var string[]
     */
    private $stacking = [
        "group" => "A",
        "mode"  => "none" // "normal" activates stacking
    ];

    /**
     * @return array
     */
    public function getFieldConfigDefaults(): array {
        $this->getFieldConfigDefaultConfiguration();
        return $this->fieldConfigDefaults;
    }

    /**
     * @return void
     */
    private function getFieldConfigDefaultConfiguration() {
        $this->fieldConfigDefaults = [
            "custom"     => [
                "drawStyle"       => $this->drawStyle,
                "spanNulls"       => $this->spanNulls, //connect null values
                "showPoints"      => $this->showPoints,
                "fillOpacity"     => $this->fillOpacity,
                "stacking"        => $this->stacking,
            ],
            "unit"       => $this->unit,
        ];
    }

    /**
     * @param string $drawStyle
     */
    public function setDrawStyle(string $drawStyle): void {
        $this->drawStyle = $drawStyle;
    }

    /**
     * @param bool $spanNulls
     */
    public function setSpanNulls(bool $spanNulls): void {
        $this->spanNulls = $spanNulls;
    }

    /**
     * @param string $showPoints
     */
    public function setShowPoints(string $showPoints): void {
        $this->showPoints = $showPoints;
    }

    /**
     * @param int $fillOpacity
     */
    public function setFillOpacity(int $fillOpacity): void {
        $this->fillOpacity = $fillOpacity;
    }

    /**
     * example of the stacking mode:
     * "stacking"=> [
     *      "group"=> "A",
     *      "mode"=> "normal" // or "none"
     * ]
     * @param string[] $stacking
     */
    public function setStacking(array $stacking): void {
        $stackingValue = [];
        if (!empty($stacking) && isset($stacking['mode'])) {
            switch ($stacking['mode']) {
                case 'normal':
                case 'none':
                    $stackingValue['group'] = "A";
                    $stackingValue['mode'] = $stacking['mode'];
                    break;
            }
        }
        $this->stacking = $stackingValue;
    }

    /**
     * Set the unit for a whole panel, can be overridden by GrafanaPanelOverrides for each metric in a Panel
     * @param string $unit
     */
    public function setUnit(string $unit): void {
        $this->unit = $unit;
    }
}
