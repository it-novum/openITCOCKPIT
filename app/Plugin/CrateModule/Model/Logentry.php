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

class Logentry extends CrateModuleAppModel
{
    public $useDbConfig = 'Crate';
    public $useTable = 'logentries';
    public $tablePrefix = 'statusengine_';
    public $maxBit = 2096895;

    public function listSettings($requestData)
    {
        $return = [
            'conditions'   => [],
            'paginator'    => [],
            'Listsettings' => [],
        ];

        if(isset($requestData['sort']) && isset($requestData['direction'])){
            $return['paginator']['order'] = [
                $requestData['sort'] =>$requestData['direction']
            ];
        }

        if (isset($requestData['Listsettings']['limit']) && is_numeric($requestData['Listsettings']['limit'])) {
            $return['paginator']['limit'] = $requestData['Listsettings']['limit'];
            $return['Listsettings']['limit'] = $return['paginator']['limit'];
        }

        if (isset($requestData['Listsettings']['logentry_type'])) {
            if (!is_array($requestData['Listsettings']['logentry_type'])) {
                $requestData['Listsettings']['logentry_type'] = $this->getTypesByBitValue(
                    $requestData['Listsettings']['logentry_type']
                );
            }
            $bitSelector = array_sum($requestData['Listsettings']['logentry_type']);

            $selected = [];
            foreach($requestData['Listsettings']['logentry_type'] as $bitValue){
                if($bitValue > 0){
                    $selected[] = $bitValue;
                }
            }

            if ($bitSelector < $this->maxBit && !empty($selected)) {
                //2096895 is the value, if the user ticket all checkboxes.
                $return['conditions'] = ['Logentry.logentry_type' => $selected];
                $return['Listsettings']['logentry_type'] = $bitSelector;
            }
        }
        return $return;

    }

    public function types()
    {
        $LogentryTypes = new \itnovum\openITCOCKPIT\Core\ValueObjects\LogentryTypes();
        return $LogentryTypes->getTypes();
    }

    /**
     * @param int $bitValue
     * @return array
     */
    public function getTypesByBitValue($bitValue){
        $types = [];
        foreach($this->types() as $type => $typeName){
            if($type & $bitValue){
                $types[] = $type;
            }
        }
        return $types;
    }

}