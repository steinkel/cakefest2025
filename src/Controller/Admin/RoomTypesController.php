<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\AppController;

/**
 * RoomTypes Controller
 *
 * @property \App\Model\Table\RoomTypesTable $RoomTypes
 */
class RoomTypesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->RoomTypes->find();
        $roomTypes = $this->paginate($query);

        $this->set(compact('roomTypes'));
    }

    /**
     * View method
     *
     * @param string|null $id Room Type id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $roomType = $this->RoomTypes->get($id, contain: ['Rooms']);
        $this->set(compact('roomType'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $roomType = $this->RoomTypes->newEmptyEntity();
        if ($this->request->is('post')) {
            $roomType = $this->RoomTypes->patchEntity($roomType, $this->request->getData());
            if ($this->RoomTypes->save($roomType)) {
                $this->Flash->success(__('The room type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The room type could not be saved. Please, try again.'));
        }
        $this->set(compact('roomType'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Room Type id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $roomType = $this->RoomTypes->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $roomType = $this->RoomTypes->patchEntity($roomType, $this->request->getData());
            if ($this->RoomTypes->save($roomType)) {
                $this->Flash->success(__('The room type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The room type could not be saved. Please, try again.'));
        }
        $this->set(compact('roomType'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Room Type id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $roomType = $this->RoomTypes->get($id);
        if ($this->RoomTypes->delete($roomType)) {
            $this->Flash->success(__('The room type has been deleted.'));
        } else {
            $this->Flash->error(__('The room type could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
