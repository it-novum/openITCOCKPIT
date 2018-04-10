<?php


class RolesShell extends AppShell {
    public $uses = ['Usergroup', 'Aro', 'Aco', 'Permission'];

    private $onlyAdminGroup = false;

    public function main() {
        $this->parser = $this->getOptionParser();
        if (array_key_exists('admin', $this->params)) {
            $this->onlyAdminGroup = true;
        }

        $this->stdout->styles('green', ['text' => 'green']);
        $this->out('Setting aros_acos...    ');

        $acos = $this->Aco->find('threaded', [
            'recursive' => -1,
        ]);
        $alwaysAllowedAcos = $this->Usergroup->getAlwaysAllowedAcos($acos);
        $acoDependencies = $this->Usergroup->getAcoDependencies($acos);

        $inserted = 0;

        $userGroups = [];
        if($this->onlyAdminGroup === false) {
            $userGroups = $this->Usergroup->find('all', [
                'recurisve' => -1,

                'fields'  => [
                    'Usergroup.id', 'Usergroup.name'
                ],
                'contain' => [],
            ]);
        }

        if($this->onlyAdminGroup === true) {
            $userGroups = $this->Usergroup->find('all', [
                'recurisve' => -1,

                'fields'     => [
                    'Usergroup.id', 'Usergroup.name'
                ],
                'conditions' => [
                    'Usergroup.name' => 'Administrator'
                ],
                'contain'    => [],
            ]);
        }

        $permissions = $this->Permission->find('all', [
            'recurisve' => -1,
            'contain'   => [],
        ]);
        $myPermissions = $aclData = [];
        foreach ($permissions as $permission) {
            $myPermissions[$permission['Permission']['aro_id']][] = $permission['Permission']['aco_id'];
        }

        if (!empty($userGroups)) {
            foreach ($userGroups as $userGroup) {
                $usergroupCount = 0;
                $this->out('Setting for ' . $userGroup['Usergroup']['name'] . '...', false);
                $aro = $this->Aro->find('first', [
                    'recursive'  => -1,
                    'conditions' => [
                        'Aro.foreign_key' => $userGroup['Usergroup']['id'],
                    ],
                    'fields'     => [
                        'Aro.id',
                    ],
                ]);
                if (!isset($aro['Aro']['id'])) {
                    $this->out('<red>no Aro found</red>');
                    continue;
                }
                $myAroId = intval($aro['Aro']['id']);
                // checking always allowed
                foreach ($alwaysAllowedAcos as $acoId => $acoFullName) {
                    if (!in_array($acoId, $myPermissions[$myAroId])) {
                        if ($usergroupCount == 0)
                            $this->out('');
                        $this->out('<green>Inserting</green> ' . $acoFullName);
                        $aclData[] = [
                            'Permission' => [
                                'aro_id'  => $myAroId,
                                'aco_id'  => $acoId,
                                '_create' => 1,
                                '_read'   => 1,
                                '_update' => 1,
                                '_delete' => 1,
                            ],
                        ];
                        $myPermissions[$myAroId][] = $acoId;
                        ++$inserted;
                        ++$usergroupCount;
                    }
                }

                // checking user rights
                $acoUsergroups = $this->Usergroup->getUsergroupAcos($acos, $userGroup['Usergroup']['name']);
                foreach ($acoUsergroups as $acoId => $acoUsergroup) {
                    if (!in_array($acoId, $myPermissions[$myAroId])) {
                        if ($usergroupCount == 0)
                            $this->out('');
                        $this->out('<green>Inserting</green> ' . $acoUsergroup);
                        $aclData[] = [
                            'Permission' => [
                                'aro_id'  => $myAroId,
                                'aco_id'  => $acoId,
                                '_create' => 1,
                                '_read'   => 1,
                                '_update' => 1,
                                '_delete' => 1,
                            ],
                        ];
                        $myPermissions[$myAroId][] = $acoId;
                        ++$inserted;
                        ++$usergroupCount;
                    }
                }

                // checking depended
                foreach ($acoDependencies as $mainAcoId => $dependedAcoIds) {
                    if (in_array($mainAcoId, $myPermissions[$myAroId])) {
                        foreach ($dependedAcoIds as $dependedAcoId => $dependedFullName) {
                            if (!in_array($dependedAcoId, $myPermissions[$myAroId])) {
                                $helpArr = explode('/', $dependedFullName);
                                if (isset($helpArr[3])) {
                                    $fixedDependedFullName = $helpArr[0] . '/' . $helpArr[1] . '/' . $helpArr[3];
                                } else {
                                    $fixedDependedFullName = $helpArr[0] . '/' . $helpArr[2];
                                }
                                $parsedDependedFullName = preg_replace('/(.+)(\/.+\/)(.+)/', '$1/$3', $fixedDependedFullName);
                                if ($usergroupCount == 0)
                                    $this->out('');
                                $this->out('<green>Inserting</green> ' . $parsedDependedFullName);
                                $aclData[] = [
                                    'Permission' => [
                                        'aro_id'  => $myAroId,
                                        'aco_id'  => $dependedAcoId,
                                        '_create' => 1,
                                        '_read'   => 1,
                                        '_update' => 1,
                                        '_delete' => 1,
                                    ],
                                ];
                                $myPermissions[$myAroId][] = $dependedAcoId;
                                ++$inserted;
                                ++$usergroupCount;
                            }
                        }
                    }
                }

                if ($usergroupCount == 0)
                    $this->out('     <green>done</green>');
            }
        }
        if (!empty($aclData)) {
            $this->Aro->Permission->saveAll($aclData);
        }

        $this->out('<green>' . strval($inserted) . ' row(s) inserted!</green>');

    }


    /**
     * @return ConsoleOptionParser
     */
    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser->addOptions([
            'admin' => ['help' => 'Restore all default user role permissions for Administrator role'],
        ]);

        return $parser;
    }

}