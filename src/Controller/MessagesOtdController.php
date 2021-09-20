<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\MessagesOtdTable;
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
            /** @var MessagesOtdTable $MessagesOtdTable */
            $MessagesOtdTable = TableRegistry::getTableLocator()->get('MessagesOtd');
            $requestData = $this->request->getData();
            if(!empty($requestData['MessagesOtd']['date'])){
                /** @var FrozenDate $frozenDate */
                $frozenDate = new FrozenDate($requestData['MessagesOtd']['date']);
                $requestData['MessagesOtd']['date'] = $frozenDate->format('Y-m-d');
            }
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

    }

    /**
     * Delete method
     *
     * @param string|null $id Messages Otd id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {

    }
}
