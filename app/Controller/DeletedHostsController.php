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

use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Filter\HostFilter;

class DeletedHostsController extends AppController {
    public $layout = 'blank';

    public $uses = ['DeletedHost'];


    public function index() {
        $this->Paginator->settings['order'] = [
            'created' => 'DESC',
        ];

        if ($this->isApiRequest() && !$this->isAngularJsRequest()) {
            $deletedHosts = $this->DeletedHost->find('all');
            if (isset($this->Paginator->settings['limit'])) {
                unset($this->Paginator->settings['limit']);
            }
            $deletedHosts = $this->DeletedHost->find('all');
            $this->set(compact(['deletedHosts']));
            $this->set('_serialize', ['deletedHosts', 'paging']);
            return;
        } else {
            $HostFilter = new HostFilter($this->request);
            $this->Paginator->settings['conditions'] = $HostFilter->deletedFilter();
            $this->Paginator->settings['order'] = $HostFilter->getOrderForPaginator('DeletedHost.created', 'desc');
            $this->Paginator->settings['page'] = $HostFilter->getPage();
            $hosts = $this->Paginator->paginate();
        }

        $UserTime = new UserTime($this->Auth->user('timezone'), $this->Auth->user('dateformat'));

        $deletedHosts = [];
        foreach ($hosts as $host) {
            $DeletedHost = new \itnovum\openITCOCKPIT\Core\Views\DeletedHost($host, $UserTime);
            $deletedHosts[] = [
                'DeletedHost' => $DeletedHost->toArray()
            ];
        }


        $this->set(compact(['deletedHosts']));
        $this->set('_serialize', ['deletedHosts', 'paging']);

    }
}