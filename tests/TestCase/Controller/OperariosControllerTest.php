<?php
namespace App\Test\TestCase\Controller;

use App\Controller\OperariosController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\OperariosController Test Case
 *
 * @uses \App\Controller\OperariosController
 */
class OperariosControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Operarios',
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
