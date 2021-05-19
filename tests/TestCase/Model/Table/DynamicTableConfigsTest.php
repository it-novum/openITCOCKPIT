<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DynamicTableConfigs;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DynamicTableConfigs Test Case
 */
class DynamicTableConfigsTest extends TestCase {
    /**
     * Test subject
     *
     * @var \App\Model\Table\DynamicTableConfigs
     */
    protected $DynamicTableConfigs;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void {
        parent::setUp();
        $config = $this->getTableLocator()->exists('DynamicConfigs') ? [] : ['className' => DynamicTableConfigs::class];
        $this->DynamicTableConfigs = $this->getTableLocator()->get('DynamicConfigs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void {
        unset($this->DynamicTableConfigs);

        parent::tearDown();
    }
}
