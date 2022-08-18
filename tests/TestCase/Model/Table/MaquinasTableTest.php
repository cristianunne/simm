<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MaquinasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MaquinasTable Test Case
 */
class MaquinasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MaquinasTable
     */
    public $Maquinas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Maquinas',
        'app.Operarios',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Maquinas') ? [] : ['className' => MaquinasTable::class];
        $this->Maquinas = TableRegistry::getTableLocator()->get('Maquinas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Maquinas);

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
