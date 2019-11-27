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
use App\Model\Table\ServicesTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\AngularJS\Request\ServicechecksControllerRequest;
use itnovum\openITCOCKPIT\Core\DbBackend;
use itnovum\openITCOCKPIT\Core\ServicechecksConditions;
use itnovum\openITCOCKPIT\Database\PaginateOMat;

/**
 * Class ServicechecksController
 * @property AppPaginatorComponent $Paginator
 * @property AppAuthComponent $Auth
 * @property DbBackend $DbBackend
 */
class ServicechecksController extends AppController {

    public $layout = 'blank';

    /**
     * @param null $id
     * @throws \App\Lib\Exceptions\MissingDbBackendException
     */
    public function index($id = null) {
        if (!$this->isAngularJsRequest()) {
            //Only ship html template
            return;
        }

        session_write_close();

        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        if (!$ServicesTable->existsById($id)) {
            throw new NotFoundException(__('Invalid service'));
        }

        /** @var \App\Model\Entity\Service $service */
        $service = $ServicesTable->getServiceByIdForPermissionsCheck($id);
        if (!$this->allowedByContainerId($service->getContainerIds(), false)) {
            $this->render403();
            return;
        }

        $ServicechecksControllerRequest = new ServicechecksControllerRequest($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $ServicechecksControllerRequest->getPage());

        //Process conditions
        $Conditions = new ServicechecksConditions();
        $Conditions->setHostUuid($service->host->uuid);
        $Conditions->setFrom($ServicechecksControllerRequest->getFrom());
        $Conditions->setTo($ServicechecksControllerRequest->getTo());
        $Conditions->setOrder($ServicechecksControllerRequest->getOrderForPaginator('Servicecheck.start_time', 'desc'));
        $Conditions->setStates($ServicechecksControllerRequest->getServiceStates());
        $Conditions->setStateTypes($ServicechecksControllerRequest->getServiceStateTypes());
        $Conditions->setServiceUuid($service->get('uuid'));
        $Conditions->setConditions($ServicechecksControllerRequest->getIndexFilters());


        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
        $UserTime = $User->getUserTime();

        $ServicechecksTable = $this->DbBackend->getServicechecksTable();

        $all_servicechecks = [];
        foreach ($ServicechecksTable->getServicechecks($Conditions, $PaginateOMat) as $servicecheck) {
            /** @var \Statusengine2Module\Model\Entity\Servicecheck $servicecheck */
            $Servicecheck = new \itnovum\openITCOCKPIT\Core\Views\Servicecheck($servicecheck->toArray(), $UserTime);

            $all_servicechecks[] = [
                'Servicecheck' => $Servicecheck->toArray()
            ];
        }

        $this->set('all_servicechecks', $all_servicechecks);
        $toJson = ['all_servicechecks', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_servicechecks', 'scroll'];
        }
        $this->viewBuilder()->setOption('serialize', $toJson);
    }
}
