<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ConstantesController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\ConstantesController Test Case
 *
 * @uses \App\Controller\ConstantesController
 */
class ConstantesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Constantes',
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
