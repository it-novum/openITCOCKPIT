<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WizardAssignmentsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WizardAssignmentsTable Test Case
 */
class WizardAssignmentsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\WizardAssignmentsTable
     */
    protected $WizardAssignments;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.WizardAssignments',
        'app.Types',
        'app.ServicetemplatesToWizardAssignments',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('WizardAssignments') ? [] : ['className' => WizardAssignmentsTable::class];
        $this->WizardAssignments = $this->getTableLocator()->get('WizardAssignments', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->WizardAssignments);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
