<?php
namespace App\Test\TestCase\Controller;

use App\Controller\RemitosController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\RemitosController Test Case
 *
 * @uses \App\Controller\RemitosController
 */
class RemitosControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Remitos',
        'app.Users',
        'app.Empresas',
    ];

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
