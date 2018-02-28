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

class MonitoringHelper extends AppHelper
{

    /**
     * Returns usefull HTML code of the flap detection. Only out of configurationd atabase
     * Does not use $hostatus!
     *
     * @param array $host       from find('first')
     * @param array $hoststatus , if not given the $hoststatus array of the current view will be used (default)
     *
     * @return array with the flap detection settings. Array keys: 'string', 'html' and 'value'
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function checkFlapDetection($value = 0)
    {
        if ($value == 1) {
            return ['string' => __('On'), 'html' => '<span class="label bg-color-green">'.__('On').'</span>', 'value' => $value];
        }

        return ['string' => __('Off'), 'html' => '<span class="label bg-color-red">'.__('Off').'</span>', 'value' => $value];
    }


    /**
     * Check if ther is a difference betwen monitoring hoststatus flap_detection_ebabled and the itcockpit database
     * configuration If yes it will return the current setting from $hostatus This can hapen, if a user disable the
     * flep detection with an external command, but not in the host configuration
     *
     * @param array $host       from find('first')
     * @param array $hoststatus , if not given the $hoststatus array of the current view will be used (default)
     *
     * @return array with the flap detection settings. Array keys: 'string', 'html' and 'value'
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function compareHostFlapDetectionWithMonitoring($host, $hoststatus = null)
    {
        if ($hoststatus === null) {
            $hoststatus = $this->_View->viewVars['hoststatus'];
        }

        $flapDetectionEnabledFromMonitoring = $host['Host']['flap_detection_enabled'];
        $flapDetectionEnabledFromConfig = $host['Host']['flap_detection_enabled'];
        if (isset($hoststatus[$host['Host']['uuid']])) {
            $flapDetectionEnabledFromMonitoring = $hoststatus[$host['Host']['uuid']]['Hoststatus']['flap_detection_enabled'];
        }

        if ($flapDetectionEnabledFromConfig != $flapDetectionEnabledFromMonitoring) {
            //Flapdetection was temporary en- or disabled by an external command
            if ($flapDetectionEnabledFromMonitoring == 1) {
                return ['string' => __('Temporary on'), 'html' => '<a data-original-title="'.__('Difference to configuration detected').'" data-placement="bottom" rel="tooltip" href="javascript:void(0);"><i class="fa fa-exclamation-triangle txt-color-orange"></i></a> <span class="label bg-color-greenLight">'.__('Temporary on').'</span>', 'value' => $flapDetectionEnabledFromMonitoring];
            }

            return ['string' => __('Temporary off'), 'html' => '<a data-original-title="'.__('Difference to configuration detected').'" data-placement="bottom" rel="tooltip" href="javascript:void(0);"><i class="fa fa-exclamation-triangle txt-color-orange"></i></a> <span class="label bg-color-redLight">'.__('Temporary off').'</span>', 'value' => $flapDetectionEnabledFromMonitoring];
        }

        if ($flapDetectionEnabledFromConfig == 1) {
            return ['string' => __('On'), 'html' => '<span class="label bg-color-green">'.__('On').'</span>', 'value' => $flapDetectionEnabledFromConfig];
        }

        return ['string' => __('Off'), 'html' => '<span class="label bg-color-red">'.__('Off').'</span>', 'value' => $flapDetectionEnabledFromConfig];
    }

    /**
     * Check if ther is a difference betwen monitoring serviestatus flap_detection_ebabled and the itcockpit database
     * configuration If yes it will return the current setting from $servicestatus This can hapen, if a user disable
     * the flep detection with an external command, but not in the host configuration
     *
     * @param array $service       from find('first')
     * @param array $servicestatus , if not given the $servicestatus array of the current view will be used (default)
     *
     * @return array with the flap detection settings. Array keys: 'string', 'html' and 'value'
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function compareServiceFlapDetectionWithMonitoring($service, $servicestatus = null)
    {
        if ($servicestatus === null) {
            $servicestatus = $this->_View->viewVars['servicestatus'];
        }

        $flapDetectionEnabledFromMonitoring = $service['Service']['flap_detection_enabled'];
        $flapDetectionEnabledFromConfig = $service['Service']['flap_detection_enabled'];
        if (isset($servicestatus[$service['Service']['uuid']])) {
            $flapDetectionEnabledFromMonitoring = $servicestatus[$service['Service']['uuid']]['Servicestatus']['flap_detection_enabled'];
        }

        if ($flapDetectionEnabledFromConfig != $flapDetectionEnabledFromMonitoring) {
            //Flapdetection was temporary en- or disabled by an external command
            if ($flapDetectionEnabledFromMonitoring == 1) {
                return ['string' => __('Temporary on'), 'html' => '<a data-original-title="'.__('Difference to configuration detected').'" data-placement="bottom" rel="tooltip" href="javascript:void(0);"><i class="fa fa-exclamation-triangle txt-color-orange"></i></a> <span class="label bg-color-greenLight">'.__('Temporary on').'</span>', 'value' => $flapDetectionEnabledFromMonitoring];
            }

            return ['string' => __('Temporary off'), 'html' => '<a data-original-title="'.__('Difference to configuration detected').'" data-placement="bottom" rel="tooltip" href="javascript:void(0);"><i class="fa fa-exclamation-triangle txt-color-orange"></i></a> <span class="label bg-color-redLight">'.__('Temporary off').'</span>', 'value' => $flapDetectionEnabledFromMonitoring];
        }

        if ($flapDetectionEnabledFromConfig == 1) {
            return ['string' => __('On'), 'html' => '<span class="label bg-color-green">'.__('On').'</span>', 'value' => $flapDetectionEnabledFromConfig];
        }

        return ['string' => __('Off'), 'html' => '<span class="label bg-color-red">'.__('Off').'</span>', 'value' => $flapDetectionEnabledFromConfig];
    }

    /**
     * @param $notifications
     * @return string
     * @deprecated
     */
    public function formatNotifyOnHost($notifications)
    {
        $_options = [
            'notify_on_down'        => 1,
            'notify_on_unreachable' => 0,
            'notify_on_recovery'    => 1,
            'notify_on_flapping'    => 0,
            'notify_on_downtime'    => 0,
        ];

        $notifications = Hash::merge($_options, $notifications);
        $html = '';
        foreach ($notifications as $key => $value) {
            $html .= '<dd>';
            if ($value == 1) {
                $html .= '<i class="fa fa-check txt-color-green"></i> '.__($key);
            } else {
                $html .= '<i class="fa fa-times txt-color-red"></i> '.__($key);
            }
            $html .= '</dd>';
        }

        return $html;
    }

    public function formatNotifyOnService($notifications)
    {
        $_options = [
            'notify_on_warning'  => 1,
            'notify_on_critical' => 0,
            'notify_on_unknown'  => 0,
            'notify_on_recovery' => 1,
            'notify_on_flapping' => 0,
            'notify_on_downtime' => 0,
        ];

        $notifications = Hash::merge($_options, $notifications);
        $html = '';
        foreach ($notifications as $key => $value) {
            $html .= '<dd>';
            if ($value == 1) {
                $html .= '<i class="fa fa-check txt-color-green"></i> '.__($key);
            } else {
                $html .= '<i class="fa fa-times txt-color-red"></i> '.__($key);
            }
            $html .= '</dd>';
        }

        return $html;
    }

    /**
     * Check if ther is a dir with graphs for this host
     *
     * @param string $hostUuid , the UUID of the host you want to check
     *
     * @return bool true if exits or false if not
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function checkForHostGraph($hostUuid)
    {
        if (!isset($this->RRDPath)) {
            $this->RRDPath = Configure::read('rrd.path');
        }

        if (is_dir($this->RRDPath.$hostUuid)) {
            return true;
        }

        return false;
    }

    /**
     * Check if ther is a file with graphs for this servic
     *
     * @param string $hostUuid    , the UUID of the host you want to check
     * @param string $serviceUuid , the UUID of the host you want to check
     *
     * @return bool true if exits or false if not
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function checkForServiceGraph($hostUuid, $serviceUuid)
    {
        if (!isset($this->RRDPath)) {
            $this->RRDPath = Configure::read('rrd.path');
        }

        if (file_exists($this->RRDPath.$hostUuid.'/'.$serviceUuid.'.rrd')) {
            return true;
        }

        return false;
    }


    /**
     * Return an `<a />` with the status icon of a notification
     *
     * @param integer $status            , from notifications table
     * @param integer $notification_type from notifications (0 = host, 1 = service) [yes, nagios change this on EVERY
     *                                   table -.-]
     * @param string  $href              href of the <a> tag
     *
     * @return string status 'icon' as HTML for host and service notifications
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function NotificationStatusIcon($status = 3, $notification_type = 1, $href = 'javascript:void(0)')
    {
        if ($notification_type == 1) {
            //Service
            switch ($status) {
                case 0:
                    return '<a href="'.$href.'" class="btn btn-success status-circle"></a>';
                    break;
                case 1:
                    return '<a href="'.$href.'" class="btn btn-warning status-circle" ></a>';
                    break;
                case 2:
                    return '<a href="'.$href.'" class="btn btn-danger status-circle" ></a>';
                    break;
                default:
                    return '<a href="'.$href.'" class="btn btn-default status-circle" ></a>';
            }
        } else {
            //Host
            switch ($status) {
                case 0:
                    return '<a href="'.$href.'" class="btn btn-success status-circle"></a>';
                    break;
                case 1:
                    return '<a href="'.$href.'" class="btn btn-danger status-circle" ></a>';
                    break;
                default:
                    return '<a href="'.$href.'" class="btn btn-default status-circle" ></a>';
            }
        }
    }

    public function hostFlappingIconColored($is_flapping = 0, $class = '', $state = null)
    {

        $stateColors = [
            0 => 'ok',
            1 => 'critical',
            2 => '',
        ];

        if ($is_flapping == 1) {
            if ($state !== null) {
                return '<span class="flapping_airport '.$class.' '.$stateColors[$state].'"><i class="fa fa-circle '.$stateColors[$state].'"></i> <i class="fa fa-circle-o '.$stateColors[$state].'"></i></span>';
            }

            return '<span class="flapping_airport text-primary '.$class.'"><i class="fa fa-circle '.$stateColors[$state].'"></i> <i class="fa fa-circle-o '.$stateColors[$state].'"></i></span>';
        }

        return '';
    }

    public function serviceFlappingIconColored($is_flapping = 0, $class = '', $state = null)
    {

        $stateColors = [
            0 => 'txt-color-green',
            1 => 'warning',
            2 => 'txt-color-red',
            3 => 'txt-color-blueDark',
        ];

        if ($is_flapping == 1) {
            if ($state !== null) {
                return '<span class="'.$stateColors[$state].'"><span class="flapping_airport '.$class.' '.$stateColors[$state].'"><i class="fa fa-circle '.$stateColors[$state].'"></i> <i class="fa fa-circle-o '.$stateColors[$state].'"></i></span></span>';
            }

            return '<span class="'.$stateColors[$state].'"><span class="flapping_airport text-primary '.$class.'"><i class="fa fa-circle '.$stateColors[$state].'"></i> <i class="fa fa-circle-o '.$stateColors[$state].'"></i></span></span>';
        }

        return '';
    }

    public function serviceFlappingIcon($is_flapping = 0, $class = '')
    {
        if ($is_flapping == 1) {
            return '<span class="flapping_airport '.$class.'"><i class="fa fa-circle"></i> <i class="fa fa-circle-o"></i></span>';
        }

        return '';
    }

    public function hostFlappingIcon($is_flapping = 0, $class = '')
    {
        return $this->serviceFlappingIcon($is_flapping, $class);
    }

    public function checkForDowntime($downtime_deep)
    {
        if ($downtime_deep > 0) {
            return true;
        }

        return false;
    }

    public function checkForAck($ack)
    {
        if ($ack == 1) {
            return true;
        }

        return false;
    }

    public function replaceCommandArguments($commandarguments, $command_line)
    {
        return str_replace(array_keys($commandarguments), array_values($commandarguments), $command_line);
    }

    public function checkForGrapherTemplate($commandUuid)
    {
        if (file_exists(APP.'GrapherTemplates'.DS.$commandUuid.'.php')) {
            return true;
        }

        return false;
    }
}