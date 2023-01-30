<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AnalisisCostosTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AnalisisCostosTable Test Case
 */
class AnalisisCostosTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AnalisisCostosTable
     */
    public $AnalisisCostos;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AnalisisCostos',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AnalisisCostos') ? [] : ['className' => AnalisisCostosTable::class];
        $this->AnalisisCostos = TableRegistry::getTableLocator()->get('AnalisisCostos', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AnalisisCostos);

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
