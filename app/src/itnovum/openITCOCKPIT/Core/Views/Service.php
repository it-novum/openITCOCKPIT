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


class Service {

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
    private $servicename;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int|bool
     */
    private $active_checks_enabled;

    /**
     * @var string
     */
    private $tags;


    /**
     * Service constructor.
     * @param $service
     * @param null $servicename
     */
    public function __construct($service, $servicename = null){
        if (isset($service['Service']['id'])) {
            $this->id = $service['Service']['id'];
        }

        if (isset($service['Service']['uuid'])) {
            $this->uuid = $service['Service']['uuid'];
        }

        if (isset($service['Service']['name'])) {
            $this->servicename = $service['Service']['name'];
        }

        if($servicename !== null){
            $this->servicename = $servicename;
        }

        if (isset($service['Service']['description'])) {
            $this->description = $service['Service']['description'];
        }

        if (isset($service['Service']['active_checks_enabled'])) {
            $this->active_checks_enabled = (bool)$service['Service']['active_checks_enabled'];
        }

        if (isset($service['Service']['tags'])) {
            $this->tags = $service['Service']['tags'];
        }

    }

    public static function fromServiceNotification($serviceNotification){
        $servicename = null;
        if (isset($serviceNotification['NotificationService']['servicename'])) {
            $servicename = $serviceNotification['NotificationService']['servicename'];
        }

        return new self($serviceNotification, $servicename);
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
    public function getServicename(){
        return $this->servicename;
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
     * @return string
     */
    public function getTags(){
        return $this->tags;
    }



}
