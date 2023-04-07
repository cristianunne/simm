<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ArreglosMecanicosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ArreglosMecanicosTable Test Case
 */
class ArreglosMecanicosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ArreglosMecanicosTable
     */
    public $ArreglosMecanicos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ArreglosMecanicos',
        'app.Users',
        'app.Maquinas',
        'app.Parcelas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ArreglosMecanicos') ? [] : ['className' => ArreglosMecanicosTable::class];
        $this->ArreglosMecanicos = TableRegistry::getTableLocator()->get('ArreglosMecanicos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ArreglosMecanicos);

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
     * Test findGetArreglosByConditions method
     *
     * @return void
     */
    public function testFindGetArreglosByConditions()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
