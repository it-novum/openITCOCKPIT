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
App::uses('UUID', 'Lib');

/**
 * Class BackgroundUploadsController
 * @property MapUpload $MapUpload
 * @property Mapicon $Mapicon
 */
class BackgroundUploadsController extends MapModuleAppController {

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
            $this->set('_serialize', ['response']);
            return;
        }

        $response = $this->MapUpload->getUploadResponse($_FILES['file']['error']);
        if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $backgroundImgDirectory = APP . 'Plugin' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'backgrounds';
            $backgroundFolder = new Folder($backgroundImgDirectory);
            $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if (!$this->MapUpload->isFileExtensionSupported($fileExtension)) {
                $response = [
                    'success' => false,
                    'message' => __('File extension ".%s" not supported!', $fileExtension)
                ];
                $this->set('response', $response);
                $this->set('_serialize', ['response']);
                return;
            }

            $uploadFilename = str_replace('.' . $fileExtension, '', pathinfo($_FILES['file']['name'], PATHINFO_BASENAME));
            $saveFilename = UUID::v4();
            $fullFilePath = $backgroundFolder->path . DS . $saveFilename . '.' . $fileExtension;
            try {
                //check if upload folder exist
                if (!is_dir($backgroundImgDirectory)) {
                    mkdir($backgroundImgDirectory);
                }

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
        $this->set('_serialize', ['response']);
    }

    public function delete() {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $filename = $this->request->data('filename');

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
            $backgroundImgDirectory = APP . 'Plugin' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'backgrounds';

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
            $this->set('_serialize', ['response']);
            return;
        }

        $this->response->statusCode(500);
        $response = [
            'success' => false,
            'message' => __('Error while deleting background.')
        ];
        $this->set('response', $response);
        $this->set('_serialize', ['response']);
    }

    public function icon() {
        if (empty($_FILES)) {
            $response = [
                'success' => false,
                'message' => __('There is no file to store')
            ];
            $this->set('response', $response);
            $this->set('_serialize', ['response']);
            return;
        }

        $response = $this->MapUpload->getUploadResponse($_FILES['file']['error']);
        if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $iconImgDirectory = APP . 'Plugin' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'icons';

            //$iconFolder = new Folder($iconImgDirectory);
            $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

            if (!$this->MapUpload->isFileExtensionSupported($fileExtension)) {
                $response = [
                    'success' => false,
                    'message' => __('File extension ".%s" not supported!', $fileExtension)
                ];
                $this->set('response', $response);
                $this->set('_serialize', ['response']);
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
        $this->set('_serialize', ['response']);
    }

    public function deleteIcon(){
        $iconImgDirectory = APP . 'Plugin' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'icons';
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }

        $filename = $this->request->data('filename');
        $fullFilePath = $iconImgDirectory . DS . $filename;

        if (!file_exists($fullFilePath) || is_dir($fullFilePath)) {
            throw new NotFoundException();
        }

        unlink($fullFilePath);
        if($this->Mapicon->deleteAll(['Mapicon.icon' => $filename])){
            $response = [
                'success' => true,
                'message' => __('Icon deleted successfully.')
            ];
            $this->set('response', $response);
            $this->set('_serialize', ['response']);
            return;
        }

        $this->response->statusCode(500);
        $response = [
            'success' => false,
            'message' => __('Error while deleting icon.')
        ];
        $this->set('response', $response);
        $this->set('_serialize', ['response']);


    }

    /**
     * @todo REMOVE ME!
     */
    public function uploadIconsSet() {
        $this->autoRender = false;
        if (empty($_FILES)) {
            throw new ForbiddenException(__('There is no file to store'));
        }

        $itemsImgDirectory = APP . 'Plugin' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'items';
        $tempZipsDirectory = APP . 'Plugin' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'temp';

        //check if upload folder exist
        if (!is_dir($itemsImgDirectory)) {
            mkdir($itemsImgDirectory);
            chmod($itemsImgDirectory, 0777);
        }
        if (!is_dir($tempZipsDirectory)) {
            mkdir($tempZipsDirectory);
            chmod($tempZipsDirectory, 0777);
        }

        $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $uploadFilename = str_replace('.' . $fileExtension, '', pathinfo($_FILES['file']['name'], PATHINFO_BASENAME));
        $saveFilename = preg_replace("/[^a-zA-Z0-9]+/", "", $uploadFilename);

        $zipTempFolder = new Folder($tempZipsDirectory);
        $fullZipTempPath = $zipTempFolder->path . DS . $saveFilename . '.zip';
        $fullFolderTempPath = $zipTempFolder->path . DS . $saveFilename;

        try {
            if ($fileExtension !== 'zip') {
                throw new Exception(__('Only zip files are accepted'));
            }

            if ($_FILES['file']['error'] === 1) {
                throw new Exception('The uploaded file exceeds the upload_max_filesize directive in php.ini');
            }

            if (is_dir($itemsImgDirectory . DS . $saveFilename)) {
                throw new Exception(__('Icons set already exists'), 13);
            }

            mkdir($fullFolderTempPath);
            chmod($fullFolderTempPath, 0777);

            if (!move_uploaded_file($_FILES['file']['tmp_name'], $fullZipTempPath)) {
                throw new Exception(__('Cannot upload zip'));
            }

            $myZip = new ZipArchive;
            $openZip = $myZip->open($fullZipTempPath);
            if (!$openZip) {
                throw new Exception(__('Cannot unzip file'));
            }
            $myZip->extractTo($fullFolderTempPath);
            $myZip->close();

            $iconsNames = $this->MapUpload->getIconsNames();
            $iconsDir = $this->getIconsSubDirectory($fullFolderTempPath, $iconsNames);

            if (is_null($iconsDir)) {
                throw new Exception(__('Please check the zip file. It must contain all icons: ' . implode(', ', $iconsNames)));
            }

            mkdir($itemsImgDirectory . DS . $saveFilename);
            foreach (scandir($iconsDir) as $object) {
                if ($object != "." && $object != ".." && in_array($object, $iconsNames))
                    copy($iconsDir . DS . $object, $itemsImgDirectory . DS . $saveFilename . DS . $object);
            }

            $this->MapUpload->save([
                'upload_type'  => MapUpload::TYPE_ICON_SET,
                'upload_name'  => $uploadFilename,
                'saved_name'   => $saveFilename,
                'user_id'      => $this->Auth->user('id'),
                'container_id' => '1',
            ]);
            echo 'Upload successful';
        } catch (Exception $e) {
            if (is_dir($itemsImgDirectory . DS . $saveFilename) && $e->getCode() !== 13) {
                $this->removeDirectory($itemsImgDirectory . DS . $saveFilename);
            }
            throw new ForbiddenException($uploadFilename . '.' . $fileExtension . ': ' . $e->getMessage());

        } finally {
            if (is_file($fullZipTempPath)) {
                unlink($fullZipTempPath);
            }
            if (is_dir($fullFolderTempPath)) {
                $this->removeDirectory($fullFolderTempPath);
            }
        }


    }

    /**
     * @todo REMOVE ME!
     */
    private function getIconsSubDirectory($startDir, $iconsNames) {
        $this->autoRender = false;

        $iconDir = null;
        foreach (scandir($startDir) as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($startDir . DS . $object)) {
                    $iconDir = $this->getIconsSubDirectory($startDir . DS . $object, $iconsNames);
                    if (!is_null($iconDir))
                        return $iconDir;
                } else if (($keyO = array_search($object, $iconsNames)) !== false) {
                    unset($iconsNames[$keyO]);
                }
            }
        }

        if (empty($iconsNames)) { // array contains the rest of icons we didn't find
            return $startDir;
        }

        return $iconDir;
    }

    /**
     * @todo REMOVE ME!
     */
    private function removeDirectory($dir) {
        $this->autoRender = false;

        foreach (scandir($dir) as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . "/" . $object))
                    $this->removeDirectory($dir . "/" . $object);
                else
                    unlink($dir . "/" . $object);
            }
        }
        rmdir($dir);
    }

    /**
     * @todo REMOVE ME!
     */
    public function deleteIconsSet($setId) {
        $this->autoRender = false;

        $containerIds = $this->Tree->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $mapUpload = $this->MapUpload->find('first', [
            'conditions' => [
                'MapUpload.id'           => $setId,
                'MapUpload.container_id' => $containerIds,
            ],
        ]);
        if (empty($mapUpload)) {
            return false;
        }
        $iconSetName = $mapUpload['MapUpload']['saved_name'];
        if (!$this->MapUpload->delete($setId)) {
            return false;
        }
        $itemsImgDirectory = APP . 'Plugin' . DS . 'MapModule' . DS . 'webroot' . DS . 'img' . DS . 'items' . DS . $iconSetName;

        if (is_dir($itemsImgDirectory)) {
            $this->removeDirectory($itemsImgDirectory);
        }
    }

}
