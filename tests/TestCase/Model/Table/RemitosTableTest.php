<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RemitosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RemitosTable Test Case
 */
class RemitosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\RemitosTable
     */
    public $Remitos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Remitos',
        'app.Users',
        'app.Worksgroups',
        'app.Propietarios',
        'app.Parcelas',
        'app.Productos',
        'app.Destinos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Remitos') ? [] : ['className' => RemitosTable::class];
        $this->Remitos = TableRegistry::getTableLocator()->get('Remitos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Remitos);

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
