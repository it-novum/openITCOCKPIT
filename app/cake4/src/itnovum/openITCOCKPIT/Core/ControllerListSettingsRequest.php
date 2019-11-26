<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace itnovum\openITCOCKPIT\Core;


use itnovum\openITCOCKPIT\Core\ValueObjects\HostStates;
use itnovum\openITCOCKPIT\Core\ValueObjects\ListSettingsDefaults;

abstract class ControllerListSettingsRequest {

    /**
     * @var \CakeRequest
     */
    protected $request;

    /**
     * @var ListSettingsDefaults
     */
    protected $ListSettingsDefaults;

    /**
     * @var HostStates
     */
    protected $HostStates;


    /**
     * @var array
     */
    protected $requestParameters = [];

    public function __construct(\CakeRequest $request, HostStates $HostStates, $userLimit = 30) {
        $this->ListSettingsDefaults = new ListSettingsDefaults($userLimit);

        $this->HostStates = $HostStates;
        $this->request = $request;

        if ($this->request->is('post')) {
            $this->requestParameters = $this->request->data;
        }

        //Parameters from URL (GET)
        if (isset($this->request->params['named']['Listsettings'])) {
            $this->requestParameters['Listsettings'] = $this->request->params['named']['Listsettings'];
        }
    }

    /**
     * @return int
     */
    public function getLimit() {
        if (isset($this->requestParameters['Listsettings']['limit'])) {
            return (int)$this->requestParameters['Listsettings']['limit'];
        }
        return $this->ListSettingsDefaults->getDefaultLimit();
    }

    /**
     * @param array $defaultOrder
     * @return array
     */
    public function getOrder($defaultOrder = []) {
        if (isset($this->request['named']['sort']) && isset($this->request['named']['direction'])) {
            return [
                $this->request['named']['sort'] => $this->request['named']['direction']
            ];
        }

        return $defaultOrder;
    }

    /**
     * @return false|int
     */
    public function getFrom() {
        if (isset($this->requestParameters['Listsettings']['from'])) {
            return strtotime($this->requestParameters['Listsettings']['from']);
        }
        return $this->ListSettingsDefaults->getDefaultFrom();
    }

    /**
     * @return false|int
     */
    public function getTo() {
        if (isset($this->requestParameters['Listsettings']['to'])) {
            return strtotime($this->requestParameters['Listsettings']['to']);
        }
        return $this->ListSettingsDefaults->getDefaultTo();
    }

    /**
     * @return HostStates
     */
    public function getHostStates() {
        $availableStates = $this->HostStates->getAvailableStateIds();

        if (isset($this->requestParameters['Listsettings']['state_types'])) {
            foreach ($this->requestParameters['Listsettings']['state_types'] as $stateName => $value) {
                if (isset($availableStates[$stateName]) && $value == 1) {
                    $this->HostStates->setState($availableStates[$stateName], true);
                }
            }
        }
        return $this->HostStates;
    }

    /**
     * @return array
     */
    public function getRequestSettingsForListSettings() {
        //Overwirte me!
        return [];
    }
}
