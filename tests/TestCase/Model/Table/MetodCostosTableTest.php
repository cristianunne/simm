<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MetodCostosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MetodCostosTable Test Case
 */
class MetodCostosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MetodCostosTable
     */
    public $MetodCostos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.MetodCostos',
        'app.Users',
        'app.Empresas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('MetodCostos') ? [] : ['className' => MetodCostosTable::class];
        $this->MetodCostos = TableRegistry::getTableLocator()->get('MetodCostos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MetodCostos);

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
