<?php
// Copyright (C) <2018>  <it-novum GmbH>
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

class ConfigurationFile extends AppModel {

    /**
     * @param string $configFile
     * @return array|null
     */
    public function getConfigValuesByConfigFile($configFile) {
        return $this->find('all', [
            'recursive'  => -1,
            'conditions' => [
                'ConfigurationFile.config_file' => $configFile
            ]
        ]);
    }

    /**
     * @param $configFile
     * @return bool
     */
    public function saveConfigurationValuesForConfigFile($configFile, $records) {
        if (!$this->deleteAll([
            'ConfigurationFile.config_file' => $configFile
        ])) {
            return false;
        }

        return $this->saveAll($records);
    }

}
