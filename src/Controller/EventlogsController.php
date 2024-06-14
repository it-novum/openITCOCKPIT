<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//     This program is free software: you can redistribute it and/or modify
//     it under the terms of the GNU General Public License as published by
//     the Free Software Foundation, version 3 of the License.
//
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//     If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//     under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//     License agreement and license key will be shipped with the order
//     confirmation.

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

declare(strict_types=1);

namespace App\Controller;

use App\itnovum\openITCOCKPIT\Filter\EventlogsFilter;
use App\Model\Table\EventlogsTable;
use Cake\ORM\TableRegistry;
use itnovum\openITCOCKPIT\Core\ValueObjects\User;
use itnovum\openITCOCKPIT\Database\PaginateOMat;

class EventlogsController extends AppController {

    public function index() {
        if (!$this->isApiRequest()) {
            //Only ship HTML Template
            return;
        }

        /** @var EventlogsTable $EventlogsTable */
        $EventlogsTable = TableRegistry::getTableLocator()->get('Eventlogs');

        $EventlogsFilter = new EventlogsFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $EventlogsFilter->getPage());
        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges === true) {
            $MY_RIGHTS = [];
        }

        $logTypes = $this->request->getQuery('types', []);

        if (!is_array($logTypes)) {
            $logTypes = [$logTypes];
        }

        $all_events = [];
        if (!empty($logTypes)) {
            $all_events = $EventlogsTable->getEventlogIndex($EventlogsFilter, $logTypes, $PaginateOMat, $MY_RIGHTS, false);
        }

        $User = new User($this->getUser());
        $UserTime = $User->getUserTime();

        foreach ($all_events as $index => $event) {
            $changeTimestamp = $event['created']->getTimestamp();
            $all_events[$index]['time'] = $UserTime->format($changeTimestamp);
            $all_events[$index]['recordExists'] = $EventlogsTable->recordExists($event['model'], $event['object_id']);
            json_decode($all_events[$index]['data'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $all_events[$index]['data'] = json_decode($all_events[$index]['data'], true);
            }
        }

        $this->set('all_events', $all_events);
        $this->set('logTypes', $logTypes);
        $this->viewBuilder()->setOption('serialize', ['all_events', 'logTypes']);
    }

    public function listToPdf() {

        /** @var EventlogsTable $EventlogsTable */
        $EventlogsTable = TableRegistry::getTableLocator()->get('Eventlogs');

        $EventlogsFilter = new EventlogsFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $EventlogsFilter->getPage());
        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges === true) {
            $MY_RIGHTS = [];
        }

        $logTypes = $this->request->getQuery('types', []);

        if (!is_array($logTypes)) {
            $logTypes = [$logTypes];
        }

        $all_events = [];
        if (!empty($logTypes)) {
            $all_events = $EventlogsTable->getEventlogIndex($EventlogsFilter, $logTypes, $PaginateOMat, $MY_RIGHTS, false);
        }

        $User = new User($this->getUser());
        $UserTime = $User->getUserTime();

        foreach ($all_events as $index => $event) {
            $changeTimestamp = $event['created']->getTimestamp();
            $all_events[$index]['time'] = $UserTime->format($changeTimestamp);
            $all_events[$index]['recordExists'] = $EventlogsTable->recordExists($event['model'], $event['object_id']);
            json_decode($all_events[$index]['data'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $all_events[$index]['data'] = json_decode($all_events[$index]['data'], true);
            }
        }

        $this->set('all_events', $all_events);
        $this->set('logTypes', $logTypes);

        $this->viewBuilder()->setOption(
            'pdfConfig',
            [
                'download' => true,
                'filename' => __('Eventlogs_') . date('dmY_his') . '.pdf',
            ]
        );
    }

    public function listToCsv() {

        /** @var EventlogsTable $EventlogsTable */
        $EventlogsTable = TableRegistry::getTableLocator()->get('Eventlogs');

        $EventlogsFilter = new EventlogsFilter($this->request);
        $PaginateOMat = new PaginateOMat($this, $this->isScrollRequest(), $EventlogsFilter->getPage());
        $MY_RIGHTS = $this->MY_RIGHTS;
        if ($this->hasRootPrivileges === true) {
            $MY_RIGHTS = [];
        }

        $logTypes = $this->request->getQuery('types', []);

        if (!is_array($logTypes)) {
            $logTypes = [$logTypes];
        }

        $events = [];
        if (!empty($logTypes)) {
            $events = $EventlogsTable->getEventlogIndex($EventlogsFilter, $logTypes, $PaginateOMat, $MY_RIGHTS, false);
        }

        $User = new User($this->getUser());
        $UserTime = $User->getUserTime();

        $all_events = [];

        foreach ($events as $index => $event) {
            $changeTimestamp = $event['created']->getTimestamp();
            $events[$index]['time'] = $UserTime->format($changeTimestamp);
            $recordExists = $EventlogsTable->recordExists($event['model'], $event['object_id']);
            json_decode($event['data'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $events[$index]['data'] = json_decode($event['data'], true);
            }

            $all_events[$index] = [
                $event['type']
            ];

            if (in_array('login', $logTypes)) {
                if ($recordExists) {
                    $all_events[$index][] = $event['full_name'];
                    $all_events[$index][] = $event['user']['email'];
                } else {
                    $all_events[$index][] = $events[$index]['data']['full_name'];
                    $all_events[$index][] = $events[$index]['data']['user_email'];
                }
            }
            $all_events[$index][] = $events[$index]['time'];

        }

        $header = [
            'event_type',
        ];

        if (in_array('login', $logTypes)) {
            $header[] = 'full_name';
            $header[] = 'user_email';
        }

        $header[] = 'time';

        $this->set('data', $all_events);

        $filename = __('Eventlogs_') . date('dmY_his') . '.csv';
        $this->setResponse($this->getResponse()->withDownload($filename));
        $this->viewBuilder()
            ->setClassName('CsvView.Csv')
            ->setOptions([
                'delimiter' => ';', // Excel prefers ; over ,
                'bom'       => true, // Fix UTF-8 umlauts in Excel
                'serialize' => 'data',
                'header'    => $header,
            ]);
    }

}
