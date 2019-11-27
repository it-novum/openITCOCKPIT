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

use App\Model\Table\SystemfailuresTable;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\SystemfailuresFilter;

/**
 * Class SystemfailuresController
 * @property AppPaginatorComponent $Paginator
 * @property AppAuthComponent $Auth
 */
class SystemfailuresController extends AppController {

    public $layout = 'blank';

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }


        /** @var $SystemfailuresTable SystemfailuresTable */
        $SystemfailuresTable = TableRegistry::getTableLocator()->get('Systemfailures');

        $SystemfailuresFilter = new SystemfailuresFilter($this->request);
        $PaginateOMat = new PaginateOMat($this->Paginator, $this, $this->isScrollRequest(), $SystemfailuresFilter->getPage());

        $systemfailures = $SystemfailuresTable->getSystemfailuresIndex($SystemfailuresFilter, $PaginateOMat);

        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);
        $UserTime = $User->getUserTime();

        foreach ($systemfailures as $index => $systemfailure) {
            /** @var FrozenTime $FrozenStartTime */
            $FrozenStartTime = $systemfailure['start_time'];
            /** @var FrozenTime $FrozenEndTime */
            $FrozenEndTime = $systemfailure['end_time'];

            $systemfailures[$index]['start_time'] = $UserTime->format($FrozenStartTime->timestamp);
            $systemfailures[$index]['end_time'] = $UserTime->format($FrozenEndTime->timestamp);
        }


        $this->set('all_systemfailures', $systemfailures);
        $toJson = ['all_systemfailures', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_systemfailures', 'scroll'];
        }
        $this->set('_serialize', $toJson);
    }

    public function add() {
        $User = new \itnovum\openITCOCKPIT\Core\ValueObjects\User($this->Auth);

        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            $this->set('User', $User);
            return;
        }

        if ($this->request->is('post')) {
            /** @var $SystemfailuresTable SystemfailuresTable */
            $SystemfailuresTable = TableRegistry::getTableLocator()->get('Systemfailures');
            $this->request->data['Systemfailure']['user_id'] = $User->getId();

            $this->request->data['Systemfailure']['start_time'] = '';
            $this->request->data['Systemfailure']['end_time'] = '';

            $startTime = strtotime(trim($this->request->data('Systemfailure.from_date') . ' ' . trim($this->request->data('Systemfailure.from_time'))));
            if ($this->request->data('Systemfailure.from_date') !== '' && $startTime > 0) {
                $this->request->data['Systemfailure']['start_time'] = date('Y-m-d H:i:s', $startTime);
            }

            $endTime = strtotime(trim($this->request->data('Systemfailure.to_date') . ' ' . trim($this->request->data('Systemfailure.to_time'))));
            if ($this->request->data('Systemfailure.to_date') !== '' && $endTime > 0) {
                $this->request->data['Systemfailure']['end_time'] = date('Y-m-d H:i:s', $endTime);
            }


            $systemfailure = $SystemfailuresTable->newEmptyEntity();
            $systemfailure = $SystemfailuresTable->patchEntity($systemfailure, $this->request->data('Systemfailure'));

            $SystemfailuresTable->save($systemfailure);
            if ($systemfailure->hasErrors()) {
                $this->response->statusCode(400);
                $this->set('error', $systemfailure->getErrors());
                $this->set('_serialize', ['error']);
                return;
            } else {
                //No errors

                if ($this->request->ext == 'json') {
                    $this->serializeCake4Id($systemfailure); // REST API ID serialization
                    return;
                }
            }
            $this->set('systemfailure', $systemfailure);
            $this->set('_serialize', ['systemfailure']);
        }
    }

    /**
     * @param null|int $id
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $SystemfailuresTable SystemfailuresTable */
        $SystemfailuresTable = TableRegistry::getTableLocator()->get('Systemfailures');

        if (!$SystemfailuresTable->existsById($id)) {
            throw new NotFoundException(__('System failure not found'));
        }

        $systemfailure = $SystemfailuresTable->get($id);

        if ($SystemfailuresTable->delete($systemfailure)) {
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }

        $this->response->statusCode(500);
        $this->set('success', false);
        $this->set('_serialize', ['success']);
        return;

    }
}
