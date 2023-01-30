<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ArreglosMecanicosYearTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ArreglosMecanicosYearTable Test Case
 */
class ArreglosMecanicosYearTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ArreglosMecanicosYearTable
     */
    public $ArreglosMecanicosYear;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ArreglosMecanicosYear',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ArreglosMecanicosYear') ? [] : ['className' => ArreglosMecanicosYearTable::class];
        $this->ArreglosMecanicosYear = TableRegistry::getTableLocator()->get('ArreglosMecanicosYear', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ArreglosMecanicosYear);

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
