<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ServicesFixture
 *
 */
class ServicesFixture extends TestFixture {

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
      'id' => ['type' => 'integer', 'length' => 10, 'autoIncrement' => true, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null],
      'name' => ['type' => 'string', 'length' => 255, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'fixed' => null],
      'connector_id' => ['type' => 'integer', 'length' => 10, 'default' => null, 'null' => false, 'comment' => null, 'precision' => null, 'unsigned' => null, 'autoIncrement' => null],
      '_indexes' => [
        'services_fk_services_applications1_idx' => ['type' => 'index', 'columns' => ['connector_id'], 'length' => []],
      ],
      '_constraints' => [
        'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        'services_name_UNIQUE' => ['type' => 'unique', 'columns' => ['name'], 'length' => []],
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
        'name' => 'actes',
        'connector_id' => 1
      ],
      [
        'id' => 2,
        'name' => 'helios',
        'connector_id' => 1
      ],
      [
        'id' => 3,
        'name' => 'mailsec',
        'connector_id' => 1
      ],
      [
        'id' => 4,
        'name' => 'signature',
        'connector_id' => 2
      ],
    ];

}
