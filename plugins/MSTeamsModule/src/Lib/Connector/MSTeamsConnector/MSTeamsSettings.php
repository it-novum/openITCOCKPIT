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

namespace MSTeamsModule\Lib\Connector\MSTeamsConnector;

use App\Model\Table\SystemsettingsTable;
use Cake\ORM\TableRegistry;
use MSTeamsModule\Model\Table\MsteamsSettingsTable;

final class MSTeamsSettings {
    /** @var string */
    public $url;

    /** @var string */
    public $apiKey;

    /** @var bool */
    public $useProxy;

    /** @var string */
    public $oitcUrl;

    /**
     * I will solely build the Credentials object.
     * @return self
     */
    public static function fetch(): self {
        /** @var MsteamsSettingsTable $MSTeamsSettingsTable */
        $MSTeamsSettingsTable = TableRegistry::getTableLocator()->get('MSTeamsModule.MsteamsSettings');
        $teamsSettings = $MSTeamsSettingsTable
            ->find()
            ->where([
                'id' => 1
            ])
            ->firstOrFail();


        /** @var SystemsettingsTable $SystemSettingsTable */
        $SystemSettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $result = $SystemSettingsTable->getSystemsettingByKey('SYSTEM.ADDRESS');

        $credentials = new self();
        $credentials->url = $teamsSettings->webhook_url;
        $credentials->apiKey = $teamsSettings->apikey;
        $credentials->useProxy = $teamsSettings->use_proxy;
        $credentials->oitcUrl = sprintf('https://%s', $result->get('value'));
        return $credentials;
    }
}