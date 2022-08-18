<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ArreglosMecanicosController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\ArreglosMecanicosController Test Case
 *
 * @uses \App\Controller\ArreglosMecanicosController
 */
class ArreglosMecanicosControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ArreglosMecanicos',
        'app.Empresas',
        'app.Users',
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
