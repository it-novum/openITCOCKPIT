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

namespace itnovum\openITCOCKPIT\Core;


use itnovum\openITCOCKPIT\Filter\ServiceFilter;

class ServiceControllerRequest {

    /**
     * @var \CakeRequest
     */
    private $request;

    /**
     * @var ServiceFilter
     */
    private $ServiceFilter;

    public function __construct(\CakeRequest $request, ServiceFilter $ServiceFilter){
        $this->request = $request;
        $this->ServiceFilter = $ServiceFilter;
    }

    /**
     * @return bool
     */
    public function isRequestFromBrowser(){
        if(isset($this->request->query['BrowserContainerId'])){
            return true;
        }
        return isset($this->request->params['named']['BrowserContainerId']);
    }

    /**
     * @return array
     */
    public function getBrowserContainerIdsByRequest(){
        if(isset($this->request->params['named']['BrowserContainerId'])){
            $containerIds = $this->request->params['named']['BrowserContainerId'];
        }

        if(isset($this->request->query['BrowserContainerId'])){
            $containerIds = $this->request->query['BrowserContainerId'];
        }

        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }
        return $containerIds;
    }

    /**
     * @return bool
     */
    public function hasLimit(){
        if(isset($this->request->query['limit'])){
            return true;
        }
        return false;
    }

    /**
     * @param string $sort
     * @param string $direction
     * @return array
     */
    public function getOrder($sort = '', $direction = ''){
        return $this->ServiceFilter->getOrderForPaginator($sort, $direction);
    }

}