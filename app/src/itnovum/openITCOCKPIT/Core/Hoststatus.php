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


class Hoststatus {

    /**
     * @var null|int
     */
    private $currentState = null;

    /**
     * @var bool
     */
    private $isFlapping;

    /**
     * @var bool
     */
    private $problemHasBeenAcknowledged;

    /**
     * @var string
     */
    private $scheduledDowntimeDepth;

    /**
     * @var string
     */
    private $lastCheck;

    /**
     * @var string
     */
    private $nextCheck;

    /**
     * @var bool
     */
    private $activeChecksEnabled;

    /**
     * @var string
     */
    private $lastHardStateChange;

    /**
     * @var string
     */
    private $output;

    /**
     * @var int
     */
    private $acknowledgement_type;

    public function __construct($data){
        if (isset($data['current_state'])) {
            $this->currentState = $data['current_state'];
        }

        if (isset($data['is_flapping'])) {
            $this->isFlapping = (bool)$data['is_flapping'];
        }

        if (isset($data['problem_has_been_acknowledged'])) {
            $this->problemHasBeenAcknowledged = $data['problem_has_been_acknowledged'];
        }

        if (isset($data['scheduled_downtime_depth'])) {
            $this->scheduledDowntimeDepth = $data['scheduled_downtime_depth'];
        }

        if (isset($data['last_check'])) {
            $this->lastCheck = $data['last_check'];
        }

        if (isset($data['next_check'])) {
            $this->nextCheck = $data['next_check'];
        }

        if (isset($data['active_checks_enabled'])) {
            $this->activeChecksEnabled = $data['active_checks_enabled'];
        }

        if (isset($data['last_hard_state_change'])) {
            $this->lastHardStateChange = $data['last_hard_state_change'];
        }

        if (isset($data['acknowledgement_type'])) {
            $this->acknowledgement_type = (int)$data['acknowledgement_type'];
        }

        if (isset($data['output'])) {
            $this->output = $data['output'];
        }
    }

    public function getHumanHoststatus($href = 'javascript:void(0)', $style = ''){
        if ($this->currentState === null) {
            return ['state' => 2, 'human_state' => __('Not found in monitoring'), 'html_icon' => '<a href="' . $href . '" class="btn btn-primary status-circle" style="padding:0;' . $style . '"></a>', 'icon' => 'fa fa-question-circle'];
        }

        switch ($this->currentState) {
            case 0:
                return ['state' => 0, 'human_state' => __('Up'), 'html_icon' => '<a href="' . $href . '" class="btn btn-success status-circle" style="padding:0;' . $style . '"></a>', 'icon' => 'glyphicon glyphicon-ok'];
                break;

            case 1:
                return ['state' => 1, 'human_state' => __('Down'), 'html_icon' => '<a href="' . $href . '" class="btn btn-danger status-circle" style="padding:0;' . $style . '"></a>', 'icon' => 'fa fa-exclamation'];
                break;

            default:
                return ['state' => 2, 'human_state' => __('Unreachable'), 'html_icon' => '<a href="' . $href . '" class="btn btn-default status-circle" style="padding:0;' . $style . '"></a>'];
        }
    }

    public function getHostFlappingIconColored($class = ''){
        $stateColors = [
            0 => 'ok',
            1 => 'critical',
            2 => '',
        ];

        if ($this->isFlapping() === true) {
            if ($this->currentState !== null) {
                return '<span class="flapping_airport ' . $class . ' ' . $stateColors[$this->currentState] . '"><i class="fa fa-circle ' . $stateColors[$this->currentState] . '"></i> <i class="fa fa-circle-o ' . $stateColors[$this->currentState] . '"></i></span>';
            }

            return '<span class="flapping_airport text-primary ' . $class . '"><i class="fa fa-circle ' . $stateColors[$this->currentState] . '"></i> <i class="fa fa-circle-o ' . $stateColors[$this->currentState] . '"></i></span>';
        }

        return '';
    }

    public function currentState(){
        return $this->currentState;
    }

    public function isAacknowledged(){
        return (bool)$this->problemHasBeenAcknowledged;
    }

    /**
     * @return bool
     */
    public function isInDowntime(){
        if ($this->scheduledDowntimeDepth > 0) {
            return true;
        }

        return false;
    }

    public function getLastHardStateChange(){
        return $this->lastHardStateChange;
    }

    public function getLastCheck(){
        return $this->lastCheck;
    }

    public function getNextCheck(){
        return $this->nextCheck;
    }

    public function isActiveChecksEnabled(){
        return (bool)$this->activeChecksEnabled;
    }

    /**
     * @return bool
     */
    public function isFlapping(){
        return (bool)$this->isFlapping;
    }

    /**
     * @return int
     */
    public function getAcknowledgementType(){
        return $this->acknowledgement_type;
    }

    /**
     * @return string
     */
    public function getOutput(){
        return $this->output;
    }

}