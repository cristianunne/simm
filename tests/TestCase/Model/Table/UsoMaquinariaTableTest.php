<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsoMaquinariaTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsoMaquinariaTable Test Case
 */
class UsoMaquinariaTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsoMaquinariaTable
     */
    public $UsoMaquinaria;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UsoMaquinaria',
        'app.Users',
        'app.Maquinas',
        'app.Empresas',
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
        $config = TableRegistry::getTableLocator()->exists('UsoMaquinaria') ? [] : ['className' => UsoMaquinariaTable::class];
        $this->UsoMaquinaria = TableRegistry::getTableLocator()->get('UsoMaquinaria', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsoMaquinaria);

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
