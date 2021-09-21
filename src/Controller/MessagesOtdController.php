<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\MessagesOtdTable;
use Cake\Http\Exception\MethodNotAllowedException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\FrozenDate;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\FileDebugger;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Core\Views\UserTime;
use itnovum\openITCOCKPIT\Database\PaginateOMat;
use itnovum\openITCOCKPIT\Filter\GenericFilter;

/**
 * MessagesOtd Controller
 *
 * @property \App\Model\Table\MessagesOtdTable $MessagesOtd
 * @method \App\Model\Entity\MessagesOtd[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MessagesOtdController extends AppController {
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index() {
        if (!$this->isAngularJsRequest()) {
            //Only ship HTML Template
            return;
        }
        /** @var  MessagesOtdTable $MessagesOtdTable */
        $MessagesOtdTable = TableRegistry::getTableLocator()->get('MessagesOtd');

        $GenericFilter = new GenericFilter($this->request);
        $GenericFilter->setFilters([
            'like' => [
                'MessagesOtd.title',
                'MessagesOtd.description',
                'MessagesOtd.date'
            ]
        ]);

        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $GenericFilter->getPage());

        $messagesOtd = $MessagesOtdTable->getMessagesOTDIndex($GenericFilter, $PaginateOMat);

        $this->set('messagesOtd', $messagesOtd);
        $this->viewBuilder()->setOption('serialize', ['messagesOtd']);
    }

    /**
     * View method
     *
     * @param string|null $id Messages Otd id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $messagesOtd = $this->MessagesOtd->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('messagesOtd'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     * @throws \Exception
     */
    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if ($this->request->is('post')) {
            $User = new User($this->getUser());
            /** @var MessagesOtdTable $MessagesOtdTable */
            $MessagesOtdTable = TableRegistry::getTableLocator()->get('MessagesOtd');
            $requestData = $this->request->getData();
            if (!empty($requestData['MessagesOtd']['date'])) {
                /** @var FrozenDate $frozenDate */
                $frozenDate = new FrozenDate($requestData['MessagesOtd']['date']);
                $requestData['MessagesOtd']['date'] = $frozenDate->format('Y-m-d');
            }
            $requestData['MessagesOtd']['user_id'] = $User->getId();
            $messageOtd = $MessagesOtdTable->newEntity($requestData);

            $MessagesOtdTable->save($messageOtd);

            if ($messageOtd->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $messageOtd->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            } else {
                if ($this->isJsonRequest()) {
                    $this->serializeCake4Id($messageOtd); // REST API ID serialization
                    return;
                }
            }
            $this->set('messageOtd', $messageOtd);
            $this->viewBuilder()->setOption('serialize', ['messageOtd']);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Messages Otd id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        /** @var MessagesOtdTable $MessagesOtdTable */
        $MessagesOtdTable = TableRegistry::getTableLocator()->get('MessagesOtd');

        if (!$MessagesOtdTable->existsById($id)) {
            throw new NotFoundException(__('Invalid message of the day'));
        }

        $messageOtd = $MessagesOtdTable->getMessageOtdByIdForEdit($id);

        if ($this->request->is('get')) {
            if (!empty($messageOtd['date'])) {
                /** @var FrozenDate $frozenDate */
                $frozenDate = new FrozenDate($messageOtd['date']);
                $messageOtd['date'] = $frozenDate->format('d.m.Y');
            }
            $this->set('messageOtd', $messageOtd);
            $this->viewBuilder()->setOption('serialize', ['messageOtd']);
            return;
        }

        if ($this->request->is('post')) {
            $User = new User($this->getUser());
            $requestData = $this->request->getData();
            if (!empty($requestData['MessagesOtd']['date'])) {
                /** @var FrozenDate $frozenDate */
                $frozenDate = new FrozenDate($requestData['MessagesOtd']['date']);
                $requestData['MessagesOtd']['date'] = $frozenDate->format('Y-m-d');
            }
            $requestData['MessagesOtd']['user_id'] = $User->getId();

            $Entity = $MessagesOtdTable->get($id);
            $Entity = $MessagesOtdTable->patchEntity($Entity, $requestData);

            $MessagesOtdTable->save($Entity);
            if ($Entity->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $Entity->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }
            $this->set('messageOtd', $Entity);
            $this->viewBuilder()->setOption('serialize', ['messageOtd']);
        }
    }

    /**
     * @param null $id
     */
    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        /** @var MessagesOtdTable $MessagesOtdTable */
        $MessagesOtdTable = TableRegistry::getTableLocator()->get('MessagesOtd');

        if (!$MessagesOtdTable->existsById($id)) {
            throw new NotFoundException(__('Invalid message of the day'));
        }

        $messageOtd = $MessagesOtdTable->get($id);

        if ($MessagesOtdTable->delete($messageOtd)) {
            $this->set('success', true);
            $this->set('message', __('Message of the day deleted successfully'));
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
            return;
        }

        $this->response = $this->response->withStatus(400);
        $this->set('success', false);
        $this->set('message', __('Issue while deleting message of the days'));
        $this->viewBuilder()->setOption('serialize', ['success', 'message']);
    }
}
