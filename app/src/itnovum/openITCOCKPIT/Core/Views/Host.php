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

namespace itnovum\openITCOCKPIT\Core\Views;


class Host {

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $uuid;

    /**
     * @var string
     */
    private $hostname;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int|bool
     */
    private $active_checks_enabled;

    /**
     * @var int
     */
    private $satelliteId;

    /**
     * @var int
     */
    private $containerId;

    /**
     * @var array
     */
    private $containerIds;

    /**
     * @var string
     */
    private $tags;


    public function __construct($host){
        if (isset($host['Host']['id'])) {
            $this->id = $host['Host']['id'];
        }

        if (isset($host['Host']['uuid'])) {
            $this->uuid = $host['Host']['uuid'];
        }

        if (isset($host['Host']['name'])) {
            $this->hostname = $host['Host']['name'];
        }

        if (isset($host['Host']['address'])) {
            $this->address = $host['Host']['address'];
        }

        if (isset($host['Host']['description'])) {
            $this->description = $host['Host']['description'];
        }

        if (isset($host['Host']['active_checks_enabled'])) {
            $this->active_checks_enabled = (bool)$host['Host']['active_checks_enabled'];
        }

        if (isset($host['Host']['satellite_id'])) {
            $this->satelliteId = (int)$host['Host']['satellite_id'];
        }

        if (isset($host['Host']['container_id'])) {
            $this->containerId = (int)$host['Host']['container_id'];
        }

        if (isset($host['Container'])) {
            //MySQL
            $this->containerIds = \Hash::extract($host, 'Container.{n}.HostsToContainer.container_id');
        }

        if (isset($host['Host']['container_ids'])) {
            //CrateDB
            $this->containerIds = $host['Host']['container_ids'];
        }

        if (isset($host['Host']['tags'])) {
            $this->tags = $host['Host']['tags'];
        }

    }

    /**
     * @return string
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUuid(){
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getHostname(){
        return $this->hostname;
    }

    /**
     * @return string
     */
    public function getAddress(){
        return $this->address;
    }

    /**
     * @return string
     */
    public function getDescription(){
        return $this->description;
    }

    /**
     * @return bool|int
     */
    public function isActiveChecksEnabled(){
        return $this->active_checks_enabled;
    }

    /**
     * @return int
     */
    public function getSatelliteId(){
        return $this->satelliteId;
    }

    /**
     * @return int
     */
    public function getContainerId(){
        return $this->containerId;
    }

    /**
     * @return array
     */
    public function getContainerIds(){
        return $this->containerIds;
    }

    /**
     * @return string
     */
    public function getTags(){
        return $this->tags;
    }



}
