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

class Servicestatus extends NagiosModuleAppModel
{
    //public $useDbConfig = 'nagios';
    public $useTable = 'servicestatus';
    public $primaryKey = 'servicestatus_id';
    public $tablePrefix = 'nagios_';
    public $belongsTo = [
        'Objects' => [
            'className'  => 'NagiosModule.Objects',
            'foreignKey' => 'service_object_id',
        ],
    ];

    /**
     * Return the service status as array for given uuid as stirng or array
     *
     * @param          string   or array $uuid you want to get service status for
     * @param    array $options for the find request (see cakephp's find for all options)
     *
     * @return    array
     * @author     Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since      3.0
     * @version    3.0.1
     */
    public function byUuid($uuid = null, $options = [])
    {
        $return = [];
        if ($uuid !== null) {

            $_options = [
                'conditions' => [
                    'Objects.name2'         => $uuid,
                    'Objects.objecttype_id' => 2,
                ],
            ];

            $options = Hash::merge($_options, $options);
            $servicestatus = $this->find('all', $options);

            if (!empty($servicestatus)) {
                foreach ($servicestatus as $nagios_servicestatus) {
                    $return[$nagios_servicestatus['Objects']['name2']] = $nagios_servicestatus;
                }
            }
        }

        debug($return);

        return $return;
    }

    /**
     * Return the service status as string for given uuid
     *
     * @param    string $uuid    you want to get service status for
     * @param    array  $options for the find request (see cakephp's find for all options)
     *
     * @return    void
     * @author     Irina Bering <irina.bering@it-novum.com>
     * @since      3.0.6
     * @version    3.0.6
     */
    public function currentStateByUuid($uuid = null)
    {
        $return = null;
        if ($uuid !== null) {

            $_options = [
                'conditions' => [
                    'Objects.name2'         => $uuid,
                    'Objects.objecttype_id' => 2,
                ],
            ];
            $servicestatus = $this->find('all', $_options);

            if (!empty($servicestatus)) {
                foreach ($servicestatus as $nagios_servicestatus) {
                    return $nagios_servicestatus['Servicestatus']['current_state'];
                }
            }
        }

        return $return;
    }
}
