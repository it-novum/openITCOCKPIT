<?php
// Copyright (C) <2015>  <it-novum GmbH>
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

namespace itnovum\openITCOCKPIT\HostgroupsController;

use itnovum\openITCOCKPIT\HostgroupsController\Exceptions\RecordExistsException;

class CumulatedServicestatusCollection
{

    /**
     * @var array
     */
    private $collection = [];

    /**
     * CumulatedServicestatusCollection constructor.
     * @param array $comulatedServicestatusArray
     */
    public function __construct($comulatedServicestatusArray)
    {
        foreach($comulatedServicestatusArray as $servicestatus){
            $this->collection[$servicestatus['Host']['id']] = $servicestatus[0]['cumulated'];
        }
    }

    /**
     * @param int $hostId
     * @retrun bool
     */
    public function existsByHostId($hostId){
        return isset($this->collection[$hostId]);
    }

    /**
     * @param $hostId
     * @return int Servicestatus cumulated (0, 1, 2, 3)
     * @throws RecordExistsException
     */
    public function getByHostId($hostId){
        if($this->existsByHostId($hostId)){
            return $this->collection[$hostId];
        }
        throw new RecordExistsException('Record not found in collection!');
    }

    /**
     * @param $hostId
     * @return int|-1
     */
    public function getByHostIdEvenIfNotExists($hostId){
        if($this->existsByHostId($hostId)){
            return $this->collection[$hostId];
        }

        return -1;
    }

    /**
     * @return array
     */
    public function getCollectionAsArray(){
        return $this->collection;
    }
}
