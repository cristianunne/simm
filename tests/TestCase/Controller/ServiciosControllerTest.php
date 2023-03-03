<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ServiciosController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\ServiciosController Test Case
 *
 * @uses \App\Controller\ServiciosController
 */
class ServiciosControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Servicios',
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
