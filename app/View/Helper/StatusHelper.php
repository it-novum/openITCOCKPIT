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

/**
 * Class StatusHelper
 * @deprecated
 */
class StatusHelper extends AppHelper {

    //Delete with CakePHP 4

    /**
     * Return the status color for a Service
     *
     * @param int $state the current status of a Service
     *
     * @return array which contains the human state and the css class
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     * @since  3.0
     * @deprecated
     */
    function ServiceStatusColorSimple($state) {
        if (isset($state)) {
            switch ((int)$state) {
                case 0:
                    return [
                        'human_state' => __('Ok'),
                        'class'       => 'btn-success',
                        'hexColor'    => '#5CB85C'
                    ];
                case 1:
                    return [
                        'human_state' => __('Warning'),
                        'class'       => 'btn-warning',
                        'hexColor'    => '#f0ad4e'
                    ];
                case 2:
                    return [
                        'human_state' => __('Critical'),
                        'class'       => 'btn-danger',
                        'hexColor'    => '#d9534f'
                    ];
                case 3:
                    return [
                        'human_state' => __('Unknown'),
                        'class'       => 'btn-unknown',
                        'hexColor'    => '#4C4F53'
                    ];
                default:
                    return [
                        'human_state' => __('Not Found'),
                        'class'       => 'btn-primary',
                        'hexColor'    => '#337ab7'
                    ];
            }
        }
    }


    /**
     * Return the status color for a Host
     *
     * @param int $state the current status of a Host
     *
     * @return array which contains the human state and the css class
     * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
     * @since  3.0
     * @deprecated
     */
    function HostStatusColorSimple($state) {
        if (isset($state)) {
            switch ((int)$state) {
                case 0:
                    return [
                        'human_state' => __('Ok'),
                        'class'       => 'btn-success',
                        'hexColor'    => '#5CB85C'
                    ];
                case 1:
                    return [
                        'human_state' => __('Down'),
                        'class'       => 'btn-danger',
                        'hexColor'    => '#d9534f'
                    ];
                case 2:
                    return [
                        'human_state' => __('Unreachable'),
                        'class'       => 'btn-unknown',
                        'hexColor'    => '#4C4F53'
                    ];
                default:
                    return [
                        'human_state' => __('Not Found'),
                        'class'       => 'btn-primary',
                        'hexColor'    => '#337ab7'
                    ];
            }
        }
    }


    /**
     * @param int $state
     * @return string
     * @deprecated
     */
    function HostStatusTextColor($state = 2) {
        if ($state === null) {
            return 'txt-primary';
        }

        switch ($state) {
            case 0:
                return 'txt-color-green';

            case 1:
                return 'txt-color-red';

            default:
                return 'txt-color-blueLight';
        }
    }

    /**
     * @param int $state
     * @return string
     * @deprecated
     */
    function ServiceStatusTextColor($state = 2) {
        if ($state === null) {
            return 'txt-primary';
        }

        switch ($state) {
            case 0:
                return 'txt-color-green';

            case 1:
                return 'warning';

            case 2:
                return 'txt-color-red';

            default:
                return 'txt-color-blueLight';
        }
    }

    /**
     * Returns human_state for service
     *
     * @param integer $hoststatus
     *
     * @return string host status for humans
     * @author Irina Bering <irina.bering@it-novum.com>
     * @since  3.0
     * @deprecated
     */
    public function humanSimpleHostStatus($hoststatus = 0) {
        switch ($hoststatus) {
            case 0:
                return __('Up');
            case 1:
                return __('Down');
            case 2:
                return __('Unreachable');
        }
    }

    /**
     * Returns human_state for service
     *
     * @param integer $hoststatus
     *
     * @return string host status for humans
     * @author Irina Bering <irina.bering@it-novum.com>
     * @since  3.0
     * @deprecated
     */
    public function humanSimpleServiceStatus($servicestatus = 0) {
        switch ($servicestatus) {
            case 0:
                return __('Ok');
            case 1:
                return __('Warning');
            case 2:
                return __('Critical');
            case 3:
                return __('Unknown');
        }
    }

}