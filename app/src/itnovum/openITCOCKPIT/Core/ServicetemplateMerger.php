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


class ServicetemplateMerger {

    /**
     * @var array
     */
    private $service = [
        'Service' => []
    ];

    /**
     * @var array
     */
    private $servicetemplate = [
        'Servicetemplate' => []
    ];

    /**
     * @var array
     */
    private $ignore = [
        'id',
        'uuid',
        'host_id',
        'service_type',
        'created',
        'modified'
    ];

    private $contactsFromService = false;

    private $contactsFromServicetemplate = false;

    /**
     * ServicetemplateMerger constructor.
     * @param $service
     * @param $servicetemplate
     */
    public function __construct($service, $servicetemplate) {
        $this->service = $service;
        $this->servicetemplate = $servicetemplate;
    }

    /**
     * @return array
     */
    public function mergeServiceWithTemplate() {
        $mergedService = $this->service['Service'];

        foreach ($this->service['Service'] as $key => $value) {
            if (in_array($key, $this->ignore, true)) {
                continue;
            }

            if ($value === null || $value === '') {
                if (isset($this->servicetemplate['Servicetemplate'][$key])) {
                    $mergedService[$key] = $this->servicetemplate['Servicetemplate'][$key];
                }
            }
        }

        $bools = [
            'notify_on_warning',
            'notify_on_unknown',
            'notify_on_critical',
            'notify_on_recovery',
            'notify_on_flapping',
            'notify_on_downtime',
            'flap_detection_enabled',
            'flap_detection_on_ok',
            'flap_detection_on_warning',
            'flap_detection_on_critical',
            'flap_detection_on_unknown',
            'freshness_checks_enabled',
            'passive_checks_enabled',
            'event_handler_enabled',
            'active_checks_enabled',
            'notifications_enabled',
        ];
        foreach($bools as $boolField){
            $mergedService[$boolField] = (bool)$mergedService[$boolField];
        }

        return $mergedService;
    }

    /**
     * @return array
     */
    public function mergeCheckPeriod() {
        if (empty($this->service['Service']['check_period_id'])) {
            return $this->servicetemplate['CheckPeriod'];
        }
        return $this->service['CheckPeriod'];
    }

    /**
     * @return array
     */
    public function mergeNotifyPeriod() {
        if (empty($this->service['Service']['notify_period_id'])) {
            return $this->servicetemplate['NotifyPeriod'];
        }
        return $this->service['NotifyPeriod'];
    }

    /**
     * @return array
     */
    public function mergeCheckCommand() {
        if (empty($this->service['Service']['command_id'])) {
            return $this->servicetemplate['CheckCommand'];
        }
        return $this->service['CheckCommand'];
    }

    /**
     * @return array
     */
    public function mergeCustomvariables() {
        $customvariablesTmp = [];

        foreach ($this->servicetemplate['Customvariable'] as $customvariable) {
            $customvariablesTmp[$customvariable['name']] = $customvariable;
        }

        foreach ($this->service['Customvariable'] as $customvariable) {
            $customvariablesTmp[$customvariable['name']] = $customvariable;
        }

        //Remove string keys to get array, not a hash map
        return array_values($customvariablesTmp);
    }

    /**
     * @return array
     */
    public function mergeCommandarguments() {
        if (empty($this->service['Servicecommandargumentvalue'])) {
            return $this->servicetemplate['Servicetemplatecommandargumentvalue'];
        }
        return $this->service['Servicecommandargumentvalue'];
    }

    /**
     * @return array
     */
    public function mergeCommandargumentsForReplace() {
        $commandargumentvaluesTmp = [];


        if (empty($this->service['Servicecommandargumentvalue'])) {
            $commandargumentvaluesTmp = $this->servicetemplate['Servicetemplatecommandargumentvalue'];
        } else {
            $commandargumentvaluesTmp = $this->service['Servicecommandargumentvalue'];
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
        if (empty($this->service['Contact']) && empty($this->service['Contactgroup'])) {
            //Contacts from Servicetemplate, or from Host/Hosttemplate?
            if (!empty($this->servicetemplate['Contact']) || !empty($this->servicetemplate['Contactgroup'])) {
                $this->contactsFromServicetemplate = true;
            }

            return $this->servicetemplate['Contact'];
        }
        $this->contactsFromService = true;
        return $this->service['Contact'];
    }

    /**
     * @return array
     */
    public function mergeContactgroups() {
        if (empty($this->service['Contactgroup']) && empty($this->service['Contact'])) {
            //Contactgroups from Servicetemplate, or from Host/Hosttemplate?
            if (!empty($this->servicetemplate['Contactgroup']) || !empty($this->servicetemplate['Contact'])) {
                $this->contactsFromServicetemplate = true;
            }

            return $this->servicetemplate['Contactgroup'];
        }

        $this->contactsFromService = true;
        return $this->service['Contactgroup'];
    }

    /**
     * @return bool
     */
    public function areContactsFromService() {
        return $this->contactsFromService;
    }

    /**
     * @return bool
     */
    public function areContactsFromServicetemplate() {
        return $this->contactsFromServicetemplate;
    }


}
