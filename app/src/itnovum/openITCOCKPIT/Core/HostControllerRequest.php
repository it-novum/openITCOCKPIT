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


class HostControllerRequest {

    /**
     * @var \CakeRequest
     */
    private $request;

    public function __construct(\CakeRequest $request){
        $this->request = $request;
    }

    /**
     * @return bool
     */
    public function isRequestFromBrowser(){
        return isset($this->request->params['named']['BrowserContainerId']);
    }

    /**
     * @return array
     */
    public function getBrowserContainerIdsByRequest(){
        $containerIds = $this->request->params['named']['BrowserContainerId'];
        if (!is_array($containerIds)) {
            $containerIds = [$containerIds];
        }
        return $containerIds;
    }

    /**
     * @param array $defaultOrder
     * @return array
     */
    public function getOrder($defaultOrder = []){
        if (isset($this->request['named']['sort']) && isset($this->request['named']['direction'])) {
            return [
                $this->request['named']['sort'] => $this->request['named']['direction']
            ];
        }

        return $defaultOrder;

    }

}