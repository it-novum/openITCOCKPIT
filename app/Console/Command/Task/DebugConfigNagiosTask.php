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

class DebugConfigNagiosTask extends AppShell {

    public $uses = ['Host', 'Hosttemplate', 'Timeperiod', 'Command', 'Contact', 'Contactgroup', 'Container', 'Customvariable', 'Hostescalation', 'Serviceescalation', 'Hostgroup', 'Service', 'Servicetemplate', 'Serviceescalations', 'Servicegroup', 'Hostdependency', 'Servicedependency'];

    public function execute() {
        //Do some cool stuff
        debug($this->Hosttemplate->find('all'));
    }

    public function setup($conf = []) {
        $this->conf = $conf;
        $this->monitoringLog = Configure::read('nagios.logfilepath') . Configure::read('nagios.logfilename');
        App::uses('Folder', 'Utility');
        //Loading components
        App::uses('Component', 'Controller');
        App::uses('ConstantsComponent', 'Controller/Component');
        $this->Constants = new ConstantsComponent();
        $this->_buildUuidCache();
        $this->searchUuids();
    }

    private function _buildUuidCache() {
        $this->uuidCache = [];
        $Models = [
            'Host',
            'Hosttemplate',
            'Timeperiod',
            'Command',
            'Contact',
            'Contactgroup',
            'Hostgroup',
            'Servicegroup',
            'Service',
            'Servicetemplate',
            'Hostescalation',
            'Serviceescalation'
        ];
        $options = [
            'Host'            => [
                'recursive' => -1,
                'fields'    => ['id', 'uuid', 'name'],
            ],
            'Hosttemplate'    => [
                'recursive' => -1,
                'fields'    => ['id', 'uuid', 'name'],
            ],
            'Timeperiod'      => [
                'recursive' => -1,
                'fields'    => ['id', 'uuid', 'name'],
            ],
            'Command'         => [
                'recursive' => -1,
                'fields'    => ['id', 'uuid', 'name'],
            ],
            'Contact'         => [
                'recursive' => -1,
                'fields'    => ['id', 'uuid', 'name'],
            ],
            'Contactgroup'    => [
                'recursive' => -1,
                'fields'    => ['Contactgroup.id', 'Contactgroup.uuid', 'Contactgroup.container_id'],
                'contain'   => [
                    'Container' => [
                        'fields' => ['Container.name'],
                    ],
                ],
            ],
            'Hostgroup'       => [
                'recursive' => -1,
                'fields'    => ['Hostgroup.id', 'Hostgroup.uuid', 'Hostgroup.container_id'],
                'contain'   => [
                    'Container' => [
                        'fields' => ['Container.name'],
                    ],
                ],
            ],
            'Servicegroup'    => [
                'recursive' => -1,
                'fields'    => ['Servicegroup.id', 'Servicegroup.uuid', 'Servicegroup.container_id'],
                'contain'   => [
                    'Container' => [
                        'fields' => ['Container.name'],
                    ],
                ],
            ],
            'Service'         => [
                'recursive' => -1,
                'fields'    => ['Service.id', 'Service.uuid', 'Service.name'],
                'contain'   => [
                    'Servicetemplate' => [
                        'fields' => ['Servicetemplate.name'],
                    ],
                ],
            ],
            'Servicetemplate' => [
                'recursive' => -1,
                'fields'    => ['Servicetemplate.id', 'Servicetemplate.uuid', 'Servicetemplate.name'],
            ],
            'Hostescalation'    => [
                'recursive' => -1,
                'fields'    => ['id', 'uuid'],
            ],
            'Serviceescalation'    => [
                'recursive' => -1,
                'fields'    => ['id', 'uuid'],
            ],
            'Hostdependency'    => [
                'recursive' => -1,
                'fields'    => ['id', 'uuid'],
            ],
            'Servicedependency'    => [
                'recursive' => -1,
                'fields'    => ['id', 'uuid'],
            ],
        ];

        foreach ($Models as $ModelName) {
            if (!in_array($ModelName, $this->uses)) {
                $this->loadModel($ModelName);
            }

            foreach ($this->{$ModelName}->find('all', $options[$ModelName]) as $result) {
                $tmp_result = [];
                if (isset($result[$ModelName]['id'])) {
                    $tmp_result['id'] = $result[$ModelName]['id'];
                }

                if (isset($result[$ModelName]['name'])) {
                    $tmp_result['name'] = $result[$ModelName]['name'];
                } else {
                    //in php isset() returns false, if a variable is null
                    // $a = null, isset($a) will return false!!!
                    if ($ModelName == 'Service') {
                        if ($result['Service']['name'] == null || $result['Service']['name'] == '') {
                            $tmp_result['name'] = $result['Servicetemplate']['name'];
                        }
                    }
                }

                if (isset($result[$ModelName]['description'])) {
                    $tmp_result['description'] = $result[$ModelName]['description'];
                }

                if (isset($result[$ModelName]['container_id'])) {
                    $tmp_result['container_id'] = $result[$ModelName]['container_id'];
                }

                if (isset($result['Container']['name'])) {
                    $tmp_result['container_name'] = $result['Container']['name'];
                }


                $tmp_result['ModelName'] = $ModelName;

                if (!isset($result[$ModelName]['uuid'])) {
                    debug($result[$ModelName]);
                }

                $this->uuidCache[$result[$ModelName]['uuid']] = $tmp_result;
                unset($tmp_result);
            }
        }
    }

    public function debug($ModelName = null, $confName) {
        if ($ModelName !== null && is_array($this->uses)) {
            $ModelSchema = $this->{$ModelName}->schema();
            $IsModelWithoutName = in_array($ModelName, [
                'Hostescalation',
                'Serviceescalation',
                'Hostdependency',
                'Servicedependency'
            ], true); // Exclude Escalations, no name exists, get all lists
            if (in_array('name', array_keys($ModelSchema)) && !$IsModelWithoutName) {
                $input = $this->in(__d('oitc_console', 'Please enter the name of the ' . $ModelName . '! This is a wildcard search, for example type "default host" or just "def". Hit return to see all ' . Inflector::pluralize($ModelName)));
                $result = $this->{$ModelName}->find('all', [
                    'conditions' => [
                        $ModelName . '.name LIKE' => '%' . $input . '%',
                    ],
                    'contain'    => [],
                ]);
            } else if (in_array('container_id', array_keys($ModelSchema)) && !$IsModelWithoutName) {
                $input = $this->in(__d('oitc_console', 'Please enter the name of the ' . $ModelName . '! This is a wildcard search, for example type "default host" or just "def". Hit return to see all ' . Inflector::pluralize($ModelName)));
                $result = $this->{$ModelName}->find('all', [
                    'conditions' => [
                        'Container.name LIKE'        => '%' . $input . '%',
                        'Container.containertype_id' => $this->containertypeByModelName($ModelName),
                    ],
                    ['contain']  => [
                        'Container',
                    ],
                ]);
            } else if($IsModelWithoutName){
                $result = $this->{$ModelName}->find('all', [
                    'recursive' => -1
                ]);
            } else {
                $this->out('<error>' . __d('oitc_console', 'No name field for ' . $ModelName . ' found in database!') . '</error>');
                $result = $this->{$ModelName}->find('all');
            }

            if (sizeof($result) > 1) {
                if (isset($input)) {
                    $this->out(__d('oitc_console', 'I found ' . sizeof($result) . ' results matching to "' . $input . '". Please select one ' . $ModelName . ' by typing the number in square brackets'));
                } else {
                    $this->out(__d('oitc_console', 'Please select one ' . $ModelName . ' by typing the number in square brackets'));
                }
                foreach ($result as $r) {
                    if (isset($r[$ModelName]['name'])) {
                        $this->out('[' . $r[$ModelName]['id'] . '] ' . $r[$ModelName]['name']);
                    } else if (isset($r['Container']['name'])) {
                        $this->out('[' . $r[$ModelName]['id'] . '] ' . $r['Container']['name']);
                    } else {
                        $this->out('[' . $r[$ModelName]['id'] . '] ' . $r[$ModelName]['uuid']);
                    }
                }
                $input = $this->in(__d('oitc_console', 'Your choice please'));
                if ($this->{$ModelName}->exists($input)) {
                    $result = [];
                    $result[0] = $this->{$ModelName}->findById($input);
                    $this->_outFile($result[0][$ModelName]['uuid'], $ModelName, $confName);
                } else {
                    $this->out('<error>' . $ModelName . __d('oitc_console', ' not found') . '</error>');
                }
            } else {
                if (sizeof($result) == 1) {
                    $this->_outFile($result[0][$ModelName]['uuid'], $ModelName, $confName);
                } else {
                    $this->out('<error>No object metching given conditions</error>');
                }
            }

        } else {
            echo "Unknown Model !";
            exit();
        }
    }

    public function debugByUuid($uuid = null) {
        if ($uuid !== null) {

        } else {
            $input = $this->in(__d('oitc_console', 'Please enter your UUID'));
            $result = $this->searchByUuid($input);
            if (!empty($result)) {
                $this->_outFile($input, $result['ModelName'], strtolower(Inflector::pluralize($result['ModelName'])));
            } else {
                $this->out('<error>' . __d('oitc_console', 'No result for given UUID') . '</error>');
            }
        }
    }

    public function parseMonitoringLogfile() {
        foreach ($this->tail() as $line) {
            $timestamp = null;
            preg_match('#\d{10,11}#', $line, $timestamp);
            if (isset($timestamp[0]) && is_numeric($timestamp[0])) {
                $this->out(preg_replace('#\d{10,11}#', '<comment>' . date('d.m.Y - H:i:s', $timestamp[0]) . '</comment>', $line));
            } else {
                $this->out($line);
            }
        }
    }

    private function tail($lines = 100) {
        $return = [];
        $file = fopen($this->monitoringLog, "r");
        $flen = filesize($this->monitoringLog);
        if ($flen % 4096 != 0) {
            $flen = ((int)($flen / 4096) + 1) * 4096;
        }

        $newlines = 0;
        $startpos = -1;

        for ($goto = 0; $goto <= $flen - 4096; $goto += 4096) {
            fseek($file, $flen - $goto - 4096);
            $data = fread($file, 4096);
            if ($data === false)
                continue;
            for ($i = strlen($data) - 1; $i >= 0; $i--) {
                if ($data[$i] == "\n") {
                    $newlines++;
                    $startpos = $flen - $goto - 4096 + $i + 1;
                    if ($newlines > $lines)
                        break 2;
                }
            }
        }

        if ($startpos != -1) {
            fseek($file, $startpos);
            $fileContent = fread($file, $flen - $startpos);
            $return[] = $this->searchUuids($fileContent);
        }
        $ret = [];
        foreach ($return as $r) {
            $ret = explode("\n", $r);
        }

        return $ret;
    }

    public function tailf() {
        $callback = function ($timestamp) {
            return '<comment>' . date('d.m.Y - H:i:s', $timestamp[0]) . '</comment>';
        };

        $options = [
            'cwd' => Configure::read('nagios.logfilepath'),
            'env' => [
                'LANG'     => 'C',
                'LANGUAGE' => 'en_US.UTF-8',
                'LC_ALL'   => 'C',
                'PATH'     => '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin'
            ],
        ];

        $descriptorspec = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "r"],
        ];

        $process = proc_open('/usr/bin/tail -f -n 100 ' . Configure::read('nagios.logfilename'), $descriptorspec, $pipes, $options['cwd'], $options['env']);
        while (true) {
            $status = proc_get_status($process);
            if ($status['running'] != 1) {
                fclose($pipes[0]);
                fclose($pipes[1]);
                fclose($pipes[2]);
                proc_close($process);
                break;
            }

            $string = fgets($pipes[1], 1024);
            $string = preg_replace_callback('#\d{10,11}#', $callback, $string);
            $string = $this->searchUuids($string);
            $this->out($string, 0);
        }
    }

    private function _outFile($uuid, $ModelName, $confName) {
        if (!$this->conf['minified'] || in_array($ModelName, ['Hostdependency', 'Hostescalation', 'Servicedependency', 'Serviceescalation', 'Servicegroup'])) {
            // Model that are not saved as minified config files or minified configs asre tournd off
            $file = new File($this->conf['path'] . $this->conf[$confName] . $uuid . $this->conf['suffix']);
            if ($file->exists()) {
                $this->hr();
                $this->out('<warning>File: ' . $this->conf['path'] . $this->conf[$confName] . $uuid . $this->conf['suffix'] . '</warning>');
                $this->out('<info>' . __d('oitc_console', 'Notice: This is not the real nagios configuration file. This is a human readable version of the config.') . '</info>');
                $this->hr();
                $this->out($this->nl());
                $this->out($this->searchUuids($file->read()));
                $file->close();
            } else {
                $this->out(__d('oitc_console', '<error>File not found! (' . $this->conf['path'] . $this->conf[$confName] . $uuid . $this->conf['suffix'] . ')</error>'));
            }
        } else {
            $configFolder = new Folder($this->conf['path'] . $this->conf[$confName]);
            $configFiles = $configFolder->find();

            // User want the file of an object, that is inside of an minified file, so we need to parse the minified config
            if (!empty($configFiles)) {
                foreach ($configFiles as $configFile) {
                    $fileAsArray = file($configFolder->pwd() . $configFile);

                    $content = [];
                    $startParsing = false;

                    $modelToNagios = [
                        'Command'         => 'command_name',
                        //'Contactgroup' => ,
                        'Contact'         => 'contact_name',
                        //'Hostgroup' => '',
                        'Host'            => 'host_name',
                        'Hosttemplate'    => 'host_name',
                        'Service'         => 'service_description',
                        'Servicetemplate' => 'service_description',
                        'Timeperiod'      => 'timeperiod_name',
                    ];

                    $needel = $modelToNagios[$ModelName];

                    $searchForEnd = false;

                    $configContent = [];
                    $breakForeach = false;
                    $state = 'SEARCH_FOR_DEFINITION';
                    foreach ($fileAsArray as $line) {
                        $configContent[] = $line;
                        switch ($state) {
                            case 'SEARCH_FOR_DEFINITION':
                                if (preg_match('/^define .*\{[\s\t]*$/', $line)) {
                                    $state = 'SEARCH_FOR_OBJECT_NAME';
                                }
                                break;

                            case 'SEARCH_FOR_OBJECT_NAME': //host_name, command_name, contacnt_name, etc
                                if (preg_match('/' . $needel . '/', $line)) {
                                    $check = explode($needel, $line);
                                    if (sizeof($check) == 2) {
                                        if (trim($check[1]) == $uuid) {
                                            //this is the object we search for!
                                            $state = 'SEARCH_FOR_END_OF_DEFENITION';
                                            break;
                                        }
                                    }
                                    //This is not the object we are searching for!
                                    $state = 'SEARCH_FOR_END_OF_DEFENITION_AND_CONTINUE';
                                }
                                break;

                            case 'SEARCH_FOR_END_OF_DEFENITION':
                                if (trim($line) == '}') {
                                    // We have the complet object now, so we can break out of switch and foreach
                                    $breakForeach = true;
                                    break;
                                }
                                break;

                            case 'SEARCH_FOR_END_OF_DEFENITION_AND_CONTINUE':
                                // this was the wrong object, so throw everyting away and continue with next definition
                                if (trim($line) == '}') {
                                    $configContent = [];
                                    $state = 'SEARCH_FOR_DEFINITION';
                                }
                                break;
                        }

                        if ($breakForeach === true) {
                            break;
                        }
                    }

                    if (!empty($configContent)) {
                        $this->hr();
                        $this->out(__('<warning>File: %s</warning>', $configFolder->pwd() . $configFile));
                        $this->out('<info>' . __d('oitc_console', 'Notice: This is not the real nagios configuration file. This is a human readable version of the config.') . '</info>');
                        $this->hr();
                        foreach ($configContent as $line) {
                            $this->out($this->searchUuids($line), false);
                        }
                        //break config files foreach.
                        break;
                    }

                }
            } else {
                $this->out(__d('oitc_console', '<error>Folder %s is empty!</error>', $configFolder->pwd()));
            }

        }
    }

    public function searchUuids($string = '') {
        if (!isset($this->uuidCache) || empty($this->uuidCache) || !is_array($this->uuidCache)) {
            $this->_buildUuidCache();
        }

        $string = preg_replace_callback(\itnovum\openITCOCKPIT\Core\UUID::regex(), [$this, '_replaceUuid'], $string);

        return $string;
    }

    public function searchByUuid($uuid = null) {
        if (isset($this->uuidCache[$uuid])) {
            return $this->uuidCache[$uuid];
        }

        return [];
    }

    private function _replaceUuid($matches) {
        foreach ($matches as $match) {
            if (isset($this->uuidCache[$match])) {
                //Checking if name exists or if we need to use the container:
                if (isset($this->uuidCache[$match]['name'])) {
                    return '<red_bold>' . $this->uuidCache[$match]['name'] . "</red_bold><comment>[" . $match . "]</comment>";
                } else if (isset($this->uuidCache[$match]['container_name'])) {
                    return '<red_bold>' . $this->uuidCache[$match]['container_name'] . "</red_bold><comment>[" . $match . "]</comment>";
                } else {
                    return "<error>name not found in DB</error><comment>[" . $match . "]</comment>";
                }

            }

            return "<error>object not found in UUID cache</error><comment>[" . $match . "]</comment>";
        }
    }

    public function translateStdin() {
        $result = null;
        do {
            $result = $this->stdin->read();
            $this->out($this->searchUuids($result));
        } while ($result !== false);
    }

    /**
     * Unbind all accociations for the next find() call for every model
     * @return void
     * @since 3.0
     */
    private function _unbindAssociations() {
        $excludeAccociations = ['Container', 'Servicetemplate'];
        $excludeModels = ['Container'];
        foreach ($this->uses as $ModelName) {
            if (in_array($ModelName, $excludeModels)) {
                continue;
            }
            foreach (['hasOne', 'hasMany', 'belongsTo', 'hasAndBelongsToMany'] as $association)
                if (!empty($this->{$ModelName}->{$association})) {
                    foreach ($this->{$ModelName}->{$association} as $accociatedModel) {
                        if (!in_array($accociatedModel['className'], $excludeAccociations)) {
                            $this->{$ModelName}->unbindModel([$association => $accociatedModel]);
                        }
                    }
                }
        }
    }

    /**
     * Returns the containerttype_id of by $ModelName
     *
     * @param string $modelName of the Model to check
     *
     * @return string with the containertype_id
     */
    private function containertypeByModelName($modelName = '') {
        $objects = [
            'Servicetemplate' => CT_SERVICETEMPLATEGROUP,
            'Servicegroup'    => CT_SERVICEGROUP,
            'Hostgroup'       => CT_HOSTGROUP,
            'Contactgroup'    => CT_CONTACTGROUP,
            //'Devicegroup' => CT_DEVICEGROUP,
            'Location'        => CT_LOCATION,
            'Tenant'          => CT_TENANT,
            'Container'       => CT_GLOBAL,
        ];

        if (isset($objects[$modelName])) {
            return $objects[$modelName];
        }

        throw new NotFoundException(__('Object not found'));
    }
}
