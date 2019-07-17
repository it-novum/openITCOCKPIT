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

/**
 * Class ConfigurationFile
 * @deprecated
 */
class ConfigurationFile extends AppModel {

    /**
     * @param string $configFile
     * @return array|null
     * @deprecated
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
     * @deprecated
     */
    public function saveConfigurationValuesForConfigFile($configFile, $records) {
        if (!$this->deleteAll([
            'ConfigurationFile.config_file' => $configFile
        ])) {
            return false;
        }

        return $this->saveAll($records);
    }

    /**
     * @param array $currentConfiguration from Cake's findAll
     * @param array $newConfiguration for Cake's saveAll
     * @return bool
     * @deprecated
     */
    public function hasChanged($currentConfiguration, $newConfiguration) {
        $currentConfigKeyValue = [];
        foreach ($currentConfiguration as $record) {
            $key = $record['ConfigurationFile']['key'];
            $value = $record['ConfigurationFile']['value'];
            $currentConfigKeyValue[$key] = $value;
        }

        foreach ($newConfiguration as $record) {
            $key = $record['ConfigurationFile']['key'];
            $value = $record['ConfigurationFile']['value'];

            if (!isset($currentConfigKeyValue[$key])) {
                //Key not found in old configuration
                //mark configuration file to rewrite
                return true;
            }

            if ($currentConfigKeyValue[$key] != $value) {
                //Value has changed
                //mark configuration file to rewrite
                return true;
            }
        }
        return false;
    }

}
