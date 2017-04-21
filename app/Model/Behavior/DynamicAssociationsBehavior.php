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

class DynamicAssociationsBehavior extends ModelBehavior {

    /*
     * the setup function gets called by cake automaticly like __construct in normal php classes
     */
    public function setup(Model $model, $settings = []) {
        $this->dynamicAssociations = $this->_loadDynamicAssociationsConfiguration();
    }

    /*
     * This function searches for the associations.php in $PlugnName/config/ and returns the
     * config content.
     */
    protected function _loadDynamicAssociationsConfiguration() {
        $configFileName = 'associations';
        $dynamicAssociations = [];

        $modulePlugins = array_filter(CakePlugin::loaded(), function ($value) {
            return strpos($value, 'Module') !== false;
        });
        foreach ($modulePlugins as $pluginName) {
            Configure::load($pluginName . '.' . $configFileName, 'silent', 'false');
        }

        $dynamicAssociations = Configure::read($configFileName);

        return $dynamicAssociations;
    }

    /*
     * Is called by the AppModel and returns needed dynamic associations for the current model
     */
    public function dynamicAssociations($modelName, $modelCallback) {
        if (isset($this->dynamicAssociations[$modelName])) {
            if (in_array($modelCallback, $this->dynamicAssociations[$modelName]['callbacks'])) {
                //Avoud php overload proerty error
                $return = $this->dynamicAssociations[$modelName];
                unset($return['callbacks']);

                return $return;
            }

        }

        return [];
    }

    public function dynamicAssociationsIgnoreCallback($modelName) {
        if (isset($this->dynamicAssociations[$modelName])) {
            $return = $this->dynamicAssociations[$modelName];
            unset($return['callbacks']);
            return $return;

        }

        return [];
    }
}