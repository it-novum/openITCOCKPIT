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

require_once APP . 'Vendor' . DS . 'minify' . DS . 'src' . DS . 'Minify.php';
require_once APP . 'Vendor' . DS . 'minify' . DS . 'src' . DS . 'JS.php';

use MatthiasMullie\Minify;
class CompressShell extends AppShell{
	
	//This shell search all javascript files and compress it to one big javascript file
	
	public function main(){
		App::uses('Folder', 'Utility');
		App::uses('File', 'Utility');
		
		$this->out('Compress JavaScript controller components...    ', false);
		$components = $this->fetchAllJavaScriptComponents();
		$this->compressFiles($components, 'compressed_components.js');
		$this->minifyJsFile('compressed_components.js');
		$this->out('done');
		
		$this->out('Compress JavaScript action controllers...    ', false);
		$controllers = $this->fetchAllJavaScriptControllers();
		$this->compressFiles($controllers, 'compressed_controllers.js');
		$this->minifyJsFile('compressed_controllers.js');
		$this->out('done');
	}
	
	public function fetchAllJavaScriptComponents(){
		$core = new Folder(WWW_ROOT . 'js' . DS . 'app' . DS . 'components');
		$components = $core->findRecursive('.*\.js');
		
		foreach(CakePlugin::loaded() as $pluginName){
			$plugin = new Folder(APP . 'Plugin' . DS . $pluginName . DS . 'webroot'. DS . 'js' . DS . 'app' . DS . 'components');
			$components = array_merge($components, $plugin->findRecursive('.*\.js'));
		}
		
		//remove ._ controller files
		$_components = [];
		foreach($components as $component){
			if(!strpos($component, '._')){
				$_components[] = $component;
			}
		}
		return $_components;
	}
	
	public function fetchAllJavaScriptControllers(){
		$core = new Folder(WWW_ROOT . 'js' . DS . 'app' . DS . 'controllers');
		$controllers = $core->findRecursive('.*\.js');
		
		foreach(CakePlugin::loaded() as $pluginName){
			$plugin = new Folder(APP . 'Plugin' . DS . $pluginName . DS . 'webroot'. DS . 'js' . DS . 'app' . DS . 'controllers');
			$controllers = array_merge($controllers, $plugin->findRecursive('.*\.js'));
		}
		
		//remove ._ controller files
		$_controllers = [];
		foreach($controllers as $controller){
			if(!strpos($controller, '._')){
				$_controllers[] = $controller;
			}
		}
		return $_controllers;
	}
	
	public function compressFiles($files, $outFileName){
		$outFile = new File(WWW_ROOT . 'js' . DS . $outFileName);
		if($outFile->exists()){
			$outFile->delete();
		}
		$outFile->create();
		$content = '';
		foreach($files as $file){
			$fileObject = new File($file);
			if($fileObject->exists()){
				//Remove strict because of js issue:
				//Uncaught SyntaxError: Octal literals are not allowed in strict mode.
				//Not all JS files are strict compatible
				$content .= str_replace(["'use strict';", '"use strict";'], '', $fileObject->read());
			}
		}
		$outFile->write($content);
		$outFile->close();
	}
	
	public function minifyJsFile($fileName){
		$minifier = new Minify\JS(WWW_ROOT . 'js' . DS . $fileName);
		$file = new File(WWW_ROOT . 'js' . DS . $fileName);
		if($file->exists()){
			$file->delete();
		}
		$file->create();
		$file->write($minifier->minify());
		$file->close();
	}
	
}
