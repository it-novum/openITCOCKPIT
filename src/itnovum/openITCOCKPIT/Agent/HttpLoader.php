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


use App\Model\Table\AgentconfigsTable;
use App\Model\Table\ProxiesTable;
use Cake\ORM\TableRegistry;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

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
        if (!isset($config['proxy'])) {
            $config['proxy'] = 1;
        }
        $this->config = $config;
        $this->hostaddress = $hostaddress;
    }

    private function buildConnectionOptions($config) {
        /** @var ProxiesTable $ProxiesTable */
        $ProxiesTable = TableRegistry::getTableLocator()->get('Proxies');
        $proxySettings = $ProxiesTable->getSettings();

        $this->guzzleProtocol = 'http';
        $this->guzzleOptions = [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'proxy'   => [
                'http'  => false,
                'https' => false
            ],
            'timeout' => 4
        ];

        if ($proxySettings['enabled'] === 1 && $config['proxy'] === 1) {
            $this->guzzleOptions['proxy'] = [
                'http'  => sprintf('%s:%s', $proxySettings['ipaddress'], $proxySettings['port']),
                'https' => sprintf('%s:%s', $proxySettings['ipaddress'], $proxySettings['port'])
            ];
        }

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

    private function updateAgentconfigProxy($configupdate) {
        /** @var $AgentconfigsTable AgentconfigsTable */
        $AgentconfigsTable = TableRegistry::getTableLocator()->get('Agentconfigs');

        if (isset($this->config['host_id'])) {
            $Agentconfig = $AgentconfigsTable->getConfigOrEmptyEntity($this->config['host_id']);
            $Agentconfig = $AgentconfigsTable->patchEntity($Agentconfig, $configupdate);
            $AgentconfigsTable->save($Agentconfig);
        }
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

        try {
            $response = $Client->request('GET', $url, $this->guzzleOptions);
        } catch (RequestException | GuzzleException $e) {
            try {
                $this->guzzleOptions['proxy'] = false;
                unset($response);
                $response = $Client->request('GET', $url, $this->guzzleOptions);
                $this->config['proxy'] = false;
                $this->updateAgentconfigProxy(['proxy' => $this->config['proxy']]);
            } catch (RequestException | GuzzleException $e) {

            }
        }

        $configResult = '';
        if ($checkConfig) {
            $configResponse = $Client->request('GET', $configUrl, $this->guzzleOptions);
            if ($configResponse->getStatusCode() === 200) {
                $configResult = json_decode($configResponse->getBody()->getContents(), true);
            }
        }

        if (isset($response) && $response->getStatusCode() !== 200) {
            return [
                'response' => null,
                'config'   => $configResult,
                'error'    => $response->getBody()->getContents(),
                'success'  => false
            ];
        } else if (!isset($response)) {
            return [
                'response' => null,
                'config'   => $configResult,
                'error'    => '',
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

    public function sendCertificateToAgent() {
        /** @var AgentCertificateData $AgentCertificateData */
        $AgentCertificateData = new AgentCertificateData();

        $this->buildConnectionOptions($this->config);
        $Client = new Client();
        $useSSL = false;

        try {
            $responseOne = $Client->get('http://' . $this->hostaddress . ':' . $this->config['port'] . '/getCsr', $this->guzzleOptions);
            $result = json_decode($responseOne->getBody()->getContents(), true);
        } catch (RequestException $e) {
            $this->updateGuzzleOptionsToUseSSL();
            $useSSL = true;

            $responseTwo = $Client->get('https://' . $this->hostaddress . ':' . $this->config['port'] . '/getCsr', $this->guzzleOptions);
            $result = json_decode($responseTwo->getBody()->getContents(), true);

            if ($result === false) {
                return false;
            }
        }

        try {
            if (isset($result['csr']) && $result['csr'] != "disabled") {
                $data_string = json_encode($AgentCertificateData->signAgentCsr($result['csr']));
                $this->guzzleOptions['json'] = $data_string;
                //$this->guzzleOptions['headers']['Content-Length'] = strlen($data_string);

                if ($useSSL) {
                    $responseThree = $Client->post('https://' . $this->hostaddress . ':' . $this->config['port'] . '/updateCrt', $this->guzzleOptions);
                } else {
                    $responseThree = $Client->post('http://' . $this->hostaddress . ':' . $this->config['port'] . '/updateCrt', $this->guzzleOptions);
                }
                $this->updateAgentconfigProxy(['use_https' => 1]);
            }
        } catch (\Exception $e) {
            if (!$e->getCode() == 0) {     //else: got no response; need to be fixed in a future version
                echo 'Error: ' . $e->getCode() . ' - ' . $e->getMessage();
            }
        }
    }
}
