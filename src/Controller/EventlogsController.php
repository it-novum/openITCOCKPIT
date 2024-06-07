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

        if (getType($logTypes) === 'string') {
            $logTypes = [$logTypes];
        }

        $logTypeQueriesArray = $EventlogsTable->getQueriesByTypes($logTypes);
        $tableColumns = $this->getFrontendTableColumnsByTypes($logTypes);

        if (!empty($logTypes)) {
            $all_events = $EventlogsTable->getEventlogIndex($EventlogsFilter, $PaginateOMat, $MY_RIGHTS, $logTypeQueriesArray, false);
        } else {
            $all_events = [];
        }

        $User = new User($this->getUser());
        $UserTime = $User->getUserTime();

        foreach ($all_events as $index => $event) {
            $changeTimestamp = $event['created']->getTimestamp();
            $all_events[$index]['time'] = $UserTime->format($changeTimestamp);
            $all_events[$index]['recordExists'] = $EventlogsTable->recordExists($event['model'], $event['object_id']);
            if (json_validate($all_events[$index]['data'])) {
                $all_events[$index]['data'] = json_decode($all_events[$index]['data'], true);
            }
        }

        $this->set('all_events', $all_events);
        $this->set('tableColumns', $tableColumns);
        $this->viewBuilder()->setOption('serialize', ['all_events', 'tableColumns']);
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

        if (getType($logTypes) === 'string') {
            $logTypes = [$logTypes];
        }

        $logTypeQueriesArray = $EventlogsTable->getQueriesByTypes($logTypes);
        $tableColumns = $this->getFrontendTableColumnsByTypes($logTypes);

        if (!empty($logTypes)) {
            $all_events = $EventlogsTable->getEventlogIndex($EventlogsFilter, $PaginateOMat, $MY_RIGHTS, $logTypeQueriesArray, false);
        } else {
            $all_events = [];
        }

        $User = new User($this->getUser());
        $UserTime = $User->getUserTime();

        foreach ($all_events as $index => $event) {
            $changeTimestamp = $event['created']->getTimestamp();
            $all_events[$index]['time'] = $UserTime->format($changeTimestamp);
            $all_events[$index]['recordExists'] = $EventlogsTable->recordExists($event['model'], $event['object_id']);
            if (json_validate($all_events[$index]['data'])) {
                $all_events[$index]['data'] = json_decode($all_events[$index]['data'], true);
            }
        }

        $this->set('all_events', $all_events);
        $this->set('tableColumns', $tableColumns);

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

        if (getType($logTypes) === 'string') {
            $logTypes = [$logTypes];
        }

        $logTypeQueriesArray = $EventlogsTable->getQueriesByTypes($logTypes);
        $tableColumns = $this->getFrontendTableColumnsByTypes($logTypes);

        if (!empty($logTypes)) {
            $events = $EventlogsTable->getEventlogIndex($EventlogsFilter, $PaginateOMat, $MY_RIGHTS, $logTypeQueriesArray, false);
        } else {
            $events = [];
        }

        $User = new User($this->getUser());
        $UserTime = $User->getUserTime();

        $all_events = [];

        foreach ($events as $index => $event) {
            $changeTimestamp = $event['created']->getTimestamp();
            $events[$index]['time'] = $UserTime->format($changeTimestamp);
            $recordExists = $EventlogsTable->recordExists($event['model'], $event['object_id']);
            if (json_validate($event['data'])) {
                $events[$index]['data'] = json_decode($event['data'], true);
            }

            $all_events[$index] = [
                $event['type']
            ];

            if ($tableColumns['login']['full_name']) {
                if ($recordExists) {
                    $all_events[$index][1] = $event['full_name'];
                } else {
                    $all_events[$index][1] = $events[$index]['data']['full_name'];
                }
            }
            if ($tableColumns['login']['user_email']) {
                if ($recordExists) {
                    $all_events[$index][2] = $event['user']['email'];
                } else {
                    $all_events[$index][2] = $events[$index]['data']['user_email'];

                }
            }
            $all_events[$index][] = $events[$index]['time'];

        }

        $header = [
            'event_type',
        ];

        if ($tableColumns['login']['full_name']) {
            $header[] = 'full_name';
        }
        if ($tableColumns['login']['user_email']) {
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

    /**
     * @param $logTypes
     * @return array
     */
    private function getFrontendTableColumnsByTypes($logTypes) {
        $tableColumns = [];
        if (!empty($logTypes)) {
            foreach ($logTypes as $logType) {
                if ($logType === 'login') {
                    $tableColumns[$logType] = [
                        'full_name'  => 1,
                        'user_email' => 1,
                    ];
                }
            }
        }
        return $tableColumns;
    }

}
