<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TableConfigs;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TableConfigs Test Case
 */
class TableConfigsTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TableConfigs
     */
    protected $TableConfigs;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Configs') ? [] : ['className' => TableConfigs::class];
        $this->TableConfigs = $this->getTableLocator()->get('Configs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->TableConfigs);

        parent::tearDown();
    }
}
