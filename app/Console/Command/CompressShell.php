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

//require_once APP.'Vendor'.DS.'minify'.DS.'src'.DS.'Minify.php';
//require_once APP.'Vendor'.DS.'minify'.DS.'src'.DS.'JS.php';

use MatthiasMullie\Minify;

class CompressShell extends AppShell {

    //This shell search all javascript files and compress it to one big javascript file

    public function main() {
        App::uses('Folder', 'Utility');
        App::uses('File', 'Utility');
        $this->stdout->styles('green', ['text' => 'green']);

        //javascript components
        $this->out('Compress JavaScript controller components...    ', false);
        $components = $this->fetchAllJavaScriptComponents();
        $this->compressFiles($components, 'compressed_components.js');
        $this->minifyJsFile('compressed_components.js');
        $this->out('<green>done</green>');

        //javascript controllers
        $this->out('Compress JavaScript action controllers...    ', false);
        $controllers = $this->fetchAllJavaScriptControllers();
        $this->compressFiles($controllers, 'compressed_controllers.js');
        $this->minifyJsFile('compressed_controllers.js');
        $this->out('<green>done</green>');

        //angular controllers
        $this->out('Compress Angular controllers...    ', false);
        $angularControllers = $this->fetchAllAngularControllers();
        $this->compressFiles($angularControllers, 'compressed_angular_controllers.js');
        $this->minifyJsFile('compressed_angular_controllers.js');
        $this->out('<green>done</green>');

        //angular directives
        $this->out('Compress Angular directives...    ', false);
        $angularDirectives = $this->fetchAllAngularDirectives();
        $this->compressFiles($angularDirectives, 'compressed_angular_directives.js');
        $this->minifyJsFile('compressed_angular_directives.js');
        $this->out('<green>done</green>');

        //angular services
        $this->out('Compress Angular services...    ', false);
        $angularServices = $this->fetchAllAngularServices();
        $this->compressFiles($angularServices, 'compressed_angular_services.js');
        $this->minifyJsFile('compressed_angular_services.js');
        $this->out('<green>done</green>');

        //angular services
        $this->out('Compress Angular states...    ', false);
        $angularStates = $this->fetchAllPluginNGStateFiles();
        $this->compressFiles($angularStates, 'compressed_angular_states.js');
        $this->minifyJsFile('compressed_angular_states.js');
        $this->out('<green>done</green>');
    }

    public function fetchAllJavaScriptComponents() {
        $core = new Folder(WWW_ROOT . 'js' . DS . 'app' . DS . 'components');
        $components = $core->findRecursive('.*\.js');

        foreach (CakePlugin::loaded() as $pluginName) {
            $plugin = new Folder(OLD_APP . 'Plugin' . DS . $pluginName . DS . 'webroot' . DS . 'js' . DS . 'app' . DS . 'components');
            $components = array_merge($components, $plugin->findRecursive('.*\.js'));
        }

        return $this->removeDotFiles($components);
    }

    public function fetchAllJavaScriptControllers() {
        $core = new Folder(WWW_ROOT . 'js' . DS . 'app' . DS . 'controllers');
        $controllers = $core->findRecursive('.*\.js');

        foreach (CakePlugin::loaded() as $pluginName) {
            $plugin = new Folder(OLD_APP . 'Plugin' . DS . $pluginName . DS . 'webroot' . DS . 'js' . DS . 'app' . DS . 'controllers');
            $controllers = array_merge($controllers, $plugin->findRecursive('.*\.js'));
        }

        return $this->removeDotFiles($controllers);
    }


    public function fetchAllAngularControllers() {
        $core = new Folder(WWW_ROOT . 'js' . DS . 'scripts' . DS . 'controllers');
        $angularControllers = $core->findRecursive('.*\.js');

        foreach (CakePlugin::loaded() as $pluginName) {
            $plugin = new Folder(OLD_APP . 'Plugin' . DS . $pluginName . DS . 'webroot' . DS . 'js' . DS . 'scripts' . DS . 'controllers');
            $angularControllers = array_merge($angularControllers, $plugin->findRecursive('.*\.js'));
        }

        return $this->removeDotFiles($angularControllers);
    }

    public function fetchAllAngularDirectives() {
        $core = new Folder(WWW_ROOT . 'js' . DS . 'scripts' . DS . 'directives');
        $angularDirectives = $core->findRecursive('.*\.js');

        foreach (CakePlugin::loaded() as $pluginName) {
            $plugin = new Folder(OLD_APP . 'Plugin' . DS . $pluginName . DS . 'webroot' . DS . 'js' . DS . 'scripts' . DS . 'directives');
            $angularDirectives = array_merge($angularDirectives, $plugin->findRecursive('.*\.js'));
        }

        return $this->removeDotFiles($angularDirectives);

    }

    public function fetchAllAngularServices() {
        $core = new Folder(WWW_ROOT . 'js' . DS . 'scripts' . DS . 'services');
        $angularServices = $core->findRecursive('.*\.js');

        foreach (CakePlugin::loaded() as $pluginName) {
            $plugin = new Folder(OLD_APP . 'Plugin' . DS . $pluginName . DS . 'webroot' . DS . 'js' . DS . 'scripts' . DS . 'services');
            $angularServices = array_merge($angularServices, $plugin->findRecursive('.*\.js'));
        }

        return $this->removeDotFiles($angularServices);
    }

    public function fetchAllPluginNGStateFiles() {
        $core = new Folder(WWW_ROOT . 'js' . DS . 'scripts' . DS . 'states');
        $angularPluginStates = [];

        foreach (CakePlugin::loaded() as $pluginName) {
            $plugin = new Folder(OLD_APP . 'Plugin' . DS . $pluginName . DS . 'webroot' . DS . 'js' . DS . 'scripts');
            $angularPluginStates = array_merge($angularPluginStates, $plugin->findRecursive('ng.states.js'));
        }

        return $this->removeDotFiles($angularPluginStates);
    }

    public function compressFiles($files, $outFileName) {
        $outFile = new File(WWW_ROOT . 'js' . DS . $outFileName);
        if ($outFile->exists()) {
            $outFile->delete();
        }
        $outFile->create();
        $content = '';
        foreach ($files as $file) {
            $fileObject = new File($file);
            if ($fileObject->exists()) {
                //Remove strict because of js issue:
                //Uncaught SyntaxError: Octal literals are not allowed in strict mode.
                //Not all JS files are strict compatible
                $content .= str_replace(["'use strict';", '"use strict";'], '', $fileObject->read());
            }
        }
        $outFile->write($content);
        $outFile->close();
    }

    public function minifyJsFile($fileName) {
        $minifier = new Minify\JS(WWW_ROOT . 'js' . DS . $fileName);
        $file = new File(WWW_ROOT . 'js' . DS . $fileName);
        if ($file->exists()) {
            $file->delete();
        }
        $file->create();
        $file->write($minifier->minify());
        $file->close();
    }

    /**
     * remove ._ files from the list
     * @param array $arr
     * @return array
     */
    public function removeDotFiles($items = []) {
        $_items = [];
        foreach ($items as $item) {
            if (!strpos($item, '._')) {
                $_items[] = $item;
            }
        }
        return $_items;
    }

    public function _welcome() {
        //Disable CakePHP welcome messages
    }

}
