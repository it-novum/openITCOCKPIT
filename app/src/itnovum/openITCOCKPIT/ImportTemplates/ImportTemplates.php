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

namespace itnovum\openITCOCKPIT\ImportTemplates;

/**
 * Class ImportTemplates
 * @package itnovum\openITCOCKPIT\ImportTemplates
 */
class ImportTemplates {
    public $mapping = [];
    //root path for json and check files
    public $path = '';
    public $contactId = [1];
    public $macroNames;

    /**
     * ImportTemplates constructor.
     * @param \Model $servicetemplate
     * @param \Model $servicetemplategroup
     * @param \Model $hosttemplate
     * @param \Model $contact
     * @param \Model $command
     * @param \Model $commandargument
     * @param \Model $macro
     */
    public function __construct(\Model $servicetemplate, \Model $servicetemplategroup, \Model $hosttemplate, \Model $contact, \Model $command, \Model $commandargument, \Model $macro) {
        $this->Servicetemplate = $servicetemplate;
        $this->Servicetemplategroup = $servicetemplategroup;
        $this->Hosttemplate = $hosttemplate;
        $this->Contact = $contact;
        $this->Command = $command;
        $this->Commandargument = $commandargument;
        $this->Macro = $macro;
    }


    /**
     * check if dependencies for installing the templates
     * @throws \Exception
     */
    public function checkDependencies() {
        $contact = $this->Contact->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'Contact.name' => 'info'
            ],
            'fields'     => [
                'Contact.id'
            ]
        ]);
        if (empty($contact)) {
            throw new \Exception('Found no "info" contact on your System. To Continue the Installation Please create an "info" Contact!');
        }
        $this->contactId = \Hash::extract($contact, 'Contact.id');
    }

    /**
     * @param $files
     */
    public function startInstall($files) {
        foreach ($files as $key => $file) {
            $stData = $this->readJsonFile($file);
            switch ($key) {
                case 'Macro':
                    $dataToSave = $this->setMacroNames($stData);
                    break;
                case 'Command':
                    $dataToSave = $this->replaceMacros($stData, $this->macroNames);
                    break;
                case 'Servicetemplate':
                    $dataToSave = $this->modifyServicetemplatedata($stData);
                    break;
                case 'Servicetemplategroup':
                    $dataToSave = $this->modifyServicetemplategroupdata($stData);
                    break;
                case 'Hosttemplate':
                    $dataToSave = $this->modifyHosttemplatedata($stData);
                    break;
            }
            $this->saveData($dataToSave);
        }
    }


    /**
     * saves the Template Data into the Database
     * @param $dataToSave
     * @return bool
     */
    private function saveData($dataToSave) {
        try {
            $model = key($dataToSave[0]);
            foreach ($dataToSave as $data) {
                //calling model->create() so we can use save in a loop
                $this->{$model}->create();
                //check if the record already exists
                switch ($model) {
                    case 'Macro':
                        $field = 'description';
                        break;
                    case 'Servicetemplategroup':
                        $field = 'uuid';
                        break;
                    default:
                        $field = 'name';
                        break;
                }

                $existingData = $this->{$model}->find('first', [
                    'recursive'  => -1,
                    'conditions' => [
                        $model . '.' . $field => $data[$model][$field]
                    ]
                ]);


                if (!empty($existingData)) {
                    //the data already exists. Skip the loop for this record
                    switch ($model) {
                        case 'Command':
                            //add mapping for commands with the already existing record from the database
                            $this->mapping['Command'][$data['Command']['uuid']] = $existingData['Command']['id'];
                            $this->mapCommandArgs($existingData['Command']['id']);
                            break;
                        case 'Servicetemplate':
                            //add mapping for servicetemplates with the already existing record from the database
                            $this->mapping['Template'][$data['Servicetemplate']['uuid']] = $existingData['Servicetemplate']['id'];
                            break;
                    }
                    continue;
                }
                if ($model == 'Command') {
                    $data = \Hash::remove($data, 'Commandargument.{n}.id');
                }

                if ($this->{$model}->saveAll($data)) {
                    switch ($model) {
                        case 'Command':
                            $this->mapping['Command'][$data['Command']['uuid']] = $this->{$model}->id;
                            $this->mapCommandArgs($this->{$model}->id);
                            break;
                        case 'Servicetemplate':
                            $this->mapping['Template'][$data['Servicetemplate']['uuid']] = $this->{$model}->id;
                            break;

                    }
                } else {
                    print_r($data);
                    print_r($this->{$model}->validationErrors);
                    throw new \Exception('the data for ' . $model . ' ' . $data[$model]['name'] . ' could not be saved');
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            echo "\n";
        }
        return false;
    }

    /**
     * reads a json file and return the json representation as php array
     * @param $file - the json file to read
     * @return mixed - the php array representation of the json
     */
    private function readJsonFile($file) {
        try {
            if (!file_exists($file)) {
                throw new \Exception($file . ' could not be found');
            }
            $fileData = file_get_contents($file);
            $data = json_decode($fileData, true);
            $jsonErr = json_last_error();
            if ($jsonErr != JSON_ERROR_NONE) {
                throw  new \Exception($jsonErr);
            }
            return $data;
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * @param $hosttemplateData
     * @return mixed
     */
    private function modifyHosttemplatedata($hosttemplateData) {
        if (!empty($this->mapping['Commandarguments'])) {
            foreach ($hosttemplateData as $key => $hosttemplate) {
                $commandId = $this->mapping['Command'][$hosttemplate['Hosttemplate']['command_id']];
                //replacing the command uuid with the command id
                $hosttemplateData[$key]['Hosttemplate']['command_id'] = $commandId;
                $i = 0;
                //go through the hosttemplatecommandarguments
                if (!empty($hosttemplate['Hosttemplatecommandargumentvalue'])) {
                    foreach ($hosttemplate['Hosttemplatecommandargumentvalue'] as $stcavKey => $stcav) {
                        //replace the commandargument ids from the servicetemplatecommandarguments
                        // with the commandargument ids (the id they have after save)
                        if (array_key_exists($commandId, $this->mapping['Commandarguments']) && !empty($this->mapping['Commandarguments'][$commandId])) {
                            $hosttemplateData[$key]['Hosttemplatecommandargumentvalue'][$stcavKey]['commandargument_id'] = $this->mapping['Commandarguments'][$commandId][$i];
                            $i++;
                        }
                    }
                }
            }
        }
        return $hosttemplateData;
    }

    /**
     * @param $servicetemplateData
     * @return mixed
     */
    private function modifyServicetemplatedata($servicetemplateData) {
        if (!empty($this->mapping['Commandarguments'])) {
            foreach ($servicetemplateData as $key => $servicetemplate) {
                $commandId = $this->mapping['Command'][$servicetemplate['Servicetemplate']['command_id']];
                //replacing the command uuid with the command id
                $servicetemplateData[$key]['Servicetemplate']['command_id'] = $commandId;
                //insert a new UUID for the Servicetemplates
                //$servicetemplateData[$key]['Servicetemplate']['uuid'] = UUID::v4();
                $i = 0;
                //go through the servicetemplatecommandarguments
                if (!empty($servicetemplate['Servicetemplatecommandargumentvalue'])) {
                    foreach ($servicetemplate['Servicetemplatecommandargumentvalue'] as $stcavKey => $stcav) {
                        //replace the commandargument ids from the servicetemplatecommandarguments
                        // with the commandargument ids (the id they have after save)
                        if (array_key_exists($commandId, $this->mapping['Commandarguments']) && !empty($this->mapping['Commandarguments'][$commandId])) {
                            $servicetemplateData[$key]['Servicetemplatecommandargumentvalue'][$stcavKey]['commandargument_id'] = $this->mapping['Commandarguments'][$commandId][$i];
                            $i++;
                        }
                    }
                }
            }
        }
        return $servicetemplateData;
    }

    /**
     * @param $servicetemplategroupData
     * @return mixed
     */
    private function modifyServicetemplategroupdata($servicetemplategroupData) {
        foreach ($servicetemplategroupData as $keyGroup => $servicetemplate) {
            $templateIds = array_values($this->mapping['Template']);
            $servicetemplategroupData[$keyGroup]['Servicetemplate'] = $templateIds;
            $servicetemplategroupData[$keyGroup]['Servicetemplategroup']['Servicetemplate'] = $templateIds;
        }
        return $servicetemplategroupData;
    }


    /**
     * maps the command ids with the commandargument ids
     * @param $commandId
     */
    private function mapCommandArgs($commandId) {
        $commands = $this->Command->find('all', [
            'conditions' => [
                'id' => $commandId,
            ],
        ]);
        $commandargIds = \Hash::filter(\Hash::extract($commands, '{n}.Commandargument.{n}.id'));
        $this->mapping['Commandarguments'][$commandId] = $commandargIds;
    }

    /**
     * @param $macros
     * @return mixed
     */
    private function setMacroNames($macros) {
        $lastMacro = $this->Macro->find('first', [
            'order' => [
                'Macro.id' => 'DESC'
            ],
        ]);

        preg_match('/(\d+)/', $lastMacro['Macro']['name'], $erg);
        $number = $erg[0] + 1;
        $macroNames = [];

        foreach ($macros as $key => $macro) {
            $macroNames[$macros[$key]['Macro']['name']] = '$USER' . $number . '$';
            $macros[$key]['Macro']['name'] = '$USER' . $number . '$';
            $number++;
        }

        $this->macroNames = $macroNames;
        return $macros;
    }

    /**
     * @param $commands
     * @param $macros
     * @return mixed
     */
    private function replaceMacros($commands, $macros) {
        if (empty($macros)) {
            return $commands;
        }

        foreach ($commands as $key => $command) {
            $commands[$key]['Command']['command_line'] = str_replace(array_keys($macros), array_values($macros), $commands[$key]['Command']['command_line']);
        }
        return $commands;
    }
}
