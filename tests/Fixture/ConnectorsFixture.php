<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ConnectorsFixture
 *
 */
class ConnectorsFixture extends TestFixture {

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
      'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
      'name' => ['type' => 'string', 'length' => 255, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'fixed' => null],
      'url' => ['type' => 'string', 'length' => 425, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'fixed' => null],
      '_constraints' => [
        'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        'connectors_name_UNIQUE' => ['type' => 'unique', 'columns' => ['name'], 'length' => []],
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
        'id' => 1,
        'name' => 'pastell',
        'url' => 'https://pastell.example.org'
      ],
      [
        'id' => 2,
        'name' => 'parapheur',
        'url' => 'https://parapheur.example.org'
      ],
    ];

}
