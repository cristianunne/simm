<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InformesResumenTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InformesResumenTable Test Case
 */
class InformesResumenTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\InformesResumenTable
     */
    public $InformesResumen;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.InformesResumen',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('InformesResumen') ? [] : ['className' => InformesResumenTable::class];
        $this->InformesResumen = TableRegistry::getTableLocator()->get('InformesResumen', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InformesResumen);

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
