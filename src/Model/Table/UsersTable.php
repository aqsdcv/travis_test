<?php

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\HasMany $UsersMemberships
 */
class UsersTable extends Table {

  /**
   * Initialize method
   *
   * @param array $config The configuration for the Table.
   * @return void
   */
  public function initialize(array $config) {
    parent::initialize($config);

    $this->table('users');
    $this->displayField('name');
    $this->primaryKey('id');

    $this->hasMany('UsersMemberships', [
      'foreignKey' => 'user_id'
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
        ->requirePresence('name', 'create', __('The name is required'))
        ->notEmpty('name', __('The name cannot be empty'))
        ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => __('The provided user already exists')]);

    $validator
        ->requirePresence('password', 'create', __('The password is required'))
        ->notEmpty('password', __('The password cannot be empty'));

    $validator
        ->requirePresence('mail', 'create')
        ->notEmpty('mail')
        ->add('mail', 'unique', ['rule' => 'validateUnique', 'provider' => 'table'])
        ->add('mail', 'validFormat', ['rule' => 'email', 'message' => __('E-mail must be valid')]);

    $validator
        ->requirePresence('firstname', 'create')
        ->notEmpty('firstname');

    $validator
        ->requirePresence('lastname', 'create')
        ->notEmpty('lastname');

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
    $rules->add($rules->isUnique(['mail']));
    return $rules;
    }

}
