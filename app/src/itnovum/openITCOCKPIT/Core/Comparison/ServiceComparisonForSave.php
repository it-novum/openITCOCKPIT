<?php
// Copyright (C) <2018>  <it-novum GmbH>
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

namespace itnovum\openITCOCKPIT\Core\Comparison;

use itnovum\openITCOCKPIT\Core\CustomVariableDiffer;

/**
 * Class ServiceComparisonForSave
 *
 * Compares a given service with a given service template
 * Replace equal values with null in the $service array.
 *
 * @package itnovum\openITCOCKPIT\Core\Comparison
 */
class ServiceComparisonForSave {

    /**
     * @var array
     */
    private $service;

    /**
     * @var array
     */
    private $servicetemplate;

    /**
     * @var bool
     */
    private $hasOwnContacts = false;

    /**
     * @var bool
     */
    private $hasOwnCustomvariables = false;

    /**
     * Contacts and contact groups of the host
     * @var array
     */
    private $hostContactAndContactgroups = [];

    /**
     * Contacts and contact groups of the host template
     * @var array
     */
    private $hosttemplateContactAndContactgroups = [];

    /**
     * ServiceComparisonForSave constructor.
     * @param array $service
     * @param array $servicetemplate
     * @param array $hostContactAndContactgroups
     * @param array $hosttemplateContactAndContactgroups
     */
    public function __construct($service, $servicetemplate, $hostContactAndContactgroups = [], $hosttemplateContactAndContactgroups = []) {
        $this->service = $service['Service'];
        $this->servicetemplate = $servicetemplate['Servicetemplate'];
        $this->hostContactAndContactgroups = $hostContactAndContactgroups;
        $this->hosttemplateContactAndContactgroups = $hosttemplateContactAndContactgroups;
    }

    /**
     * @return array
     */
    public function getDataForSaveForAllFields() {
        $data = $this->getServiceBasicFields();

        $contactsAndContactgroups = $this->getDataForContactsAndContactgroups();
        $data['contacts'] = $contactsAndContactgroups['contacts'];
        $data['contactgroups'] = $contactsAndContactgroups['contactgroups'];
        $data['servicegroups'] = $this->getDataForServicegroups();
        $data['servicecommandargumentvalues'] = $this->getDataForCommandarguments();
        $data['serviceeventcommandargumentvalues'] = $this->getDataForEventHandlerCommandarguments();
        $data['customvariables'] = $this->getDataForCustomvariables();

        //Add service default data
        $data['service_type'] = GENERIC_SERVICE;
        $data['usage_flag'] = 0;
        $data['own_contacts'] = (int)$this->hasOwnContacts;
        $data['own_contactgroups'] = (int)$this->hasOwnContacts;
        $data['own_customvariables'] = (int)$this->hasOwnCustomvariables;
        $data['host_id'] = $this->service['host_id'];
        $data['servicetemplate_id'] = $this->service['servicetemplate_id'];

        return $data;
    }

    /**
     * @return array
     */
    public function getServiceBasicFields() {
        $fields = [
            'name',
            'description',
            'command_id',
            'eventhandler_command_id',
            'check_interval',
            'retry_interval',
            'max_check_attempts',
            'first_notification_delay',
            'notification_interval',
            'notify_on_recovery',
            'notify_on_warning',
            'notify_on_critical',
            'notify_on_unknown',
            'notify_on_flapping',
            'notify_on_downtime',
            'flap_detection_enabled',
            'flap_detection_on_ok',
            'flap_detection_on_warning',
            'flap_detection_on_critical',
            'flap_detection_on_unknown',
            'low_flap_threshold',
            'high_flap_threshold',
            'process_performance_data',
            'freshness_checks_enabled',
            'freshness_threshold',
            'passive_checks_enabled',
            'event_handler_enabled',
            'active_checks_enabled',
            'retain_status_information',
            'retain_nonstatus_information',
            'notifications_enabled',
            'notes',
            'priority',
            'check_period_id',
            'notify_period_id',
            'tags',
            'service_url',
            'is_volatile'
        ];

        $data = [];
        foreach ($fields as $field) {
            if ($this->service[$field] != $this->servicetemplate[$field]) {
                $data[$field] = $this->service[$field];
            } else {
                $data[$field] = null;
            }
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getDataForContacts() {
        return $this->getDataForContactsAndContactgroups()['contacts'];
    }

    /**
     * @return array
     */
    public function getDataForContactgroups() {
        return $this->getDataForContactsAndContactgroups()['contactgroups'];
    }

    /**
     * @return bool
     */
    public function hasOwnContactsAndContactgroups() {
        return $this->hasOwnContacts;
    }

    /**
     * With Nagios 4 (and Naemon) the inheritance of contacts and contact groups has changed.
     * Service and Services can now only inherit contacts AND contact groups at the same time.
     *
     * This will not get fixed anymore.
     *
     * See https://github.com/naemon/naemon-core/pull/92
     * @return array
     */
    public function getDataForContactsAndContactgroups() {
        //Where contacts changed or edited?
        $contactsDiff = array_diff($this->service['contacts']['_ids'], $this->servicetemplate['contacts']['_ids']);
        if (empty($contactsDiff)) {
            //Check if contacts got removed
            $contactsDiff = array_diff($this->servicetemplate['contacts']['_ids'], $this->service['contacts']['_ids']);
        }
        $this->hasOwnContacts = !empty($contactsDiff);

        //Where contact groups changed or edited?
        $contactgroupsDiff = array_diff($this->service['contactgroups']['_ids'], $this->servicetemplate['contactgroups']['_ids']);
        if (empty($contactgroupsDiff)) {
            //Check if contact groups got removed
            $contactgroupsDiff = array_diff($this->servicetemplate['contactgroups']['_ids'], $this->service['contactgroups']['_ids']);
        }

        if ($this->hasOwnContacts === false) {
            $this->hasOwnContacts = !empty($contactgroupsDiff);
        }

        if (!empty($contactsDiff) && !empty($contactgroupsDiff)) {
            //Contacts AND contact groups where modified
            //Due to https://github.com/naemon/naemon-core/pull/92
            //always save contacts AND contactgroups on a diff

            return [
                'contacts'      => [
                    '_ids' => $this->service['contacts']['_ids'],
                ],
                'contactgroups' => [
                    '_ids' => $this->service['contactgroups']['_ids']
                ]
            ];
        }

        if (empty($contactsDiff) && !empty($contactgroupsDiff)) {
            //Contact groups have been modified
            //Due to https://github.com/naemon/naemon-core/pull/92
            //always save contacts AND contactgroups on a diff

            return [
                'contacts'      => [
                    '_ids' => $this->servicetemplate['contacts']['_ids']
                ],
                'contactgroups' => [
                    '_ids' => $this->service['contactgroups']['_ids']
                ]
            ];
        }

        if (empty($contactgroupsDiff) && !empty($contactsDiff)) {
            //Contacts have been modified
            //Due to https://github.com/naemon/naemon-core/pull/92
            //always save contacts AND contactgroups on a diff

            return [
                'contacts'      => [
                    '_ids' => $this->service['contacts']['_ids'],
                ],
                'contactgroups' => [
                    '_ids' => $this->servicetemplate['contactgroups']['_ids']
                ]
            ];
        }

        return [
            'contacts'      => [
                '_ids' => []
            ],
            'contactgroups' => [
                '_ids' => []
            ]
        ];
    }

    /**
     * @return array
     */
    public function getDataForServicegroups() {
        //Where servicegroups changed or edited?
        $servicegroupsDiff = array_diff($this->service['servicegroups']['_ids'], $this->servicetemplate['servicegroups']['_ids']);
        if (empty($servicegroupsDiff)) {
            //Check if service groups got removed
            $servicegroupsDiff = array_diff($this->servicetemplate['servicegroups']['_ids'], $this->service['servicegroups']['_ids']);
        }

        if (!empty($servicegroupsDiff)) {
            //Service use own service groups
            return [
                '_ids' => $this->service['servicegroups']['_ids']
            ];
        }

        return [
            '_ids' => []
        ];
    }

    /**
     * @return array
     */
    public function getDataForCustomvariables() {
        $customVariableDiffer = new CustomVariableDiffer(
            $this->service['customvariables'],
            $this->servicetemplate['customvariables']
        );

        $customvariables = $customVariableDiffer->getCustomVariablesToSaveAsRepository();
        if ($customvariables->getSize() === 0) {
            //No diff
            return [];
        }

        $this->hasOwnCustomvariables = true;

        return $customvariables->getAllCustomVariablesAsArray();
    }

    /**
     * @return bool
     */
    public function hasOwnCustomvariables() {
        return $this->hasOwnCustomvariables;
    }

    /**
     * @return array
     */
    public function getDataForCommandarguments() {
        if ($this->service['command_id'] != $this->servicetemplate['command_id']) {
            //Different check command than the service template uses.
            //Definitely the command arguments has changed
            return $this->service['servicecommandargumentvalues'];
        }

        $serviceCommandArguments = [];
        $servicetemplateCommandArguments = [];
        foreach ($this->service['servicecommandargumentvalues'] as $hcargv) {
            $serviceCommandArguments[$hcargv['commandargument_id']] = $hcargv['value'];
        }

        foreach ($this->servicetemplate['servicetemplatecommandargumentvalues'] as $htcargv) {
            $servicetemplateCommandArguments[$htcargv['commandargument_id']] = $htcargv['value'];
        }

        $diff = array_diff($serviceCommandArguments, $servicetemplateCommandArguments);
        if (empty($diff)) {
            $diff = array_diff($servicetemplateCommandArguments, $serviceCommandArguments);
        }

        if (empty($diff)) {
            return [];
        }

        //There is a diff, save all command argument values for this service
        return $this->service['servicecommandargumentvalues'];
    }

    /**
     * @return array
     */
    public function getDataForEventHandlerCommandarguments() {
        if ($this->service['eventhandler_command_id'] != $this->servicetemplate['eventhandler_command_id']) {
            //Different check command than the service template uses.
            //Definitely the command arguments has changed
            return $this->service['serviceeventcommandargumentvalues'];
        }

        $serviceCommandArguments = [];
        $servicetemplateCommandArguments = [];
        foreach ($this->service['serviceeventcommandargumentvalues'] as $sehcargv) {
            $serviceCommandArguments[$sehcargv['commandargument_id']] = $sehcargv['value'];
        }

        foreach ($this->servicetemplate['servicetemplateeventcommandargumentvalues'] as $htcargv) {
            $servicetemplateCommandArguments[$htcargv['commandargument_id']] = $htcargv['value'];
        }

        $diff = array_diff($serviceCommandArguments, $servicetemplateCommandArguments);
        if (empty($diff)) {
            $diff = array_diff($servicetemplateCommandArguments, $serviceCommandArguments);
        }

        if (empty($diff)) {
            return [];
        }

        //There is a diff, save all command argument values for this service
        return $this->service['serviceeventcommandargumentvalues'];
    }

}

