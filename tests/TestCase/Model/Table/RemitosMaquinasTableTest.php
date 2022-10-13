<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RemitosMaquinasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RemitosMaquinasTable Test Case
 */
class RemitosMaquinasTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RemitosMaquinasTable
     */
    public $RemitosMaquinas;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.RemitosMaquinas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('RemitosMaquinas') ? [] : ['className' => RemitosMaquinasTable::class];
        $this->RemitosMaquinas = TableRegistry::getTableLocator()->get('RemitosMaquinas', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RemitosMaquinas);

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
