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
App::import('Controller', 'MapModule.BackgroundUploads');
App::import('Model', 'Host');
App::import('Model', 'Service');
App::import('Model', 'Hostgroup');
App::import('Model', 'Servicegroup');
App::import('Model', 'MapModule.Map');

class NagvisMigrationShell extends AppShell {

	//public $uses = ['Host'];
	private $host;
	private $service;
	private $hostgroup;
	private $servicegroup;
	private $map;
	
	public function main(){
		if(!$this->checkForSSH2Installed()){
			$this->error('SSH2 not found!', 'Please install the SSH2 PHP package!');
		}
		$this->stdout->styles('success',['text' => 'green']);
		$this->out('Welcome to the Nagvis Migration!');

		$hostData = $this->getHostData();
		$path = $this->configFilesPath();
		$session = $this->connectRemoteServer($hostData);

		/*
		  get config files
		 */
		$this->out('<info>Getting config files</info>');
		$cfgPath = $path.'etc'.DS.'maps'.DS;
		//receive a file list
		$configFileList = $this->getFileList($session, $cfgPath, '/^.*\.(cfg)$/i');

		//check download directory
		$pluginPath = APP.'Plugin'.DS.'MapModule'.DS.'webroot'.DS;
		$dirName = 'NagvisMaps';
		$cfgDownloadDir = $pluginPath.$dirName;
		//create download dir if there is no one
		($this->checkConfigFilesDir($cfgDownloadDir))?:$this->createDownloadDirectory($cfgDownloadDir);
		//download the files
		$configFilesReceived = $this->getFiles($session, $cfgPath, $configFileList, $cfgDownloadDir);
		
		/*
		  get background images
		 */
		$this->out('<info>Getting Background images</info>');
		$bgPath = $path.DS.'share'.DS.'userfiles'.DS.'images'.DS.'maps'.DS;
		//receive a file list
		$bgImgList = $this->getFileList($session, $bgPath, '/^.*\.(jpg|jpeg|png|gif)$/i');
		//remove the default change_me background image from the list
		$foundKey = array_search('change_me.png', $bgImgList);
		if(isset($foundKey)){
			unset($bgImgList[$foundKey]);
		}
		//download the files
		$this->getFiles($session, $bgPath, $bgImgList, $cfgDownloadDir);

		$destination = $pluginPath.'img'.DS.'backgrounds'.DS;
		$this->moveToDestination($bgImgList,$cfgDownloadDir, $destination);

		$this->triggerThumbnailCreation($destination, $bgImgList);
	

		/*
		  get iconsets
		 */
		$this->out('<info>Getting Iconsets</info>');
		$iconsetPath = $path.DS.'share'.DS.'userfiles'.DS.'images'.DS.'iconsets'.DS;
		//receive a file list
		$iconsetList = $this->getFileList($session, $iconsetPath, '/^.*\.(jpg|jpeg|png|gif)$/i');
		$iconsetDir = $cfgDownloadDir.DS.'iconsets';
		($this->checkConfigFilesDir($iconsetDir))?:$this->createDownloadDirectory($iconsetDir);
		//download the files
		$this->getFiles($session, $iconsetPath, $iconsetList, $iconsetDir);
		//sort every icon into its folder
		$this->sortList($iconsetList, $iconsetDir);

		$this->convert($iconsetDir);

		//@TODO move the iconsets to their destination
		//@TODO write config files into the DB
		//@TODO cleanup the directory

		//$this->cleanup($session, $cfgDownloadDir);
		
		//create Object instances
		$this->host = new Host();
		$this->service = new Service();
		$this->hostgroup = new Hostgroup();
		$this->servicegroup = new Servicegroup();
		$this->map = new Map();

		if($configFilesReceived){
			$this->startFiletransform($configFileList, $cfgDownloadDir);
		}
	}

	/**
	 * get the Host information of the remote server where the nagvis maps are located
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @return Array 	hostname, user and password of the server
	 */
	protected function getHostData(){
		$host = $this->in('Please Enter the Hostname OR the IP Adress of the Server');
		$user = $this->in('Please Enter a valid user on '.$host);
		$pass = $this->in('Please Enter the Password for user '.$user.' on '.$host);

		$this->hr(1);
		return ['host' => $host, 'user' => $user, 'pass' => $pass];
	}

	/**
	 * get the filepath where the config files are located
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @return String 	the path
	 */
	protected function configFilesPath(){
		$path = 'opt/openitc/nagios/share/3rd/nagvis/';
		$correctPath = $this->in('Is this path to the nagvis module correct? '.$path, ['y','n'], 'y');
		if($correctPath == 'n'){
			return $path = $this->in('Please Enter the correct path');
		}
		return $path;
	}

	/**
	 * Connect to a remote server
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  Array $hostData 		the Hostname or IP, username and password
	 * @return Resource           	the session resource
	 */
	protected function connectRemoteServer($hostData){
		$this->out('<info>Trying to connect to Remote Host using '.$hostData['user'].'@'.$hostData['host'].'</info>');
		try{
			if(@$session = ssh2_connect($hostData['host'], 22)){
				$this->out('<info>Connection established!</info>');
				$session = $this->remoteAuth($session, $hostData['user'], $hostData['pass']);
				return $session;
			}else{
				throw new Exception('Connection failed! cannot connect to the server');
			}
		}catch(Exception $e){
			$this->error($e->getMessage());
		}
	}

	/**
	 * Authenticate on the remote server
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  Resource $session 	the Session resource from the ssh2_connect
	 * @param  String $user    		username
	 * @param  String $pass    		password
	 * @return Resource          	the session resource
	 */
	protected function remoteAuth($session, $user, $pass){
		try{
			if(@ssh2_auth_password($session, $user, $pass)){
				$this->out('<info>Authentication succeeded!</info>');
				return $session;
			}else{
				throw new Exception('Authentication failed! it seems that these credentials are wrong');
			}
		}catch(Exception $e){
			$this->error($e->getMessage());
		}
	}

	/**
	 * get a List of existing files in the given path on the remote Host
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  Resource $session 	the session resource
	 * @param  String $path    		the path to the condig files
	 * @param  String $regex 		a Regex who defines the files to look for
	 * @return Array          		the list of existing files
	 */
	protected function getFileList($session, $path, $regex){
		$sftp = ssh2_sftp($session);
		//open fileHandle
		$remoteFilePath = 'ssh2.sftp://'.$sftp.'/'.$path;
		if(file_exists($remoteFilePath)){
			if($handle = opendir($remoteFilePath)){
				$this->out('<info>Receiving File list</info>');
				$fileList = [];
				$i = 0;
				while (false !== ($file = readdir($handle))) {
					if (substr($file, 0, 1) != '.'){
						if(preg_match_all($regex, $file, $ConfigFile)){
							// build up list of filenames
							$fileList[$i] = $ConfigFile[0][0];
							// increment Array counter
							$i++;
						}
					}
				}
			}else{
				$this->error("Error while opening ".$path);
			}
		}else{
			$this->error("Error! The given Path ". $path ." does not exist on the Server");
		}
		return $fileList;
	}

	/**
	 * get the config files from the remote Host
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  Resource $session  the session resource
	 * @param  String $path       the path string
	 * @param  Array $fileList    the config file list
	 * @return Bool               true if the transfer is complete
	 */
	protected function getFiles($session, $path, $fileList, $downloadDir){
		$a = ["Measuring the cable length to fetch your data... ",
				"Warming up Large Hadron Collider...",
				"Elf down! We're cloning the elf that was supposed to get you the data. Please wait.",
				"Do you suffer from ADHD? Me neith- oh look a bunny... What was I doing again? Oh, right. Here we go.",
			];
		$this->out('<info>'.$a[rand(0,3)].'</info>');
		usleep(1000000);
		
		$this->out('<info>Starting Filetransfer</info>');
		$count = 1;
		$fileAmount = sizeof($fileList);
		foreach($fileList as $key => $file){
			try{
				if(@ssh2_scp_recv($session, '/'.$path.'/'.$file , $downloadDir.'/'.$file)){
					$this->show_download_status($count, $fileAmount, 60);
					usleep(100000);
					$count++;
				}else{
					echo PHP_EOL;
					throw new Exception('Error getting File '.$file);
				}
			}catch(Exception $e){
				$this->out('<warning>'.$e->getMessage().'</warning>');
			}
		}
		echo PHP_EOL;
		$this->out('<info>Filetransfer complete!</info>');
		return true;
	}

	/**
	 * Iterates though the file list and triggers the transformation
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  Array $fileList 		Array of every file to transform
	 * @param  String $folder   	folder where the files are located
	 * @return void
	 */
	protected function startFiletransform($fileList, $folder){
		$this->out('<info>Starting File Transformation</info>');
		foreach ($fileList as $key => $file) {
			echo 'Processing file '.$file;
			$fileData = $this->transformFileContentToArray($folder.'/'.$file);
			if($fileData == false){
				$this->out('<error> ...Transform Failed!</error>');
			}else{
				$mapname = preg_replace('/(\..*)/', '', $file);
				if($this->saveConfigToDB($mapname, $fileData)){
					$this->out('<success> ...Complete!</success>');
				}else{
					$this->out('<error> ...Save to Database Failed!</error>');
				}
			}
		}
		$this->out('<info>File Transformation Complete!</info>');
	}

	protected function saveConfigToDB($mapname, $data){
		//debug($mapname);
		foreach ($data as $key => $items) {
			foreach ($items as $item) {
				//debug($key);
				$currentData = [];
				switch ($key) {
					case 'global':
						//contains background and grid(but this option is not be implemented in the map Module)
						$mapId = $this->map->find('first',[
							'recursive' => -1,
							'conditions' => [
								'name' => $mapname,
							],
							'fields' => [
								'name',
								'id',
							],
						]);
						//debug($mapId);
						$currentData = [
							'mapname' => $mapname, // neccesary for assigning the background image correctly
							'background' => $item['map_image']
						];
						break;
					case 'host':
						$hostId = $this->resolveHostname($item['host_name']);
						//host not found
						if(empty($hostId)){
							continue;
						}
						$currentData = [
							'object_id' => $hostId, // must be resolved. object_id needed
							'y' => $item['x'],
							'x' => $item['y'],
							//'iconset' => $item['iconset'],
						];
						
						break;
					case 'service':
						$ids = $this->resolveServicename($item['host_name'], $item['service_description']);
						debug($ids);
						//host or service not found
						if(empty($ids)){
							continue;
						}
						$currentData = [
							'object_id' => $ids['Service']['id'], //must be resolved from hostId
							'x' => $item['x'],
							'y' => $item['y'],
							'type' => $item['view_type'] // gadget, icon or line
						];
						
						break;
					case 'hostgroup':
						$currentData = [
							'hostgroupname' => $item['hostgroup_name'], // must be resolved
							'x' => $item['x'],
							'y' => $item['y']
						];
						break;
					case 'servicegroup':
						$currentData = [
							'servicegroup' => $item['servicegroup_name'],
							'x' => $item['x'],
							'y' => $item['y']
						];
						break;
					case 'textbox':
						//text gadget
						$currentData = [
							'text' => $item['text'],
							'x' => $item['x'],
							'y' => $item['y']
						];
						break;
					case 'line':
						$currentData = [
							'x' => $item['x'], // comma separated 
							'y' => $item['y'], // comma separated
							'lineType' => $item['line_type'] //10 for line with 2 arrows -><-, 11 for 1 arrow -->, 12 for no arrow -- CURRENTLY NOT IMPLEMENTED
						];
						
						break;
					case 'shape':
						//icon
						$currentData = [
							'icon' => $item['icon'],
							'x' => $item['x'],
							'y' => $item['y']
						];
						
						break;
					default:
						$this->out('<warning>the type '.$key.' is not specified!</warning>');
						break;
				}
				//debug($currentData);
			}
		}
		return true;
	}

	/**
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  String $hostname
	 * @return host id
	 */
	protected function resolveHostname($hostname){
		$hostId = $this->host->find('first',[
			'recursive' => -1,
			'conditions' => [
				'Host.name' => $hostname
			],
			'fields' => [
				'Host.id'
			]
		]);
		if(!empty($hostId)){
			return $hostId['Host']['id'];
		}
		return false;
	}

	/**
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  String $hostname
	 * @param  String $servicename
	 * @return host and service id
	 */
	protected function resolveServicename($hostname, $servicename){
		$hostId = $this->resolveHostname($hostname);
		if(!empty($hostId)){
			$serviceId = $this->service->find('first',[
				'conditions' => [
					'OR' => [
						'Service.name' => $servicename,
						'Servicetemplate.name' => $servicename
					]
				],
				'fields' => [
					'Service.id',
				],
				'contain' => [
					'Host',
					'Servicetemplate'
				]
			]);
			if(!empty($serviceId)){
				return $serviceId;
			}
			$this->out('<warning>Could not resolve Service '.$servicename.'</warning>');
			return false; // thers no service id
		}
		$this->out('<warning>Could not resolve Host '.$hostname.'</warning>');
		return false; // thers no host id
	}

	/**
	 * Transform the content of the config files into an array
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  String $filenames 	filename of the file to transform
	 * @return Array           		The Transformed filecontent
	 */
	protected function transformFileContentToArray($filenames){
		$data_key = null;
		$data_array = [];
		$file_definition_end = false;
		$intern_key = 0;
		if(file_exists($filenames)){
			foreach(file($filenames) as $key => $row){
				$row = trim($row);
				if(empty($row)){
					continue;
				}
				if($row == '}'){
					$file_definition_end = true;
					continue;
				}
				//build the key
				if(preg_match_all('/(?<=\bdefine\s)(\w+)/', $row, $matches)){
					$data_key = $matches[0][0];
					$file_definition_end = false;
					$intern_key = (!array_key_exists($data_key, $data_array))?0:((sizeof($data_array[$data_key]))+1);
					continue;
				}
				//fill the data
				if($data_key && !$file_definition_end){
					$data_value_array = explode('=',$row);
					$data_array[$data_key][$intern_key][$data_value_array[0]] = $data_value_array[1];
				}
			}
			//var_dump($data_array);
			return $data_array;
		}else{
			$this->out('<warning>Warning! The Specified File does not exist!</warning>');
			return false;
		}
	}

	/**
	 * triggers the thumbnail creation which is within the BackgroundUploadsController
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  String $dir    the directory where the Backgrounds are located
	 * @param  Array $images  the list of the Backgrounds from the old system
	 * @return void
	 */
	protected function triggerThumbnailCreation($dir, $images){
		$folderInstance = new Folder($dir);
		$this->out('<info>Creating Thumbnails from the imported Backgrounds</info>');
		foreach ($images as $image) {
			$fullFilePath = $dir.DS.$image;
			$fileExtension = pathinfo($fullFilePath,PATHINFO_EXTENSION);
			$filename = preg_replace('/(\..*)/', '', $image);
			$data = [
				'fullPath' => $fullFilePath,
				'uuidFilename' => $filename,
				'fileExtension' => $fileExtension,
				'folderInstance' => $folderInstance
			];
			$backgroundUploadInstance = new BackgroundUploadsController();
			$backgroundUploadInstance->createThumbnailsFromBackgrounds($data, true);
		}
	}


	/**
	 * check if the download directory for the config files exist
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @return Bool 	true if download dir exist false if not
	 */
	protected function checkConfigFilesDir($dir){
		if(file_exists($dir)){
			$this->out('<info>Download folder already exist!</info>');
			return true;
		}
		return false;
	}

	/**
	 * create the folder where the config files will be downloaded
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @return void
	 */
	protected function createDownloadDirectory($dir){
		$this->out('<info>Creating Download Folder</info>');
		//take an example folder to get the rights
		$exampleFolder = APP.'Plugin'.DS.'MapModule'.DS.'webroot'.DS.'js'.DS;
		$owner = posix_getpwuid(fileowner($exampleFolder));
		mkdir($dir, fileperms($exampleFolder));
		chown($dir, $owner['name']);
	}

	/**
	 * mass move of files
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  Array $files  An Array of files to move
	 * @param  String $from  current destination of the files
	 * @param  String $to    destination where to files shall be moved
	 * @return void
	 */
	protected function moveToDestination($files, $from, $to){
		foreach ($files as $file) {
			$this->moveIconFiles($from, $to, $file);
		}
	}

	/**
	 * extract the name of iconset files, create the directory with the name
	 * and move the files into the directory
	 * 
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  Array $list  the list of iconset files
	 * @param  String $dir  the Directory where the new folders should be create
	 * @return void
	 */
	protected function sortList($list, $dir){
		$pattern = '/[^\_]+$/';
		foreach ($list as $listItem) {
			$item = preg_split($pattern, $listItem);
			if(!empty($item[0])){
				//remove underscore from the item 
				$item = preg_replace('/_$/', '', $item[0]);
				//get the new filename
				preg_match_all('/[^\_]+$/', $listItem, $newFilename);
				$newFilename = $newFilename[0][0];
				$folderName = $item;
				$to = $dir.DS.$folderName;
				//check/create iconset folder 
				if($this->createIconsetDirectories($dir, $folderName)){
					$this->moveIconFiles($dir, $to, $listItem, $newFilename);
				}
			}
		}
	}

	/**
	 * Create image Directories for the Iconsets
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  String $path  the Path where the directory shall be created
	 * @param  String $name  the name of the new folder 
	 * @return Bool          true if the directory has benn created or if its already existing 
	 *                       false if everything fails
	 */
	protected function createIconsetDirectories($path, $name){
		$folder = $path.DS.$name; 
		if(is_dir($folder)){
			return true;
		}else{
			$this->out('<info>creating Folder '.$name.'</info>');
			mkdir($folder);
			return true;
		}
		return false;
	}

	/**
	 * iterates through the iconsets and convert every image to PNG
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  $string $baseDir  the base directory of the iconsets
	 * @return void
	 */
	protected function convert($baseDir){
		$dir = new DirectoryIterator($baseDir);
		//iterate through base dir
		foreach ($dir as $fileInfo) {
			if($fileInfo->isDir() && !$fileInfo->isDot()){
				$this->out('<info>Convert icons in '.$fileInfo->getFilename().'</info>');
				$subDir = new DirectoryIterator($fileInfo->getPathname());
				//iterate through sub directory
				foreach ($subDir as $files) {
					if($files->isFile() && !$files->isDot()){
						$file = $files->getPathname();
						$path = $files->getPath();
						$filename = $files->getFilename();
						$filename = preg_replace('/(\..*)/', '', $filename);
						$fullPath = $path.DS.$filename.'.png';
						if($this->convertToPNG($file, $fullPath)){
							$this->deleteFile($file);
						}
					}
				}
			}else{
				continue;
			}
		}
	}

	/**
	 * deletes the given file
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  String $file  the file to delete
	 * @return Bool          true on success false on error
	 */
	protected function deleteFile($file){
		return unlink($file);
	}

	/**
	 * checks if the SSH2 Package is installed
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @return Bool 	true if installed false if not
	 */
	public function checkForSSH2Installed(){
		return (function_exists('ssh2_connect'))?true:false;
	}

	/**
	 * move the specified file to the given directory
	 * you can also rename the file 
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  String $from         The Path where the file is located
	 * @param  String $to           The Path where the file shall be moved
	 * @param  String $filename     The File to be moved
	 * @param  String $newFilename  optional if set rename the file
	 * @return Bool             True on success, false on failure
	 */
	protected function moveIconFiles($from, $to, $filename, $newFilename = null){
		$newFilename = ($newFilename == null)?$filename:$newFilename;
		return rename($from.DS.$filename, $to.DS.$newFilename);
	}

	/**
	 * convert an image to PNG 
	 * @author Maximilian Pappert <maximilian.pappert@it-novum.com>
	 * @param  String $file  the file
	 * @param  String $name  path and filename for the created image 
	 * @return Bool          true on success false on error
	 */
	protected function convertToPNG($file, $name){
		return imagepng(imagecreatefromstring(file_get_contents($file)), $name);
	}

	// shall cleanup the ssh2 connection and delete the downloaded config files
	protected function cleanupData($session, $downloadDir){
		$this->out('<info>Cleaning up Directory</info>');
		ssh2_exec($session, 'exit');
		unset($session);
		//delete download folder with everything in it
		$this->deleteDownloadData($downloadDir);
	}

	//delete the download data
	protected function deleteDownloadData($dir){

	}

	/*
	Copyright (c) 2010, dealnews.com, Inc.
	All rights reserved.
	 
	Redistribution and use in source and binary forms, with or without
	modification, are permitted provided that the following conditions are met:
	 
	 * Redistributions of source code must retain the above copyright notice,
	  this list of conditions and the following disclaimer.
	 * Redistributions in binary form must reproduce the above copyright
	  notice, this list of conditions and the following disclaimer in the
	  documentation and/or other materials provided with the distribution.
	 * Neither the name of dealnews.com, Inc. nor the names of its contributors
	  may be used to endorse or promote products derived from this software
	  without specific prior written permission.
	 
	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
	AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
	IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
	ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
	LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
	CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
	SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
	INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
	CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
	ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
	POSSIBILITY OF SUCH DAMAGE.
	 
	 */

	/**
	 * show a status bar in the console
	 *
	 * <code>
	 * for($x=1;$x<=100;$x++){
	 *
	 * show_status($x, 100);
	 *
	 * usleep(100000);
	 *
	 * }
	 * </code>
	 *
	 * @param int $done how many items are completed
	 * @param int $total how many items are to be done total
	 * @param int $size optional size of the status bar
	 * @return void
	 *
	 */
	 
	public function show_download_status($done, $total, $size=30) {

		static $start_time;

		// if we go over our bound, just ignore it
		if($done > $total) return;

		if(empty($start_time)) $start_time=time();
		$now = time();

		$perc=(double)($done/$total);

		$bar=floor($perc*$size);

		$status_bar="\r[";
		$status_bar.=str_repeat("=", $bar);
		if($bar<$size){
			$status_bar.=">";
			$status_bar.=str_repeat(" ", $size-$bar);
		} else {
			$status_bar.="=";
		}

		$disp=number_format($perc*100, 0);

		$status_bar.="] $disp% $done/$total";

		$rate = ($now-$start_time)/$done;
		$left = $total - $done;
		$eta = round($rate * $left, 2);

		$elapsed = $now - $start_time;

		$status_bar.= " remaining: ".number_format($eta)." sec. elapsed: ".number_format($elapsed)." sec.";

		echo "$status_bar ";

		flush();

		// when done, send a newline
		if($done == $total) {
			echo "\n";
		}
	}
}