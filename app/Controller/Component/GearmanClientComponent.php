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

class GearmanClientComponent extends Component
{

    public $client = null;

    public function initialize(Controller $controller)
    {
        $this->Controller = $controller;
        Configure::load('gearman');
        $this->Config = Configure::read('gearman');

        $this->client = new GearmanClient();
        $this->client->addServer($this->Config['address'], $this->Config['port']);
    }

    public function send($task, $payload = [])
    {
        $payload['task'] = $task;
        $payload = serialize($payload);

        if ($this->Config['encryption'] === true) {
            $result = $this->client->do('oitc_gearman', Security::cipher($payload, $this->Config['password']));
        } else {
            $result = $this->client->do('oitc_gearman', $payload);
        }

        $result = @unserialize($result);
        if (!is_array($result)) {
            return 'Corrupt data';
        }

        return $result;
    }

    public function sendBackground($task, $payload = [])
    {
        $payload['task'] = $task;
        $payload = serialize($payload);

        if ($this->Config['encryption'] === true) {
            $result = $this->client->doBackground('oitc_gearman', Security::cipher($payload, $this->Config['password']));
        } else {
            $result = $this->client->doBackground('oitc_gearman', $payload);
        }

        $result = @unserialize($result);
        if (!is_array($result)) {
            return 'Corrupt data';
        }

        return $result;
    }
}
