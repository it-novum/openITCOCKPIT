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
use itnovum\openITCOCKPIT\Core\ValueObjects\StateTypes;

class StatehistoryControllerRequest extends ControllerListSettingsRequest {

    /**
     * @var StateTypes
     */
    protected $StateTypes;

    public function __construct(\CakeRequest $request, HostStates $HostStates, StateTypes $StateTypes){
        $this->StateTypes = $StateTypes;
        parent::__construct($request, $HostStates);
    }

    /**
     * @return StateTypes
     */
    public function getStateTypes(){
        $availableStateTypes = $this->StateTypes->getAvailableStateTypeIds();
        if (isset($this->requestParameters['Listsettings']['nag_state_types'])) {
            foreach ($this->requestParameters['Listsettings']['nag_state_types'] as $stateTypeName => $value) {
                if (isset($availableStateTypes[$stateTypeName]) && $value == 1) {
                    $this->StateTypes->setStateType($availableStateTypes[$stateTypeName], true);
                }
            }
        }
        return $this->StateTypes;
    }

    /**
     * @return array
     */
    public function getRequestSettingsForListSettings(){
        $states = $this->HostStates->asArray();
        if(!$this->HostStates->hasRecovery() && !$this->HostStates->hasDown() && !$this->HostStates->hasUnreachable()){
            //User disabled all check boxes or first page load
            //Enable all to avoid empty list
            foreach($states as $key => $value){
                $states[$key] = true;
            }
        }

        $stateTypes = $this->StateTypes->asArray();
        if(!$this->StateTypes->hasSoft() && !$this->StateTypes->hasHard()){
            //User disabled all check boxes or first page load
            //Enable all to avoid empty list
            foreach($stateTypes as $key => $value){
                $stateTypes[$key] = true;
            }
        }

        return [
            'limit' => $this->getLimit(),
            'state_types' => $states,
            'nag_state_types' => $stateTypes,
            'from' => date('d.m.Y H:i', $this->getFrom()),
            'to' => date('d.m.Y H:i', $this->getTo())
        ];
    }
}
