<?php
// Copyright (C) <2018>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

namespace itnovum\openITCOCKPIT\Core\System;

use Symfony\Component\Finder\Finder;

class ImageUpload {

    public $supportedFileExtensions = [
        'jpg',
        'jpeg',
        'gif',
        'png'
    ];

    /**
     * @param $fileExtension
     */
    public function addFileExtensions($fileExtension){
        $this->supportedFileExtensions[] = $fileExtension;
    }

    /**
     * @return array
     */
    public function getFileExtensions(){
        return $this->supportedFileExtensions;
    }

    /**
     * @param $fileExtension
     * @return bool
     */
    public function isFileExtensionSupported($fileExtension) {
        return in_array(strtolower(trim($fileExtension)), $this->supportedFileExtensions, true);
    }

    /**
     * @param $imageConfig
     * @param Folder $Folder
     * @throws Exception
     */
    public function createThumbnailsFromBackgrounds($imageConfig, Folder $Folder) {

        $file = $imageConfig['fullPath'];

        //check if thumb folder exist
        if (!is_dir($Folder->path . DS . 'thumb')) {
            mkdir($Folder->path . DS . 'thumb');
        }

        $imgsize = getimagesize($file);
        $width = $imgsize[0];
        $height = $imgsize[1];
        $imgtype = $imgsize[2];
        $aspectRatio = $width / $height;

        $thumbnailWidth = 150;
        $thumbnailHeight = 150;


        switch ($imgtype) {
            /**
             * 1 => GIF
             * 2 => JPG
             * 3 => PNG
             * 4 => SWF
             * 5 => PSD
             * 6 => BMP
             * 7 => TIFF(intel byte order)
             * 8 => TIFF(motorola byte order)
             * 9 => JPC
             * 10 => JP2
             * 11 => JPX
             * 12 => JB2
             * 13 => SWC
             * 14 => IFF
             * 15 => WBMP
             * 16 => XBM
             */
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
                throw new Exception('Filetype not supported!');
                break;
        }

        //calculate the new height or width and keep the aspect ration
        if ($aspectRatio == 1) {
            //source image X = Y
            $newWidth = $thumbnailWidth;
            $newHeight = $thumbnailHeight;
        } else if ($aspectRatio > 1) {
            //source image X > Y
            $newWidth = $thumbnailWidth;
            $newHeight = ($thumbnailHeight / $aspectRatio);
        } else {
            //source image X < Y
            $newWidth = ($thumbnailWidth * $aspectRatio);
            $newHeight = $thumbnailHeight;
        }

        $destImg = imagecreatetruecolor($newWidth, $newHeight);
        $transparent = imagecolorallocatealpha($destImg, 0, 0, 0, 127);
        imagefill($destImg, 0, 0, $transparent);
        imageCopyResized($destImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagealphablending($destImg, false);
        imagesavealpha($destImg, true);


        //Save image to disk
        switch ($imgtype) {
            /**
             * 1 => GIF
             * 2 => JPG
             * 3 => PNG
             * 4 => SWF
             * 5 => PSD
             * 6 => BMP
             * 7 => TIFF(intel byte order)
             * 8 => TIFF(motorola byte order)
             * 9 => JPC
             * 10 => JP2
             * 11 => JPX
             * 12 => JB2
             * 13 => SWC
             * 14 => IFF
             * 15 => WBMP
             * 16 => XBM
             */
            case 1:
                imagegif($destImg, $Folder->path . DS . 'thumb' . DS . 'thumb_' . $imageConfig['uuidFilename'] . '.' . $imageConfig['fileExtension']);
                break;
            case 2:
                imagejpeg($destImg, $Folder->path . DS . 'thumb' . DS . 'thumb_' . $imageConfig['uuidFilename'] . '.' . $imageConfig['fileExtension']);
                break;
            case 3:
                imagepng($destImg, $Folder->path . DS . 'thumb' . DS . 'thumb_' . $imageConfig['uuidFilename'] . '.' . $imageConfig['fileExtension']);
                break;
            default:
                throw new Exception('Filetype not supported!');
                break;
        }
        imagedestroy($destImg);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getIconSets() {
        $basePath = APP . 'Plugin' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'items';
        $finder = new Finder();
        $finder->directories()->in($basePath);

        $allIconsets = $this->find('all', [
            'conditions' => [
                'MapUpload.upload_type' => self::TYPE_ICON_SET
            ],
            'recursive'  => -1
        ]);

        $availableIconsets = [];
        foreach ($allIconsets as $iconset) {
            if (file_exists($basePath . DS . $iconset['MapUpload']['saved_name'] . DS . 'ok.png')) {
                $availableIconsets[$iconset['MapUpload']['saved_name']] = $iconset;
            }
        }

        /** @var \Symfony\Component\Finder\SplFileInfo $folder */
        foreach ($finder as $folder) {
            $dirName = $folder->getFilename();

            //Does icon set exists in database?
            if (!isset($availableIconsets[$dirName])) {
                if (file_exists($basePath . DS . $dirName . DS . 'ok.png')) {
                    //Icon set is missing in database, add it
                    $this->create();
                    $data = [
                        'upload_type'  => MapUpload::TYPE_ICON_SET,
                        'upload_name'  => $dirName,
                        'saved_name'   => $dirName,
                        'user_id'      => null,
                        'container_id' => 1
                    ];
                    if ($this->save($data)) {
                        $data['id'] = $this->id;
                        $availableIconsets[$dirName] = $data;
                    }

                }
            }
        }
        return array_values($availableIconsets);
    }


    public function getUploadResponse($error) {
        switch ($error) {
            case UPLOAD_ERR_OK:
                $response = [
                    'success' => true,
                    'message' => __('File uploaded successfully')
                ];
                break;

            case UPLOAD_ERR_INI_SIZE:
                $response = [
                    'success' => false,
                    'message' => __('The uploaded file exceeds the upload_max_filesize directive in php.ini')
                ];
                break;

            case UPLOAD_ERR_FORM_SIZE:
                $response = [
                    'success' => false,
                    'message' => __('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form')
                ];
                break;

            case UPLOAD_ERR_PARTIAL:
                $response = [
                    'success' => false,
                    'message' => __('The uploaded file was only partially uploaded')
                ];
                break;

            case UPLOAD_ERR_NO_FILE:
                $response = [
                    'success' => false,
                    'message' => __('No file was uploaded')
                ];
                break;

            case UPLOAD_ERR_NO_TMP_DIR:
                $response = [
                    'success' => false,
                    'message' => __('Missing a temporary folder.')
                ];
                break;

            case UPLOAD_ERR_CANT_WRITE:
                $response = [
                    'success' => false,
                    'message' => __('Failed to write file to disk.')
                ];
                break;

            case UPLOAD_ERR_EXTENSION:
                $response = [
                    'success' => false,
                    'message' => __('A PHP extension stopped the file upload.')
                ];
                break;
        }
        return $response;
    }

}
