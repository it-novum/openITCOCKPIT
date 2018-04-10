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


class HosttemplateMerger {

    /**
     * @var array
     */
    private $host = [
        'Host' => []
    ];

    /**
     * @var array
     */
    private $hosttemplate = [
        'Hosttemplate' => []
    ];

    /**
     * @var array
     */
    private $ignore = [
        'id',
        'uuid',
        'container_id',
        'created',
        'modified'
    ];

    private $contactsFromHost = false;

    private $contactsFromHosttemplate = false;

    /**
     * HosttemplateMerger constructor.
     * @param $host
     * @param $hosttemplate
     */
    public function __construct($host, $hosttemplate) {
        $this->host = $host;
        $this->hosttemplate = $hosttemplate;
    }

    /**
     * @return array
     */
    public function mergeHostWithTemplate() {
        $mergedHost = $this->host['Host'];

        foreach ($this->host['Host'] as $key => $value) {
            if (in_array($key, $this->ignore, true)) {
                continue;
            }

            if ($value === null || $value === '') {
                if (isset($this->hosttemplate['Hosttemplate'][$key])) {
                    $mergedHost[$key] = $this->hosttemplate['Hosttemplate'][$key];
                }
            }
        }

        $bools = [
            'notify_on_down',
            'notify_on_unreachable',
            'notify_on_recovery',
            'notify_on_flapping',
            'notify_on_downtime',
            'flap_detection_enabled',
            'flap_detection_on_up',
            'flap_detection_on_down',
            'flap_detection_on_unreachable',
            'freshness_checks_enabled',
            'passive_checks_enabled',
            'event_handler_enabled',
            'active_checks_enabled',
            'notifications_enabled',
        ];
        foreach($bools as $boolField){
            $mergedHost[$boolField] = (bool)$mergedHost[$boolField];
        }

        return $mergedHost;
    }

    /**
     * @return array
     */
    public function mergeCheckPeriod() {
        if (empty($this->host['Host']['check_period_id'])) {
            return $this->hosttemplate['CheckPeriod'];
        }
        return $this->host['CheckPeriod'];
    }

    /**
     * @return array
     */
    public function mergeNotifyPeriod() {
        if (empty($this->host['Host']['notify_period_id'])) {
            return $this->hosttemplate['NotifyPeriod'];
        }
        return $this->host['NotifyPeriod'];
    }

    /**
     * @return array
     */
    public function mergeCheckCommand() {
        if (empty($this->host['Host']['command_id'])) {
            return $this->hosttemplate['CheckCommand'];
        }
        return $this->host['CheckCommand'];
    }

    /**
     * @return array
     */
    public function mergeCustomvariables() {
        $customvariablesTmp = [];

        foreach ($this->hosttemplate['Customvariable'] as $customvariable) {
            $customvariablesTmp[$customvariable['name']] = $customvariable;
        }

        foreach ($this->host['Customvariable'] as $customvariable) {
            $customvariablesTmp[$customvariable['name']] = $customvariable;
        }

        //Remove string keys to get array, not a hash map
        return array_values($customvariablesTmp);
    }

    /**
     * @return array
     */
    public function mergeCommandarguments() {
        if (empty($this->host['Hostcommandargumentvalue'])) {
            return $this->hosttemplate['Hosttemplatecommandargumentvalue'];
        }
        return $this->host['Hostcommandargumentvalue'];
    }

    /**
     * @return array
     */
    public function mergeCommandargumentsForReplace() {
        $commandargumentvaluesTmp = [];
        if (empty($this->host['Hostcommandargumentvalue'])) {
            $commandargumentvaluesTmp = $this->hosttemplate['Hosttemplatecommandargumentvalue'];
        } else {
            $commandargumentvaluesTmp = $this->host['Hostcommandargumentvalue'];
        }

        $commandargumentvalues = [];
        foreach ($commandargumentvaluesTmp as $commandargumentvalue) {
            $commandargumentName = $commandargumentvalue['Commandargument']['name'];
            $commandargumentvalues[$commandargumentName] = $commandargumentvalue['value'];
        }
        return $commandargumentvalues;
    }

    /**
     * @return array
     */
    public function mergeContacts() {
        if (empty($this->host['Contact']) && empty($this->host['Contactgroup'])) {
            $this->contactsFromHosttemplate = true;
            return $this->hosttemplate['Contact'];
        }
        $this->contactsFromHost = true;
        return $this->host['Contact'];
    }

    /**
     * @return array
     */
    public function mergeContactgroups() {
        if (empty($this->host['Contactgroup']) && empty($this->host['Contact'])) {
            $this->contactsFromHosttemplate = true;
            return $this->hosttemplate['Contactgroup'];
        }
        $this->contactsFromHost = true;
        return $this->host['Contactgroup'];
    }

    /**
     * @return bool
     */
    public function areContactsFromHost() {
        return $this->contactsFromHost;
    }

    /**
     * @return bool
     */
    public function areContactsFromHosttemplate() {
        return $this->contactsFromHosttemplate;
    }


}
