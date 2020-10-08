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


class GrafanaSeriesOverrides {

    /**
     * @var array
     */
    private $overrides = [];

    /**
     * GrafanaSeriesOverrides constructor.
     * @param GrafanaTargetCollection $targetCollection
     */
    public function __construct(GrafanaTargetCollection $targetCollection) {
        if ($targetCollection->canDisplayUnits() && sizeof($targetCollection->getUnits()) === 2) {

            foreach ($targetCollection->getUnits() as $index => $unit) {
                $unitsToAxis[$unit] = $index + 1;
            }

            foreach ($targetCollection->getTargets() as $target) {
                /** @var GrafanaTargetInterface $target */

                // Get Y-axis id for current metric / unit
                $axisId = $unitsToAxis[$target->getUnit()];

                if ($target->getAlias()) {
                    $alias = str_replace('/', '\/', $target->getAlias());
                    $override = [
                        'alias' => $alias,
                        'yaxis' => $axisId
                    ];
                } else {
                    $alias = str_replace('/', '\/', $target->getTarget());
                    $override = [
                        'alias' => $alias,
                        'yaxis' => $axisId
                    ];
                }

                $this->overrides[] = $override;
            }
        }
    }

    /**
     * @return bool
     */
    public function hasOverrides() {
        return !empty($this->overrides);
    }

    /**
     * @return array
     */
    public function getOverrides() {
        return $this->overrides;
    }

}
