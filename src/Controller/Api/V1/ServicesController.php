<?php

namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Network\Exception\ServiceNotFoundException;
use App\Network\Exception\ValidationException;

/**
 * Services Controller
 *
 * @property \App\Model\Table\ServicesTable $Services
 */
class ServicesController extends AppController {

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index() {

        $connector = $this->Services->getConnector($this->request->params['connector_id']);

        $this->paginate = [
//          'contain' => ['Connectors'],
          'conditions' => ['Services.connector_id' => $connector->id]
        ];
        $services = $this->paginate($this->Services);

        $pagination = $this->request->params['paging']['Services'];

        $paginationResponse = [
          'page_count' => $pagination['pageCount'],
          'current_page' => $pagination['page'],
          'has_next_page' => $pagination['nextPage'],
          'has_prev_page' => $pagination['prevPage'],
          'count' => $pagination['count'],
          'limit' => $pagination['limit']
        ];

        $this->set('pagination', $paginationResponse);
        $this->set('services', $services);
    }

    /**
     * View method
     *
     * @param string $id Service id.
     * @return \Cake\Network\Response|null
     * @throws \App\Network\Exception\ServiceNotFoundException When service not found.
     */
    public function view($id) {

        $connector = $this->Services->getConnector($this->request->params['connector_id']);
        try {
            $service = $this->Services->get($id, [
              'conditions' => ['Services.connector_id' => $connector->id]
            ]);
        }
        catch (\Exception $e) {
            throw new ServiceNotFoundException("The service with the id $id does not exist");
        }
        $this->set('service', $service);
        $this->set('_serialize', 'service');
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $this->request->allowMethod('post');

        $connector = $this->Services->getConnector($this->request->params['connector_id']);
        $this->request->data['connector_id'] = $connector->id;
        $service = $this->Services->newEntity($this->request->data);
        if ($this->Services->save($service)) {
            $this->response->statusCode(201);
            $this->set('service', $service);
            $this->set('_serialize', 'service');
        }
        else {
            $this->response->statusCode(400);
            throw new ValidationException($service);
        }
    }

    /**
     * Edit method
     *
     * @param string $id Service id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \App\Network\Exception\ServiceNotFoundException When service not found.
     */
    public function edit($id) {
        $this->request->allowMethod('put');

        $connector = $this->Services->getConnector($this->request->params['connector_id']);
        try {
            $service = $this->Services->get($id, [
              'conditions' => ['Services.connector_id' => $connector->id]
            ]);
        }
        catch (\Exception $e) {
            throw new ServiceNotFoundException("The service with the id $id does not exist");
        }

        $service = $this->Services->patchEntity($service, $this->request->data);

        if ($this->Services->save($service)) {
            $this->set('service', $service);
            $this->set('_serialize', 'service');
        }
        else {
            throw new ValidationException($service);
        }
    }

    /**
     * Delete method
     *
     * @param string $id Service id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \App\Network\Exception\ServiceNotFoundException When service not found.
     */
    public function delete($id) {
        $this->request->allowMethod('delete');

        $connector = $this->Services->getConnector($this->request->params['connector_id']);
        try {
            $service = $this->Services->get($id, [
              'conditions' => ['Services.connector_id' => $connector->id]
            ]);
        }
        catch (\Exception $e) {
            throw new ServiceNotFoundException("The service with the id $id does not exist");
        }

        $this->Services->delete($service);
        $this->response->statusCode(204);
    }

}
