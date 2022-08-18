<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ListaConstantesController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\ListaConstantesController Test Case
 *
 * @uses \App\Controller\ListaConstantesController
 */
class ListaConstantesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ListaConstantes',
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
