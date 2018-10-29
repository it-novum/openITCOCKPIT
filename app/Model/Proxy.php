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

App::import('Model', 'Changelog');

class Proxy extends AppModel {

    public $validate = [
        'port' => [
            'ruleName'  => [
                'rule'     => 'numeric',
                'message'  => 'The Port must be a numeric value',
                'required' => true,
            ],
            'ruleName2' => [
                'rule'    => ['maxLength', 5],
                'message' => 'Port must be no larger than 5 numbers long.',
            ],
        ],
    ];

    function getSettings() {
        $proxy = $this->find('first');

        $settings = ['ipaddress' => '', 'port' => 0, 'enabled' => false];
        if (isset($proxy['Proxy'])) {
            $settings = Hash::merge($settings, $proxy['Proxy']);
        }

        return $settings;
    }

    /*
    public function afterFind($results = array(), $primary = false){
        $this->afterFind = $results;
    }

    public function afterSave($created, $options=array()){
        $key = '';
        $changed_data = array();
        $label = array('port' => __('proxy.port'), 'ipaddress' => __('proxy.address'), 'enabled' => __('proxy.enabled'));
        foreach($this->afterFind as $source_data){
            $key = key($source_data);
            $diff = Set::diff($this->data[$key], $source_data[$key]);
            if(!empty($diff)){
                foreach($diff as $field => $value){
                    $changed_data[$field]['before'] = $source_data[$key][$field];
                    $changed_data[$field]['after'] = $this->data[$key][$field];
                    $changed_data[$field]['label'] = $label[$field];
                }
            }
        }

        //Sofern Änderungen gefunden wurden, diese in das Changelog schreiben
        if(!empty($changed_data)){
            $this->Changelog = new Changelog();
            //debug($this->controller);
            $this->Changelog->save(array(
                'controller' => 'Proxy',
                'action' => 'edit',
                'data' => json_encode(array('object_name' => '', 'data' => $changed_data)),
            ));
        }
    }*/
}