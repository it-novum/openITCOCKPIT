<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace itnovum\openITCOCKPIT\Core;

use App\Lib\Constants;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\Log\Log;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

class NagiosConfigParser {

    private const KEY_FILE = "file";
    private const KEY_CONTENT = "content";
    private $conf = [];
    private $monitoringLog = '';
    private $uuidCache = [];

    private $tablesToSeach = [
        'Hosts'               => [
            'fields' => ['id', 'uuid', 'name'],
        ],
        'Hosttemplates'       => [
            'fields' => ['id', 'uuid', 'name'],
        ],
        'Timeperiods'         => [
            'fields' => ['id', 'uuid', 'name'],
        ],
        'Commands'            => [
            'fields' => ['id', 'uuid', 'name'],
        ],
        'Contacts'            => [
            'fields' => ['id', 'uuid', 'name'],
        ],
        'Contactgroups'       => [
            'fields'           => ['id', 'uuid', 'container_id'],
            'containertype_id' => CT_CONTACTGROUP,
            'contain'          => [
                'Containers' => [
                    'fields' => [
                        'name'
                    ]
                ]
            ]
        ],
        'Hostgroups'          => [
            'fields'           => ['id', 'uuid', 'container_id'],
            'containertype_id' => CT_HOSTGROUP,
            'contain'          => [
                'Containers' => [
                    'fields' => [
                        'name'
                    ]
                ]
            ]
        ],
        'Servicegroups'       => [
            'fields'           => ['id', 'uuid', 'container_id'],
            'containertype_id' => CT_SERVICEGROUP,
            'contain'          => [
                'Containers' => [
                    'fields' => [
                        'name'
                    ]
                ]
            ]
        ],
        'Services'            => [
            'fields'  => ['id', 'uuid', 'name'],
            'contain' => [
                'Servicetemplates' => [
                    'fields' => [
                        'name'
                    ]
                ]
            ]
        ],
        'Servicetemplates'    => [
            'fields' => ['id', 'uuid', 'name'],
        ],
        'Hostescalations'     => [
            'fields'  => ['id', 'uuid', 'container_id'],
            'contain' => [
                'Hostgroups' => [
                    'Containers' => [
                        'fields' => [
                            'name'
                        ]
                    ]
                ]
            ]
        ],
        'Serviceescalations'  => [
            'fields'  => ['id', 'uuid', 'container_id'],
            'contain' => [
                'Servicegroups' => [
                    'Containers' => [
                        'fields' => [
                            'name'
                        ]
                    ]
                ]
            ]
        ],
        'Hostdependencies'    => [
            'fields' => ['id', 'uuid', 'container_id'],
        ],
        'Servicedependencies' => [
            'fields' => ['id', 'uuid', 'container_id']
        ]
    ];

    public function setup($conf = []) {
        $this->conf = $conf;
        $this->monitoringLog = Configure::read('nagios.logfilepath') . Configure::read('nagios.logfilename');
        $this->buildUuidCache();
        $this->searchUuids();
    }

    private function buildUuidCache() {
        $this->uuidCache = [];
        foreach ($this->tablesToSeach as $TableName => $searchDefinition) {
            /** @var Table $Table */
            $Table = TableRegistry::getTableLocator()->get($TableName);

            $query = $Table->find()
                ->select($this->tablesToSeach[$TableName]['fields']);

            if (isset($this->tablesToSeach[$TableName]['contain']) && !empty($this->tablesToSeach[$TableName]['contain'])) {
                $containForQuery = [];
                foreach (array_keys($this->tablesToSeach[$TableName]['contain']) as $tableName) {
                    $containForQuery[] = $tableName;
                    if (isset($this->tablesToSeach[$TableName]['contain'][$tableName]['fields']) && !empty($this->tablesToSeach[$TableName]['contain'][$tableName]['fields'])) {
                        $fields = $this->tablesToSeach[$TableName]['contain'][$tableName]['fields'];
                        $containForQuery[$tableName] = function (Query $query) use ($fields) {
                            return $query->select($fields);
                        };
                    }
                }
                $query->contain($containForQuery);
            }

            $result = $query->disableHydration()->all();

            if (!empty($result)) {
                $result = $result->toArray();
            }

            foreach ($result as $entry) {
                if ($TableName == 'Services') {
                    if ($entry['name'] == null || $entry['name'] == '') {
                        if (isset($entry['servicetemplate']) && isset($entry['servicetemplate']['name'])) {
                            $entry['name'] = $entry['servicetemplate']['name'];
                        }
                    }
                }
                if (isset($entry['container']['name'])) {
                    $entry['container_name'] = $entry['container']['name'];
                }

                $entry['TableName'] = $TableName;

                if (!isset($entry['uuid'])) {
                    debug($entry);
                }

                $this->uuidCache[$entry['uuid']] = $entry;
            }
        }
    }

    /**
     * @param string $TableName
     * @param string $confName
     * @param int $id
     * @return array|string
     */
    public function getConfig(string $TableName, string $confName, int $id) {

        $result = [];

        if ($TableName !== null && is_array($this->tablesToSeach) && isset($this->tablesToSeach[$TableName])) {

            /** @var Table $Table */
            $Table = TableRegistry::getTableLocator()->get($TableName);

            $result = $Table->find()->all();

            if (!empty($result)) {
                $result = $result->toArray();
            }

            if (sizeof($result) > 1) {
                if ($Table->exists(['id' => $id])) {
                    $result = [];
                    $result[0] = $Table->find()->where([
                        'id' => $id
                    ])->firstOrFail();
                    $parseResult = $this->parse($result[0]['uuid'], $TableName, $confName);
                    if (getType($parseResult) == "string") {
                        return $parseResult;
                    } else {
                        $result = $parseResult;
                    }
                } else {
                    Log::error(sprintf('NagiosConfigParser: record %s not found.', $id));
                    return 'No record ' . $id . ' found in ' . $TableName;
                }
            } else {
                if (sizeof($result) == 1) {
                    $parseResult = $this->parse($result[0]['uuid'], $TableName, $confName);
                    if (getType($parseResult) == "string") {
                        return $parseResult;
                    } else {
                        $result = $parseResult;
                    }
                } else {
                    Log::error(sprintf('NagiosConfigParser: record %s not found.', $id));
                    return 'No record ' . $id . ' found in ' . $TableName;
                }
            }

        } else {
            Log::error(sprintf('NagiosConfigParser: Unknown Model %s!.', $TableName));
            return "Unknown Model " . $TableName . "!";
        }

        return $result;

    }

    /**
     * @param string $uuid
     * @param string $TableName
     * @param string $confName
     * @return array|string
     */
    private function parse($uuid, $TableName, $confName) {

        $result = [];

        if (!$this->conf['minified'] || in_array($TableName, ['Hostdependencies', 'Hostescalations', 'Servicedependencies', 'Serviceescalations'])) {
            // Model that are not saved as minified config files or minified configs asre tournd off
            $file = new \SplFileInfo($this->conf['path'] . $this->conf[$confName] . $uuid . $this->conf['suffix']);
            if ($file->isFile() && $file->isReadable()) {
                $result[self::KEY_FILE] = $this->conf['path'] . $this->conf[$confName] . $uuid . $this->conf['suffix'];
                $result[self::KEY_CONTENT] = $this->searchUuids($file->openFile()->fread($file->getSize()));
            } else {
                Log::error(sprintf('NagiosConfigParser: File not found! (%s)', $this->conf['path'] . $this->conf[$confName] . $uuid . $this->conf['suffix']));
                return 'File not found! (' . $this->conf['path'] . $this->conf[$confName] . $uuid . $this->conf['suffix'] . ')';
            }
        } else {
            $configFolder = new Folder($this->conf['path'] . $this->conf[$confName]);
            $configFiles = $configFolder->find();

            // User want the file of an object, that is inside of an minified file, so we need to parse the minified config
            if (!empty($configFiles)) {
                foreach ($configFiles as $configFile) {
                    $fileAsArray = file($configFolder->pwd() . $configFile);

                    $tableToNagios = [
                        'Commands'         => 'command_name',
                        'Contactgroups'    => 'contactgroup_name',
                        'Contacts'         => 'contact_name',
                        'Hosts'            => 'host_name',
                        'Hostgroups'       => 'hostgroup_name',
                        'Hosttemplates'    => 'host_name',
                        'Services'         => 'service_description',
                        'Servicegroups'    => 'servicegroup_name',
                        'Servicetemplates' => 'service_description',
                        'Timeperiods'      => 'timeperiod_name'
                    ];

                    $needel = $tableToNagios[$TableName];

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

                            case 'SEARCH_FOR_OBJECT_NAME': //host_name, command_name, contact_name, etc
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
                                    // We have the complete object now, so we can break out of switch and foreach
                                    $breakForeach = true;
                                    break;
                                }
                                break;

                            case 'SEARCH_FOR_END_OF_DEFENITION_AND_CONTINUE':
                                // this was the wrong object, so throw everything away and continue with next definition
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
                        $result[self::KEY_FILE] = $configFolder->pwd() . $configFile;
                        $content = [];
                        foreach ($configContent as $contentPart) {
                            $trimmedContentPart = trim($this->searchUuids($contentPart));
                            if (str_contains($trimmedContentPart, "    ")) {
                                $keyAndValue = explode("    ", $trimmedContentPart);
                                $key = str_replace(";", "",
                                    str_replace(":", "",
                                        trim($keyAndValue[0])
                                    )
                                );
                                $value = trim($keyAndValue[count($keyAndValue) - 1]);
                                $content[$key] = $value;
                            }
                        }
                        $result[self::KEY_CONTENT] = $content;
                        //break config files foreach.
                        break;
                    }

                }
            } else {
                Log::error(sprintf('NagiosConfigParser: Folder %s is empty!', $configFolder->pwd()));
                return 'Folder ' . $configFolder->pwd() . ' is empty!';
            }

        }

        return $result;

    }

    /**
     * @param string $string
     * @return string
     */
    public function searchUuids($string = '') {
        if (!isset($this->uuidCache) || empty($this->uuidCache) || !is_array($this->uuidCache)) {
            $this->buildUuidCache();
        }

        $string = preg_replace_callback(UUID::regex(), [$this, 'replaceUuid'], $string);

        return $string;
    }

    private function replaceUuid($matches) {
        foreach ($matches as $match) {
            if (isset($this->uuidCache[$match])) {
                //Checking if name exists or if we need to use the container:
                if (isset($this->uuidCache[$match]['name'])) {
                    return $this->uuidCache[$match]['name'] . "[" . $match . "]";
                } else if (isset($this->uuidCache[$match]['container_name'])) {
                    return $this->uuidCache[$match]['container_name'] . "[" . $match . "]";
                } else {
                    return "name not found in DB[" . $match . "]";
                }

            }
            
            return "object not found in UUID cache[" . $match . "]";
        }
    }

    /**
     * @param string $uuid
     * @return string|array
     */
    public function searchByUuid($uuid = null) {
        if (isset($this->uuidCache[$uuid])) {
            return $this->uuidCache[$uuid];
        }

        return [];
    }

}
