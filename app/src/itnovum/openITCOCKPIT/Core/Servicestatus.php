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


use CakeTime;
use itnovum\openITCOCKPIT\Core\Views\UserTime;

class Servicestatus {

    private $currentState = null;

    private $lastHardState = null;

    private $isFlapping;

    private $problemHasBeenAcknowledged;

    private $scheduledDowntimeDepth;

    private $lastCheck;

    private $nextCheck;

    private $activeChecksEnabled;

    private $lastHardStateChange;

    private $last_state_change;

    private $processPerformanceData;

    private $state_type;

    private $acknowledgement_type;

    private $flap_detection_enabled;

    private $notifications_enabled;

    private $current_check_attempt;

    private $output;

    private $long_output;

    private $perfdata;

    private $latency;

    private $max_check_attempts;

    /**
     * @var UserTime|null
     */
    private $UserTime;

    public function __construct($data, $UserTime = null) {
        if (isset($data['current_state'])) {
            $this->currentState = (int)$data['current_state'];
        }

        if (isset($data['is_flapping'])) {
            $this->isFlapping = (bool)$data['is_flapping'];
        }

        if (isset($data['problem_has_been_acknowledged'])) {
            $this->problemHasBeenAcknowledged = $data['problem_has_been_acknowledged'];
        }

        if (isset($data['scheduled_downtime_depth'])) {
            $this->scheduledDowntimeDepth = (int)$data['scheduled_downtime_depth'];
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

        if (isset($data['last_hard_state'])) {
            $this->lastHardState = (int)$data['last_hard_state'];
        }

        if (isset($data['last_state_change'])) {
            $this->last_state_change = $data['last_state_change'];
        }

        if (isset($data['process_performance_data'])) {
            $this->processPerformanceData = $data['process_performance_data'];
        }

        if (isset($data['state_type'])) {
            $this->state_type = $data['state_type'];
        }

        if (isset($data['is_hardstate'])) {
            $this->state_type = $data['is_hardstate'];
        }

        if (isset($data['acknowledgement_type'])) {
            $this->acknowledgement_type = (int)$data['acknowledgement_type'];
        }

        if (isset($data['flap_detection_enabled'])) {
            $this->flap_detection_enabled = (bool)$data['flap_detection_enabled'];
        }

        if (isset($data['notifications_enabled'])) {
            $this->notifications_enabled = (bool)$data['notifications_enabled'];
        }

        if (isset($data['current_check_attempt'])) {
            $this->current_check_attempt = $data['current_check_attempt'];
        }

        if (isset($data['output'])) {
            $this->output = $data['output'];
        }

        if (isset($data['long_output'])) {
            $this->long_output = $data['long_output'];
        }

        if (isset($data['perfdata'])) {
            $this->perfdata = $data['perfdata'];
        }

        if (isset($data['latency'])) {
            $this->latency = $data['latency'];
        }

        if (isset($data['max_check_attempts'])) {
            $this->max_check_attempts = (int)$data['max_check_attempts'];
        }

        $this->UserTime = $UserTime;
    }

    /**
     * @return bool
     */
    public function isHardState() {
        return (bool)$this->state_type;
    }

    public function getHumanServicestatus($href = 'javascript:void(0)', $content = '', $style = '') {
        if ($this->currentState === null) {
            return ['state' => 3, 'human_state' => __('Not found in monitoring'), 'html_icon' => '<a href="' . $href . '" class="btn btn-primary status-circle" style="padding:0;' . $style . '"></a>', 'icon' => 'fa fa-question-circle'];
        }
        switch ($this->currentState) {
            case 0:
                return ['state' => 0, 'human_state' => __('Ok'), 'html_icon' => '<a href="' . $href . '" class="btn btn-success btn-xs status-circle" style="' . $style . '">' . $content . '</a>', 'icon' => 'glyphicon glyphicon-ok'];
                break;

            case 1:
                return ['state' => 1, 'human_state' => __('Warning'), 'html_icon' => '<a href="' . $href . '" class="btn btn-warning btn-xs status-circle" style="' . $style . '">' . $content . '</a>', 'icon' => 'fa fa-exclamation'];
                break;
            case 2:
                return ['state' => 2, 'human_state' => __('Critical'), 'html_icon' => '<a href="' . $href . '" class="btn btn-danger btn-xs status-circle" style="' . $style . '">' . $content . '</a>', 'icon' => 'fa fa-exclamation'];
                break;
            default:
                return ['state' => 3, 'human_state' => __('Unknown'), 'html_icon' => '<a href="' . $href . '" class="btn btn-default btn-xs status-circle" style="' . $style . '">' . $content . '</a>', 'icon' => 'fa fa-warning'];
        }
    }

    public function getServiceFlappingIconColored($class = '') {
        $stateColors = [
            0 => 'txt-color-green',
            1 => 'warning',
            2 => 'txt-color-red',
            3 => 'txt-color-blueDark',
        ];

        if ($this->isFlapping() === true) {
            if ($this->currentState !== null) {
                return '<span class="flapping_airport ' . $class . ' ' . $stateColors[$this->currentState] . '"><i class="fa fa-circle ' . $stateColors[$this->currentState] . '"></i> <i class="fa fa-circle-o ' . $stateColors[$this->currentState] . '"></i></span>';
            }

            return '<span class="flapping_airport text-primary ' . $class . '"><i class="fa fa-circle ' . $stateColors[$this->currentState] . '"></i> <i class="fa fa-circle-o ' . $stateColors[$this->currentState] . '"></i></span>';
        }

        return '';
    }

    /**
     * Return the CSS class for the current service status
     * <span class="<?php echo $this->ServiceStatusColor($uuid); ?>"></span>
     *
     * @param string $uuid of the object
     * @param array $servicestauts , if not given the $servicestatus array of the current view will be used (default)
     *
     * @return string CSS class of the color
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function ServiceStatusColor() {
        if ($this->currentState === null) {
            return 'text-primary';
        }

        switch ($this->currentState) {
            case 0:
                return 'txt-color-green';

            case 1:
                return 'warning';

            case 2:
                return 'txt-color-red';

            default:
                return 'txt-color-blueDark';
        }
    }

    /**
     * Return the status background color for a Service
     *
     * @param int $state the current status of a Service
     *
     * @return array which contains the human state and the css class
     */
    function ServiceStatusBackgroundColor() {
        $state = ($this->currentState === null) ? 3 : $this->currentState;
        $background_color = [
            0 => 'bg-color-green',
            1 => 'bg-color-orange',
            2 => 'bg-color-red',
            3 => 'bg-color-blueLight',
        ];

        return $background_color[$state];
    }

    /**
     * Check if there is a difference between monitoring hoststatus flap_detection_ebabled and the itcockpit database
     * configuration If yes it will return the current setting from $hostatus This can happen, if a user disable the
     * flap detection with an external command, but not in the host configuration
     *
     * @param array $host ['Host']['flap_detection_enabled']
     *
     * @return array with the flap detection settings. Array keys: 'string', 'html' and 'value'
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function compareHostFlapDetectionWithMonitoring($flapDetectionEnabledFromConfig) {
        if ($flapDetectionEnabledFromConfig != $this->flap_detection_enabled) {
            //Flapdetection was temporary en- or disabled by an external command
            if ($this->flap_detection_enabled) {
                return ['string' => __('Temporary on'), 'html' => '<a data-original-title="' . __('Difference to configuration detected') . '" data-placement="bottom" rel="tooltip" href="javascript:void(0);"><i class="fa fa-exclamation-triangle txt-color-orange"></i></a> <span class="label bg-color-greenLight">' . __('Temporary on') . '</span>', 'value' => $this->flap_detection_enabled];
            }

            return ['string' => __('Temporary off'), 'html' => '<a data-original-title="' . __('Difference to configuration detected') . '" data-placement="bottom" rel="tooltip" href="javascript:void(0);"><i class="fa fa-exclamation-triangle txt-color-orange"></i></a> <span class="label bg-color-redLight">' . __('Temporary off') . '</span>', 'value' => $this->flap_detection_enabled];
        }

        if ($flapDetectionEnabledFromConfig == 1) {
            return ['string' => __('On'), 'html' => '<span class="label bg-color-green">' . __('On') . '</span>', 'value' => $flapDetectionEnabledFromConfig];
        }

        return ['string' => __('Off'), 'html' => '<span class="label bg-color-red">' . __('Off') . '</span>', 'value' => $flapDetectionEnabledFromConfig];
    }

    public function currentState() {
        return $this->currentState;
    }

    public function isAcknowledged() {
        return (bool)$this->problemHasBeenAcknowledged;
    }

    public function isInDowntime() {
        if ($this->scheduledDowntimeDepth > 0) {
            return true;
        }

        return false;
    }

    public function getLastHardStateChange() {
        if (!is_numeric($this->lastHardStateChange)) {
            return strtotime($this->lastHardStateChange);
        }
        return $this->lastHardStateChange;
    }

    public function getLastHardState() {
        return $this->lastHardState;
    }

    public function getLastStateChange() {
        if (!is_numeric($this->last_state_change)) {
            return strtotime($this->last_state_change);
        }
        return $this->last_state_change;
    }

    public function getLastCheck() {
        if (!is_numeric($this->lastCheck)) {
            return strtotime($this->lastCheck);
        }
        return $this->lastCheck;
    }

    public function getNextCheck() {
        if (!is_numeric($this->nextCheck)) {
            return strtotime($this->nextCheck);
        }
        return $this->nextCheck;
    }

    public function isActiveChecksEnabled() {
        return (bool)$this->activeChecksEnabled;
    }

    public function processPerformanceData() {
        return (bool)$this->processPerformanceData;
    }

    /**
     * @return bool
     */
    public function isFlapping() {
        return (bool)$this->isFlapping;
    }

    /**
     * @return bool
     */
    public function isFlapDetectionEnabled() {
        return (bool)$this->flap_detection_enabled;
    }

    /**
     * @return bool
     */
    public function isNotificationsEnabled() {
        return (bool)$this->notifications_enabled;
    }

    /**
     * @return int
     */
    public function getAcknowledgementType() {
        return $this->acknowledgement_type;
    }

    public function getCurrentCheckAttempt() {
        return $this->current_check_attempt;
    }

    /**
     * @return mixed
     */
    public function getOutput() {
        return $this->output;
    }

    /**
     * @return mixed
     */
    public function getLongOutput() {
        return $this->long_output;
    }

    public function getPerfdata() {
        return $this->perfdata;
    }

    public function getLatency() {
        return $this->latency;
    }

    /**
     * @return bool
     */
    public function isInMonitoring() {
        return !is_null($this->currentState);
    }

    public function getMaxCheckAttempts() {
        return $this->max_check_attempts;
    }

    /**
     * @return array
     */
    public function toArray() {
        $arr = get_object_vars($this);
        if (isset($arr['UserTime'])) {
            unset($arr['UserTime']);
        }

        if ($this->UserTime !== null) {
            $arr['lastHardStateChange'] = $this->UserTime->format($this->getLastHardStateChange());
            $arr['last_state_change'] = $this->UserTime->format($this->getLastStateChange());
            $arr['lastCheck'] = $this->UserTime->format($this->getLastCheck());
            $arr['nextCheck'] = $this->UserTime->format($this->getNextCheck());
        } else {
            $arr['lastHardStateChange'] = $this->getLastHardStateChange();
            $arr['last_state_change'] = $this->getLastStateChange();
            $arr['lastCheck'] = $this->getLastCheck();
            $arr['nextCheck'] = $this->getNextCheck();
        }

        $arr['isHardstate'] = $this->isHardState();
        $arr['problemHasBeenAcknowledged'] = $this->isAcknowledged();
        $arr['isInMonitoring'] = $this->isInMonitoring();
        return $arr;
    }

    /**
     * @return array
     */
    public function toArrayForBrowser() {
        $arr = $this->toArray();
        $arr['lastHardStateChange'] = $this->UserTime->secondsInHumanShort(time() - $this->getLastHardStateChange());
        $arr['last_state_change'] = $this->UserTime->secondsInHumanShort(time() - $this->getLastStateChange());
        $arr['lastCheck'] = CakeTime::timeAgoInWords($this->getLastCheck());
        $arr['nextCheck'] = CakeTime::timeAgoInWords($this->getNextCheck());
        return $arr;
    }

}
