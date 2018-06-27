<?php
// Copyright (C) <2015>  <it-novum GmbH>
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

use GuzzleHttp\Client;
use \itnovum\openITCOCKPIT\Core\Interfaces\CronjobInterface;
use itnovum\openITCOCKPIT\Core\System\Health\StatisticsCollector;

/**
 * Class SystemMetricsTask
 *
 * Send anonymous statistics to report.openitcockpit.io
 *
 * @property Host $Host
 * @property Service $Service
 * @property Proxy $Proxy
 */
class SystemMetricsTask extends AppShell implements CronjobInterface {

    public $uses = [
        'Host',
        'Service',
        'Proxy'
    ];

    function execute($quiet = false) {
        $this->params['quiet'] = $quiet;
        $this->out('Sending anonymous statistic information...', false);

        $StatisticsCollector = new StatisticsCollector($this->Host, $this->Service);
        $dataToSend = $StatisticsCollector->getData();

        $params = [
            'form_params' => $dataToSend,
            'proxy'       => [
                'http'  => false,
                'https' => false
            ]
        ];

        $proxySettings = $this->Proxy->getSettings();
        if ($proxySettings['enabled']) {
            $params['proxy']['http'] = sprintf('%s:%s', $proxySettings['ipaddress'], $proxySettings['port']);
            $params['proxy']['https'] = $params['proxy']['http'];
        }

        $client = new Client();
        $response = $client->request('POST', 'https://packagemanager.it-novum.com/statistics/submit', $params);

        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException($response->getBody()->getContents());
        }


        $this->out('<green>   Ok</green>');
        $this->hr();

    }

}