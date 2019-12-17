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

namespace MapModule\Controller;

use itnovum\openITCOCKPIT\Core\UUID;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;


/**
 * Class BackgroundUploadsController
 * @property MapUpload $MapUpload
 * @property Mapicon $Mapicon
 */
class BackgroundUploadsController extends AppController {

    public $layout = 'Admin.default';
    public $uses = [
        'MapModule.MapUpload',
        'MapModule.Mapicon',
    ];


    public function upload() {
        if (empty($_FILES)) {
            $response = [
                'success' => false,
                'message' => __('There is no file to store')
            ];
            $this->set('response', $response);
            $this->viewBuilder()->setOption('serialize', ['response']);
            return;
        }

        $response = $this->MapUpload->getUploadResponse($_FILES['file']['error']);
        if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $backgroundImgDirectory = OLD_APP . 'Plugin' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'backgrounds';

            //check if upload folder exist
            if (!is_dir($backgroundImgDirectory)) {
                mkdir($backgroundImgDirectory);
            }

            $backgroundFolder = new Folder($backgroundImgDirectory);
            $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if (!$this->MapUpload->isFileExtensionSupported($fileExtension)) {
                $response = [
                    'success' => false,
                    'message' => __('File extension ".%s" not supported!', $fileExtension)
                ];
                $this->set('response', $response);
                $this->viewBuilder()->setOption('serialize', ['response']);
                return;
            }

            $uploadFilename = str_replace('.' . $fileExtension, '', pathinfo($_FILES['file']['name'], PATHINFO_BASENAME));
            $saveFilename = UUID::v4();
            $fullFilePath = $backgroundFolder->path . DS . $saveFilename . '.' . $fileExtension;
            try {
                if (!move_uploaded_file($_FILES['file']['tmp_name'], $fullFilePath)) {
                    throw new Exception(__('Cannot move uploaded file'));
                }

                $imageConfig = [
                    'fullPath'      => $fullFilePath,
                    'uuidFilename'  => $saveFilename,
                    'fileExtension' => $fileExtension
                ];
                $this->MapUpload->createThumbnailsFromBackgrounds($imageConfig, $backgroundFolder);
                $this->MapUpload->save([
                    'upload_type'  => MapUpload::TYPE_BACKGROUND,
                    'upload_name'  => $uploadFilename . '.' . $fileExtension,
                    'saved_name'   => $saveFilename . '.' . $fileExtension,
                    'user_id'      => $this->Auth->user('id'),
                    'container_id' => '1',
                ]);

                $response = [
                    'success'  => true,
                    'message'  => __('File uploaded successfully'),
                    'filename' => $saveFilename . '.' . $fileExtension
                ];
            } catch (Exception $e) {
                $response = [
                    'success' => false,
                    'message' => __('Upload failed: %s', $e->getMessage())
                ];
            }
        }


        $this->response->statusCode(200);
        if (!$response['success']) {
            $this->response->statusCode(500);
        }
        $this->set('response', $response);
        $this->viewBuilder()->setOption('serialize', ['response']);
    }

    public function delete() {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $filename = $this->request->getData('filename');

        $background = $this->MapUpload->find('first', [
            'recursive'  => -1,
            'conditions' => [
                'MapUpload.saved_name'   => $filename,
                'MapUpload.container_id' => $this->MY_RIGHTS,
            ],
        ]);
        if (empty($background)) {
            throw new NotFoundException();
        }

        if ($this->MapUpload->delete($background['MapUpload']['id'])) {
            $backgroundImgDirectory = OLD_APP . 'Plugin' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'backgrounds';

            if (file_exists($backgroundImgDirectory . DS . $filename)) {
                unlink($backgroundImgDirectory . DS . $filename);
            }

            if (file_exists($backgroundImgDirectory . DS . 'thumb' . DS . 'thumb_' . $filename)) {
                unlink($backgroundImgDirectory . DS . 'thumb' . DS . 'thumb_' . $filename);
            }

            $response = [
                'success' => true,
                'message' => __('Background deleted successfully.')
            ];
            $this->set('response', $response);
            $this->viewBuilder()->setOption('serialize', ['response']);
            return;
        }

        $this->response->statusCode(500);
        $response = [
            'success' => false,
            'message' => __('Error while deleting background.')
        ];
        $this->set('response', $response);
        $this->viewBuilder()->setOption('serialize', ['response']);
    }

    public function icon() {
        if (empty($_FILES)) {
            $response = [
                'success' => false,
                'message' => __('There is no file to store')
            ];
            $this->set('response', $response);
            $this->viewBuilder()->setOption('serialize', ['response']);
            return;
        }

        $response = $this->MapUpload->getUploadResponse($_FILES['file']['error']);
        if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $iconImgDirectory = OLD_APP . 'Plugin' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'icons';

            //$iconFolder = new Folder($iconImgDirectory);
            $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if (!$this->MapUpload->isFileExtensionSupported($fileExtension)) {
                $response = [
                    'success' => false,
                    'message' => __('File extension ".%s" not supported!', $fileExtension)
                ];
                $this->set('response', $response);
                $this->viewBuilder()->setOption('serialize', ['response']);
                return;
            }

            $fileName = preg_replace('/[^a-zA-Z0-9\.]+/', '', $_FILES['file']['name']);

            try {
                //check if icon folder exist
                if (!is_dir($iconImgDirectory)) {
                    mkdir($iconImgDirectory);
                }

                if (!move_uploaded_file($_FILES['file']['tmp_name'], $iconImgDirectory . DS . $fileName)) {
                    throw new Exception(__('Cannot move uploaded file'));
                }

                $response = [
                    'success'  => true,
                    'message'  => __('File uploaded successfully'),
                    'filename' => $fileName
                ];
            } catch (Exception $e) {
                $response = [
                    'success' => false,
                    'message' => __('Upload failed: %s', $e->getMessage())
                ];
            }
        }

        $this->response->statusCode(200);
        if (!$response['success']) {
            $this->response->statusCode(500);
        }
        $this->set('response', $response);
        $this->viewBuilder()->setOption('serialize', ['response']);
    }

    public function deleteIcon() {
        $iconImgDirectory = OLD_APP . 'Plugin' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'icons';
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $filename = $this->request->getData('filename');
        $fullFilePath = $iconImgDirectory . DS . $filename;

        if (!file_exists($fullFilePath) || is_dir($fullFilePath)) {
            throw new NotFoundException();
        }

        unlink($fullFilePath);
        if ($this->Mapicon->deleteAll(['Mapicon.icon' => $filename])) {
            $response = [
                'success' => true,
                'message' => __('Icon deleted successfully.')
            ];
            $this->set('response', $response);
            $this->viewBuilder()->setOption('serialize', ['response']);
            return;
        }

        $this->response->statusCode(500);
        $response = [
            'success' => false,
            'message' => __('Error while deleting icon.')
        ];
        $this->set('response', $response);
        $this->viewBuilder()->setOption('serialize', ['response']);
    }

    public function iconset() {
        if (empty($_FILES)) {
            $response = [
                'success' => false,
                'message' => __('There is no file to store')
            ];
            $this->set('response', $response);
            $this->viewBuilder()->setOption('serialize', ['response']);
            return;
        }

        $response = $this->MapUpload->getUploadResponse($_FILES['file']['error']);
        if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $iconsetImgDirectory = OLD_APP . 'Plugin' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'items';
            $tempZipsDirectory = OLD_APP . 'Plugin' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'temp';

            if (!is_dir($tempZipsDirectory)) {
                mkdir($tempZipsDirectory);
            }

            //$iconFolder = new Folder($iconImgDirectory);
            $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if ($fileExtension !== 'zip') {
                $response = [
                    'success' => false,
                    'message' => __('Iconsets needs to be packed as an .zip file.', $fileExtension)
                ];
                $this->set('response', $response);
                $this->viewBuilder()->setOption('serialize', ['response']);
                return;
            }

            $fileName = preg_replace('/[^a-zA-Z0-9\.\_]+/', '', $_FILES['file']['name']);

            try {
                //check if iconset folder exist
                if (!is_dir($iconsetImgDirectory)) {
                    mkdir($iconsetImgDirectory);
                }

                if (!move_uploaded_file($_FILES['file']['tmp_name'], $tempZipsDirectory . DS . $fileName)) {
                    throw new Exception(__('Cannot move uploaded file'));
                }

                $zipFile = new ZipArchive();
                $openZip = $zipFile->open($tempZipsDirectory . DS . $fileName);
                if (!$openZip) {
                    throw new Exception(__('Could not open uploaded zip file.'));
                }

                $unzipDirectory = $tempZipsDirectory . DS . 'uploaded_' . str_replace('.zip', '', $fileName);

                if (!is_dir($unzipDirectory)) {
                    mkdir($unzipDirectory);
                }
                $zipFile->extractTo($unzipDirectory);
                $zipFile->close();

                //Remove upoaded zip file
                unlink($tempZipsDirectory . DS . $fileName);

                $finder = new Finder();
                $finder->directories()->in($unzipDirectory);

                $hasDirectory = false;
                $iconsetName = null;
                $iconsetIcons = [];
                $uploadedIconsetDirectoryName = null;

                /** @var SplFileInfo $folder */
                foreach ($finder as $folder) {
                    //In the folder was a zip with the icons
                    $hasDirectory = true;
                    $uploadedIconsetDirectoryName = $folder->getFilename();
                    $iconsetName = preg_replace('/[^a-zA-Z0-9\.\_]+/', '', $uploadedIconsetDirectoryName);

                    /** @var SplFileInfo $image */
                    foreach ($finder->files()->in($unzipDirectory . DS . $uploadedIconsetDirectoryName) as $image) {
                        $iconsetIcons[$image->getFilename()] = [
                            'filename' => $image->getFilename(),
                            'path'     => $image->getPath(),
                            'full'     => $image->getPath() . DS . $image->getFilename()
                        ];
                    }
                    break; //Only one loop to get to the directory name
                }


                if ($hasDirectory === false) {
                    $iconsetName = preg_replace('/[^a-zA-Z0-9\.\_]+/', '', str_replace('.zip', '', $fileName));
                    //May be inside of the zip are only icons. (Not folder with icons)
                    /** @var SplFileInfo $image */
                    foreach ($finder->files()->in($unzipDirectory) as $image) {
                        $iconsetIcons[$image->getFilename()] = [
                            'filename' => $image->getFilename(),
                            'path'     => $image->getPath(),
                            'full'     => $image->getPath() . DS . $image->getFilename()
                        ];
                    }
                }

                if ($iconsetName === null || $iconsetName === '') {
                    //Remove tmp directory
                    $fs = new Filesystem();
                    $fs->remove($unzipDirectory);

                    throw new Exception('Iconset name is empty');
                }

                //Check if all required icons exists and make sure the images are PNGs
                $missingIcons = [];
                $notAPng = [];
                foreach ($this->MapUpload->getIconsNames() as $iconsName) {
                    if (!isset($iconsetIcons[$iconsName])) {
                        $missingIcons[] = $iconsName;
                    } else {
                        //Make sure we have a png
                        if (exif_imagetype($iconsetIcons[$iconsName]['full']) !== IMAGETYPE_PNG) {
                            $notAPng[] = $iconsName;
                        }
                    }
                }

                if (!empty($missingIcons) || !empty($notAPng)) {
                    $error = '';
                    if (!empty($missingIcons)) {
                        $error .= __(sprintf(
                            'Thow following icons are missing in uploaded zip archive: %s',
                            implode(', ', $missingIcons)
                        ));
                    }

                    if (!empty($notAPng)) {
                        $error .= __(sprintf(
                            'The following icons are not a PNG image: %s',
                            implode(', ', $notAPng)
                        ));
                    }

                    //Remove tmp directory
                    $fs = new Filesystem();
                    $fs->remove($unzipDirectory);

                    throw new Exception($error);

                }

                //Copy new icons into iconsets directory
                $destinationDirectory = $iconsetImgDirectory . DS . $iconsetName;
                if (is_dir($destinationDirectory)) {
                    throw new Exception(sprintf(
                        'Iconset "%s" already exists',
                        $iconsetName
                    ));
                }

                mkdir($destinationDirectory);
                if (!is_dir($destinationDirectory)) {

                    //Remove tmp directory
                    $fs = new Filesystem();
                    $fs->remove($unzipDirectory);
                    throw new Exception('Could not create directory: ' . $destinationDirectory);
                }

                foreach ($iconsetIcons as $icon) {
                    copy($icon['full'], $destinationDirectory . DS . $icon['filename']);
                }

                //Remove tmp directory
                $fs = new Filesystem();
                $fs->remove($unzipDirectory);

                $response = [
                    'success'     => true,
                    'message'     => __('File uploaded successfully'),
                    'iconsetname' => $iconsetName
                ];
            } catch (Exception $e) {
                $response = [
                    'success' => false,
                    'message' => __('Upload failed: %s', $e->getMessage())
                ];
            }
        }

        $this->response->statusCode(200);
        if (!$response['success']) {
            $this->response->statusCode(500);
        }
        $this->set('response', $response);
        $this->viewBuilder()->setOption('serialize', ['response']);
    }
}
