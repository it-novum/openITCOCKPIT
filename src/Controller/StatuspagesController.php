<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\StatuspagesTable;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\StatuspagesFilter;

/**
 * Statuspages Controller
 *
 * @property \App\Model\Table\StatuspagesTable $Statuspages
 * @method \App\Model\Entity\Statuspage[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StatuspagesController extends AppController {
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }


        /** @var $StatuspagesTable StatuspagesTable */
        $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');

        $statuspagesFilter = new StatuspagesFilter($this->request);

        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $statuspagesFilter->getPage());

        $all_statuspages = $StatuspagesTable->getStatuspagesIndex($statuspagesFilter, $PaginateOMat, $this->MY_RIGHTS);

        $statuspagesWithContainers = [];
        foreach ($all_statuspages as $key => $statuspage) {
            $statuspagesWithContainers[$statuspage['id']] = [];
            foreach ($statuspage['containers'] as $container) {
                $statuspagesWithContainers[$statuspage['id']][] = $container['id'];
            }

            $all_statuspages[$key]['allow_edit'] = true;
            if ($this->hasRootPrivileges === false) {
                $all_statuspages[$key]['allow_edit'] = false;
                if (!empty(array_intersect($statuspagesWithContainers[$statuspage['id']], $this->getWriteContainers()))) {
                    $all_statuspages[$key]['allow_edit'] = true;
                }
            }
        }

        $this->set('all_statuspages', $all_statuspages);
        $toJson = ['all_statuspages', 'paging'];
        if ($this->isScrollRequest()) {
            $toJson = ['all_statuspages', 'scroll'];
        }
        $this->viewBuilder()->setOption('serialize', $toJson);
    }

    /**
     * View method
     *
     * @param string|null $id Statuspage id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $statuspage = $this->Statuspages->get($id, [
            'contain' => ['Users', 'HostgroupsToStatuspages', 'HostsToStatuspages', 'ServicegroupsToStatuspages', 'ServicesToStatuspages'],
        ]);

        $this->set(compact('statuspage'));
    }

    /**
     * Public viewable statuspages
     * @return void
     */
    public function status() {
        //statuspages need to have public = true to be listed here

    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if ($this->request->is('post')) {
            /** @var $StatuspagesTable StatuspagesTable */
            $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');

            $statuspageData = $this->request->getData();


            debug($statuspageData);
            // die('ende');

            $statuspage = $StatuspagesTable->newEmptyEntity();
            $statuspage = $StatuspagesTable->patchEntity($statuspage, $statuspageData);

            debug($statuspage);
            die('ende');
            $StatuspagesTable->save($statuspage);
            if ($statuspage->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $statuspage->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($statuspage); // REST API ID serialization
                    return;
                }
            }

        }

        $this->set('Statuspage', $statuspage);
        $this->viewBuilder()->setOption('serialize', ['Statuspage']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Statuspage id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $StatuspagesTable StatuspagesTable */
        $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');
        if (!$StatuspagesTable->existsById($id)) {
            throw new NotFoundException('Statuspage not found');
        }

        $statuspage = $StatuspagesTable->get($id, [
            'contain' => [
                'Containers',
                'Hosts',
                'Services',
                'Hostgroups',
                'Servicegroups'
            ]
        ]);
        $statuspage['containers'] = [
            '_ids' => Hash::extract($statuspage, 'containers.{n}.id')
        ];

        $statuspage['hosts'] = [
            '_ids' => Hash::extract($statuspage, 'hosts.{n}.id')
        ];

        $statuspage['hostgroups'] = [
            '_ids' => Hash::extract($statuspage, 'hostgroups.{n}.id')
        ];

        $statuspage['services'] = [
            '_ids' => Hash::extract($statuspage, 'services.{n}.id')
        ];

        $statuspage['servicegroups'] = [
            '_ids' => Hash::extract($statuspage, 'servicegroups.{n}.id')
        ];

        //debug($statuspage);
/*
        if (!$this->allowedByContainerId($statuspage['containers']['_ids'])) {
            $this->render403();
            return;
        }
*/
        if ($this->request->is('post')) {
            $statuspageData = $this->request->getData();
            $statuspage = $StatuspagesTable->patchEntity($statuspage, $statuspageData);
            $StatuspagesTable->save($statuspage);
            if ($statuspage->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $statuspage->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($statuspage); // REST API ID serialization
                    return;
                }
            }

        }

        $this->set('Statuspage', $statuspage);
        $this->viewBuilder()->setOption('serialize', ['Statuspage']);
    }

    public function stepTwo($id = null){
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var $StatuspagesTable StatuspagesTable */
        $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');
        if (!$StatuspagesTable->existsById($id)) {
            throw new NotFoundException('Statuspage not found');
        }
        $statuspage = $StatuspagesTable->getStatuspageObjects($id);

        $this->set('Statuspages', $statuspage);
        $this->viewBuilder()->setOption('serialize', ['Statuspages']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Statuspage id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
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
