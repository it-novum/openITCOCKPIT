<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\ContainersTable;
use App\Model\Table\HostgroupsTable;
use App\Model\Table\HostsTable;
use App\Model\Table\ServicegroupsTable;
use App\Model\Table\ServicesTable;
use App\Model\Table\StatuspagesTable;
use Cake\Event\EventInterface;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use itnovum\openITCOCKPIT\Core\AngularJS\Api;
use itnovum\openITCOCKPIT\Core\HostConditions;
use itnovum\openITCOCKPIT\Core\HostgroupConditions;
use itnovum\openITCOCKPIT\Core\ServiceConditions;
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
class StatuspagesController extends AppController {

    public function beforeFilter(EventInterface $event) {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['status']);
    }

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
                if (empty(array_diff($statuspagesWithContainers[$statuspage['id']], $this->getWriteContainers()))) {
                    //statuspage has no containers where user has no edit permission
                    $all_statuspages[$key]['allow_edit'] = true;
                }
            }

            $all_statuspages[$key]['allow_view'] = true;
            if ($all_statuspages[$key]['public'] === false) {
                if ($this->hasRootPrivileges === false) {
                    $all_statuspages[$key]['allow_view'] = false;
                    if (empty(array_diff($statuspagesWithContainers[$statuspage['id']], $this->MY_RIGHTS))) {
                        //statuspage has no containers where user has no edit permission
                        $all_statuspages[$key]['allow_view'] = true;
                    }
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
        $this->viewBuilder()->setLayout('statuspage_fullscreen');

        /** @var $StatuspagesTable StatuspagesTable */
        $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');
        if (!$StatuspagesTable->existsById($id)) {
            throw new NotFoundException('Statuspage not found');
        }


        $DbBackend = $this->DbBackend;
        $statuspage = $StatuspagesTable->getStatuspageObjectsForView($id, $DbBackend);
        if (!empty($statuspage) && $statuspage['statuspage']['public'] === false) {
            if (!$StatuspagesTable->allowedByStatuspageId($id, $this->MY_RIGHTS)) {
                $this->render403();
                return;
            }
        }

        $this->set('Statuspage', $statuspage);
        $this->viewBuilder()->setOption('serialize', ['Statuspage']);
    }

    /**
     * Public viewable statuspages
     * @return void
     */
    public function status($id = null) {
        //statuspages need to have public = true to be listed here
        $this->viewBuilder()->setLayout('statuspage_fullscreen');

        /** @var $StatuspagesTable StatuspagesTable */
        $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');
        if (!$StatuspagesTable->existsById($id)) {
            throw new NotFoundException('Statuspage not found');
        }

        $conditions = ['Statuspages.public' => 1];

        $DbBackend = $this->DbBackend;
        $statuspage = $StatuspagesTable->getStatuspageObjectsForView($id, $DbBackend, $conditions);

        $this->set('Statuspage', $statuspage);
        $this->viewBuilder()->setOption('serialize', ['Statuspage']);
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

            $statuspage = $StatuspagesTable->newEmptyEntity();
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

        if (!$StatuspagesTable->allowedByStatuspageId($id, $this->MY_RIGHTS)) {
            $this->render403();
            return;
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

        if (!$this->allowedByContainerId($statuspage['containers']['_ids'])) {
            $this->render403();
            return;
        }

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

    /**
     * Set display names for Hosts, Services, Hostgroups and Servicegroups
     *
     * @param $id
     * @return void
     */
    public function stepTwo($id = null) {
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

        if ($this->request->is('post')) {
            $statuspage = $StatuspagesTable->get($id, [
                'contain' => [
                    'Containers',
                    'Hosts',
                    'Services',
                    'Hostgroups',
                    'Servicegroups'
                ]
            ]);

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
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var $AutomapsTable StatuspagesTable */
        $StatuspagesTable = TableRegistry::getTableLocator()->get('Statuspages');

        if (!$StatuspagesTable->existsById($id)) {
            throw new NotFoundException(__('Invalid Statuspage'));
        }

        $statuspage = $StatuspagesTable->get($id);

        if (!$this->allowedByContainerId($statuspage->get('container_id'), true)) {
            $this->render403();
            return;
        }

        if (!$StatuspagesTable->delete($statuspage)) {
            $this->response = $this->response->withStatus(400);
            $this->set('success', false);
            $this->set('id', $id);
            $this->viewBuilder()->setOption('serialize', ['success', 'id']);
            return;
        }

        $this->set('success', true);
        $this->set('id', $id);
        $this->viewBuilder()->setOption('serialize', ['success', 'id']);
    }

    /**
     * differnt from the standard method as these can handle multiple containerIDs
     * @return void
     */
    public function loadHostsByContainerIds() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $containerIds = $this->request->getQuery('containerIds');
        $selected = $this->request->getQuery('selected');

        /** @var $HostsTable HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        $HostFilter = new HostFilter($this->request);

        $containerIds = array_merge($containerIds, [ROOT_CONTAINER]);
        $containerIds = array_unique($containerIds);

        $HostCondition = new HostConditions($HostFilter->ajaxFilter());
        $HostCondition->setContainerIds($containerIds);

        $hosts = Api::makeItJavaScriptAble(
            $HostsTable->getHostsForAngular($HostCondition, $selected)
        );

        $this->set('hosts', $hosts);
        $this->viewBuilder()->setOption('serialize', ['hosts']);
    }

    /**
     * differnt from the standard method as these can handle multiple containerIDs
     * @return void
     */
    public function loadServicesByContainerIds() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $selected = $this->request->getQuery('selected');
        $containerIds = $this->request->getQuery('containerIds');

        $ServiceFilter = new ServiceFilter($this->request);

        $containerIds = array_merge($containerIds, [ROOT_CONTAINER]);
        $containerIds = array_unique($containerIds);

        $ServiceCondition = new ServiceConditions($ServiceFilter->indexFilter());
        $ServiceCondition->setContainerIds($containerIds);
        $ServiceCondition->setIncludeDisabled(false);


        /** @var $ServicesTable ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        $services = Api::makeItJavaScriptAble(
            $ServicesTable->getServicesForAngularCake4($ServiceCondition, $selected)
        );

        $this->set('services', $services);
        $this->viewBuilder()->setOption('serialize', ['services']);
    }

    /**
     * differnt from the standard method as these can handle multiple containerIDs
     * @return void
     */
    public function loadServicegroupsByContainerIds() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ServicegroupsTable ServicegroupsTable */
        $ServicegroupsTable = TableRegistry::getTableLocator()->get('Servicegroups');

        $containerIds = $this->request->getQuery('containerIds');

        $containerIds = array_merge($containerIds, [ROOT_CONTAINER]);
        $containerIds = array_unique($containerIds);

        $servicegroups = $ServicegroupsTable->getServicegroupsByContainerId($containerIds, 'list');
        $servicegroups = Api::makeItJavaScriptAble($servicegroups);

        $this->set('servicegroups', $servicegroups);
        $this->viewBuilder()->setOption('serialize', ['servicegroups']);
    }

    /**
     * differnt from the standard method as these can handle multiple containerIDs
     * @return void
     */
    public function loadHostgroupsByContainerIds() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        $containerIds = $this->request->getQuery('containerIds');
        $HostgroupFilter = new HostgroupFilter($this->request);

        /** @var $HostgroupsTable HostgroupsTable */
        $HostgroupsTable = TableRegistry::getTableLocator()->get('Hostgroups');

        $containerIds = array_merge($containerIds, [ROOT_CONTAINER]);
        $containerIds = array_unique($containerIds);


        $HostgroupCondition = new HostgroupConditions($HostgroupFilter->indexFilter());
        $HostgroupCondition->setContainerIds($containerIds);

        $hostgroups = $HostgroupsTable->getHostgroupsByContainerIdNew($HostgroupCondition);
        $hostgroups = Api::makeItJavaScriptAble($hostgroups);

        $this->set('hostgroups', $hostgroups);
        $this->viewBuilder()->setOption('serialize', ['hostgroups']);
    }

    public function loadContainers() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        /** @var $ContainersTable ContainersTable */
        $ContainersTable = TableRegistry::getTableLocator()->get('Containers');

        if ($this->hasRootPrivileges === true) {
            $containers = $ContainersTable->easyPath($this->MY_RIGHTS, OBJECT_CONTACT, [], $this->hasRootPrivileges, [CT_CONTACTGROUP]);
        } else {
            $containers = $ContainersTable->easyPath($this->getWriteContainers(), OBJECT_CONTACT, [], $this->hasRootPrivileges, [CT_CONTACTGROUP]);
        }


        $this->set('containers', Api::makeItJavaScriptAble($containers));
        $this->viewBuilder()->setOption('serialize', ['containers']);
    }
}
