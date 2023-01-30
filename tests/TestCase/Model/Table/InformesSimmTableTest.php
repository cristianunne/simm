<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InformesSimmTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InformesSimmTable Test Case
 */
class InformesSimmTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\InformesSimmTable
     */
    public $InformesSimm;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.InformesSimm',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('InformesSimm') ? [] : ['className' => InformesSimmTable::class];
        $this->InformesSimm = TableRegistry::getTableLocator()->get('InformesSimm', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InformesSimm);

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
