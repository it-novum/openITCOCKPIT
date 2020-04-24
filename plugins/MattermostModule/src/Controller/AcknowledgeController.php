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

declare(strict_types=1);

namespace MattermostModule\Controller;

use App\Model\Table\HostsTable;
use App\Model\Table\ServicesTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\FileDebugger;
use itnovum\openITCOCKPIT\Core\System\Gearman;

/**
 * Class AcknowledgeController
 * @package MattermostModule\Controller
 */
class AcknowledgeController extends AppController {

    public function host() {
        $hostUuid = $this->request->getQuery('uuid', null);
        if ($hostUuid === null) {
            throw new BadRequestException('Missing query parameter uuid');
        }

        $data = $this->request->getData();
        if (!isset($data['user_name']) || !isset($data['post_id'])) {
            throw new BadRequestException('Missing data in Webhook payload.');
        }

        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');

        //Check if the host exists
        try {
            $host = $HostsTable->getHostByUuid($hostUuid);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException('No host with given uuid found.');
        }

        $GearmanClient = new Gearman();

        $GearmanClient->sendBackground('cmd_external_command', [
            'command'     => 'ACKNOWLEDGE_HOST_PROBLEM',
            'parameters'  => [
                'hostUuid'   => $hostUuid,
                'sticky'     => 1,
                'notify'     => 0, // do not enable - loop!
                'persistent' => 1,
                'author'     => $data['user_name'],
                'comment'    => __('Issue got acknowledged by {0} via Mattermost.', $data['user_name']),
            ],
            'satelliteId' => null
        ]);

        //Update Mattermost message
        $update = [
            'message' => __('Issue got acknowledged by **@{0}** via Mattermost.', $data['user_name'])
        ];

        $this->set('update', $update);
        $this->viewBuilder()->setOption('serialize', [
            'update'
        ]);
    }

    public function service() {
        $hostUuid = $this->request->getQuery('hostuuid', null);
        $serviceUuid = $this->request->getQuery('serviceuuid', null);


        if ($hostUuid === null || $serviceUuid === null) {
            throw new BadRequestException('Missing query parameter uuid');
        }

        $data = $this->request->getData();
        if (!isset($data['user_name']) || !isset($data['post_id'])) {
            throw new BadRequestException('Missing data in Webhook payload.');
        }

        /** @var HostsTable $HostsTable */
        $HostsTable = TableRegistry::getTableLocator()->get('Hosts');
        /** @var ServicesTable $ServicesTable */
        $ServicesTable = TableRegistry::getTableLocator()->get('Services');

        //Check if the host exists
        try {
            $host = $HostsTable->getHostByUuid($hostUuid);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException('No host with given uuid found.');
        }

        //Check if the service exists
        try {
            $service = $ServicesTable->getServiceByUuid($serviceUuid);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException('No service with given uuid found.');
        }

        $GearmanClient = new Gearman();

        $GearmanClient->sendBackground('cmd_external_command', [
            'command'     => 'ACKNOWLEDGE_SVC_PROBLEM',
            'parameters'  => [
                'hostUuid'    => $hostUuid,
                'serviceUuid' => $serviceUuid,
                'sticky'      => 1,
                'notify'      => 0, // do not enable - loop!
                'persistent'  => 1,
                'author'      => $data['user_name'],
                'comment'     => __('Issue got acknowledged by {0} via Mattermost.', $data['user_name']),
            ],
            'satelliteId' => null
        ]);

        //Update Mattermost message
        $update = [
            'message' => __('Issue got acknowledged by **@{0}** via Mattermost.', $data['user_name'])
        ];

        $this->set('update', $update);
        $this->viewBuilder()->setOption('serialize', [
            'update'
        ]);
    }

}
