<?php

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConnectorsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConnectorsTable Test Case
 */
class ConnectorsTableTest extends TestCase {

    /**
     * Test subject
     *
     * @var \App\Model\Table\ConnectorsTable
     */
    public $Connectors;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
      'app.connectors',
      'app.services'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $config = TableRegistry::exists('Connectors') ? [] : ['className' => 'App\Model\Table\ConnectorsTable'];
        $this->Connectors = TableRegistry::get('Connectors', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown() {
        unset($this->Connectors);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize() {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
//    public function testValidationDefault() {
//        $this->markTestIncomplete('Not implemented yet.');
//    }

    /**
     * Test buildRules method
     *
     * @return void
     */
//    public function testBuildRules() {
//        $this->markTestIncomplete('Not implemented yet.');
//    }

}
