<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * MessagesOtd Controller
 *
 * @property \App\Model\Table\MessagesOtdTable $MessagesOtd
 * @method \App\Model\Entity\MessagesOtd[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MessagesOtdController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $messagesOtd = $this->paginate($this->MessagesOtd);

        $this->set(compact('messagesOtd'));
    }

    /**
     * View method
     *
     * @param string|null $id Messages Otd id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $messagesOtd = $this->MessagesOtd->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set(compact('messagesOtd'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $messagesOtd = $this->MessagesOtd->newEmptyEntity();
        if ($this->request->is('post')) {
            $messagesOtd = $this->MessagesOtd->patchEntity($messagesOtd, $this->request->getData());
            if ($this->MessagesOtd->save($messagesOtd)) {
                $this->Flash->success(__('The messages otd has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The messages otd could not be saved. Please, try again.'));
        }
        $users = $this->MessagesOtd->Users->find('list', ['limit' => 200]);
        $this->set(compact('messagesOtd', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Messages Otd id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $messagesOtd = $this->MessagesOtd->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $messagesOtd = $this->MessagesOtd->patchEntity($messagesOtd, $this->request->getData());
            if ($this->MessagesOtd->save($messagesOtd)) {
                $this->Flash->success(__('The messages otd has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The messages otd could not be saved. Please, try again.'));
        }
        $users = $this->MessagesOtd->Users->find('list', ['limit' => 200]);
        $this->set(compact('messagesOtd', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Messages Otd id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $messagesOtd = $this->MessagesOtd->get($id);
        if ($this->MessagesOtd->delete($messagesOtd)) {
            $this->Flash->success(__('The messages otd has been deleted.'));
        } else {
            $this->Flash->error(__('The messages otd could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
