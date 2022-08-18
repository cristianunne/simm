<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OperariosMaquinasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OperariosMaquinasTable Test Case
 */
class OperariosMaquinasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OperariosMaquinasTable
     */
    public $OperariosMaquinas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.OperariosMaquinas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('OperariosMaquinas') ? [] : ['className' => OperariosMaquinasTable::class];
        $this->OperariosMaquinas = TableRegistry::getTableLocator()->get('OperariosMaquinas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->OperariosMaquinas);

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
