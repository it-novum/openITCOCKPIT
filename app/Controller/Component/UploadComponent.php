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

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class UploadComponent extends Component {

    public $path = null;
    public $userWidth = 250;
    public $userHeight = 150;

    public function setPath($path) {
        $this->path = $path; // Example /usr/share/openitcockpit/webroot/images/
    }

    public function uploadUserimage($fileFromUpload) {
        if (is_dir($this->path)) {
            $fileExtension = pathinfo($fileFromUpload['name'], PATHINFO_EXTENSION);
            $tmpName = $newFilename = \itnovum\openITCOCKPIT\Core\UUID::v4();
            if (move_uploaded_file($fileFromUpload['tmp_name'], $this->path . $tmpName)) {
                $newFilename = \itnovum\openITCOCKPIT\Core\UUID::v4() . '.png';
                if ($this->createUserImage($tmpName, $newFilename)) {
                    return $newFilename;
                }
            } else {
                return false;
            }
        } else {
            throw new InternalErrorException('Folder ' . $this->path . ' does not exists');
        }
    }

    public function createUserImage($tmpFilename, $newFilename) {
        if (file_exists($this->path . $tmpFilename)) {
            $file = $this->path . $tmpFilename;
            $newFile = $this->path . $newFilename;

            $imgsize = getimagesize($file);
            $width = $imgsize[0];
            $height = $imgsize[1];
            $imgtype = $imgsize[2];

            switch ($imgtype) {
                // 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM
                case 1:
                    $srcImg = imagecreatefromgif($file);
                    break;
                case 2:
                    $srcImg = imagecreatefromjpeg($file);
                    break;
                case 3:
                    $srcImg = imagecreatefrompng($file);
                    break;
                default:
                    unlink($file);

                    return false;
                    break;
            }

            $newWidth = 120;
            $newHeight = 120;

            //Thanks to http://php.net/manual/de/function.imagecopyresized.php#50019 :)

            if ($width > $height && $newWidth < $newHeight) {
                $newHeight = $height / ($width / $newWidth);
            } else if ($width < $height && $newHeight < $width) {
                $newWidth = $width / ($height / $newHeight);
            } else {
                $newHeight = $height;
                $newWidth = $width;
            }

            $destImg = imagecreatetruecolor($newWidth, $newHeight);
            $transparent = imagecolorallocatealpha($destImg, 0, 0, 0, 127);
            imagefill($destImg, 0, 0, $transparent);
            imagecopyresized($destImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagealphablending($destImg, false);
            imagesavealpha($destImg, true);
            imagepng($destImg, $newFile);

            imagedestroy($destImg);

            //Delete source image
            if (file_exists($file)) {
                unlink($file);
            }

            return true;

        }

        return false;
    }

}