<?php
// Copyright (C) <2024>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

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
