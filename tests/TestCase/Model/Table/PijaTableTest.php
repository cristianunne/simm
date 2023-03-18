<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PijaTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PijaTable Test Case
 */
class PijaTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PijaTable
     */
    public $Pija;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Pija',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Pija') ? [] : ['className' => PijaTable::class];
        $this->Pija = TableRegistry::getTableLocator()->get('Pija', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Pija);

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
