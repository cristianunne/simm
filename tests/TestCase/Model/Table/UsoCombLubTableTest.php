<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsoCombLubTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsoCombLubTable Test Case
 */
class UsoCombLubTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsoCombLubTable
     */
    public $UsoCombLub;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UsoCombLub',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UsoCombLub') ? [] : ['className' => UsoCombLubTable::class];
        $this->UsoCombLub = TableRegistry::getTableLocator()->get('UsoCombLub', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UsoCombLub);

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
