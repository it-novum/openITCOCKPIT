<?php
// Copyright (C) <2018>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace itnovum\openITCOCKPIT\Agent;


use GuzzleHttp\Client;

class HttpLoader {

    /**
     * @var array
     */
    private $config = [];

    /**
     * @var string
     */
    private $hostaddress = '';

    private $guzzleOptions = [];
    private $guzzleProtocol = 'http';

    /**
     * HttpLoader constructor.
     * @param array $config
     * @param string $hostaddress
     */
    public function __construct($config, $hostaddress) {
        $this->config = $config;
        $this->hostaddress = $hostaddress;
    }

    private function buildConnectionOptions($config) {
        $this->guzzleOptions = [];
        $this->guzzleProtocol = 'http';

        if ($config['use_https'] === 1) {
            $this->guzzleProtocol = 'https';
            $this->updateGuzzleOptionsToUseSSL();
        }

        if ($config['insecure'] === 1) {
            $this->guzzleOptions['verify'] = false;
        }

        if ($config['basic_auth'] === 1) {
            $this->guzzleOptions['auth'] = [
                $config['username'],
                $config['password']
            ];
        }
    }

    private function updateGuzzleOptionsToUseSSL() {
        /** @var AgentCertificateData $AgentCertificateData */
        $AgentCertificateData = new AgentCertificateData();

        //$this->guzzleOptions['verify'] = $AgentCertificateData->getCaCertPath();     //do i need this? CURLOPT_SSL_VERIFYHOST was disabled in curl version
        $this->guzzleOptions['verify'] = false;
        $this->guzzleOptions['cert'] = $AgentCertificateData->getCaCertPath();
        $this->guzzleOptions['ssl_key'] = $AgentCertificateData->getCaKeyPath();
    }

    /**
     * @param bool $checkConfig
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function queryAgent($checkConfig = false) {
        $Client = new Client();
        $config = $this->config;
        $this->buildConnectionOptions($config);

        $url = sprintf(
            '%s://%s:%s',
            $this->guzzleProtocol,
            $this->hostaddress,
            $config['port']
        );
        $configUrl = sprintf(
            '%s://%s:%s/config',
            $this->guzzleProtocol,
            $this->hostaddress,
            $config['port']
        );

        $response = $Client->request('GET', $url, $this->guzzleOptions);

        $configResult = '';
        if ($checkConfig) {
            $configResponse = $Client->request('GET', $configUrl, $this->guzzleOptions);
            if ($response->getStatusCode() === 200) {
                $configResult = json_decode($configResponse->getBody()->getContents(), true);
            }
        }

        if ($response->getStatusCode() !== 200) {
            return [
                'response' => null,
                'config'   => $configResult,
                'error'    => $response->getBody()->getContents(),
                'success'  => false
            ];
        }

        $agentOutput = json_decode($response->getBody()->getContents(), true);

        return [
            'response' => $agentOutput,
            'config'   => $configResult,
            'error'    => null,
            'success'  => true
        ];
    }

    public function updateAgentConfig($agentConfig) {
        $Client = new Client();
        $config = $this->config;
        $this->buildConnectionOptions($config);
        $this->guzzleOptions['json'] = [
            'config' => $agentConfig
        ];

        $url = sprintf(
            '%s://%s:%s/config',
            $this->guzzleProtocol,
            $this->hostaddress,
            $config['port']
        );

        $response = $Client->request('POST', $url, $this->guzzleOptions);
        if ($response->getStatusCode() !== 200) {
            return [
                'error'   => $response->getBody()->getContents(),
                'success' => false
            ];
        }

        return [
            'error'    => null,
            'success'  => true,
            'response' => json_decode($response->getBody()->getContents(), true)
        ];
    }

}
