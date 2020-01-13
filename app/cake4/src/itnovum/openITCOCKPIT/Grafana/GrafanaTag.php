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

namespace itnovum\openITCOCKPIT\Grafana;

use \Cake\Filesystem\File;

class GrafanaTag {

    private $tagfilePath = '/etc/openitcockpit/app/Config/';
    private $tagfileName = 'openitcGrafanaTag.php';

    private function generateTag() {
        return uniqid('oitc');
    }

    public function writeTagfile() {
        $fullTagfilePath = $this->tagfilePath . $this->tagfileName;
        $tagfile = new File($fullTagfilePath);
        if ($tagfile->exists()) {
            $tagfile->delete();
        }
        $tagfile = new File($fullTagfilePath, true, 0644);

        try {
            $tagfile->write($this->generateTag());
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

    }

    private function readTagFile() {
        $fullTagfilePath = $this->tagfilePath . $this->tagfileName;
        $tagfile = new File($fullTagfilePath);
        try {
            if (!$tagfile->exists()) {
                //throw new Exception('Tag File could not be found in '. $this->tagfilePath);
                $this->writeTagfile();
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return $tagfile->read();
    }

    /**
     * Wrapper for readTagFile()
     */
    public function getTag() {
        return $this->readTagFile();
    }
}
