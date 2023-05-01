<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InformesVariacionesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InformesVariacionesTable Test Case
 */
class InformesVariacionesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\InformesVariacionesTable
     */
    public $InformesVariaciones;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.InformesVariaciones',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('InformesVariaciones') ? [] : ['className' => InformesVariacionesTable::class];
        $this->InformesVariaciones = TableRegistry::getTableLocator()->get('InformesVariaciones', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InformesVariaciones);

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
