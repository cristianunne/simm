<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CostosMaquinasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CostosMaquinasTable Test Case
 */
class CostosMaquinasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CostosMaquinasTable
     */
    public $CostosMaquinas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CostosMaquinas',
        'app.Maquinas',
        'app.Worksgroups',
        'app.CentrosCostos',
        'app.MetodCostos',
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
        $config = TableRegistry::getTableLocator()->exists('CostosMaquinas') ? [] : ['className' => CostosMaquinasTable::class];
        $this->CostosMaquinas = TableRegistry::getTableLocator()->get('CostosMaquinas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CostosMaquinas);

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
