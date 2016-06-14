<?php

namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Network\Exception\ValidationException;
use App\Network\Exception\ConnectorNotFoundException;

/**
 * Connectors Controller
 *
 * @property \App\Model\Table\ConnectorsTable $Connectors
 */
class ConnectorsController extends AppController {

    public $paginate = [
      'page' => 1,
      'limit' => 20,
      'maxLimit' => 100,
      'fields' => [
        'id', 'name', 'url'
      ],
      'sortWhitelist' => [
        'id', 'name', 'url'
      ]
    ];

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index() {
        $this->request->allowMethod('get');

        $connectors = $this->paginate($this->Connectors);

        $pagination = $this->request->params['paging']['Connectors'];

        $paginationResponse = [
          'page_count' => $pagination['pageCount'],
          'current_page' => $pagination['page'],
          'has_next_page' => $pagination['nextPage'],
          'has_prev_page' => $pagination['prevPage'],
          'count' => $pagination['count'],
          'limit' => $pagination['limit']
        ];

        $this->set('pagination', $paginationResponse);
        $this->set('connectors', $connectors);
    }

    /**
     * View method
     *
     * @param string $id Connector name.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id) {
        $this->request->allowMethod('get');

        try {
            $connector = $this->Connectors->get($id);
        }
        catch (\Exception $ex) {
            throw new ConnectorNotFoundException("The connector with the id $id does not exist");
        }

        $this->set('connector', $connector);
        $this->set('_serialize', 'connector');
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $this->request->allowMethod('post');

        $connector = $this->Connectors->newEntity($this->request->data);
        if ($this->Connectors->save($connector)) {
            $this->response->statusCode(201);
            $this->set('connector', $connector);
            $this->set('_serialize', 'connector');
        }
        else {
            $this->response->statusCode(400);
            throw new ValidationException($connector);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Connector id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id) {
        $this->request->allowMethod('put');

        try {
            $connector = $this->Connectors->get($id);
        }
        catch (\Exception $ex) {
            throw new ConnectorNotFoundException("The connector with the id $id does not exist");
        }

        if (isset($this->request->data['name'])) {
            unset($this->request->data['name']);
        }

        $connector = $this->Connectors->patchEntity($connector, $this->request->data);

        if ($this->Connectors->save($connector)) {
            $this->response->statusCode(200);
            $this->set('connector', $connector);
            $this->set('_serialize', 'connector');
        }
        else {
            $this->response->statusCode(400);
            throw new ValidationException($connector);
        }
    }

    /**
     * Delete method
     *
     * @param string $id Connector id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id) {
        $this->request->allowMethod('delete');
        try {
            $connector = $this->Connectors->get($id);
        }
        catch (\Exception $ex) {
            throw new ConnectorNotFoundException("The connector with the id $id does not exist");
        }
        $this->Connectors->delete($connector);
        $this->response->statusCode(204);
    }

}
