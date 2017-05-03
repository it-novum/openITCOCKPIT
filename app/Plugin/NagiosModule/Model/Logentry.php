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

class Logentry extends NagiosModuleAppModel
{
    //public $useDbConfig = 'nagios';
    public $useTable = 'logentries';
    public $primaryKey = 'logentry_id';
    public $tablePrefix = 'nagios_';
    public $maxBit = 2096895;

    public function listSettings($requestData)
    {
        $return = [
            'conditions'   => [],
            'paginator'    => [],
            'Listsettings' => [],
        ];

        if (isset($requestData['Listsettings']['limit']) && is_numeric($requestData['Listsettings']['limit'])) {
            $return['paginator']['limit'] = $requestData['Listsettings']['limit'];
            $return['Listsettings']['limit'] = $return['paginator']['limit'];
        }

        if (isset($requestData['Listsettings']['logentry_type'])) {
            if (!is_array($requestData['Listsettings']['logentry_type'])) {
                $requestData['Listsettings']['logentry_type'] = [$requestData['Listsettings']['logentry_type']];
            }
            $bitSelector = array_sum($requestData['Listsettings']['logentry_type']);
            if ($bitSelector < $this->maxBit) {
                //2096895 is the value, if the user ticket all checkboxes.
                $return['conditions'] = ['Logentry.logentry_type & '.$bitSelector];
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

}