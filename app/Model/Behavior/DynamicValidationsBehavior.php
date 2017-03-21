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

App::uses('ModelBehavior', 'Model');

class DynamicValidationsBehavior extends ModelBehavior {

    /*
     * the setup function gets called by cake automaticly like __construct in normal php classes
     */
    public function setup(Model $model, $settings = []) {
        $this->dynamicValidations = $this->_loadDynamicValidationConfiguration();
    }

    /*
     * This function searches for the validations.php in $PluginName/config/ and returns the
     * config content.
     */
    protected function _loadDynamicValidationConfiguration() {
        $configFileName = 'validations';
        $dynamicValidations = [];

        $modulePlugins = array_filter(CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });
        foreach ($modulePlugins as $pluginName) {
            Configure::load($pluginName . '.' . $configFileName, 'silent', 'false');
        }

        $dynamicValidations = Configure::read($configFileName);
        return $dynamicValidations;
    }

    /*
     * Is called by the AppModel and returns needed dynamic validations for the current model
     */
    public function dynamicValidations($modelName) {
        if (isset($this->dynamicValidations[$modelName])) {
            return $this->dynamicValidations[$modelName];
        }
        return [];
    }
}