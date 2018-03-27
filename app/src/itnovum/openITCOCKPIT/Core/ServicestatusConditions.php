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


class ServicestatusConditions {

    /**
     * @var array
     */
    private $conditions = [];

    /**
     * @var DbBackend
     */
    private $DbBackend;

    public function __construct(DbBackend $DbBackend) {
        $this->DbBackend = $DbBackend;
    }

    private function addCondition($field) {
        $this->conditions[] = $field;
    }

    public function getConditions() {
        return $this->conditions;
    }

    /**
     * @return bool
     */
    public function hasConditions(){
        return !empty($this->conditions);
    }

    public function servicesWarningCriticalAndUnknown(){
        $this->addCondition('Servicestatus.current_state > 0');
        return $this;
    }

    public function perfdataIsNotNull(){
        $this->addCondition('Servicestatus.perfdata IS NOT NULL');
        return $this;
    }

    /**
     * @param $currentStateId
     * @return $this
     */
    public function currentState($currentStateId){
        if(is_array($currentStateId)){
            $this->conditions['Servicestatus.current_state'] = $currentStateId;
        }else {
            $this->conditions['Servicestatus.current_state'][] = $currentStateId;
        }
        return $this;
    }

    /**
     * @param int $value
     */
    public function setProblemHasBeenAcknowledged($value){
        $value = (int)$value;
        if($this->DbBackend->isNdoUtils()) {
            $this->conditions['Servicestatus.problem_has_been_acknowledged'] = $value;
        }

        if($this->DbBackend->isCrateDb()){
            $this->conditions['Servicestatus.problem_has_been_acknowledged'] = (bool)$value;
        }
    }

    /**
     * @param int $value
     */
    public function setScheduledDowntimeDepth($value){
        $value = (int)$value;
        if($value === 0){
            $this->conditions['Servicestatus.scheduled_downtime_depth >'] = $value;
        }else{
            $this->conditions['Servicestatus.scheduled_downtime_depth'] = $value;
        }
    }
}