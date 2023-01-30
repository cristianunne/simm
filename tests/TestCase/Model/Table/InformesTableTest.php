<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InformesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InformesTable Test Case
 */
class InformesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\InformesTable
     */
    public $Informes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Informes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Informes') ? [] : ['className' => InformesTable::class];
        $this->Informes = TableRegistry::getTableLocator()->get('Informes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Informes);

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
