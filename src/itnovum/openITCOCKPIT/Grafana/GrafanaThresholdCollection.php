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

class GrafanaThresholdCollection {

    /**
     * @var GrafanaTargetCollection
     */
    private $target;

    /**
     * @var null|int
     */
    private $warning = null;

    /**
     * @var null|int
     */
    private $critical = null;

    public function __construct(GrafanaTargetInterface $target) {
        $this->target = $target;
        $this->warning = $this->getWarningThreshold();
        $this->critical = $this->getCriticalsThreshold();

    }

    /**
     * @return bool
     */
    private function canDisplayWarningThreshold(): bool {
        return !(is_null($this->warning));
    }

    /**
     * @return int|null
     */
    private function getWarningThreshold() {
        /** @var GrafanaTargetInterface $target */
        if ($this->target->getThresholds()->hasWarning()) {
            return $this->target->getThresholds()->getWarning();
        }
        return null;
    }

    /**
     * @return bool
     */
    private function canDisplayCriticalThreshold(): bool {
        return !(is_null($this->critical));
    }

    /**
     * @return int|null
     */
    private function getCriticalsThreshold() {
        /** @var GrafanaTargetInterface $target */
        if ($this->target->getThresholds()->hasCritical()) {
            return $this->target->getThresholds()->getCritical();
        }
        return null;
    }

    /**
     * @return bool
     */
    private function isInvertedThresholds() {
        if ($this->warning === null || $this->critical === null) {
            return false;
        }

        return ($this->warning > $this->critical);
    }

    /**
     * @return array
     */
    public function getThresholdsAsArray(): array {
        $thresholds = [];
        if ($this->isInvertedThresholds() === false) {
            if ($this->canDisplayWarningThreshold()) {
                $thresholds[] = [
                    "color" => "yellow",
                    "value" => $this->warning
                ];
            }

            if ($this->canDisplayCriticalThreshold()) {
                $thresholds[] = [
                    "color" => "red",
                    "value" => $this->critical
                ];
            }
        }

        if ($this->isInvertedThresholds() === true) {
            if ($this->canDisplayCriticalThreshold()) {
                $thresholds[] = [
                    "color" => "red",
                    "value" => $this->critical
                ];
            }

            if ($this->canDisplayWarningThreshold()) {
                $thresholds[] = [
                    "color" => "yellow",
                    "value"     => $this->warning
                ];
            }
        }

        if(!empty($thresholds)){
            //prepend green base to the thresholds
            $thresholdBaseStep = [
                "color" => "green",
                "value" => null,
            ];
            array_unshift($thresholds, $thresholdBaseStep);
        }
        return $thresholds;
    }
}
