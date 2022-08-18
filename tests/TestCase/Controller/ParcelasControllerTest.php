<?php
namespace App\Test\TestCase\Controller;

use App\Controller\ParcelasController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\ParcelasController Test Case
 *
 * @uses \App\Controller\ParcelasController
 */
class ParcelasControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Parcelas',
        'app.Users',
        'app.Propietarios',
        'app.Lotes',
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
