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

class BackgroundUploadsController extends MapModuleAppController {

	public $layout = 'Admin.default';
	//prevent asking for a view
	public $autoRender = false;
	//public $backgroundFolder = new Folder(APP .'Plugin'. DS .'MapModule'. DS .'Upload');

	public function upload(){
		if(!empty($_FILES)){

			//define background image directory
			$backgroundImgDirectory = APP .'Plugin'. DS .'MapModule'. DS .'webroot'. DS .'img'. DS .'backgrounds';

			//check if upload folder exist
			if(!is_dir($backgroundImgDirectory)){
				mkdir($backgroundImgDirectory);
			}

			$backgroundFolder = new Folder($backgroundImgDirectory);

			//this name should be also stored in the Database
			//-> displayed name when you choose the background
			// must be unique
			//$_FILES['file']['name'];

			$fileExtension = pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);
			$filename = UUID::v4();
			$fullFilePath = $backgroundFolder->path.DS.$filename.'.'.$fileExtension;
			if(move_uploaded_file($_FILES['file']['tmp_name'], $fullFilePath)){
				echo 'successfull';
				$obj = [
					'fullPath'=>$fullFilePath,
					'uuidFilename'=>$filename,
					'fileExtension'=>$fileExtension,
					'folderInstance'=>$backgroundFolder
				];
				$this->createThumbnailsFromBackgrounds($obj);
				return true;
			}else{
				return false;
			}

		}else{
			echo 'there is no file to store';
		}
	}

	public function uploadIconsSet(){
		if(empty($_FILES)) {
			throw new ForbiddenException(__('There is no file to store'));
		}

		$itemsImgDirectory = APP .'Plugin'. DS .'MapModule'. DS .'webroot'. DS .'img'. DS .'items';
		$tempZipsDirectory = APP .'Plugin'. DS .'MapModule'. DS .'webroot'. DS .'img'.DS.'temp';

		//check if upload folder exist
		if(!is_dir($itemsImgDirectory)){
			mkdir($itemsImgDirectory);
			chmod($itemsImgDirectory, 0777);
		}
		if(!is_dir($tempZipsDirectory)){
			mkdir($tempZipsDirectory);
			chmod($tempZipsDirectory, 0777);
		}

		$fileExtension = pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);
		$filename = str_replace('.'.$fileExtension, '', pathinfo($_FILES['file']['name'],PATHINFO_BASENAME));

		$zipTempFolder = new Folder($tempZipsDirectory);
		$fullZipTempPath = $zipTempFolder->path.DS.$filename.'.zip';
		$fullFolderTempPath = $zipTempFolder->path.DS.$filename;

		try {
			if($fileExtension !== 'zip') {
				throw new Exception(__('Only zip files are accepted'));
			}

			if($_FILES['file']['error'] === 1){
				throw new Exception('The uploaded file exceeds the upload_max_filesize directive in php.ini');
			}

			if(is_dir($itemsImgDirectory. DS .$filename)){
				throw new Exception(__('Icons set already exists'), 13);
			}

			mkdir($fullFolderTempPath);
			chmod($fullFolderTempPath, 0777);

			if(!move_uploaded_file($_FILES['file']['tmp_name'], $fullZipTempPath)) {
				throw new Exception(__('Cannot upload zip'));
			}

			$myZip = new ZipArchive;
			$openZip = $myZip->open($fullZipTempPath);
			if (!$openZip) {
				throw new Exception(__('Cannot unzip file'));
			}
			$myZip->extractTo($fullFolderTempPath);
			$myZip->close();

			$iconsNames = $this->getIconsNames();
			$iconsDir = $this->getIconsSubDirectory($fullFolderTempPath);

			if(is_null($iconsDir)){
				throw new Exception(__('Please check the zip file. It must contain all icons: '.implode(', ', $iconsNames)));
			}

			mkdir($itemsImgDirectory. DS .$filename);
			foreach (scandir($iconsDir) as $object) {
				if ($object != "." && $object != ".." && in_array($object, $iconsNames))
					copy($iconsDir. DS .$object, $itemsImgDirectory. DS .$filename. DS .$object);
			}

		}catch(Exception $e){
			if(is_dir($itemsImgDirectory. DS .$filename) && $e->getCode() !== 13){
				$this->removeDirectory($itemsImgDirectory. DS .$filename);
			}
			throw new ForbiddenException($filename.'.'.$fileExtension.': '.$e->getMessage());

		}finally{
			if(is_file($fullZipTempPath)){
				unlink($fullZipTempPath);
			}
			if(is_dir($fullFolderTempPath)){
				$this->removeDirectory($fullFolderTempPath);
			}
		}


	}

	private function getIconsNames(){
		return [
			'ack.png',
			'critical.png',
			'down.png',
			'error.png',
			'okaytime.png',
			'okaytimeuser.png',
			'ok.png',
			'pending.png',
			'sack.png',
			'sdowntime.png',
			'unknown.png',
			'unreachable.png',
			'up.png',
			'warning.png',
		];
	}

	private function getIconsSubDirectory($startDir){
		$iconDir = null;
		$iconsNames = $this->getIconsNames();
		foreach (scandir($startDir) as $object) {
			if ($object != "." && $object != "..") {
				if (is_dir($startDir. DS .$object)) {
					$iconDir = $this->getIconsSubDirectory($startDir . DS . $object);
					if(!is_null($iconDir))
						return $iconDir;
				}elseif(($keyO = array_search($object, $iconsNames)) !== false) {
					unset($iconsNames[$keyO]);
				}
			}
		}

		if(empty($iconsNames)) { // array contains the rest of icons we didn't find
			return $startDir;
		}

		return $iconDir;
	}

	private function removeDirectory($dir){
		foreach (scandir($dir) as $object) {
			if ($object != "." && $object != "..") {
				if (is_dir($dir."/".$object))
					$this->removeDirectory($dir."/".$object);
				else
					unlink($dir."/".$object);
			}
		}
		rmdir($dir);
	}

	public function createThumbnailsFromBackgrounds($obj, $isShell = false){
		$file = $obj['fullPath'];
		$folderInstance = $obj['folderInstance'];

		//check if thumb folder exist
		if(!is_dir($folderInstance->path.DS. 'thumb')){
			mkdir($folderInstance->path.DS. 'thumb');
		}

		$imgsize = getimagesize($file);
		$width = $imgsize[0];
		$height = $imgsize[1];
		$imgtype = $imgsize[2];
		$aspectRatio = $width/$height;

		$thumbnailWidth = 150;
		$thumbnailHeight = 150;


		switch($imgtype){
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
				echo __('Filetype not supported!');
				break;
		}

		//calculate the new height or width and keep the aspect ration
		if($aspectRatio == 1){
			//source image X = Y
			$newWidth = $thumbnailWidth;
			$newHeight = $thumbnailHeight;
		}elseif($aspectRatio > 1) {
			//source image X > Y
			$newWidth = $thumbnailWidth;
			$newHeight = ($thumbnailHeight / $aspectRatio);
		}else{
			//source image X < Y
			$newWidth = ($thumbnailWidth * $aspectRatio);
			$newHeight = $thumbnailHeight;
		}

		$destImg = imagecreatetruecolor($newWidth, $newHeight);
		$transparent = imagecolorallocatealpha( $destImg, 0, 0, 0, 127 );
		imagefill( $destImg, 0, 0, $transparent);
		imageCopyResized($destImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
		imagealphablending($destImg, false);
		imagesavealpha($destImg,true);

		if(!$isShell){
			header('Content-Type: image/'.$obj['fileExtension']);
		}
		switch($imgtype){
			// 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM
			case 1:
				imagegif($destImg, $folderInstance->path.DS. 'thumb'.DS.'thumb_'.$obj['uuidFilename'].'.'.$obj['fileExtension']);
				break;
			case 2:
				imagejpeg($destImg, $folderInstance->path.DS. 'thumb'.DS.'thumb_'.$obj['uuidFilename'].'.'.$obj['fileExtension']);
				break;
			case 3:
				imagepng($destImg, $folderInstance->path.DS. 'thumb'.DS.'thumb_'.$obj['uuidFilename'].'.'.$obj['fileExtension']);
				break;
			default:
				echo __('Filetype not supported!');
				break;
		}
		imagedestroy($destImg);
	}

	/**
	 * delete Background image
	 * base64 encoded due to a encoding issue
	 * @param $filename the uuid filename with file extension
	 */
	public function delete($filename){
		//delete a background including its thumbnail
		try{
			$filename = base64_decode($filename);
			//define background image directory
			$backgroundImgDirectory = APP .'Plugin'. DS .'MapModule'. DS .'webroot'. DS .'img'. DS .'backgrounds';

			//check if bg image exists
			if(!file_exists($backgroundImgDirectory. DS .$filename)){
				throw new Exception($backgroundImgDirectory. DS .$filename. ' cannot be found');
			}

			//check if thumbnail exists
			if(!file_exists($backgroundImgDirectory. DS .'thumb'. DS .'thumb_'.$filename)){
				throw new Exception($backgroundImgDirectory. DS .'thumb'. DS .'thumb_'.$filename. ' cannot be found');
			}

			if(!unlink($backgroundImgDirectory. DS .$filename)){
				//background image could not be deleted
				throw new Exception('Background image could not be deleted');
			}

			if(!unlink($backgroundImgDirectory. DS .'thumb'. DS .'thumb_'.$filename)){
				//thumbnail image could not be deleted
				throw new Exception('Thumbnail image could not be deleted');
			}
			echo 'Background successfully deleted!';
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}

	public function deleteIconsSet($filename){
		$iconSet = base64_decode($filename);
		$itemsImgDirectory = APP .'Plugin'. DS .'MapModule'. DS .'webroot'. DS .'img'. DS .'items'. DS . $iconSet;

		if(is_dir($itemsImgDirectory)){
			$this->removeDirectory($itemsImgDirectory);
		}
	}

}
