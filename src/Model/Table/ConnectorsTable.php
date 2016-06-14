<?php

namespace App\Model\Table;

use App\Model\Entity\Connector;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Connectors Model
 *
 * @property \Cake\ORM\Association\HasMany $Services
 */
class ConnectorsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('connectors');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->hasMany('Services', [
          'foreignKey' => 'connector_id'
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
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => __('The provided connector already exists')]);

        $validator
            ->requirePresence('url', 'create')
            ->notEmpty('url')
            ->add('url', 'validFormat', ['rule' => ['url', true], 'message' => __('URL must be valid')]);
        ;

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
        return $rules;
    }

}
