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
 * NagVis Migration script
 */

//@TODO iterate through multiple config files
//path of the nagvis maps is 
// /opt/openitc/nagios/share/3rd/nagvis/etc/maps

//open NagVis config file for reading
//$filename = 'BPM2_Annahme.cfg';
function transformFileContentToArray($filename){
	$data_key = null;
	$data_array = [];
	$file_definition_end = false;
	$intern_key = 0;
	if(file_exists($filename)){
		foreach(file($filename) as $key => $row){
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
		return $data_array;
	}else{
		echo 'Warning! The Specified File does not exist!'. PHP_EOL;
		return false;
	}
	
	/*echo '<pre>';
	print_r($data_array);
	echo '</pre>';
	*/
}

