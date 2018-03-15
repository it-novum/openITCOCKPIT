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


class ConstantsComponent extends Component {
    /**
     * Creates an array with the basic constants and define them automatically
     */
    public function __construct() {
        if (!defined('ROOT_CONTAINER')) {
            define('ROOT_CONTAINER', 1);
        }

        Configure::load('dbbackend');

        //core array
        $this->defines = [];

        //Monitoring Objects
        $monitoring = Configure::read('dbbackend');

        $this->defines['monitoring'] = [
            //Models

            'MONITORING_HOSTSTATUS'                => $monitoring . 'Module.Hoststatus',
            'MONITORING_SERVICESTATUS'             => $monitoring . 'Module.Servicestatus',
            'MONITORING_OBJECTS'                   => $monitoring . 'Module.Objects',

            //'MONITORING_EXTERNALCOMMAND'           => $monitoring . 'Module.Externalcommand',
            //Externalcommand Model dont use a table, so it is always NagiosModule
            'MONITORING_EXTERNALCOMMAND'           => 'NagiosModule.Externalcommand',

            'MONITORING_DOWNTIME'                  => $monitoring . 'Module.Downtime',
            'MONITORING_DOWNTIME_HOST'             => $monitoring . 'Module.DowntimeHost',
            'MONITORING_DOWNTIME_SERVICE'          => $monitoring . 'Module.DowntimeService',
            'MONITORING_LOGENTRY'                  => $monitoring . 'Module.Logentry',
            'MONITORING_NAGIOSTAT'                 => $monitoring . 'Module.Nagiostat',
            'MONITORING_NOTIFICATION'              => $monitoring . 'Module.Notification',
            'MONITORING_NOTIFICATION_HOST'         => $monitoring . 'Module.NotificationHost',
            'MONITORING_NOTIFICATION_SERVICE'      => $monitoring . 'Module.NotificationService',
            'MONITORING_SERVICECHECK'              => $monitoring . 'Module.Servicecheck',
            'MONITORING_STATEHISTORY_HOST'         => $monitoring . 'Module.StatehistoryHost',
            'MONITORING_STATEHISTORY_SERVICE'      => $monitoring . 'Module.StatehistoryService',
            'MONITORING_STATEHISTORY'              => $monitoring . 'Module.Statehistory',
            'MONITORING_HOSTCHECK'                 => $monitoring . 'Module.Hostcheck',
            'MONITORING_ACKNOWLEDGED'              => $monitoring . 'Module.Acknowledged',
            'MONITORING_ACKNOWLEDGED_HOST'         => $monitoring . 'Module.AcknowledgedHost',
            'MONITORING_ACKNOWLEDGED_SERVICE'      => $monitoring . 'Module.AcknowledgedService',
            'MONITORING_CONTACTNOTIFICATION'       => $monitoring . 'Module.Contactnotification',
            'MONITORING_CONTACTNOTIFICATIONMETHOD' => $monitoring . 'Module.Contactnotificationmethod',
            'MONITORING_PARENTHOST'                => $monitoring . 'Module.Parenthost',
            'MONITORING_COMMENTHISTORY'            => $monitoring . 'Module.Commenthistory',
            'MONITORING_FLAPPINGHISTORY'           => $monitoring . 'Module.Flappinghistory',

            'MONITORING_CORECONFIG_MODEL' => $monitoring . 'Module.Coreconfig',

            //Components
            'MONITORING_CORECONFIG'       => $monitoring . 'Module.CoreConfig',
        ];
        $this->define($this->defines['monitoring']);

        //Object definitions
        $this->defines['objects'] = [
            'OBJECT_TENANT'               => 1 << 0,
            'OBJECT_USER'                 => 1 << 1,
            'OBJECT_NODE'                 => 1 << 2,
            'OBJECT_LOCATION'             => 1 << 3,
            'OBJECT_DEVICEGROUP'          => 1 << 4,
            'OBJECT_CONTACT'              => 1 << 5,
            'OBJECT_CONTACTGROUP'         => 1 << 6,
            'OBJECT_TIMEPERIOD'           => 1 << 7,
            'OBJECT_HOST'                 => 1 << 8,
            'OBJECT_HOSTTEMPLATE'         => 1 << 9,
            'OBJECT_HOSTGROUP'            => 1 << 10,
            'OBJECT_SERVICE'              => 1 << 11,
            'OBJECT_SERVICETEMPLATE'      => 1 << 12,
            'OBJECT_SERVICEGROUP'         => 1 << 13,
            'OBJECT_COMMAND'              => 1 << 14,
            'OBJECT_SATELLITE'            => 1 << 15,
            'OBJECT_SERVICETEMPLATEGROUP' => 1 << 16,
            'OBJECT_HOSTESCALATION'       => 1 << 17,
            'OBJECT_SERVICEESCALATION'    => 1 << 18,
            'OBJECT_HOSTDEPENDENCY'       => 1 << 19,
            'OBJECT_SERVICEDEPENDENCY'    => 1 << 20,
            'OBJECT_INSTANTREPORT'        => 1 << 21,
        ];
        $this->define($this->defines['objects']);

        //Container type definitions
        $this->defines['container_types'] = [
            'CT_GLOBAL'               => 1,
            'CT_TENANT'               => 2,
            'CT_LOCATION'             => 3,
            'CT_DEVICEGROUP'          => 4,
            'CT_NODE'                 => 5,
            'CT_CONTACTGROUP'         => 6,
            'CT_HOSTGROUP'            => 7,
            'CT_SERVICEGROUP'         => 8,
            'CT_SERVICETEMPLATEGROUP' => 9,
        ];
        $this->define($this->defines['container_types']);

        //Container definitions and properties
        $this->defines['containers'] = [
            "GLOBAL_CONTAINER"               => [
                'properties'     => OBJECT_TENANT ^ OBJECT_USER ^ OBJECT_NODE ^ OBJECT_CONTACT ^ OBJECT_CONTACTGROUP ^ OBJECT_TIMEPERIOD ^ OBJECT_HOST ^ OBJECT_HOSTTEMPLATE ^ OBJECT_HOSTGROUP ^ OBJECT_SERVICE ^ OBJECT_SERVICETEMPLATE ^ OBJECT_SERVICEGROUP ^ OBJECT_SATELLITE ^ OBJECT_SERVICETEMPLATEGROUP ^ OBJECT_HOSTESCALATION ^ OBJECT_SERVICEESCALATION ^ OBJECT_HOSTDEPENDENCY ^ OBJECT_SERVICEDEPENDENCY ^ OBJECT_INSTANTREPORT,
                'container_type' => CT_GLOBAL,
            ],
            "TENANT_CONTAINER"               => [
                'properties'     => OBJECT_USER ^ OBJECT_NODE ^ OBJECT_LOCATION ^ OBJECT_CONTACT ^ OBJECT_CONTACTGROUP ^ OBJECT_TIMEPERIOD ^ OBJECT_HOST ^ OBJECT_HOSTTEMPLATE ^ OBJECT_HOSTGROUP ^ OBJECT_SERVICE ^ OBJECT_SERVICETEMPLATE ^ OBJECT_SERVICEGROUP ^ OBJECT_SATELLITE ^ OBJECT_SERVICETEMPLATEGROUP ^ OBJECT_HOSTESCALATION ^ OBJECT_SERVICEESCALATION ^ OBJECT_HOSTDEPENDENCY ^ OBJECT_SERVICEDEPENDENCY ^ OBJECT_INSTANTREPORT,
                'container_type' => CT_TENANT,
            ],
            "LOCATION_CONTAINER"             => [
                'properties'     => OBJECT_NODE ^ OBJECT_SATELLITE,
                'container_type' => CT_LOCATION,
            ],
            /*"DEVICEGROUP_CONTAINER" => [
                'properties' => OBJECT_HOST,
                'container_type' => CT_DEVICEGROUP
            ],*/
            /*"NODE_CONTAINER" 	=> [
                'properties' => OBJECT_USER^OBJECT_NODE^OBJECT_LOCATION^OBJECT_CONTACT^OBJECT_CONTACTGROUP^OBJECT_TIMEPERIOD^OBJECT_HOST^OBJECT_HOSTTEMPLATE^OBJECT_HOSTGROUP^OBJECT_SERVICE^OBJECT_SERVICETEMPLATE^OBJECT_SERVICEGROUP,
                'container_type' => CT_NODE
            ],*/
            "NODE_CONTAINER"                 => [
                'properties'     => OBJECT_USER ^ OBJECT_NODE ^ OBJECT_LOCATION ^ OBJECT_CONTACT ^ OBJECT_CONTACTGROUP ^ OBJECT_TIMEPERIOD ^ OBJECT_HOST ^ OBJECT_HOSTGROUP ^ OBJECT_SERVICEGROUP ^ OBJECT_SATELLITE ^ OBJECT_HOSTTEMPLATE ^ OBJECT_SERVICETEMPLATE ^ OBJECT_SERVICETEMPLATEGROUP ^ OBJECT_INSTANTREPORT,
                'container_type' => CT_NODE,
            ],
            "CONTACTGROUP_CONTAINER"         => [
                'properties'     => OBJECT_CONTACT,
                'container_type' => CT_CONTACTGROUP,
            ],
            "HOSTGROUP_CONTAINER"            => [
                'properties'     => OBJECT_HOST,
                'container_type' => CT_HOSTGROUP,
            ],
            "SERVICEGROUP_CONTAINER"         => [
                'properties'     => OBJECT_SERVICE,
                'container_type' => CT_SERVICEGROUP,
            ],
            "SERVICETEMPLATEGROUP_CONTAINER" => [
                'properties'     => OBJECT_SERVICETEMPLATE,
                'container_type' => CT_SERVICETEMPLATEGROUP,
            ],
            //"DEBUG_CONTAINER" => [
            //	'properties' => OBJECT_SERVICEGROUP,
            //	'container_type' => CT_TENANT
            //]
        ];

        //Command type definitions
        $this->defines['command_types'] = [
            "CHECK_COMMAND"        => 1,
            "HOSTCHECK_COMMAND"    => 2,
            "NOTIFICATION_COMMAND" => 3,
            "EVENTHANDLER_COMMAND" => 4,
        ];
        $this->define($this->defines['command_types']);

        //Service type definitions
        $this->defines['service_types'] = [
            "GENERIC_SERVICE" => 1, // 2^0
            "EVK_SERVICE"     => 2, // 2^1
            "SLA_SERVICE"     => 4,
            'MK_SERVICE'      => 8,
        ];
        $this->define($this->defines['service_types']);

        //Host type definitions
        $this->defines['host_types'] = [
            "GENERIC_HOST" => 1, // 2^0
            "EVK_HOST"     => 2, // 2^1
            "SLA_HOST"     => 4,
        ];
        $this->define($this->defines['host_types']);

        //Servicetemplate type definitions
        $this->defines['hosttemplate_types'] = [
            "GENERIC_HOSTTEMPLATE" => 1, // 2^0
            "EVK_HOSTTEMPLATE"     => 2, // 2^1
            "SLA_HOSTTEMPLATE"     => 4,
        ];
        $this->define($this->defines['hosttemplate_types']);

        //Permission level definitions
        $this->defines['container_permission_levels'] = [
            'READ_RIGHT'  => 1 << 0,
            'WRITE_RIGHT' => 1 << 1,
        ];
        $this->define($this->defines['container_permission_levels']);

        $this->defines['modules'] = [
            'AUTOREPORT_MODULE'       => 1 << 0,
            'EVENTCORRELATION_MODULE' => 1 << 1,
            'DISTRIBUTE_MODULE'       => 1 << 2,
            'IDOIT_MODULE'            => 1 << 3,
            'MAP_MODULE'              => 1 << 4,
            'MK_MODULE'               => 1 << 5,
            'MASSENVERSAND_MODULE'    => 1 << 6,
            'SAP_MODULE'              => 1 << 7,
        ];
        $this->define($this->defines['modules']);
    }

    /**
     * Define global constants of different container types
     *
     * @param array $constants Array of constants that should be defined
     *
     * @return void
     */
    private function define($constants = []) {
        foreach ($constants as $constantName => $constantValue) {
            if (!defined($constantName)) {
                define($constantName, $constantValue);
            }
        }
    }


    /**
     * This function can return all matching container types as array, by a given object.
     * Example:
     * Object OBJECT_USER will return:
     * [1,2,5]
     *
     * @param constant $object is the object constant you would to check
     * @param array $exclude to exlude some containers like Hosstgroup in Host::add()
     *
     * @return array with all matching container type ids
     */
    public function containerProperties($object = null, $exclude = []) {
        if (!empty($exclude)) {
            if (!is_array($exclude)) {
                $exclude = [$exclude];
            }
        }

        //debug($this->defines['containers']['TENANT_CONTAINER']['properties']);
        //debug((1024 & 40134));
        //Example: $this->Constants->containerProperties(OBJECT_USER)

        if ($object !== null) {
            $return = [];
            foreach ($this->defines['containers'] as $container) {
                if ($object & $container['properties']) {
                    if (!in_array($container['container_type'], $exclude)) {
                        $return[] = $container['container_type'];
                    }
                }
            }

            return $return;
        }

        return [];
    }

    /**
     * Returns the value of an Object by the ModelName
     *
     * @param string $modelName of the Model to check
     *
     * @return string with the OBJECT numeric value of the constant
     */
    public function objectByModelName($modelName = '') {
        $objects = [
            'Tenant'               => OBJECT_TENANT,
            'User'                 => OBJECT_USER,
            'Container'            => OBJECT_NODE,
            'Location'             => OBJECT_LOCATION,
            //'Devicegroup' => OBJECT_DEVICEGROUP,
            'Contact'              => OBJECT_CONTACT,
            'Contactgroup'         => OBJECT_CONTACTGROUP,
            'Timeperiod'           => OBJECT_TIMEPERIOD,
            'Host'                 => OBJECT_HOST,
            'Hosttemplate'         => OBJECT_HOSTTEMPLATE,
            'Hostgroup'            => OBJECT_HOSTGROUP,
            'Service'              => OBJECT_SERVICE,
            'Servicetemplate'      => OBJECT_SERVICETEMPLATE,
            'Servicetemplategroup' => OBJECT_SERVICETEMPLATEGROUP,
            'Servicegroup'         => OBJECT_SERVICEGROUP,
            'Hostescalation'       => OBJECT_HOSTESCALATION,
            'Serviceescalation'    => OBJECT_SERVICEESCALATION,
            'Hostdependency'       => OBJECT_HOSTDEPENDENCY,
            'Servicedependency'    => OBJECT_SERVICEDEPENDENCY,
        ];

        if (isset($objects[$modelName])) {
            return $objects[$modelName];
        }

        throw new NotFoundException(__('Object not found'));
    }

    /**
     * Returns the containerttype_id of by $ModelName
     *
     * @param string $modelName of the Model to check
     *
     * @return string with the containertype_id
     */
    public function containertypeByModelName($modelName = '') {
        $objects = [
            'Servicetemplate' => CT_SERVICETEMPLATEGROUP,
            'Servicegroup'    => CT_SERVICEGROUP,
            'Hostgroup'       => CT_HOSTGROUP,
            'Contactgroup'    => CT_CONTACTGROUP,
            //'Devicegroup' => CT_DEVICEGROUP,
            'Location'        => CT_LOCATION,
            'Tenant'          => CT_TENANT,
            'Container'       => CT_GLOBAL,
        ];

        if (isset($objects[$modelName])) {
            return $objects[$modelName];
        }

        throw new NotFoundException(__('Object not found'));
    }
}
