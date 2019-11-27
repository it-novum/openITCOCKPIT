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

App::uses('Helper', 'View');

/**
 * Class AppHelper
 * @deprecated
 */
class AppHelper extends Helper {

    /**
     * If we're in production mode, append the version to all assetes.
     *
     * @param string $path
     *
     * @return string
     * @deprecated
     */
    public function assetTimestamp($path) {
        Configure::load('version');
        if (Configure::read('debug') === 0) {
            $path .= '?v' . Configure::read('version');

            return $path;
        }
        // special case for module assets, which are routed through the CMS plugin
        // but Helper doesn't detect the absolute path to it correctly.
        else if (strpos($path, 'module_assets') !== false) {
            $path .= '?' . time();

            return $path;
        } else {
            return parent::assetTimestamp($path);
        }
    }

    /**
     * @param $change
     * @return mixed|string|null
     * @deprecated
     */
    public function change($change) {
        if ($change === false) {
            return __('false');
        }

        if ($change == 'true') {
            return __('true');
        }

        return $change;
    }

}
