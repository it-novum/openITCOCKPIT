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


// This form is used to validate the data from the Agent Wizard for \itnovum\openITCOCKPIT\Agent\AgentConfiguration
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * Class AgentConfigurationForm
 * @package App\Form
 */
class AgentConfigurationForm extends Form {
    protected function _buildSchema(Schema $schema): Schema {
        return $schema
            ->addField('bind_address', ['type' => 'string'])
            ->addField('username', ['type' => 'string'])
            ->addField('password', ['type' => 'string'])
            ->addField('push_oitc_server_url', ['type' => 'string'])
            ->addField('push_oitc_api_key', ['type' => 'string'])
            ->addField('use_http_basic_auth', ['type' => 'string'])
            ->addField('enable_push_mode', ['type' => 'boolean'])
            ->addField('bind_port', ['type' => 'integer'])
            ->addField('check_interval', ['type' => 'integer'])
            ->addField('push_timeout', ['type' => 'integer']);
    }

    public function validationDefault(Validator $validator): Validator {
        $validator
            ->requirePresence('bind_address')
            ->ip('bind_address');

        $validator
            ->scalar('username')
            ->maxLength('username', 255)
            ->requirePresence('username', false)
            ->allowEmptyString('username', __('Please enter a username.'), function ($context) {
                if (array_key_exists('use_http_basic_auth', $context['data'])) {
                    if($context['data']['use_http_basic_auth']){
                        //Basic auth is enabled
                        return false;
                    }
                }

                // Basic auth is disabled
                return true;
            });

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', false)
            ->allowEmptyString('password', __('Please enter a password.'), function ($context) {
                if (array_key_exists('use_http_basic_auth', $context['data'])) {
                    if($context['data']['use_http_basic_auth']){
                        //Basic auth is enabled
                        return false;
                    }
                }

                // Basic auth is disabled
                return true;
            });

        $validator
            ->scalar('push_oitc_server_url')
            ->requirePresence('push_oitc_server_url', false)
            ->allowEmptyString('push_oitc_server_url', __('Please enter a server URL.'), function ($context) {
                if (array_key_exists('enable_push_mode', $context['data'])) {
                    if($context['data']['enable_push_mode']){
                        //Agent is running in push mode
                        return false;
                    }
                }

                // Agent is running in pull mode
                return true;
            });

        $validator
            ->scalar('push_oitc_api_key')
            ->requirePresence('push_oitc_api_key', false)
            ->allowEmptyString('push_oitc_api_key', __('Please enter a API key.'), function ($context) {
                if (array_key_exists('enable_push_mode', $context['data'])) {
                    if($context['data']['enable_push_mode']){
                        //Agent is running in push mode
                        return false;
                    }
                }

                // Agent is running in pull mode
                return true;
            });

        $validator
            ->scalar('bind_port')
            ->integer('bind_port')
            ->range('bind_port', [1, 65535], __('The port numbers in the range from 1 to 65535'))
            ->notEmptyString('bind_port');

        $validator
            ->scalar('check_interval')
            ->integer('check_interval')
            ->range('check_interval', [1, 7200], __('The check interval has to be in range from 1 to 7200'))
            ->notEmptyString('check_interval');

        $validator
            ->scalar('push_timeout')
            ->integer('push_timeout')
            ->range('push_timeout', [1, 40], __('The push timeout has to be in range from 1 to 40'))
            ->notEmptyString('push_timeout');

        return $validator;
    }
}
