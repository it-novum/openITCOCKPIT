<?php
declare(strict_types=1);

namespace App\Test\TestCase\Shell;

use App\Shell\RightsShell;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Shell\RightsShell Test Case
 */
class RightsShellTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * ConsoleIo mock
     *
     * @var \Cake\Console\ConsoleIo|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $io;

    /**
     * Test subject
     *
     * @var \App\Shell\RightsShell
     */
    protected $Rights;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->io = $this->getMockBuilder('Cake\Console\ConsoleIo')->getMock();
        $this->Rights = new RightsShell($this->io);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Rights);

        parent::tearDown();
    }

    /**
     * Test getOptionParser method
     *
     * @return void
     */
    public function testGetOptionParser(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test main method
     *
     * @return void
     */
    public function testMain(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
