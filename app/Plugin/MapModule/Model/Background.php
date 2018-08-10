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
 * @todo REMOVE ME!
 */
class Background extends MapModuleAppModel
{
    public $useTable = false;

    public function findFiles()
    {

        App::uses('Folder', 'Utility');

        $backgroundFolder = new Folder(APP.'Plugin'.DS.'MapModule'.DS.'webroot'.DS.'img'.DS.'backgrounds');
        $itemsFolder = new Folder(APP.'Plugin'.DS.'MapModule'.DS.'webroot'.DS.'img'.DS.'items');

        $recursiveImages = $itemsFolder->findRecursive();

        $directoryFiles = [];
        foreach ($recursiveImages as $k => $recursiveImage) {
            preg_match_all("~.*items\/(.*)$~", $recursiveImage, $folderPart);
            $filename = basename($folderPart[1][0]);
            $directory = dirname($folderPart[1][0]);
            $directoryFiles[$k] = [$directory, $filename];
        }

        $items = [];
        $directories = [];
        foreach ($recursiveImages as $key => $recursiveImage) {
            preg_match_all("~.*items\/(.*)$~", $recursiveImage, $folderPart);
            $directory = dirname($folderPart[1][0]);
            //prevent images outside of iconset folder from being displayed in the accordion 
            //directory key wont be written in the array here
            if ($directory != '.') {
                $items[$directory] = [];
            }
        }

        foreach ($directoryFiles as $key => $value) {
            //prevent images outside of iconset folder from being displayed in the accordion 
            //icons within the '.' folder wont be written into the array
            if ($value[0] != '.') {
                $items[$value[0]][] = $value[1];
            }
        }

        $imageDir = DS.'map_module'.DS.'img';

        $relativeItemsPath = $imageDir.DS.'items';
        $relativeBackgroundPath = $imageDir.DS.'backgrounds';
        $relativeBackgroundThumbPath = $imageDir.DS.'backgrounds'.DS.'thumb';
        $files = [
            'items'       => [
                //keep these commented files and folders in array .. may needed for "non-iconsets"
                'path'  => $relativeItemsPath,
                /*'files'=>$itemsFolder->find(),*/
                'files' => $items,
            ],
            'backgrounds' => [
                'path'      => $relativeBackgroundPath,
                'thumbPath' => $relativeBackgroundThumbPath,
                'files'     => $backgroundFolder->find(),
            ],
        ];

        return $files;
    }

    public function findBackgrounds()
    {
        App::uses('Folder', 'Utility');
        App::uses('MapUpload', 'MapModule.Model');
        App::uses('TreeComponent', 'Controller');
        $basePath = APP.'Plugin'.DS.'MapModule'.DS.'webroot'.DS.'img'.DS.'backgrounds';
        if (!is_dir($basePath)) {
            mkdir($basePath);
        }

        $imageDir = DS.'map_module'.DS.'img';
        $relativeBackgroundPath = $imageDir.DS.'backgrounds';
        $relativeBackgroundThumbPath = $imageDir.DS.'backgrounds'.DS.'thumb';

        $myTreeComponent = new TreeComponent();
        $containerIds = $myTreeComponent->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $myMapUpload = new MapUpload();
        $allBackgrounds = $myMapUpload->find('all', [
            'conditions' => ['MapUpload.upload_type' => MapUpload::TYPE_BACKGROUND, 'MapUpload.container_id' => $containerIds],
        ]);
        if (empty($allBackgrounds)) {
            $itemsFolder = scandir($basePath);
            $backgroundCounter = 0;
            foreach ($itemsFolder as $name) {
                if (!in_array($name, ['.', '..']) && file_exists($basePath.DS.'thumb'.DS.'thumb_'.$name)) {
                    $backgroundCounter++;
                    $newUpload = new MapUpload();
                    $newUpload->set([
                        'upload_type'  => MapUpload::TYPE_BACKGROUND,
                        'upload_name'  => 'background_'.$backgroundCounter,
                        'saved_name'   => $name,
                        'container_id' => 1,
                    ]);
                    $newUpload->save();
                }
            }
            $allBackgrounds = $myMapUpload->find('all', [
                'conditions' => ['MapUpload.upload_type' => MapUpload::TYPE_BACKGROUND, 'MapUpload.container_id' => $containerIds],
            ]);
        }

        $backgroundSets = [];
        foreach ($allBackgrounds as $backgroundItem) {
            if (file_exists($basePath.DS.$backgroundItem['MapUpload']['saved_name']) && file_exists($basePath.DS.'thumb'.DS.'thumb_'.$backgroundItem['MapUpload']['saved_name'])) {
                $backgroundSets[] = [
                    'id'          => $backgroundItem['MapUpload']['id'],
                    'displayName' => $backgroundItem['MapUpload']['upload_name'],
                    'savedName'   => $backgroundItem['MapUpload']['saved_name'],
                ];
            }
        }

        return [
            'path'      => $basePath,
            'webPath'   => $relativeBackgroundPath,
            'thumbPath' => $relativeBackgroundThumbPath,
            'files'     => $backgroundSets,
        ];
    }

    public function findIconsets()
    {
        App::uses('MapUpload', 'MapModule.Model');
        App::uses('TreeComponent', 'Controller');
        $basePath = APP.'Plugin'.DS.'MapModule'.DS.'webroot'.DS.'img'.DS.'items';
        $myTreeComponent = new TreeComponent();
        $containerIds = $myTreeComponent->resolveChildrenOfContainerIds($this->MY_RIGHTS);
        $myMapUpload = new MapUpload();
        $this->importIconsFromFilesToDB();
        $allMapsIcons = $myMapUpload->find('all', [
            'conditions' => ['MapUpload.upload_type' => MapUpload::TYPE_ICON_SET, 'MapUpload.container_id' => $containerIds],
            'recursive' => -1
        ]);

        // checking the folder if something is missing
        $itemsFolder = scandir($basePath);
        $iconInsert = false;
        foreach ($itemsFolder as $name) {
            if(in_array($name, ['.', '..'])) continue;

            if(!file_exists($basePath.DS.$name.DS.'ok.png')) {
                continue;
            }

            foreach($allMapsIcons as $myMapsIcon){
                if($myMapsIcon['MapUpload']['saved_name'] === $name){
                    continue 2;
                }
            }

            $iconInsert = true;
            $newUpload = new MapUpload();
            $newUpload->set([
                'upload_type'  => MapUpload::TYPE_ICON_SET,
                'upload_name'  => $name,
                'saved_name'   => $name,
                'container_id' => 1,
            ]);
            $newUpload->save();
        }
        if($iconInsert){
            $allMapsIcons = $myMapUpload->find('all', [
                'conditions' => ['MapUpload.upload_type' => MapUpload::TYPE_ICON_SET, 'MapUpload.container_id' => $containerIds],
                'recursive' => -1
            ]);
        }

        $iconSets = [];
        foreach ($allMapsIcons as $mapUploadItem) {
            //check if there is an ok.png icon
            if (file_exists($basePath.'/'.$mapUploadItem['MapUpload']['saved_name'].'/ok.png')) {
                $dimensionArr = getimagesize($basePath.'/'.$mapUploadItem['MapUpload']['saved_name'].'/ok.png');
                $iconSets[] = [
                    'id'          => $mapUploadItem['MapUpload']['id'],
                    'displayName' => $mapUploadItem['MapUpload']['upload_name'],
                    'savedName'   => $mapUploadItem['MapUpload']['saved_name'],
                    'dimension'   => $dimensionArr[0],
                ];
            }
        }

        return [
            'items' => [
                'path'     => $basePath,
                'webPath'  => '/map_module/img/items',
                'iconsets' => $iconSets,
            ],
        ];
    }

    private function importIconsFromFilesToDB()
    { // all icon sets, user had before updating openITCOCKPIT, must be imported into DB
        $itemsImgDirectory = APP.'Plugin'.DS.'MapModule'.DS.'webroot'.DS.'img'.DS.'items';
        $findMapUpload = new MapUpload();
        $checkMapsIcons = $findMapUpload->find('all', [
            'conditions' => ['MapUpload.upload_type' => MapUpload::TYPE_ICON_SET],
        ]);
        if (!empty($checkMapsIcons))
            return true;

        //if table is empty we perform importing icons into DB
        foreach (scandir($itemsImgDirectory) as $object) {
            if ($object != "." && $object != ".." && is_dir($itemsImgDirectory.DS.$object) && file_exists($itemsImgDirectory.DS.$object.DS.'ok.png')) {
                $myMapUpload = new MapUpload();
                $myMapUpload->save([
                    'upload_type'  => MapUpload::TYPE_ICON_SET,
                    'upload_name'  => $object,
                    'saved_name'   => $object,
                    'user_id'      => null,
                    'container_id' => '1',
                ]);
            }
        }
    }

    public function findIcons()
    {
        $basePath = APP.'Plugin'.DS.'MapModule'.DS.'webroot'.DS.'img'.DS.'icons';
        if (is_dir($basePath)) {
            $iconsFolder = scandir($basePath);
            $icons = [];

            foreach ($iconsFolder as $name) {
                if (!in_array($name, ['.', '..'])) {
                    $icons[] = $name;
                }
            }

            return [
                'icons' => [
                    'path'    => $basePath,
                    'webPath' => '/map_module/img/icons',
                    'icons'   => $icons,
                ],
            ];
        }

    }
}