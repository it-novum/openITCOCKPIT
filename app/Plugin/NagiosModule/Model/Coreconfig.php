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

class Coreconfig extends NagiosModuleAppModel {
    public $useTable = false;

    /*
     * NOTICE:
     * You can use this Model, where you cant use the CoreConfigComponent, for example in a shell
     */

    function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
        $this->Systemsetting = ClassRegistry::init('Systemsetting');
        /** @var $Systemsettings App\Model\Table\SystemsettingsTable */
        $Systemsettings = TableRegistry::getTableLocator()->get('Systemsettings');
        $this->_systemsettings = $Systemsettings->findAsArray();
        $this->Config = [];
    }

    public function _read($key = null) { //sadly read() is used by cakePHP, we dont want to overright this!
        if (empty($this->Config)) {
            $this->loadConfigAsArray();
        }
        if (isset($this->Config[$key])) {
            return $this->Config[$key];
        }

        return flase;
    }

    public function loadConfigAsArray() {
        $config = $this->_systemsettings['MONITORING']['MONITORING.CORECONFIG'];
        $coreconfig = fopen($config, "r");
        while (!feof($coreconfig)) {
            $line = trim(fgets($coreconfig));
            $strpos = strpos($line, '#');

            if ($line != '' && ($strpos === false || $strpos > 0)) {
                $parsed = explode('=', $line, 2);
                $this->Config[$parsed[0]] = $parsed[1];
            }
        }
    }
}
