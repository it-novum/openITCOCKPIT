<?php
declare(strict_types=1);

namespace MSTeamsModule\Test\TestCase\Model\Table;

use Cake\TestSuite\TestCase;
use MSTeamsModule\Model\Table\MsteamsSettingsTable;

/**
 * MSTeamsModule\Model\Table\MsteamsSettingsTable Test Case
 */
class MsteamsSettingsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \MSTeamsModule\Model\Table\MsteamsSettingsTable
     */
    protected $MsteamsSettings;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'plugin.MSTeamsModule.MsteamsSettings',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('MsteamsSettings') ? [] : ['className' => MsteamsSettingsTable::class];
        $this->MsteamsSettings = $this->getTableLocator()->get('MsteamsSettings', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->MsteamsSettings);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \MSTeamsModule\Model\Table\MsteamsSettingsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
