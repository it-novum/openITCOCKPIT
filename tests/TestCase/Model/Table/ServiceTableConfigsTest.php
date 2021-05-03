<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ServiceTableConfigs;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ServiceTableConfigs Test Case
 */
class ServiceTableConfigsTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ServiceTableConfigs
     */
    protected $ServiceTableConfigs;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ServiceConfigs') ? [] : ['className' => ServiceTableConfigs::class];
        $this->ServiceTableConfigs = $this->getTableLocator()->get('ServiceConfigs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->ServiceTableConfigs);

        parent::tearDown();
    }
}
