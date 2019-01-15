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

use App\Model\Table\MacrosTable;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Hash;

class MacrosController extends AppController {

    use LocatorAwareTrait;

    public $layout = 'angularjs';

    public function index() {
        $TableLocator = $this->getTableLocator();
        /** @var $Macros MacrosTable */
        $Macros = $TableLocator->get('Macros');

        if (!$this->isAngularJsRequest()) {
            if ($this->isJsonRequest()) {
                //Legacy API
                $this->set('all_macros', $Macros->getAllMacrosInCake2Format());
                $this->set('_serialize', ['all_macros']);
                return;
            }

            //Only ship HTML template for angular
            return;
        }

        $all_macros = $Macros->find('all')->disableHydration()->toArray();
        $all_macros = Hash::sort($all_macros, '{n}.name', 'asc', 'natural');
        $this->set('all_macros', $all_macros);
        $this->set('_serialize', ['all_macros']);
    }

    public function add() {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $TableLocator = $this->getTableLocator();
        /** @var $Macros MacrosTable */
        $Macros = $TableLocator->get('Macros');

        $macro = $Macros->newEntity();
        $macro = $Macros->patchEntity($macro, $this->request->data('Macro'));
        $Macros->save($macro);

        if ($macro->hasErrors()) {
            $this->response->statusCode(400);
            $this->set('error', $macro->getErrors());
            $this->set('_serialize', ['error']);
            return;
        }

        $this->set('macro', $macro);
        $this->set('_serialize', ['macro']);
    }

    public function edit($id = null) {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $TableLocator = $this->getTableLocator();
        /** @var $Macros MacrosTable */
        $Macros = $TableLocator->get('Macros');

        if (!$Macros->exists($id)) {
            throw new NotFoundException('Macro not found');
        }

        $macro = $Macros->get($id);
        $macro = $Macros->patchEntity($macro, $this->request->data('Macro'));

        $Macros->save($macro);

        if ($macro->hasErrors()) {
            $this->response->statusCode(400);
            $this->set('error', $macro->getErrors());
            $this->set('_serialize', ['error']);
            return;
        }

        $this->set('macro', $macro);
        $this->set('_serialize', ['macro']);
    }

    public function delete($id = null) {
        if (!$this->isAngularJsRequest() || !$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $TableLocator = $this->getTableLocator();
        /** @var $Macros MacrosTable */
        $Macros = $TableLocator->get('Macros');

        if (!$Macros->exists($id)) {
            throw new NotFoundException('Macro not found');
        }

        $macro = $Macros->get($id);
        if ($Macros->delete($macro)) {
            $this->set('success', true);
            $this->set('_serialize', ['success']);
            return;
        }

        $this->response->statusCode(500);
        $this->set('success', false);
        $this->set('_serialize', ['success']);
    }

    public function getAvailableMacroNames() {
        if (!$this->isAngularJsRequest()) {
            throw new MethodNotAllowedException();
        }

        $include = $this->request->query('include');

        $TableLocator = $this->getTableLocator();
        /** @var $Macros MacrosTable */
        $Macros = $TableLocator->get('Macros');

        $availableMacroNames = array_values($Macros->getAvailableMacroNames());
        if ($include !== '') {
            $availableMacroNames[] = $include;
        }


        $this->set('availableMacroNames', $availableMacroNames);
        $this->set('_serialize', ['availableMacroNames']);
    }


    public function addMacro() {

        if (!empty($this->request->data)) {
            $all_macros = $this->request->data;
        } else {
            $all_macros = $this->Macro->find('list');
        }

        //Merging existing macros with the new one that was added by javascript
        //$all_macros = Hash::merge($this->Macro->find('list'), $this->request->data);

        $macroCount = 1;

        while (in_array('$USER' . $macroCount . '$', $all_macros)) {
            $macroCount++;
        }

        $newMacro = '$USER' . $macroCount . '$';
        $this->set('newMacro', $newMacro);
        $this->set('macroCount', $macroCount);

    }

    private function _rewritePostData($request = []) {
        /*
        If the user press on save we get an array like this:
        (int) 0 => array( <-- Data out of DB
            'Macro' => array(
                'id' => '1',
                'name' => '$USER1$',
                'value' => '/opt/openitc/nagios/libexec'
            )
        ),
        (int) 1 => array( <-- Data out of DB
            'Macro' => array(
                'id' => '2',
                'name' => '$USER2$',
                'value' => '/usr/local/share/nagios/libexec'
            )
        ),
        'a45c1e194e3a0b3919fa08afcbcb0549692208e9' => array( <-- Data was created by AJAX and JS
            'Macro' => array(
                'name' => '$USER3$',
                'value' => 'random data'
            )
        )
        
        But saveAll requires an array like this:
        (int) 0 => array(
            'Macro' => array(
                'id' => '1',
                'name' => '$USER1$',
                'value' => '/opt/openitc/nagios/libexec'
            )
        ),
        (int) 1 => array(
            'Macro' => array(
                'id' => '2',
                'name' => '$USER2$',
                'value' => '/usr/local/share/nagios/libexec'
            )
        ),
        (int) 2 => array(
            'Macro' => array(
                'name' => '$USER3$',
                'value' => 'random data'
            )
        )
        
        */

        $return = [];
        foreach ($request as $data) {
            //Remove empty values, because nagios will trhow a config error
            if (!isset($data['Macro']['value']) || !isset($data['Macro']['name']) || strlen($data['Macro']['value']) == 0) {
                continue;
            }
            $return[] = $data;
        }

        return $return;

    }
}