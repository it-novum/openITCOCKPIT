<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConfigurationFilesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConfigurationFilesTable Test Case
 */
class ConfigurationFilesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ConfigurationFilesTable
     */
    public $ConfigurationFiles;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ConfigurationFiles'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ConfigurationFiles') ? [] : ['className' => ConfigurationFilesTable::class];
        $this->ConfigurationFiles = TableRegistry::getTableLocator()->get('ConfigurationFiles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ConfigurationFiles);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
