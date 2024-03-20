<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\SystemHealthUsersController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\SystemHealthUsersController Test Case
 *
 * @uses \App\Controller\SystemHealthUsersController
 */
class SystemHealthUsersControllerTest extends TestCase {
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.SystemHealthUsers',
    ];

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\SystemHealthUsersController::index()
     */
    public function testIndex(): void {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\SystemHealthUsersController::view()
     */
    public function testView(): void {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\SystemHealthUsersController::add()
     */
    public function testAdd(): void {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\SystemHealthUsersController::edit()
     */
    public function testEdit(): void {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\SystemHealthUsersController::delete()
     */
    public function testDelete(): void {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
