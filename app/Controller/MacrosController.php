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

class MacrosController extends AppController
{
    public $layout = 'Admin.default';
    public $components = ['RequestHandler'];


    public function index()
    {

        $this->Paginator->settings['limit'] = 500;
        $this->Paginator->settings['order'] = ['Macro.name' => 'asc'];
        $all_macros = $this->Paginator->paginate();

        //Sorting the SQL result in a human frindly way. Will sort $USER10$ below $USER2$
        $all_macros = Hash::sort($all_macros, '{n}.Macro.name', 'asc', 'natural');

        //Restore submited macros after a validation error
        if (!empty($this->request->data) && $this->request->is('post')) {
            $all_macros = Hash::merge($all_macros, $this->request->data);
        }

        $this->set(compact(['all_macros']));
        $this->set('_serialize', ['all_macros']);

        //Checking if the user delete a macro
        $macrosToDelete = [];
        if (!empty($all_macros) && !empty($this->request->data)) {
            $macrosToDelete = $this->Macro->find('all', [
                'conditions' => [
                    'Macro.id' => array_diff(Hash::extract($all_macros, '{n}.Macro.id'), Hash::extract($this->request->data, '{n}.Macro.id')),
                ],
            ]);
        }


        //Delete all macros that was removed by the user:
        foreach ($macrosToDelete as $macroToDelete) {
            $this->Macro->delete($macroToDelete['Macro']['id']);
        }


        //Saving the data
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Macro->saveAll($this->_rewritePostData($this->request->data))) {
                $this->setFlash(__('Macros saves successfully'));
                $this->redirect(['action' => 'index']);
            } else {
                $this->setFlash(__('Error while saving data'), false);
            }
        }
    }

    public function addMacro()
    {

        if (!empty($this->request->data)) {
            $all_macros = $this->request->data;
        } else {
            $all_macros = $this->Macro->find('list');
        }

        //Merging existing macros with the new one that was added by javascript
        //$all_macros = Hash::merge($this->Macro->find('list'), $this->request->data);

        $macroCount = 1;

        while (in_array('$USER'.$macroCount.'$', $all_macros)) {
            $macroCount++;
        }

        $newMacro = '$USER'.$macroCount.'$';
        $this->set('newMacro', $newMacro);
        $this->set('macroCount', $macroCount);

    }

    private function _rewritePostData($request = [])
    {
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