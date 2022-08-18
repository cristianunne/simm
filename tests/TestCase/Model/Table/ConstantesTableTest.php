<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConstantesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConstantesTable Test Case
 */
class ConstantesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ConstantesTable
     */
    public $Constantes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Constantes',
        'app.Users',
        'app.Empresas',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Constantes') ? [] : ['className' => ConstantesTable::class];
        $this->Constantes = TableRegistry::getTableLocator()->get('Constantes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Constantes);

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
