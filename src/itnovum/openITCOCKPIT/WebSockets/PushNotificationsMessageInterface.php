<?php
// Copyright (C) <2015>  <it-novum GmbH>
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

namespace itnovum\openITCOCKPIT\WebSockets;


use App;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use GearmanWorker;
use itnovum\openITCOCKPIT\Core\PushNotificationClientRepository;
use itnovum\openITCOCKPIT\Core\UUID;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

/**
 * Class PushNotificationsMessageInterface
 * @package itnovum\openITCOCKPIT\Core\System
 */
class PushNotificationsMessageInterface implements MessageComponentInterface {

    /**
     * @var \SplObjectStorage
     */
    private $clients;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var GearmanWorker
     */
    private $Worker;

    /**
     * @var PushNotificationClientRepository
     */
    private $ClientsRepository;

    /**
     * PushNotificationsMessageInterface constructor.
     */
    public function __construct() {
        /** @var App\Model\Table\SystemsettingsTable $SystemsettingsTable */
        $SystemsettingsTable = TableRegistry::getTableLocator()->get('Systemsettings');
        $systemsettings = $SystemsettingsTable->findAsArray();

        $this->apiKey = $systemsettings['SUDO_SERVER']['SUDO_SERVER.API_KEY'];

        $this->clients = new \SplObjectStorage;

        $this->Worker = $this->getWorker();

        $this->ClientsRepository = new PushNotificationClientRepository();
    }

    /**
     * @return GearmanWorker
     */
    public function getWorker() {
        Configure::load('gearman');
        $config = Configure::read('gearman');

        $worker = new \GearmanWorker();
        $worker->addServer($config['address'], $config['port']);

        $worker->addFunction('oitc_push_notifications', [$this, 'processNotification']);
        $worker->addOptions(GEARMAN_WORKER_NON_BLOCKING);

        return $worker;
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn) {
        //debug($conn->resourceId);
        $uuid = UUID::v4();
        $conn->uuid = $uuid;
        $this->clients->attach($conn);
        $conn->send(json_encode([
            'type'    => 'connection',
            'message' => 'Connection established successfully',
            'data'    => [
                'uuid' => $uuid
            ]
        ]));
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn) {
        $this->ClientsRepository->removeClientByUuid($conn->uuid);
        $this->clients->detach($conn);
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
        $this->clients->detach($conn);
    }

    /**
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg) {
        $message = json_decode($msg, true);

        if (isset($message['key']) && isset($message['task']) && isset($message['uuid'])) {
            if ($message['key'] !== $this->apiKey) {
                $from->close();
                $this->clients->detach($from);
                return;
            }

            if ($message['task'] === 'register') {
                if (!isset($message['data']['userId']) || !isset($message['data']['browserUuid'])) {
                    $from->close();
                    $this->clients->detach($from);
                    return;
                }
                $this->ClientsRepository->addClient(
                    $message['data']['userId'],
                    $message['data']['browserUuid'],
                    $message['uuid']
                );
            }

            if ($message['task'] === 'keepAlive') {
                $from->send(json_encode([
                    'type'    => 'keepAlive',
                    'message' => 'Pong',
                    'data'    => []
                ]));
            }
        }
    }

    /**
     * @param $message
     */
    public function send($userId, $message) {
        foreach ($this->ClientsRepository->getUniqueClientsForNotificationByUserId($userId) as $clientMetaData) {
            foreach ($this->clients as $client) {
                if ($client->uuid === $clientMetaData['uuid']) {
                    $client->send($message);
                }
            }
        }
    }

    public function eventLoop() {
        @$this->Worker->work();
    }

    /**
     * @param \GearmanJob $job
     */
    public function processNotification($job) {
        $notification = json_decode($job->workload(), true);
        if (!isset($notification['timestamp']) || !isset($notification['userId'])) {
            return;
        }

        //Drop notifications that are in the queue for >= 10 minutes
        if ($notification['timestamp'] < (time() - 60 * 10)) {
            return;
        }

        //Drop messages where the client is not connected
        $userId = (int)$notification['userId'];
        if (!$this->ClientsRepository->hasUserIdConnectedClients($userId)) {
            return;
        }

        $this->send($userId, json_encode([
            'type'    => 'message',
            'message' => $notification['message'],
            'data'    => $notification
        ]));
    }

}
