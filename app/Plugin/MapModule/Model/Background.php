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

class Background extends MapModuleAppModel{
	public $useTable = false;
	
	public function findFiles(){
		
		App::uses('Folder', 'Utility');
		
		$backgroundFolder = new Folder(APP .'Plugin'. DS .'MapModule'. DS .'webroot'. DS .'img'. DS .'backgrounds');
		$itemsFolder = new Folder(APP .'Plugin'. DS .'MapModule'. DS .'webroot'. DS .'img'. DS .'items');

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
			if($directory != '.'){
				$items[$directory] = [];
			}
		}

		foreach ($directoryFiles as $key => $value) {
			//prevent images outside of iconset folder from being displayed in the accordion 
			//icons within the '.' folder wont be written into the array
			if($value[0] != '.'){
				$items[$value[0]][] = $value[1];
			}
		}

		$imageDir = DS .'map_module'. DS .'img';

		$relativeItemsPath = $imageDir . DS .'items';
		$relativeBackgroundPath = $imageDir. DS .'backgrounds';
		$relativeBackgroundThumbPath = $imageDir. DS .'backgrounds'. DS .'thumb';
		$files = [
			'items' => [
			//keep these commented files and folders in array .. may needed for "non-iconsets"
				'path'=>$relativeItemsPath,
				/*'files'=>$itemsFolder->find(),*/
				'files'=>$items,
			],
			'backgrounds'=>[
				'path'=>$relativeBackgroundPath,
				'thumbPath'=>$relativeBackgroundThumbPath,
				'files'=>$backgroundFolder->find(),
			]
		];
		return $files;
	}
	
	public function findBackgrounds(){
		App::uses('Folder', 'Utility');
		$basePath = APP .'Plugin'. DS .'MapModule'. DS .'webroot'. DS .'img'. DS .'backgrounds';
		$backgroundFolder = new Folder($basePath);
		$imageDir = DS .'map_module'. DS .'img';

		$relativeItemsPath = $imageDir . DS .'items';
		$relativeBackgroundPath = $imageDir. DS .'backgrounds';
		$relativeBackgroundThumbPath = $imageDir. DS .'backgrounds'. DS .'thumb';
		$files = [
			'backgrounds'=>[
				'path' => $basePath,
				'webPath'=>$relativeBackgroundPath,
				'thumbPath'=>$relativeBackgroundThumbPath,
				'files'=>$backgroundFolder->find(),
			]
		];
		
		return $files;
	}
	
	public function findIconsets(){
		$basePath = APP .'Plugin'. DS .'MapModule'. DS .'webroot'. DS .'img'. DS .'items';
		$itemsFolder = scandir($basePath);
		$iconSets = [];
		$fileDimesions = [];
		foreach($itemsFolder as $name){
			if(!in_array($name, ['.', '..']) && is_dir($basePath.'/'.$name)){
				//check if there is an ok.png icon 
				if(file_exists($basePath.'/'.$name.'/ok.png')){
					$iconSets[] = $name;
					//determine the image size for one image
					$fileDimensions[] = getimagesize($basePath.'/'.$name.'/ok.png');
				}
			}
		}
		$fileDimensions = Hash::extract($fileDimensions, '{n}.0');
		return [
			'items' => [
				'path' => $basePath,
				'webPath' => '/map_module/img/items',
				'iconsets' => $iconSets,
				'fileDimensions' => $fileDimensions
			]
		];
	}

	public function findIcons(){
		$basePath = APP .'Plugin'. DS .'MapModule'. DS .'webroot'. DS .'img'. DS .'icons';
		if(is_dir($basePath)){
			$iconsFolder = scandir($basePath);
			$icons = [];

			foreach($iconsFolder as $name){
				if(!in_array($name, ['.', '..'])){
					$icons[] = $name;
				}
			}

			return [
				'icons' => [
					'path' => $basePath,
					'webPath' => '/map_module/img/icons',
					'icons' => $icons,
				]
			];
		}
		
	}
}