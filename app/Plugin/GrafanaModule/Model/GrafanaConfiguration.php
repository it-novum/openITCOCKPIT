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
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use itnovum\openITCOCKPIT\Grafana\GrafanaApiConfiguration;

class GrafanaConfiguration extends GrafanaModuleAppModel {

    public $hasMany = [
        'GrafanaModule.GrafanaConfigurationHostgroupMembership' => [
            'foreignKey' => 'configuration_id'
        ]
    ];

    public $validate = [
        'api_url'         => [
            'allowEmpty' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank',
                'required' => true,
            ],
        ],
        'api_key'         => [
            'allowEmpty' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank',
                'required' => true,
            ],
        ],
        'graphite_prefix' => [
            'allowEmpty' => [
                'rule'     => 'notBlank',
                'message'  => 'This field cannot be left blank',
                'required' => true,
            ],
        ],
    ];


    /*
    * Parse hostgroups array for grafana configuration
    * @param Array Hostgroup-Ids
    * @param Array Hostgroup-Ids exluded
    * @return filtered array in format ['hostgroup_id' => 1..n, 'exluded' => 0/1]
    */
    public function parseHostgroupMembershipData($hostgroups = [], $hostgroupsExluded = []) {
        $hostgroupMembershipsForGrafanaConfiguration = [];
        foreach ($hostgroups as $hostgroupId) {
            $hostgroupMembershipsForGrafanaConfiguration[] = [
                'hostgroup_id'     => $hostgroupId,
                'excluded'         => '0',
                'configuration_id' => 1
            ];
        }
        foreach ($hostgroupsExluded as $hostgroupId) {
            $hostgroupMembershipsForGrafanaConfiguration[] = [
                'hostgroup_id'     => $hostgroupId,
                'excluded'         => '1',
                'configuration_id' => 1
            ];
        }

        return $hostgroupMembershipsForGrafanaConfiguration;
    }

    /**
     * @param array $hostsUnfiltered
     * @param array $includedHostgroups
     * @param array $excludedHostgroups
     * @return array filtered host ids
     */
    public function filterResults($hostsUnfiltered, $includedHostgroups = [], $excludedHostgroups = []) {
        $filteredHostIds = [];
        if (empty($includedHostgroups) && empty($excludedHostgroups)) {
            return Hash::combine($hostsUnfiltered, '{n}.Host.id', '{n}.Host');
        }
        foreach ($hostsUnfiltered as $host) {
            if (empty($host['Hostgroup']) && empty($host['Hosttemplate']['Hostgroup'])) {
                continue;
            }
            $hostHostgroups = Hash::extract(
                (!empty($host['Hostgroup'])) ? $host['Hostgroup'] : $host['Hosttemplate']['Hostgroup'],
                (!empty($host['Hostgroup'])) ? '{n}.id' : 'Hosttemplate.Hostgroup.{n}.id'
            );
            if ($this->checkIntersectForIncludedHostgroups($hostHostgroups, $includedHostgroups) &&
                $this->checkIntersectForExcludedHostgroups($hostHostgroups, $excludedHostgroups)
            ) {
                $filteredHostIds[$host['Host']['id']] = $host['Host'];

            }
        }
        return $filteredHostIds;
    }

    /**
     * @param $hostHostgroups
     * @param $includedHostgroups
     * @return bool
     */
    public function checkIntersectForIncludedHostgroups($hostHostgroups, $includedHostgroups) {
        if (empty($includedHostgroups)) {
            return true;
        }
        return !empty(array_intersect($hostHostgroups, $includedHostgroups));
    }

    /**
     * @param $hostHostgroups
     * @param $excludedHostgroups
     * @return bool
     */
    public function checkIntersectForExcludedHostgroups($hostHostgroups, $excludedHostgroups) {
        if (empty($excludedHostgroups)) {
            return true;
        }
        return empty(array_intersect($hostHostgroups, $excludedHostgroups));
    }

    /**
     * @param $grafanaApiConfiguration
     * @param $proxySettings
     * @return Client|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testConnection($grafanaApiConfiguration, $proxySettings) {
        $options = [
            'headers' => [
                'authorization' => 'Bearer ' . $grafanaApiConfiguration->getApiKey()
            ],
            'verify'  => $grafanaApiConfiguration->isIgnoreSslCertificate()
        ];
        if ($grafanaApiConfiguration->isUseProxy() && !(empty($proxySettings['ipaddress']) & empty($proxySettings['port']))) {
            $options['proxy'] = [
                'http'  => sprintf('%s:%s', $proxySettings['ipaddress'], $proxySettings['port']),
                'https' => sprintf('%s:%s', $proxySettings['ipaddress'], $proxySettings['port'])
            ];
        } else {
            $options['proxy'] = [
                'http'  => false,
                'https' => false
            ];
        }
        $client = new Client($options);
        $request = new Request('GET', $grafanaApiConfiguration->getApiUrl() . '/org');

        try {
            $response = $client->send($request);
        } catch (Exception $e) {
            if ($e instanceof ClientException) {
                $response = $e->getResponse();
                $responseBody = $response->getBody()->getContents();
                return $responseBody;
            }
            return $e->getMessage();
        }

        if ($response->getStatusCode() == 200) {
            return $client;
        }
    }

    public function getDashboard($uuid) {
        $uuid = 'c36b8048-93ce-4385-ac19-ab5c90574b77';

        if (empty($uuid)) {
            return;
        }

        $grafanaConfiguration = $this->find('first', [
            'recursive' => -1,
            'contain'   => [
                'GrafanaConfigurationHostgroupMembership'
            ]
        ]);
        if (empty($grafanaConfiguration)) {
            $this->out('<error>No Grafana configuration found</error>');
        }
        $GrafanaApiConfiguration = GrafanaApiConfiguration::fromArray($grafanaConfiguration);

        $client = $this->testConnection($GrafanaApiConfiguration, []);

        $request = new Request('POST', $this->GrafanaApiConfiguration->getApiUrl() . '/dashboards/db', ['content-type' => 'application/json'], $uuid);

        try {
            $response = $this->client->send($request);
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
            $responseBody = $response->getBody()->getContents();
            $this->out('<error>' . $responseBody . '</error>');
        }

        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $response = json_decode($body->getContents());
            //debug($response);
        }
    }

    /**
     * @param GrafanaApiConfiguration $grafanaApiConfiguration
     * @param $proxySettings
     * @param $uid
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function existsUserDashboard(GrafanaApiConfiguration $grafanaApiConfiguration, $proxySettings, $uid) {
        $client = $this->testConnection($grafanaApiConfiguration, $proxySettings);
        $request = new \GuzzleHttp\Psr7\Request(
            'GET',
            sprintf('%s/dashboards/uid/%s', $grafanaApiConfiguration->getApiUrl(), $uid),
            ['content-type' => 'application/json']
        );

        try {
            $response = $client->send($request);
        } catch (\Exception $e) {
            debug($e->getMessage());
            return false;
        }

        if ($response->getStatusCode() == 200) {
            return true;
        }

        return false;
    }

}