<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ListaConstantesFilterTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ListaConstantesFilterTable Test Case
 */
class ListaConstantesFilterTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ListaConstantesFilterTable
     */
    public $ListaConstantesFilter;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ListaConstantesFilter',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ListaConstantesFilter') ? [] : ['className' => ListaConstantesFilterTable::class];
        $this->ListaConstantesFilter = TableRegistry::getTableLocator()->get('ListaConstantesFilter', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ListaConstantesFilter);

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
}
