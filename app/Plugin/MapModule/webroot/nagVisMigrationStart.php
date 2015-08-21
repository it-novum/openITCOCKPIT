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
 *	Nagvis Migration Script
 *
 *	get the configuration files from the remote itcockpit v2 server
 *
 *	DEPENDENCIES:
 *		php5-dev
 *		libssh2-1 
 *		libssh2-1-dev
 *		libssh2-php
 *		pecl ssh2 Package http://pecl.php.net/package/ssh2
 *  		-> insert "extension=ssh2.so“ into the loaded php.ini
 *	
 *
 *	@author Maximilian Pappert <maximilian.pappert@it-novum.com>
 *  @version 0.1
 *
 *	@todo Backgrounds and usericonsets must be loaded from the v2 machine
 */
include_once('nagvis_migration.php');


echo PHP_EOL;
echo "##########################################################################".PHP_EOL;
echo "####             WELCOME TO THE NAGVIS MIGRATION SCRIPT!              ####".PHP_EOL;
echo "####                                                                  ####".PHP_EOL;
echo "#### This script will help you to Migrate your NAGVIS Maps to the new ####".PHP_EOL;
echo "#### openITCOCKPIT v3 Map Module! The Script will ask you anything it ####".PHP_EOL;
echo "#### needs to know to get the maps. So let's start!                   ####".PHP_EOL;
echo "##########################################################################".PHP_EOL;
echo PHP_EOL;



echo "Please Enter the Hostname or IP of the openITCOCKPIT v2 Machine".PHP_EOL;
$host = trim(fgets(STDIN));
echo "--------------------------------------------------------------------------".PHP_EOL;
echo PHP_EOL;

echo "Please Enter the Password for user root on ".$host . PHP_EOL;
$password = trim(fgets(STDIN));
echo "--------------------------------------------------------------------------".PHP_EOL;
echo PHP_EOL;
/*
@TODO install ncurses or smth like that to hide the password input 
ncurses_init();
ncurses_noecho();
ncurses_getch();
ncurses_echo();
*/
$path = "opt/openitc/nagios/share/3rd/nagvis/etc/maps/";
echo "The Standard path for the NagVis configuration files is '".$path. "'" . PHP_EOL;
echo "is this path correct ? (y/n)".PHP_EOL;
$usrDecision = trim(fgets(STDIN));
echo "--------------------------------------------------------------------------".PHP_EOL;

if(!getUniqueUserInput($usrDecision)){
	echo "Please Enter the correct path!".PHP_EOL;
	$path = trim(fgets(STDIN));
}elseif(getUniqueUserInput($usrDecision) === 'error'){
	die('WRONG USER INPUT! STOPPING SCRIPT EXECUTION!'.PHP_EOL);
}


echo PHP_EOL;
echo "--------------------------------------------------------------------------".PHP_EOL;
echo "#################### Please check the entered Values! ####################".PHP_EOL;
echo "--------------------------------------------------------------------------".PHP_EOL;
echo "Host: ". $host . PHP_EOL;
echo "Path: ". $path . PHP_EOL;
echo "Connect to the Server and get the maps ? (y/n)".PHP_EOL;
$usrDecisionConnect = trim(fgets(STDIN));
echo "--------------------------------------------------------------------------".PHP_EOL;



if(getUniqueUserInput($usrDecisionConnect)){
	$path = trim($path, '/');
	$res = connectToServer($host, $password);
	if($res){
		receiveData($res, $path);
	}
}elseif(getUniqueUserInput($usrDecisionConnect) == 'error'){
	die('WRONG USER INPUT! STOPPING SCRIPT EXECUTION!'.PHP_EOL);
}else{
	die('You have stopped the script execution!'.PHP_EOL);
}

echo PHP_EOL;

function connectToServer($host, $password){
	if(function_exists('ssh2_connect')){
		if(!$session = ssh2_connect($host, 22)){
			die("Connection Failed".PHP_EOL);
			return false;
		}else{
			echo "Connection Established!".PHP_EOL;
			if(ssh2_auth_password($session, 'root', $password)){
				echo "Authentication succeeded!".PHP_EOL;
				return $session;
			}else{
				die('Authentication Failed!'.PHP_EOL);
			}
		}
	}else{
		die('Error! Please Install the ssh2 package!');
	}
	
}

function receiveData($session, $path){

	$sftp = ssh2_sftp($session);
	//open fileHandle
	if(file_exists("ssh2.sftp://$sftp/$path")){
		if($handle = opendir("ssh2.sftp://$sftp/$path")){
			echo 'Receiving File list'.PHP_EOL;
			$fileList = [];
			$i = 0;
			while (false !== ($file = readdir($handle))) {
				if (substr($file, 0, 1) != '.'){
					//@todo this regex is poor .. it matches also "*.cfg.bak" files
					if(preg_match_all("/[a-zA-Z0-9_-]*(.cfg)/", $file, $ConfigFile)){
						// build up list of filenames
						$fileList[$i] = $ConfigFile[0][0];

						// increment Array counter
						$i++;
					}
				}
			}
		}else{
			die("Error while opening ".$path. PHP_EOL);
		}
	}else{
		die("Error! The given Path ". $path ." does not exist on the Server". PHP_EOL);
	}


	$downloadFolderName = 'NagvisMaps';
	if(!file_exists($downloadFolderName)){
		echo 'Creating Download Folder'.PHP_EOL;
		$owner = posix_getpwuid(fileowner('js/'));
		mkdir($downloadFolderName, fileperms('js/'));
		chown($downloadFolderName, $owner['name']);

	}else{
		echo 'Download folder already exist!'.PHP_EOL;
	}


	$a = ["Measuring the cable length to fetch your data... ",
			"Warming up Large Hadron Collider...",
			"Elf down! We're cloning the elf that was supposed to get you the data. Please wait.",
			"Do you suffer from ADHD? Me neith- oh look a bunny... What was I doing again? Oh, right. Here we go.",
		];
	echo $a[rand(0,3)].PHP_EOL;
	usleep(1000000);
	
	echo 'Starting Filetransfer'.PHP_EOL;
	$count = 1;
	$fileAmount = sizeof($fileList);
	foreach($fileList as $key => $file){
		if(ssh2_scp_recv($session, '/'.$path.'/'.$file , $downloadFolderName.'/'.$file)){
			show_status($count, $fileAmount, 100);
			usleep(100000);
			$count++;
		}else{
			echo 'Error getting File '.$file. PHP_EOL;
		}
	}
	echo PHP_EOL;
	echo 'Filetransfer complete!'.PHP_EOL;
	echo PHP_EOL;

	transformConfigFilesToArray($fileList, $downloadFolderName);
}


function getUniqueUserInput($usrInput){
	switch ($usrInput) {
		case 'NO':
		case 'N':
		case 'no':
		case 'n':
			return false;
			break;
		case 'YES':
		case 'Yes':
		case 'Y':
		case 'y':
		case 'yes':
		case 'j':
			return true;
			break;
		default:
			return 'error';
			break;
	}
}


function transformConfigFilesToArray($fileList, $folder){
	echo 'Starting File Transformation'.PHP_EOL;

	foreach ($fileList as $key => $file) {
		echo 'Processing file '.$file;
		$fileData = transformFileContentToArray($folder.'/'.$file);
		echo '...Complete!'.PHP_EOL;

		//print_r($fileData);
	}
	
	echo 'File Transformation Complete!'.PHP_EOL;

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
 
function show_status($done, $total, $size=30) {

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
?>