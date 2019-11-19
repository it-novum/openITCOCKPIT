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
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;

/**
 * Class Calendar
 * @deprecated
 */
class Calendar extends AppModel {

    public $hasMany = [
        'CalendarHoliday' => [
            'className' => 'CalendarHoliday',
            'dependent' => true,
        ],
    ];

    public $belongsTo = ['Container'];
    public $validate = [
        'name'         => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
        ],
        'container_id' => [
            'notBlank' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank.',
                'required' => true,
            ],
            'numeric'  => [
                'rule'    => 'numeric',
                'message' => 'This field needs a numeric value.',
            ],
            'notZero'  => [
                'rule'    => ['comparison', '>', 0],
                'message' => 'The value should be greate than zero.',
            ],
        ],
    ];

    /**
     * @deprecated
     * @param $request_data
     * @return array
     */
    public function prepareForSave($request_data) {
        $holidays = [];
        foreach ($request_data as $date => $holidayValues) {
            $holidays[] = [
                'date'            => $date,
                'name'            => $holidayValues['name'],
                'default_holiday' => $holidayValues['default_holiday'],
            ];
        }

        return $holidays;
    }

    /**
     * @deprecated
     * @param array $container_ids
     * @param string $type
     * @return array|null
     */
    public function calendarsByContainerId($container_ids = [], $type = 'all') {
        if (!is_array($container_ids)) {
            $container_ids = [$container_ids];
        }

        $container_ids = array_unique($container_ids);

        $tenantContainerIds = [];

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        foreach ($container_ids as $container_id) {
            if ($container_id != ROOT_CONTAINER) {

                // Get contaier id of the tenant container
                // $container_id is may be a location, devicegroup or whatever, so we need to container id of the tenant container to load contactgroups and contacts
                try {
                    $path = $ContainersTable->find('path', ['for' => $container_id])
                        ->disableHydration()
                        ->all()
                        ->toArray();
                }catch(RecordNotFoundException $e){
                    continue;
                }

                $tenantContainerIds[] = $path[1]['id']; //Array key 1 is always the tenant.
            } else {
                $tenantContainerIds[] = ROOT_CONTAINER;
            }
        }
        $tenantContainerIds = array_unique($tenantContainerIds);

        return $this->find($type, [
            'conditions' => [
                'Calendar.container_id' => $tenantContainerIds,
            ],
            'order'      => [
                'Calendar.name' => 'ASC',
            ],
        ]);
    }
}
