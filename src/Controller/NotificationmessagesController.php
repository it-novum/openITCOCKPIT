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

namespace App\Controller;

use App\Model\Table\NotificationMessagesTable;
use Cake\ORM\TableRegistry;

class NotificationmessagesController extends AppController {

    public function index() {
        if (!$this->isAngularJsRequest()) {
            $this->set('message', 'hallo_ message');
            return;
        }
        /** @var $NotificationMessageTable NotificationMessagesTable */
        $NotificationMessageTable = TableRegistry::getTableLocator()->get('NotificationMessages');
        if($this->request->is('get')){
            $messages = $NotificationMessageTable->find('all');
        }

        $this->set('messages', $messages);

        $this->viewBuilder()->setOption('serialize', ['messages']);
    }

    public function add() {
        if (!$this->isApiRequest()) {
            //Only ship HTML template for angular
            return;
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            /** @var $NotificationMessageTable NotificationMessagesTable */
            $NotificationMessageTable = TableRegistry::getTableLocator()->get('NotificationMessages');
            $notification_message = $NotificationMessageTable->newEntity($data);
            $NotificationMessageTable->save($notification_message);
            if ($notification_message->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set('error', $notification_message->getErrors());
                $this->viewBuilder()->setOption('serialize', ['error']);
                return;
            }
            $this->set('notification', $notification_message);
            $this->viewBuilder()->setOption('serialize', ['notification']);


        }

//        $this->set('add', 'add_message');
//        $this->viewBuilder()->setOption('serialize', ['add']);
    }


}

