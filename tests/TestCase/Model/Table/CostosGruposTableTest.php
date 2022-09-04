<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CostosGruposTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CostosGruposTable Test Case
 */
class CostosGruposTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CostosGruposTable
     */
    public $CostosGrupos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CostosGrupos',
        'app.Hashes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CostosGrupos') ? [] : ['className' => CostosGruposTable::class];
        $this->CostosGrupos = TableRegistry::getTableLocator()->get('CostosGrupos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CostosGrupos);

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
