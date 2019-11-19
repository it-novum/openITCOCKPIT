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


use App\Model\Table\ContainersTable;
use Cake\ORM\TableRegistry;

App::uses('Timerange', 'Model');

/**
 * Class Timeperiod
 * @deprecated
 */

class Timeperiod extends AppModel {

    public $hasMany = [
        'Timerange' => [
            'className'  => 'Timerange',
            'order'      => 'day ASC',
            'joinTable'  => 'timeperiod_timeranges',
            'foreignKey' => 'timeperiod_id',
            'dependent'  => true,
        ],
    ];

    /*
    public $belongsTo = [
        'Calendar'
    ];
    */

    var $validate = [
        'container_id'    => [
            'multiple' => [
                'rule'    => ['multiple', ['min' => 1]],
                'message' => 'Please select at least 1 container you attend'
            ],
        ],
        'name'            => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'isUnique' => [
                'rule'    => 'isUnique',
                'message' => 'This timeperiod name has already been taken.',
            ],
        ],
        'check_timerange' => [
            'rule'    => ['checkTimerangeOvelapping'],
            'message' => 'Do not enter overlapping timeframes',
        ],
    ];

    /**
     * Timeperiod constructor.
     * @param bool $id
     * @param null $table
     * @param null $ds
     * @deprecated
     */
    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
    }

    /**
     * @return bool
     * @deprecated
     */
    public function checkTimerangeOvelapping() {
        $error_arr = [];
        if (isset($this->data['Timerange']) && sizeof($this->data['Timerange']) > 0) {
            foreach ($this->data['Timerange'] as $key => $row) {
                $day[$key] = $row['day'];
                $start[$key] = $row['start'];
            }
            array_multisort($day, SORT_ASC, $start, SORT_ASC, $this->data['Timerange']);
            $check_timerange_array = [];
            foreach ($this->data['Timerange'] as $key => $timerange) {
                $check_timerange_array[$timerange['day']][] = ['start' => $timerange['start'], 'end' => $timerange['end']];
            }
            $error_arr = [];
            foreach ($check_timerange_array as $day => $timerange_data) {
                if (sizeof($timerange_data) > 1) {
                    $intern_counter = 0;
                    $tmp_start = $check_timerange_array[$day][$intern_counter]['start'];
                    $tmp_end = $check_timerange_array[$day][$intern_counter]['end'];
                    for ($input_key = 0; $input_key < sizeof($timerange_data); $input_key++) {
                        $intern_counter++;
                        if (isset($timerange_data[$intern_counter])) {
                            if ($tmp_start <= $timerange_data[$intern_counter]['start'] &&
                                $tmp_end > $timerange_data[$intern_counter]['start']
                            ) {
                                if ($tmp_end <= $timerange_data[$intern_counter]['end']) {
                                    $tmp_end = $timerange_data[$intern_counter]['end'];
                                } else {
                                    $input_key--;
                                }
                                $error_arr[$day][] = $intern_counter;

                                //	$this->invalidate('Timeperiod.'.$day.'.'.$intern_counter, 'state-error');
                                $this->invalidate('Timerange.' . $day . '.' . $intern_counter . '.start', 'state-error');

                            } else {
                                $tmp_start = $timerange_data[$intern_counter]['start'];
                                $tmp_end = $timerange_data[$intern_counter]['end'];
                                $input_key++;

                            }
                        }
                    }
                }
            }
        }
        if (sizeof($error_arr) > 0) {
            return false;
        }

        return true;
    }

    /**
     * @param array $container_ids
     * @param string $type
     * @return array|null
     * @deprecated
     */
    public function timeperiodsByContainerId($container_ids = [], $type = 'all') {
        if (!is_array($container_ids)) {
            $container_ids = [$container_ids];
        }

        $container_ids = array_unique($container_ids);


        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        $tenantContainerIds = [];
        foreach ($container_ids as $container_id) {
            if ($container_id != ROOT_CONTAINER) {
                // Get contaier id of the tenant container
                // $container_id is may be a location, devicegroup or whatever, so we need to container id of the tenant container to load contactgroups and contacts
                $path = $ContainersTable->getPathByIdAndCacheResult($container_id, 'TimeperiodTimeperiodsByContainerId');
                if (isset($path[1]['id'])) {
                    $tenantContainerIds[] = $path[1]['id'];
                }
            } else {
                $tenantContainerIds[] = ROOT_CONTAINER;
            }
        }
        $tenantContainerIds = array_unique($tenantContainerIds);

        return $this->find($type, [
            'conditions' => [
                'Timeperiod.container_id' => $tenantContainerIds,
            ],
            'order'      => [
                'Timeperiod.name' => 'ASC',
            ],
        ]);
    }
}
