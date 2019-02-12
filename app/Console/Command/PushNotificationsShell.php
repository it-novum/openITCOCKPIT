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

use itnovum\openITCOCKPIT\Core\System\PushNotificationsMessageInterface;
use itnovum\openITCOCKPIT\Ratchet\Overwrites\HttpServerSize;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\Socket\Server as Reactor;

class PushNotificationsShell extends AppShell {

    public function main() {
        $this->out('Starting push notifications service');
        $this->stdout->styles('blue', ['text' => 'blue']);
        $this->out('<blue>Exit with [STRG] + [C]</blue>');

        $this->fireUpWebSocketServer();
    }


    private function fireUpWebSocketServer() {
        $MessageInterface = new PushNotificationsMessageInterface($this);

        $loop = React\EventLoop\Factory::create();
        $loop->addPeriodicTimer(0.01, [$MessageInterface, 'eventLoop']);

        $Server = new IoServer(
            new HttpServerSize(
                new WsServer($MessageInterface)
            ),
            new Reactor(sprintf('%s:%s', '0.0.0.0', 8083), $loop),
            $loop
        );

        try {
            $Server->run();
        } catch (Exception $e) {
            debug($e);
        }

    }
}
