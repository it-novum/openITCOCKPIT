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

class Hostcheck extends NagiosModuleAppModel
{
    //public $useDbConfig = 'nagios';
    public $useTable = 'hostchecks';
    public $primaryKey = 'hostcheck_id';
    public $tablePrefix = 'nagios_';
    public $belongsTo = [
        'Objects' => [
            'className'  => 'NagiosModule.Objects',
            'foreignKey' => 'host_object_id',
        ],
    ];

    public function byUuid($uuid = null)
    {
        $return = [];
        if ($uuid !== null) {
            $hostcheck = $this->find('all', [
                'conditions' => [
                    'Objects.Name1'         => $uuid,
                    'Objects.objecttype_id' => 1,
                ],
            ]);

            if (!empty($hostcheck)) {
                foreach ($hostcheck as $hostcheck) {
                    $return[$hostcheck['Objects']['name1']] = $hostcheck;
                }
            }
        }

        return $return;
    }


    public function listSettings($cakeRequest, $hostUuid)
    {
        $requestData = $cakeRequest->data;

        if (isset($cakeRequest->params['named']['Listsettings'])) {
            $requestData['Listsettings'] = $cakeRequest->params['named']['Listsettings'];
        }
        $requestParams = $cakeRequest->params;

        $service_state_types = [
            'recovery'    => 0,
            'down'        => 1,
            'unreachable' => 2,
        ];

        $return = [
            'conditions'   => [
                'Objects.name1'         => $hostUuid,
                'Objects.objecttype_id' => 1,
            ],
            'paginator'    => [
                'limit' => 30,
                'order' => ['Hostcheck.start_time' => 'DESC'],
            ],
            'Listsettings' => [
                'limit' => 30,
            ],
        ];

        // Recovery, Down, Unreachable state
        if (isset($requestData['Listsettings']['state_types'])) {
            $return['conditions']['Hostcheck.state'] = [];
            foreach ($requestData['Listsettings']['state_types'] as $state_type => $value) {
                if (isset($service_state_types[$state_type]) && $value == 1) {
                    $return['conditions']['Hostcheck.state'][] = $service_state_types[$state_type];
                    $return['Listsettings']['state_types'][$state_type] = 1;
                }
            }
        } else {
            foreach ($service_state_types as $state_type => $state) {
                $return['Listsettings']['state_types'][$state_type] = 1;
            }
            if (isset($return['conditions']['Hostcheck.state'])) {
                unset($return['conditions']['Hostcheck.state']);
            }
        }

        if (isset($requestParams['named']['sort']) && isset($requestParams['named']['direction'])) {
            $return['paginator']['order'] = [$requestParams['named']['sort'] => $requestParams['named']['direction']];
        }


        if (isset($requestData['Listsettings']['limit']) && is_numeric($requestData['Listsettings']['limit'])) {
            $return['paginator']['limit'] = $requestData['Listsettings']['limit'];
            $return['Listsettings']['limit'] = $return['paginator']['limit'];
        }

        if (isset($requestData['Listsettings']['from'])) {
            $time = strtotime($requestData['Listsettings']['from']);
            if ($time == false || !is_numeric($time)) {
                $time = strtotime('3 days ago');
            }

            $return['conditions']['Hostcheck.start_time >'] = date('Y-m-d H:i:s', $time);
            $return['Listsettings']['from'] = date('d.m.Y H:i', $time);
        } else {
            $return['conditions']['Hostcheck.start_time >'] = date('Y-m-d H:i:s', strtotime('3 days ago'));
            $return['Listsettings']['from'] = date('d.m.Y H:i', strtotime('3 days ago'));
        }

        if (isset($requestData['Listsettings']['to'])) {
            $time = strtotime($requestData['Listsettings']['to']);
            if ($time == false || !is_numeric($time)) {
                $time = time() + (60 * 5); //Add 5 minutes to avoid missing entires in result
            }

            $return['conditions']['Hostcheck.start_time <'] = date('Y-m-d H:i:s', $time);
            $return['Listsettings']['to'] = date('d.m.Y H:i', $time);
        } else {
            $return['conditions']['Hostcheck.start_time <'] = date('Y-m-d H:i:s', time() + (60 * 5));
            $return['Listsettings']['to'] = date('d.m.Y H:i', time() + (60 * 5));
        }

        if (isset($return['conditions']['Hostcheck.state'])) {
            if (
                in_array(0, $return['conditions']['Hostcheck.state']) &&
                in_array(1, $return['conditions']['Hostcheck.state']) &&
                in_array(2, $return['conditions']['Hostcheck.state'])
            ) {
                //The user want every state, so lets remove this for faster SQL
                unset($return['conditions']['Hostcheck.state']);
            }
        }

        return $return;
    }

}