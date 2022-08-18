<?php
namespace App\Test\TestCase\Controller;

use App\Controller\DestinosProductosController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\DestinosProductosController Test Case
 *
 * @uses \App\Controller\DestinosProductosController
 */
class DestinosProductosControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.DestinosProductos',
        'app.Destinos',
        'app.Productos',
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
