<?php
namespace App\Test\TestCase\Controller;

use App\Controller\OperariosMaquinasController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\OperariosMaquinasController Test Case
 *
 * @uses \App\Controller\OperariosMaquinasController
 */
class OperariosMaquinasControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.OperariosMaquinas',
        'app.Operarios',
        'app.Maquinas',
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
