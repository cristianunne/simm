<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DestinosProductosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DestinosProductosTable Test Case
 */
class DestinosProductosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\DestinosProductosTable
     */
    public $DestinosProductos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.DestinosProductos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('DestinosProductos') ? [] : ['className' => DestinosProductosTable::class];
        $this->DestinosProductos = TableRegistry::getTableLocator()->get('DestinosProductos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DestinosProductos);

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
