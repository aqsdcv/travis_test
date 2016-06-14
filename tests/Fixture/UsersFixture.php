<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 *
 */
class UsersFixture extends TestFixture {

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
      'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
      'name' => ['type' => 'string', 'length' => 255, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'fixed' => null],
      'password' => ['type' => 'string', 'length' => 255, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'fixed' => null],
      'mail' => ['type' => 'string', 'length' => 255, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'fixed' => null],
      'firstname' => ['type' => 'string', 'length' => 255, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'fixed' => null],
      'lastname' => ['type' => 'string', 'length' => 255, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'fixed' => null],
      '_constraints' => [
        'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        'users_name_UNIQUE' => ['type' => 'unique', 'columns' => ['name'], 'length' => []],
        'users_email_UNIQUE' => ['type' => 'unique', 'columns' => ['mail'], 'length' => []],
      ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
      [
        'name' => 'mreyrolle',
        'password' => 'mreyrolle',
        'mail' => 'maxime.reyrolle@example.org',
        'firstname' => 'Maxime',
        'lastname' => 'Reyrolle'
      ],
      [
        'name' => 'aauzolat',
        'password' => 'aauzolat',
        'mail' => 'arnaud.auzolat@example.org',
        'firstname' => 'Arnaud',
        'lastname' => 'Auzolat'
      ],
    ];

}
