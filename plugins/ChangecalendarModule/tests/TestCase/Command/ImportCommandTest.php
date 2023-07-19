<?php
declare(strict_types=1);

namespace ChangecalendarModule\Test\TestCase\Command;

use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;
use ChangecalendarModule\Command\ImportCommand;

/**
 * ChangecalendarModule\Command\ImportCommand Test Case
 *
 * @uses \ChangecalendarModule\Command\ImportCommand
 */
class ImportCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
    }
    /**
     * Test buildOptionParser method
     *
     * @return void
     * @uses \ChangecalendarModule\Command\ImportCommand::buildOptionParser()
     */
    public function testBuildOptionParser(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test execute method
     *
     * @return void
     * @uses \ChangecalendarModule\Command\ImportCommand::execute()
     */
    public function testExecute(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
