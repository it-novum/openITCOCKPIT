<?php
declare(strict_types=1);

namespace DesignModule\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use DesignModule\Model\Table\DesignsTable;

/**
 * DesignModule\Model\Table\DesignsTable Test Case
 */
class DesignsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \DesignModule\Model\Table\DesignsTable
     */
    protected $Designs;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'plugin.DesignModule.Designs',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Designs') ? [] : ['className' => DesignsTable::class];
        $this->Designs = TableRegistry::getTableLocator()->get('Designs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Designs);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
