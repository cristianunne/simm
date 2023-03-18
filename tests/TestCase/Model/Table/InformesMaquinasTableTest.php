<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InformesMaquinasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InformesMaquinasTable Test Case
 */
class InformesMaquinasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\InformesMaquinasTable
     */
    public $InformesMaquinas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.InformesMaquinas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('InformesMaquinas') ? [] : ['className' => InformesMaquinasTable::class];
        $this->InformesMaquinas = TableRegistry::getTableLocator()->get('InformesMaquinas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InformesMaquinas);

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
