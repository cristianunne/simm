<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ListaConstantesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ListaConstantesTable Test Case
 */
class ListaConstantesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ListaConstantesTable
     */
    public $ListaConstantes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ListaConstantes',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ListaConstantes') ? [] : ['className' => ListaConstantesTable::class];
        $this->ListaConstantes = TableRegistry::getTableLocator()->get('ListaConstantes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ListaConstantes);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
