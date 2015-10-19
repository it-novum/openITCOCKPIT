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

class AfterExportShell extends AppShell{

	public function main(){
		$parameter = false;
		$this->stdout->styles('red', ['text' => 'red']);
		
		$modulePlugins = array_filter(CakePlugin::loaded(), function($value){
			return strpos($value, 'Module') !== false;
		});
		if(in_array('DistributeModule', $modulePlugins)){
			//Only create the task if DistributeModule is loaded
			$_task = new TaskCollection($this);
			$AfterExport = $_task->load('AfterExport');
		}else{
			$this->out('<red>Error: DistributeModule not found or installed</red>');
			exit(0);
		}
		
		if(isset($this->params['quiet']) && $this->params['quiet'] == true){
			$AfterExport->beQuiet();
		}
		
		if(array_key_exists('single', $this->params)){
			$AfterExport->init();
			$AfterExport->execute();
			$parameter = true;
		}
		
		if($parameter === false){
			$this->out('<red>'.__('Usage error: Call with --help to get more information ').'</red>');
		}
		
	}
	
	public function getOptionParser(){
		$parser = parent::getOptionParser();
		$parser->addOptions([
			'single' => ['help' => 'Run after export command single threaded', 'boolean' => false],
		]);
		return $parser;
	}
}
