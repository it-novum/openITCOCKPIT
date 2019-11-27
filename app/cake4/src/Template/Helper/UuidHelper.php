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

class UuidHelper extends AppHelper {

    /**
     * Initialize the Helper and set the needed variables
     *
     * @param CakePHP $viewFile
     *
     * @return void
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     */
    public function beforeRender($viewFile) {
        if (isset($this->_View->viewVars['hostUuids'])) {
            $this->hostUuids = $this->_View->viewVars['hostUuids'];
        }
        if (isset($this->_View->viewVars['serviceUuids'])) {
            $this->serviceUuids = $this->_View->viewVars['serviceUuids'];
        }

        if (isset($this->_View->viewVars['uuidCache'])) {
            $this->uuidCache = $this->_View->viewVars['uuidCache'];
        }
    }

    public function findHostname($uuid) {
        if (isset($this->hostUuids[$uuid])) {
            return $this->hostUuids[$uuid]['name'];
        }

        return __('Could not found hostname');
    }

    public function findServicename($uuid) {
        if (isset($this->serviceUuids[$uuid])) {
            return $this->serviceUuids[$uuid]['name'];
        }

        return __('Could not found servicename');
    }

    public function replaceUuids($string) {
        $string = preg_replace_callback(\itnovum\openITCOCKPIT\Core\UUID::regex(), function ($matches) {
            foreach ($matches as $match) {
                if (isset($this->uuidCache[$match])) {
                    //Checking if name exists or if we need to use the container:
                    if (isset($this->uuidCache[$match]['name'])) {
                        return '<strong class="text-primary">' . $this->uuidCache[$match]['name'] . '</strong>';
                    } else if (isset($this->uuidCache[$match]['container_name'])) {
                        return $this->uuidCache[$match]['container_name'] . '<strong class="text-primary">' . $match . '</strong>';
                    } else {
                        return '<strong class="txt-color-red">' . __('Name not found in database') . '[' . $match . ']</strong>';
                    }

                }

                return '<strong class="txt-color-red">' . __('Object not found in UUID cache') . '[' . $match . ']</strong>';
            }
        }, $string);

        return $string;
    }
}