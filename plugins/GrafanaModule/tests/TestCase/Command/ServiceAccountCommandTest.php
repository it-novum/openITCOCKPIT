<?php
declare(strict_types=1);

namespace GrafanaModule\Test\TestCase\Command;

use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;
use GrafanaModule\Command\ServiceAccountCommand;

/**
 * GrafanaModule\Command\ServiceAccountCommand Test Case
 *
 * @uses \GrafanaModule\Command\ServiceAccountCommand
 */
class ServiceAccountCommandTest extends TestCase
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
     * @uses \GrafanaModule\Command\ServiceAccountCommand::buildOptionParser()
     */
    public function testBuildOptionParser(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test execute method
     *
     * @return void
     * @uses \GrafanaModule\Command\ServiceAccountCommand::execute()
     */
    public function testExecute(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
