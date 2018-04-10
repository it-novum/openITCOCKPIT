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

App::uses('Model', 'Model');


class AppModel extends Model {
    const ITN_AJAX_LIMIT = 50;

    public $actsAs = ['Containable', 'DynamicAssociations'];
    protected $lastInsertedIds = [];
    protected $lastInsertedData = [];

    public function __construct($id = false, $table = null, $ds = null, $useDynamicAssociations = true) {
        parent::__construct($id, $table, $ds);

        if($useDynamicAssociations) {
            if (is_object($this->Behaviors->DynamicAssociations)) {

                $dynamicAssociations = $this->Behaviors->DynamicAssociations->dynamicAssociationsIgnoreCallback($this->alias);

                //This is not working, but i dont know why!!
                //$this->bindModel() not works on delete, so we use the --force method
                //if (!empty($dynamicAssociations) && is_array($dynamicAssociations)) {
                //    $this->bindModel($dynamicAssociations, false);
                //}

                //This is the --force way, but thi works :)
                foreach ($dynamicAssociations as $associationsName => $associationsConf) {
                    $this->{$associationsName} = Hash::merge($this->{$associationsName}, $associationsConf);
                }
            }
        }
    }



    /**
     * Validation: Checks if the field is unique in the model
     *
     * @param array $data
     * @param string $field
     * @param bool $statusScope
     *
     * @return bool
     */
    public function isUniqueInModel($data, $field, $statusScope) {
        $valid = false;
        if ($this->hasField($field)) {
            $valid = $this->isUnique($data);

            // if not unique but scope is status
            if ($statusScope && !$valid) {
                $active = $this->find('first', [
                    'conditions' => [
                        $this->alias . '.' . $field => $data[$field],
                        $this->alias . '.status' => Status::ACTIVE,
                    ],
                ]);
                if (empty($active)) {
                    $valid = true;
                }
            }
        }

        return $valid;
    }


    /**
     * Retrieves a record by its id.
     *
     * @param int $id
     * @param array|false $options Additional find options, will set 'contain' => false if false is passed
     *
     * @return array|false
     */
    public function get($id, $options = []) {
        if ($options === false) {
            $options = [
                'contain' => false,
            ];
        }
        $options = Set::merge([
            'conditions' => [
                $this->alias . '.' . $this->primaryKey => $id,
            ],
        ], $options);

        return $this->find('first', $options);
    }


    /**
     * Replacement for Model::saveField() which doesn't change the model state
     *
     * @param int $id
     * @param string $field
     * @param string $value
     *
     * @return bool
     */
    public function updateField($id, $field, $value) {
        $this->updateAll([
            $this->alias . '.' . $field => $this->getDataSource()->value($value),
        ], [
            $this->alias . '.' . $this->primaryKey => $id,
        ]);
    }

    public function isCompositeUnique($data, $options = []) {
        debug($data);
        debug($this->find('all'));
    }


    /**
     * Create an array for Router::redirect() if you want to redirect to a different page
     *
     * @param array $params _controller and _action
     * @param array $default controller and action
     *
     * @return array
     */
    public function redirect($params = [], $default = []) {
        $redirect = [];

        if (isset($params['named']['_controller'])) {
            $redirect['controller'] = $params['named']['_controller'];
        }

        if (isset($params['named']['_action'])) {
            $redirect['action'] = $params['named']['_action'];
        }

        if (isset($params['named']['_plugin'])) {
            $redirect['plugin'] = $params['named']['_plugin'];
        }

        if (isset($params['named']['_id'])) {
            $redirect[] = $params['named']['_id'];
        }


        if (!empty($default)) {
            $redirect = Hash::merge($default, $redirect);
        }

        return $redirect;
    }

    /**
     * Create an array for Router::redirect() if you want to redirect to a different page
     *
     * @param array $params _controller and _action
     * @param array $override params
     *
     * @return array
     */
    public function flashRedirect($params = [], $override = []) {
        $redirect = [
            'controller' => $params['controller'],
            'action' => $params['action'],
            'plugin' => $params['plugin'],
        ];

        if (isset($params['named']['_controller'])) {
            $redirect['_controller'] = $params['named']['_controller'];
        }

        if (isset($params['named']['_action'])) {
            $redirect['_action'] = $params['named']['_action'];
        }

        if (isset($params['named']['_plugin'])) {
            $redirect['_plugin'] = $params['named']['_plugin'];
        }


        if (!empty($override)) {
            $redirect = Hash::merge($redirect, $override);
        }

        return $redirect;
    }


    public function afterSave($created, $options = []) {
        if ($created) {
            $this->lastInsertedIds[] = $this->getLastInsertID();
            $this->lastInsertedData[] = $this->data;
        }

        return true;
    }

    public function getLastInsertedIds() {
        return $this->lastInsertedIds;
    }

    /**
     * Caution: This function will probably only work correctly when used via the REST API. If called multiple times
     * internally, it might return more results as expected.
     * @return array
     */
    public function getLastInsertedDataWithId() {
        return $this->lastInsertedData;
    }

    public function makeItJavaScriptAble($findListResult = []) {
        $return = [];
        foreach ($findListResult as $key => $value) {
            $return[] = [
                'key' => $key,
                'value' => $value,
            ];
        }

        return $return;
    }

    /**
     * Returns a array with an empty option for jquery chosen placeholder
     *
     * @param array $select with the options
     *
     * @return array $select and key 0 is empty for data-placeholder
     */
    public function chosenPlaceholder($select = []) {
        if (!array_key_exists(0, $select)) {
            //Yes right, php can use the + operator on arrays
            if (is_array($select)) {
                $select += [0 => ''];
            }
            asort($select);
        }

        return $select;
    }



}
