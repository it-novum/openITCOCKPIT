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

use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\HostFilter;
use itnovum\openITCOCKPIT\Filter\HostgroupFilter;
use itnovum\openITCOCKPIT\Filter\ServiceFilter;
use itnovum\openITCOCKPIT\Filter\StatuspagesFilter;


/**
 * Statuspages Controller
 *
 * @property \App\Model\Table\StatuspagesTable $Statuspages
 * @method \App\Model\Entity\Statuspage[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StatuspagesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $StatuspagesTable StatuspagesTable */
        $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');

        $statuspagesFilter = new StatuspagesFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $statuspagesFilter->getPage());
        $all_statuspages = $StatuspagesTable->getStatuspagesIndex($statuspagesFilter, $PaginateOMat, $this->MY_RIGHTS);


        $this->set('all_statuspages', $all_statuspages);
        $this->viewBuilder()->setOption('serialize', ['all_statuspages']);
    }

    /**
     * View method
     *
     * @param string|null $id Statuspage id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $statuspage = $this->Statuspages->get($id, [
            'contain' => ['Containers', 'StatuspageItems'],
        ]);

        $this->set(compact('statuspage'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $statuspage = $this->Statuspages->newEmptyEntity();
        if ($this->request->is('post')) {
            $statuspage = $this->Statuspages->patchEntity($statuspage, $this->request->getData());
            if ($this->Statuspages->save($statuspage)) {
                $this->Flash->success(__('The statuspage has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The statuspage could not be saved. Please, try again.'));
        }
        $containers = $this->Statuspages->Containers->find('list', ['limit' => 200])->all();
        $this->set(compact('statuspage', 'containers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Statuspage id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $statuspage = $this->Statuspages->get($id, [
            'contain' => ['Containers'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $statuspage = $this->Statuspages->patchEntity($statuspage, $this->request->getData());
            if ($this->Statuspages->save($statuspage)) {
                $this->Flash->success(__('The statuspage has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The statuspage could not be saved. Please, try again.'));
        }
        $containers = $this->Statuspages->Containers->find('list', ['limit' => 200])->all();
        $this->set(compact('statuspage', 'containers'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Statuspage id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $statuspage = $this->Statuspages->get($id);
        if ($this->Statuspages->delete($statuspage)) {
            $this->Flash->success(__('The statuspage has been deleted.'));
        } else {
            $this->Flash->error(__('The statuspage could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
