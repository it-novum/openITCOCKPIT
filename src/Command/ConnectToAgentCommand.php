<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
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

namespace App\Command;

use App\Model\Table\ProxiesTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use itnovum\openITCOCKPIT\Agent\AgentCertificateData;
use itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions;

/**
 * ConfigGeneratorShell command.
 */
class ConnectToAgentCommand extends Command {

    private array $guzzleOptions = [];
    private string $basicUsername = '';
    private string $basicPassword = '';
    private bool $noProxy = false;

    /**
     * @param ConsoleOptionParser $parser
     * @return ConsoleOptionParser
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser {
        $parser = parent::buildOptionParser($parser);

        $parser->addOptions([
            'ip'       => ['short' => 'i', 'help' => __d('oitc_console', 'IP of Host with the Agent to connect to')],
            'port'     => ['short' => 'p', 'help' => __d('oitc_console', 'Port of Host with the Agent to connect to')],
            'username' => ['help' => __d('oitc_console', 'Basic-Auth username from Agent')],
            'password' => ['help' => __d('oitc_console', 'Basic-Auth password from Agent')],
            'noproxy'  => ['boolean' => true, 'help' => __d('oitc_console', 'Disable proxy')],
        ]);

        return $parser;
    }

    private function setGuzzleOptions() {
        /** @var ProxiesTable $ProxiesTable */
        $ProxiesTable = TableRegistry::getTableLocator()->get('Proxies');
        $proxySettings = $ProxiesTable->getSettings();

        $this->guzzleOptions = [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'proxy'   => [
                'http'  => false,
                'https' => false
            ],
        ];

        if ($this->basicUsername !== '' && $this->basicPassword !== '') {
            $this->guzzleOptions['auth'] = [$this->basicUsername, $this->basicPassword];
        }

        if ($proxySettings['enabled'] == 1 && !$this->noProxy) {
            $this->guzzleOptions['proxy'] = [
                'http'  => sprintf('%s:%s', $proxySettings['ipaddress'], $proxySettings['port']),
                'https' => sprintf('%s:%s', $proxySettings['ipaddress'], $proxySettings['port'])
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
     * @param Arguments $args
     * @param ConsoleIo $io
     * @return int|void|null
     * @throws MissingParameterExceptions
     */
    public function execute(Arguments $args, ConsoleIo $io) {
        if ($args->getOption('ip') === '' || $args->getOption('port') === '') {
            throw new MissingParameterExceptions(__('Missing IP or Port of the Agent'));
        }

        /** @var AgentCertificateData $AgentCertificateData */
        $AgentCertificateData = new AgentCertificateData();

        $guzzleclient = new Client();
        $useSSL = false;
        $this->basicUsername = $args->getOption('username');
        $this->basicPassword = $args->getOption('password');
        if($args->getOption('noproxy')){
            $this->noProxy = true;
        }

        $this->setGuzzleOptions();

        try {
            $response = $guzzleclient->get('http://' . $args->getOption('ip') . ':' . $args->getOption('port') . '/getCsr', $this->guzzleOptions);
            $result = json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            $this->updateGuzzleOptionsToUseSSL();
            $useSSL = true;

            $response = $guzzleclient->get('https://' . $args->getOption('ip') . ':' . $args->getOption('port') . '/getCsr', $this->guzzleOptions);
            $result = json_decode($response->getBody()->getContents(), true);

            if ($result === false) {
                $io->abort(sprintf('Could not fetch csr from agent or invalid body: %i %s', $response->getStatusCode(), $response->getBody()), 3);
            }
        }

        try {
            if (isset($result['csr']) && $result['csr'] != "disabled") {
                $this->guzzleOptions['json'] = $AgentCertificateData->signAgentCsr($result['csr']);

                $response = null;
                if ($useSSL) {
                    $response = $guzzleclient->post('https://' . $args->getOption('ip') . ':' . $args->getOption('port') . '/updateCrt', $this->guzzleOptions);
                } else {
                    $response = $guzzleclient->post('http://' . $args->getOption('ip') . ':' . $args->getOption('port') . '/updateCrt', $this->guzzleOptions);
                }

                if ($response->getStatusCode() !== 200) {
                    $io->abort(sprintf('Error: could not update agent certificate: %s', $response->getBody()), 3);
                }
            }
        } catch (\Exception $e) {
            if (!$e->getCode() == 0) {     //else: got no response; need to be fixed in a future version
                $io->abort('Error: ' . $e->getCode() . ' - ' . $e->getMessage(), 3);
            }
        }

    }
}
