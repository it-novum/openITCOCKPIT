<?php
declare(strict_types=1);

namespace App\Controller;

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
        $statuspages = $this->paginate($this->Statuspages);

        $this->set(compact('statuspages'));
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
