<?php

namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Network\Exception\UserNotFoundException;
use \App\Network\Exception\ValidationException;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController {

    public $paginate = [
      'page' => 1,
      'limit' => 20,
      'maxLimit' => 100,
      'fields' => [
        'id', 'name', 'mail', 'firstname', 'lastname'
      ],
      'sortWhitelist' => [
        'id', 'name', 'mail', 'firstname', 'lastname'
      ]
    ];

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index() {
        $this->request->allowMethod('get');

        $users = $this->paginate($this->Users);
        $pagination = $this->request->params['paging']['Users'];

        $paginationResponse = [
          'page_count' => $pagination['pageCount'],
          'current_page' => $pagination['page'],
          'has_next_page' => $pagination['nextPage'],
          'has_prev_page' => $pagination['prevPage'],
          'count' => $pagination['count'],
          'limit' => $pagination['limit']
        ];

        $this->set('pagination', $paginationResponse);
        $this->set('users', $users);
    }

    /**
     * View method
     *
     * @param string $id User id.
     * @return \Cake\Network\Response|null
     * @throws UserNotFoundException When record not found.
     */
    public function view($id) {
        $this->request->allowMethod('get');
        
        try {
            $user = $this->Users->get($id);
        }
        catch (\Exception $e) {
            throw new UserNotFoundException(__("The user with the id $id does not exist"));
        }

        $this->set('user', $user);
        $this->set('_serialize', 'user');
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $this->request->allowMethod('post');
        $user = $this->Users->newEntity($this->request->data);
        if ($this->Users->save($user)) {
            $this->response->statusCode(201);
            $this->set('user', $user);
            $this->set('_serialize', 'user');
        }
        else {
            $this->response->statusCode(400);
            throw new ValidationException($user);
        }
    }

    /**
     * Edit method
     *
     * @param string $id User id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws UserNotFoundException When record not found.
     */
    public function edit($id) {
        $this->request->allowMethod('put');
        
        try {
            $oldUser = $this->Users->get($id);
        }
        catch (\Exception $e) {
            throw new UserNotFoundException(__("The user with the id $id does not exist"));
        }

        if (isset($this->request->data['name'])) {
            unset($this->request->data['name']);
        }

        $newUser = $this->Users->patchEntity($oldUser, $this->request->data);

        if ($this->Users->save($newUser)) {
            $this->response->statusCode(200);
            $this->set('user', $newUser);
            $this->set('_serialize', 'user');
        }
        else {
            $this->response->statusCode(400);
            throw new ValidationException($newUser);
        }
    }

    /**
     * Delete method
     *
     * @param string $id User id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws UserNotFoundException When record not found.
     */
    public function delete($id) {
        $this->request->allowMethod('delete');
        
        try {
            $user = $this->Users->get($id);
        }
        catch (\Exception $e) {
            throw new UserNotFoundException(__("The user with the id $id does not exist"));
        }

        $this->Users->delete($user);
        $this->response->statusCode(204);
    }

}
