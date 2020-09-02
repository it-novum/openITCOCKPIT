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
    private $targetCollection;

    /**
     * @var null|int
     */
    private $warning = null;

    /**
     * @var null|int
     */
    private $critical = null;

    public function __construct(GrafanaTargetCollection $targetCollection) {
        $this->targetCollection = $targetCollection;
        $this->warning = $this->getWarningThreshold();
        $this->critical = $this->getCriticalsThreshold();
    }

    /**
     * @return bool
     */
    public function canDisplayWarningThreshold() {
        return !(is_null($this->warning));
    }

    /**
     * @return null|int
     */
    public function getWarningThreshold() {
        $warnings = [];
        foreach ($this->targetCollection->getTargets() as $target) {
            /** @var GrafanaTargetInterface $target */
            if ($target->getThresholds()->hasWarning()) {
                $warnings[$target->getThresholds()->getWarning()] = true;
            }
        }
        if (sizeof($warnings) === 1) {
            return array_keys($warnings)[0];
        }
        return null;
    }

    /**
     * @return bool
     */
    public function canDisplayCriticalThreshold() {
        return !(is_null($this->critical));
    }

    /**
     * @return null|int
     */
    public function getCriticalsThreshold() {
        $criticals = [];
        foreach ($this->targetCollection->getTargets() as $target) {
            /** @var GrafanaTargetInterface $target */
            if ($target->getThresholds()->hasCritical()) {
                $criticals[$target->getThresholds()->getCritical()] = true;
            }
        }
        if (sizeof($criticals) === 1) {
            return array_keys($criticals)[0];
        }
        return null;
    }

    /**
     * @return bool
     */
    public function isInvertedThresholds() {
        if ($this->warning === null || $this->critical === null) {
            return false;
        }

        return ($this->warning > $this->critical);
    }

    /**
     * @return array
     */
    public function getThresholdsAsArray() {
        $thresholds = [];

        if ($this->isInvertedThresholds() === false) {
            if ($this->canDisplayWarningThreshold()) {
                $thresholds[] = [
                    "colorMode" => "warning",
                    "fill"      => true,
                    "line"      => true,
                    "op"        => "gt",
                    "value"     => $this->warning
                ];
            }

            if ($this->canDisplayCriticalThreshold()) {
                $thresholds[] = [
                    "colorMode" => "critical",
                    "fill"      => true,
                    "line"      => true,
                    "op"        => "gt",
                    "value"     => $this->critical
                ];
            }
        }

        if ($this->isInvertedThresholds() === true) {
            if ($this->canDisplayCriticalThreshold()) {
                $thresholds[] = [
                    "colorMode" => "critical",
                    "fill"      => true,
                    "line"      => true,
                    "op"        => "lt",
                    "value"     => $this->critical
                ];
            }

            if ($this->canDisplayWarningThreshold()) {
                $thresholds[] = [
                    "colorMode" => "warning",
                    "fill"      => true,
                    "line"      => true,
                    "op"        => "lt",
                    "value"     => $this->warning
                ];
            }
        }

        return $thresholds;
    }
}
