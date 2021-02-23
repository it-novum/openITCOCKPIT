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

use App\Model\Entity\Agentconfig;
use App\Model\Table\AgentconfigsTable;
use App\Model\Table\ProxiesTable;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use itnovum\openITCOCKPIT\Agent\AgentCertificateData;
use itnovum\openITCOCKPIT\Agent\AgentConfiguration;
use itnovum\openITCOCKPIT\ApiShell\Exceptions\MissingParameterExceptions;

/**
 * Class AgentCommand
 * @package App\Command
 */
class AgentCommand extends Command {

    /**
     * @param ConsoleOptionParser $parser
     * @return ConsoleOptionParser
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser {
        $parser = parent::buildOptionParser($parser);

        $parser->addOption('migrate', ['help' => 'Migrate Agent 1.x database records for Agent 3.x', 'boolean' => true]);

        return $parser;
    }

    /**
     * @param Arguments $args
     * @param ConsoleIo $io
     * @return int|void|null
     */
    public function execute(Arguments $args, ConsoleIo $io) {
        if ($args->getOption('migrate')) {

            /** @var AgentconfigsTable $AgentconfigsTable */
            $AgentconfigsTable = TableRegistry::getTableLocator()->get('Agentconfigs');

            foreach ($AgentconfigsTable->getOldConfigsThatNeedsMigration() as $record) {
                /** @var Agentconfig $record */
                $AgentConfiguration = new AgentConfiguration();
                $config = $AgentConfiguration->unmarshal('');

                // Migrate old config from agent 1.x to 3.x
                $config['int']['bind_port'] = (int)$record->port;
                $config['bool']['use_http_basic_auth'] = $record->basic_auth;
                $config['string']['username'] = $record->username;
                $config['string']['password'] = $record->password;
                $config['bool']['use_proxy'] = $record->proxy;
                $config['bool']['enable_push_mode'] = false;
                $autosslSuccessful = false;
                if ($record->push_noticed) {
                    $config['bool']['enable_push_mode'] = true;
                }

                if ($config['bool']['enable_push_mode'] === false) {
                    $config['bool']['use_autossl'] = true;
                    $autosslSuccessful = true;
                }

                $data = [
                    'port'               => $config['int']['bind_port'],
                    'basic_auth'         => $config['bool']['use_http_basic_auth'],
                    'username'           => $config['bool']['use_http_basic_auth'] ? $config['string']['username'] : '',
                    'password'           => $config['bool']['use_http_basic_auth'] ? $config['string']['password'] : '',
                    'proxy'              => $config['bool']['use_proxy'],
                    'insecure'           => true, // Validate TLS certificate in PULL mode
                    'use_https'          => false, // Use own TLS certificate for the agent like Let's Encrypt
                    'use_autossl'        => $config['bool']['use_autossl'], // New field with agent 3.x
                    'use_push_mode'      => $config['bool']['enable_push_mode'], // New field with agent 3.x
                    'autossl_successful' => $autosslSuccessful, // New field with agent 3.x
                    'config'             => $AgentConfiguration->marshal(), // New field with agent 3.x
                ];

                $record = $AgentconfigsTable->patchEntity($record, $data);
                $AgentconfigsTable->save($record);
            }

            exit(0);
        }


    }
}
