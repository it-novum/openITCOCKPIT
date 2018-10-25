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

class Graphgenerator extends AppModel {
    var $useTable = false;

    public function serviceGraph($hostUuid, $serviceUuid) {
        $this->_basePath();
    }

    private function _basePath() {
        if (!isset($this->rrd_path)) {
            $this->rrd_path = Configure::read('rrd.path');
            if (!is_dir($this->rrd_path)) {
                unset($this->rrd_path);
                throw new NotFoundException(__('RRD file storage path not found'));
            }
        }

        return $this->rrd_path;
    }

}
