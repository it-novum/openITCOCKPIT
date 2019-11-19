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

/**
 * Class Location
 * @deprecated
 */
class Location extends AppModel {
    var $belongsTo = [
        'Container' => [
            'className'  => 'Container',
            'foreignKey' => 'container_id',
            'conditions' => ['containertype_id' => CT_LOCATION],
        ],
    ];

    var $validate = [
        'latitude'  => [
            'rule'       => 'numeric',
            'message'    => 'This value needs to be numeric',
            'allowEmpty' => true,
        ],
        'longitude' => [
            'rule'       => 'numeric',
            'message'    => 'This value needs to be numeric',
            'allowEmpty' => true,
        ],
    ];

    /**
     * @param $location
     * @param $userId
     * @return bool
     * @deprecated
     */
    public function __delete($location, $userId) {
        if (is_numeric($location)) {
            $location = $this->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Location.id' => $location
                ],
                'contain'    => [
                    'Container'
                ]
            ]);
        } else {
            $locationId = $location['Location']['id'];
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');
        $Host = ClassRegistry::init('Host');
        //CakePHP will delete the device groups for us but we need to cleanup the hosts
        $nodes = $ContainersTable->getAllContainerByParentId($location['Container']['id']);

        $hostIds = [];
        foreach ($nodes as $node) {
            $hosts = $Host->find('all', [
                'recursive'  => -1,
                'fields'     => [
                    'Host.id',
                    'Host.uuid'
                ],
                'conditions' => [
                    'Host.container_id' => $node['id'],
                ],
            ]);
            $hostIds[] = Hash::extract($hosts, '{n}.Host.id');
        }

        if ($this->__allowDelete($hostIds)) {
            foreach ($hostIds as $currentHostIds) {
                foreach ($hosts as $host) {
                    $Host->__delete($host, $userId);
                }
            }
            if ($ContainersTable->deleteContainerById($location['Container']['id'])) {
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * @param $hostIds
     * @return bool
     * @deprecated
     */
    public function __allowDelete($hostIds) {
        //check if the hosts are used somwhere
        if (CakePlugin::loaded('EventcorrelationModule')) {
            $notInUse = true;
            $result = [];
            $this->Eventcorrelation = ClassRegistry::init('Eventcorrelation');
            $Service = ClassRegistry::init('Service');
            foreach ($hostIds as $currentHostIds) {
                foreach ($currentHostIds as $hostId) {
                    $serviceIds = Hash::extract($Service->find('all', [
                        'recursive'  => -1,
                        'conditions' => [
                            'host_id' => $hostId,
                        ],
                        'fields'     => [
                            'Service.id',
                        ],
                    ]), '{n}.Service.id');
                    $evcCount = $this->Eventcorrelation->find('count', [
                        'conditions' => [
                            'OR' => [
                                'host_id'    => $hostId,
                                'service_id' => $serviceIds,
                            ],

                        ],
                    ]);
                    $result[] = $evcCount;
                }
            }

            foreach ($result as $value) {
                if ($value > 0) {
                    $notInUse = false;
                }
            }

            return $notInUse;
        }

        return true;
    }
}
