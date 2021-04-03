<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\User;
use Cake\Event\EventInterface;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->Crud->disable(['view', 'edit', 'delete']);
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'logout']);
    }

    public function index(): void
    {
        // redirect to stripe?
    }

    public function add(): void
    {
        $this->Crud->on('beforeSave', function (EventInterface $event) {
            /** @var User $entity */
            $entity = $event->getSubject()->entity;

            if (!empty($entity)) {
                foreach ($event->getSubject()->entity->getErrors() as $key => $error) {
                    $firstKey = key($error);
                    $this->Flash->error($key . " " . $error[$firstKey]);
                }
            }
        });
        $this->Crud->on('afterSave', function (EventInterface $event) {
            if ($event->getSubject()->success) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'add']);
            }
        });
        $this->Crud->execute();
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if (!empty($user)) {
                $this->Auth->setUser($user);
                $this->redirect($this->Auth->redirectUrl());
            }

            $this->Flash->error(__('Invalid username or password'));
        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }
}
