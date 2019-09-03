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

use Cake\ORM\TableRegistry;

class SetPermissionsShell extends AppShell {
    public $uses = ['Usergroup', 'Aro', 'Tenant'];

    public function main() {
        $this->stdout->styles('green', ['text' => 'green']);
        $this->out('Set new user group permissions...    ', false);
        App::import('Component', 'Acl');
        $this->Acl = @new AclComponent(new ComponentCollection());

        /*
        array(
        'Usergroup' => array(
            'id' => '1',
            'name' => 'Administrator',
            'description' => '',
            'Aco' => array(
                (int) 3 => '1',
                (int) 4 => '1',
            )
        )
        */

        $usergroups = $this->Usergroup->find('all', [
            'recursive' => -1,
        ]);
        foreach ($usergroups as $usergroup) {
            $permissions = $this->Acl->Aro->Permission->find('all', [
                'conditions' => [
                    'Aro.foreign_key' => $usergroup['Usergroup']['id'],
                ],
            ]);
            $aros = Hash::extract($permissions, '{n}.Permission.aco_id');
            unset($permissions);
            $acos = $this->Acl->Aco->find('threaded', [
                'recursive' => -1,
            ]);

            /** @var $UsergroupsTable App\Model\Table\UsergroupsTable */
            $UsergroupsTable = TableRegistry::getTableLocator()->get('Usergroups');

            $alwaysAllowedAcos = $UsergroupsTable->getAlwaysAllowedAcos($acos);
            $acoDependencies = $this->Usergroup->getAcoDependencies($acos);
            $dependenAcoIds = $this->Usergroup->getAcoDependencyIds($acoDependencies);


            foreach ($acos as $rootElement => $rootArray) {
                foreach ($rootArray['children'] as $key => $controllerWithActions) {
                    if (!empty($controllerWithActions['children'])) {
                        $isModule = preg_match('/Module/', $controllerWithActions['Aco']['alias']);
                        foreach ($controllerWithActions['children'] as $action) {
                            if (!$isModule) {
                                //Is always allowed acos?
                                if (!isset($alwaysAllowedAcos[$action['Aco']['id']]) && !isset($dependenAcoIds[$action['Aco']['id']])) {
                                    //No
                                    //Has user this permission?
                                    if (in_array($action['Aco']['id'], $aros)) {
                                        $usergroup['Usergroup']['Aco'][$action['Aco']['id']] = 1;
                                    }
                                }
                            } else {
                                if (!empty($action['children'])) {
                                    foreach ($action['children'] as $moduleAction) {
                                        if (!isset($alwaysAllowedAcos[$moduleAction['Aco']['id']]) && !isset($dependenAcoIds[$moduleAction['Aco']['id']])) {
                                            if (in_array($moduleAction['Aco']['id'], $aros)) {
                                                $usergroup['Usergroup']['Aco'][$moduleAction['Aco']['id']] = 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $aro = $this->Acl->Aro->find('first', [
                'recursive'  => -1,
                'conditions' => [
                    'Aro.foreign_key' => $usergroup['Usergroup']['id'],
                ],
                'fields'     => [
                    'Aro.id',
                ],
            ]);
            $aclData = [];
            $avoidMysqlDuplicate = [];
            foreach ($usergroup['Usergroup']['Aco'] as $acoId => $value) {
                if ($value == 1) {
                    $aclData[] = [
                        'Permission' => [
                            'aro_id'  => $aro['Aro']['id'],
                            'aco_id'  => $acoId,
                            '_create' => 1,
                            '_read'   => 1,
                            '_update' => 1,
                            '_delete' => 1,
                        ],
                    ];
                    //Has dependend ACOs?
                    if (isset($acoDependencies[$acoId])) {
                        foreach (array_keys($acoDependencies[$acoId]) as $dependendAcoId) {
                            if (!isset($avoidMysqlDuplicate[$dependendAcoId])) {
                                $aclData[] = [
                                    'Permission' => [
                                        'aro_id'  => $aro['Aro']['id'],
                                        'aco_id'  => $dependendAcoId,
                                        '_create' => 1,
                                        '_read'   => 1,
                                        '_update' => 1,
                                        '_delete' => 1,
                                    ],
                                ];
                                $avoidMysqlDuplicate[$dependendAcoId] = true;
                            }
                        }
                    }
                }
            }

            //Add always allowd ACOs to usergroup data
            foreach ($alwaysAllowedAcos as $acoId => $description) {
                $aclData[] = [
                    'Permission' => [
                        'aro_id'  => $aro['Aro']['id'],
                        'aco_id'  => $acoId,
                        '_create' => 1,
                        '_read'   => 1,
                        '_update' => 1,
                        '_delete' => 1,
                    ],
                ];
            }

            //Delete old permissions
            $this->Acl->Aro->Permission->deleteAll([
                'Aro.id' => $aro['Aro']['id'],
            ]);

            //Save new permissions
            $this->Acl->Aro->Permission->saveAll($aclData);

            //Continue with next usergroup
        }
        $this->out('<green>done</green>');
    }

    public function _welcome() {
        //Disable CakePHP welcome messages
    }

}
