<?php
declare(strict_types=1);

namespace ChangecalendarModule\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use ChangecalendarModule\Controller\ChangecalendarsController;

/**
 * ChangecalendarModule\Controller\ChangecalendarsController Test Case
 *
 * @uses \ChangecalendarModule\Controller\ChangecalendarsController
 */
class ChangecalendarsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'plugin.ChangecalendarModule.Changecalendars',
    ];

    /**
     * Test index method
     *
     * @return void
     * @uses \ChangecalendarModule\Controller\ChangecalendarsController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \ChangecalendarModule\Controller\ChangecalendarsController::view()
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \ChangecalendarModule\Controller\ChangecalendarsController::add()
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \ChangecalendarModule\Controller\ChangecalendarsController::edit()
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \ChangecalendarModule\Controller\ChangecalendarsController::delete()
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
