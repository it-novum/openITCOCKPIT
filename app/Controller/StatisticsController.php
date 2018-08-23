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

use itnovum\openITCOCKPIT\Core\System\Health\StatisticsCollector;

/**
 * Class StatisticsController
 * @property Host $Host
 * @property Service $Service
 * @property Systemsetting $Systemsetting
 */
class StatisticsController extends AppController {

    public $uses = [
        'Host',
        'Service',
        'Systemsetting'
    ];

    public $layout = 'angularjs';

    public function index() {
        if (!$this->isAngularJsRequest()) {
            $StatisticsCollector = new StatisticsCollector($this->Host, $this->Service);
            $statisticsAsJson = json_encode($StatisticsCollector->getData(), JSON_PRETTY_PRINT);
            $this->set('statisticsAsJson', $statisticsAsJson);
            return;
        }

        $record = $this->Systemsetting->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Systemsetting.key' => 'SYSTEM.ANONYMOUS_STATISTICS'
            ]
        ]);

        $this->set('settings', $record);
        $this->set('_serialize', ['settings']);
    }

    public function ask_anonymous_statistics() {
        $this->layout = 'blank';
        //Only ship HTML template
        return;
    }

    public function saveStatisticDecision() {
        if (!$this->isApiRequest()) {
            throw new MethodNotAllowedException();
        }

        $record = $this->Systemsetting->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Systemsetting.key' => 'SYSTEM.ANONYMOUS_STATISTICS'
            ]
        ]);

        if (empty($record)) {
            throw new RuntimeException('Systemsetting is missing - did you executed openitcockpit-update?');
        }


        if(!isset($this->request->data['statistics']['decision'])){
            throw new RuntimeException('Wrong POST request');
        }


        $record['Systemsetting']['value'] = (int)$this->request->data['statistics']['decision'];

        if(isset($this->request->data['statistics']['cookie']) && $record['Systemsetting']['value'] === 2){
            $this->Cookie->write('askAgainForHelp', 'Remind me later', false, (3600*16));
        }

        if($this->Systemsetting->save($record)) {
            $this->set('success', true);
            $this->set('message', __('Record successfully saved'));
            $this->set('_serialize', ['success', 'message']);
            return;
        }
        $this->set('success', false);
        $this->set('message', __('Error while saving data'));
        $this->set('_serialize', ['success', 'message']);
    }

}
