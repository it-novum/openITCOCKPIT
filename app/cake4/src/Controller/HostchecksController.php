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

declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\HostsTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Request\HostchecksControllerRequest;
use itnovum\openITCOCKPIT\Core\HostcheckConditions;
use itnovum\openITCOCKPIT\Database\PaginateOMat;

/**
 * Class HostchecksController
 * @property AppPaginatorComponent $Paginator
 */
class HostchecksController extends AppController {


    public $layout = 'blank';

    public function index($id = null) {
        if (!$this->isAngularJsRequest()) {
            //Only ship html template
            return;
        }

        session_write_close();

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        if (!$HostsTable->existsById($id)) {
            throw new NotFoundException(__('Invalid host'));
        }

        /** @var \App\Model\Entity\Host $host */
        $host = $HostsTable->getHostByIdForPermissionCheck($id);
        if (!$this->allowedByContainerId($host->getContainerIds(), false)) {
            $this->render403();
            return;
        }

        $HostchecksControllerRequest = new HostchecksControllerRequest($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $HostchecksControllerRequest->getPage());

        //Process conditions
        $Conditions = new HostcheckConditions();
        $Conditions->setFrom($HostchecksControllerRequest->getFrom());
        $Conditions->setTo($HostchecksControllerRequest->getTo());
        $Conditions->setStates($HostchecksControllerRequest->getHostStates());
        $Conditions->setStateTypes($HostchecksControllerRequest->getHostStateTypes());
        $Conditions->setOrder($HostchecksControllerRequest->getOrderForPaginator('Hostchecks.start_time', 'desc'));
        $Conditions->setHostUuid($host->get('uuid'));
        $Conditions->setConditions($HostchecksControllerRequest->getIndexFilters());

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
        $UserTime = $User->getUserTime();

        $HostchecksTable = $this->DbBackend->getHostchecksTable();

        $all_hostchecks = [];
        foreach ($HostchecksTable->getHostchecks($Conditions, $PaginateOMat) as $hostcheck) {
            /** @var \Statusengine2Module\Model\Entity\Hostcheck $hostcheck */
            $Hostcheck = new \itnovum\openITCOCKPIT\Core\Views\Hostcheck($hostcheck->toArray(), $UserTime);

            $all_hostchecks[] = [
                'Hostcheck' => $Hostcheck->toArray()
            ];
        }

        $this->set('all_hostchecks', $all_hostchecks);
        $toJson = ['all_hostchecks', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_hostchecks', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }
}
