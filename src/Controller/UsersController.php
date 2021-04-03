<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\User;
use Cake\Event\EventInterface;
use Cake\Routing\Router;
use Stripe\StripeClient;

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
        $this->Auth->allow(['add', 'login', 'logout']);
        $this->set('user', $this->Auth->user());
    }

    /**
     * @return \Cake\Http\Response|void|null
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function index()
    {
        if ($this->request->is('post')) {

            $stripe = new StripeClient(env('STRIPE_SECRET'));
            $result = $stripe->accountLinks->create([
                'account' => env('STRIPE_ACCOUNT_ID'),
                'refresh_url' => Router::url(['controller' => 'Users', 'action' => 'refresh'], true),
                'return_url' => Router::url(['controller' => 'Users', 'action' => 'return'], true),
                'type' => 'account_onboarding',
            ]);

            if (empty($result)) {
                return;
            }

            return $this->redirect($result->url);
        }
    }

    public function return(): void
    {

    }

    public function refresh(): void
    {

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
                return $this->redirect(['action' => 'login']);
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
                return $this->redirect($this->Auth->redirectUrl());
            }

            $this->Flash->error(__('Invalid username or password'));
        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }
}
