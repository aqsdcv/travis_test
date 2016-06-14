<?php

namespace App\Model\Table;

use App\Model\Entity\Service;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Services Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Connectors
 * @property \Cake\ORM\Association\BelongsToMany $Subscriptions
 * @property \Cake\ORM\Association\BelongsToMany $UsersMemberships
 */
class ServicesTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('services');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->belongsTo('Connectors', [
          'foreignKey' => 'connector_id',
          'joinType' => 'INNER'
        ]);
        $this->belongsToMany('Subscriptions', [
          'foreignKey' => 'service_id',
          'targetForeignKey' => 'subscription_id',
          'joinTable' => 'subscriptions_services'
        ]);
        $this->belongsToMany('UsersMemberships', [
          'foreignKey' => 'service_id',
          'targetForeignKey' => 'users_membership_id',
          'joinTable' => 'users_memberships_services'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name')
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->isUnique(['name']));
        $rules->add($rules->existsIn(['connector_id'], 'Connectors'));
        return $rules;
    }

    /**
     * Returns a single connector after finding it by its id. The connector MUST
     * be parent to the current service.
     * 
     * @param $connector_id The id of the connector to find
     * @return \Cake\Datasource\EntityInterface
     * @throws \App\Network\Exception\ConnectorNotFoundException if the 
     * connector with such id could not be found
     */
    public function getConnector($connector_id) {
        try {
            $connector = $this->Connectors->get($connector_id);
        }
        catch (\Exception $e) {
            throw new \App\Network\Exception\ConnectorNotFoundException("The connector with the id $connector_id does not exist");
        }
        return $connector;
    }

}
