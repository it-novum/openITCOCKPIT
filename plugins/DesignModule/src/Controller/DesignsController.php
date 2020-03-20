<?php
declare(strict_types=1);

namespace DesignModule\Controller;

/**
 * Designs Controller
 *
 *
 * @method \DesignModule\Model\Entity\Design[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DesignsController extends AppController {

    /**
     * Edit method
     *
     * @param string|null $id Design id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        $design = $this->Designs->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $design = $this->Designs->patchEntity($design, $this->request->getData());
            if ($this->Designs->save($design)) {
                $this->Flash->success(__('The design has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The design could not be saved. Please, try again.'));
        }
        $this->set(compact('design'));
    }

    /**
     * export style to json file
     */
    public function export() {

    }

    /**
     * import json style file
     */
    public function import() {

    }

    /**
     * reset to default style
     */
    public function reset() {

    }
}
