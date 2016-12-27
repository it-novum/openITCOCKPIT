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

class Hoststatus extends NagiosModuleAppModel
{
    //public $useDbConfig = 'nagios';
    public $useTable = 'hoststatus';
    public $primaryKey = 'hoststatus_id';
    public $tablePrefix = 'nagios_';
    public $belongsTo = [
        'Objects' => [
            'className'  => 'NagiosModule.Objects',
            'foreignKey' => 'host_object_id',
        ],
    ];

    /**
     * Return the host status as array for given uuid as stirng or array
     *
     * @param          string $uuid    UUID or array $uuid you want to get host status for
     * @param    array        $options for the find request (see cakephp's find for all options)
     *
     * @return array
     */
    public function byUuid($uuid = null, $options = [])
    {
        $return = [];

        $_options = [
            'conditions' => [
                'Objects.name1'         => $uuid,
                'Objects.objecttype_id' => 1,
            ],
        ];

        $options = Hash::merge($_options, $options);

        if ($uuid !== null) {
            $hoststatus = $this->find('all', $options);

            if (!empty($hoststatus)) {
                foreach ($hoststatus as $hs) {
                    $return[$hs['Objects']['name1']] = $hs;
                }
            }
        }

        return $return;
    }
}
