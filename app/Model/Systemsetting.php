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

/**
 * Class Systemsetting
 * @deprecated
 */
class Systemsetting extends AppModel {

    /**
     * @return array
     * @deprecated
     */
    public function findNice() {
        $systemsettings = $this->find('all');
        $all_systemsettings = [];

        foreach ($systemsettings as $systemsetting) {
            $all_systemsettings[$systemsetting['Systemsetting']['section']][] = $systemsetting['Systemsetting'];
        }

        // sort the list like it is in openITCOCKPIT\InitialDatabase\Systemsettings
        // it is just sorting, no deletions, no additions
        require_once OLD_APP . 'src' . DS . 'itnovum' . DS . 'openITCOCKPIT' . DS . 'InitialDatabase' . DS . 'Systemsetting.php';
        $mySytemsettings = new itnovum\openITCOCKPIT\InitialDatabase\Systemsetting(new Model());
        $myData = $mySytemsettings->getData();
        $sortedSystemSettingsSchema = $sortedSystemSettings = [];
        foreach ($myData as $singleSetting) {
            $sortedSystemSettingsSchema[$singleSetting['Systemsetting']['section']][] = $singleSetting['Systemsetting']['key'];
        }

        foreach ($sortedSystemSettingsSchema as $sSectionName => $sSection) {
            foreach ($sSection as $sSettingOptionKey) {

                // looping through our Settings
                foreach ($all_systemsettings as $nsSectionName => $nsSection) {
                    if ($sSectionName === $nsSectionName) {
                        foreach ($nsSection as $nsSectionK => $nsSettingOption) {
                            if ($sSettingOptionKey === $nsSettingOption['key']) {
                                $sortedSystemSettings[$sSectionName][] = $nsSettingOption;
                                unset($all_systemsettings[$nsSectionName][$nsSectionK]);
                                break 2;
                            }
                        }
                    }
                }

            }
        }

        // if in DB there are some options, but not in Schema, we place them at the end
        foreach ($all_systemsettings as $nsSectionName => $nsSection) {
            foreach ($nsSection as $nsSettingOption) {
                $sortedSystemSettings[$nsSectionName][] = $nsSettingOption;
            }
        }

        return $sortedSystemSettings;
    }

    /**
     * @return array
     * @deprecated
     */
    public function findAsArray() {
        $return = [];
        $systemsettings = $this->findNice();

        foreach ($systemsettings as $key => $values) {
            $return[$key] = [];
            foreach ($values as $value) {
                $return[$key][$value['key']] = $value['value'];
            }
        }

        return $return;
    }

    /**
     * @param string $section
     * @return array
     * @deprecated
     */
    public function findAsArraySection($section = '') {
        $return = [];
        $systemsettings = $this->findAllBySection($section);

        $all_systemsettings = [];
        $all_systemsettings[$section] = Hash::extract($systemsettings, '{n}.Systemsetting[section=' . $section . ']');

        foreach ($all_systemsettings as $key => $values) {
            $return[$key] = [];
            foreach ($values as $value) {
                $return[$key][$value['key']] = $value['value'];
            }
        }
        return $return;
    }

    /**
     * @return mixed
     * @deprecated
     */
    public function getMasterInstanceName() {
        if (!Cache::read('systemsettings_master_instance', 'permissions')) {
            $name = $this->findAsArraySection('FRONTEND')['FRONTEND']['FRONTEND.MASTER_INSTANCE'];
            Cache::write('systemsettings_master_instance', $name, 'permissions');
        }
        return Cache::read('systemsettings_master_instance', 'permissions');
    }

    /**
     * @return mixed
     * @deprecated
     */
    public function getQueryHandlerPath() {
        if (!Cache::read('systemsettings_qh_path', 'permissions')) {
            $path = $this->findByKey('MONITORING.QUERY_HANDLER')['Systemsetting']['value'];
            Cache::write('systemsettings_qh_path', $path, 'permissions');
        }
        return Cache::read('systemsettings_qh_path', 'permissions');
    }

}