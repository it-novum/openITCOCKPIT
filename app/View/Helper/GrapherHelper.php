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
 * Class GrapherHelper
 * @deprecated
 */
class GrapherHelper extends AppHelper {

    /**
     * @param $error
     * @return array
     * @deprecated
     */
    public function createGrapherErrorPng($error) {

        $targetPath = WWW_ROOT . 'img' . DS . 'graphs';
        $fileName = md5(rand() . time() . rand()) . '.png';

        $img = imagecreatetruecolor(947, 173);
        imagesavealpha($img, true);
        $background = imagecolorallocatealpha($img, 255, 110, 110, 0);
        $textColor = imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 0, 0, $background);

        imagestring($img, 5, 5, 5, 'Error:', $textColor);
        imagestring($img, 5, 5, 25, $error, $textColor);

        imagepng($img, $targetPath . DS . $fileName);
        imagedestroy($img);

        return [
            'webPath'  => DS . 'img' . DS . 'graphs' . DS . $fileName,
            'diskPath' => $targetPath . DS . $fileName,
        ];
    }
}