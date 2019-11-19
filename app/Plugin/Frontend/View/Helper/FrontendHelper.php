<?php
App::import('Utility', 'Folder');

class FrontendHelper extends AppHelper
{
    /**
     * The helpers we need
     */
    public $helpers = ['Html'];

    /**
     * Holds the needed JS dependencies.
     * @var array
     */
    public $_dependencies = [
        '/frontend/js/lib/basics.js',
        '/frontend/js/lib/jinheritance.js',
        '/frontend/js/lib/jquery.min.js',
        '/frontend/js/lib/publish_subscribe_broker.js',
        '/frontend/js/lib/app.js',
        '/frontend/js/lib/controller.js',
        '/frontend/js/lib/component.js',
        '/frontend/js/lib/router.js',
    ];

    /**
     * Holds the frontendData array created by the Component
     * @var array
     */
    protected $_frontendData = [
        'jsonData' => [],
    ];

    /**
     * Initialize the helper. Needs to be called before running it.
     *
     * @param array $frontendData
     *
     * @return void
     */
    public function init($frontendData)
    {
        $this->_frontendData = Set::merge(
            $this->_frontendData, $frontendData
        );
        $this->_includeAppController();
        $this->_includeComponents();
    }

    /**
     * Returns a full list of the dependencies (used in console build task)
     *
     * @param array $defaultControllers
     *
     * @return array
     */
    public function compileDependencies($defaultControllers = [])
    {
        $this->_includeAppController();
        $this->_includeComponents();
        $this->addController($defaultControllers);
        $this->addAllControllers();
        $this->_dependencies[] = '/frontend/js/bootstrap.js';

        return array_unique($this->_dependencies);
    }

    /**
     * Includes the configured JS dependencies and appData - should
     * be called from the layout
     * @return    string    HTML
     */
    public function run()
    {
        $out = '';
        $this->_dependencies = array_unique($this->_dependencies);
        $this->_addCurrentController();

        foreach ($this->_dependencies as $dependency) {
            if (strpos($dependency, DS) !== false) {
                $dependency = str_replace(DS, '/', $dependency);
            }
            $jsFile = $this->Html->script($dependency);
            $out .= $jsFile."\n";
        }
        $out .= $this->getAppDataJs($this->_frontendData);
        $out .= $this->Html->script('/frontend/js/bootstrap.js');

        return $out;
    }

    /**
     * Adds the currently visited controller/action, if existant.
     * @return void
     */
    protected function _addCurrentController()
    {
        if (!file_exists(WWW_ROOT.'js'.DS.'compressed_controllers.js')) {
            $this->addController(Inflector::camelize($this->_frontendData['controller']).'.'.Inflector::camelize($this->_frontendData['action']));
            $this->addController(Inflector::camelize($this->_frontendData['controller']));
        }
    }

    /**
     * Adds all controllers in app/controllers to the dependencies.
     * Please use only in development model.
     * @return void
     */
    public function addAllControllers()
    {
        // app/controllers/posts/*_controller.js
        if (file_exists(WWW_ROOT.'js'.DS.'compressed_controllers.js') && ENVIRONMENT === Environments::PRODUCTION) {
            $this->_dependencies[] = '/js/compressed_controllers.js';
        } else {
            $folder = new Folder(JS.'app/controllers');
            foreach ($folder->findRecursive('.*\.js') as $file) {
                $jsFile = str_replace(JS, '', $file);
                $this->_addDependency($jsFile);
            }

            // Add All Plugin Controllers
            foreach (CakePlugin::loaded() as $pluginName) {
                $pluginJsControllersFolder = OLD_APP.'Plugin/'.$pluginName.'/webroot/js/app/controllers/';
                if (is_dir($pluginJsControllersFolder)) {
                    $folder = new Folder($pluginJsControllersFolder);
                    $files = $folder->findRecursive('.*\.js');
                    foreach ($files as $file) {
                        $file = str_replace($pluginJsControllersFolder, '', $file);
                        $this->_dependencies[] = '/'.Inflector::underscore($pluginName).'/js/app/controllers/'.$file;
                    }
                }
            }
        }
    }

    /**
     * Include one or more JS controllers. Supports the 2 different file/folder structures.
     * - app/controllers/posts_edit_permissions_controller.js
     * - app/controllers/posts/edit_permissions_controller.js
     * - app/controllers/posts_controller.js
     * - app/controllers/posts/controller.js
     *
     * @param string|array $controllerName    Dot-separated controller, TitleCased name.
     *                                        Posts.EditPermissions
     *                                        Posts.* (include all)
     *
     * @return bool
     */
    public function addController($controllerName)
    {
        if (is_array($controllerName)) {
            foreach ($controllerName as $cn) {
                $this->addController($cn);
            }

            return true;
        }

        $split = explode('.', $controllerName);
        $controller = $split[0];
        $action = null;
        if (isset($split[1])) {
            $action = $split[1];
        }

        // In the case of a plugin, we need to check the subfolder.
        // @TODO: what if we are in a plugin, but want to include a main app js file?
        if (empty($this->plugin)) {
            $absolutePath = JS;
            $pluginPrefix = '';
        } else {
            $absolutePath = App::pluginPath($this->plugin).'webroot'.DS.'js'.DS;
            $pluginPrefix = '/'.Inflector::underscore($this->plugin).'/js/';
        }

        $paths = [];
        $path = 'app/controllers/';

        if ($controller && $action == '*') {
            // add the base controller
            $this->addController($controller);

            // app/controllers/posts/*_controller.js
            $subdirPath = $path.Inflector::underscore($controller).DS;
            $folder = new Folder($absolutePath.$subdirPath);
            $files = $folder->find('.*\.js');

            if (!empty($files)) {
                foreach ($files as $file) {
                    $this->_addDependency($pluginPrefix.$subdirPath.$file);
                }
            }

            $folder = new Folder($absolutePath.$path);
            // app/controllers/posts_*.js
            $files = $folder->find(Inflector::underscore($controller).'_.*_controller\.js');
            if (!empty($files)) {
                foreach ($files as $file) {
                    $this->_addDependency($pluginPrefix.$path.$file);
                }
            }

            return true;
        } else if ($controller && $action) {
            // app/controllers/posts/edit_controller.js
            $paths[] = $path.Inflector::underscore($controller).DS.Inflector::underscore($action).'_controller';
            // app/controllers/posts_edit_controller.js
            $paths[] = $path.Inflector::underscore($controller).'_'.Inflector::underscore($action).'_controller';
        } else {
            // app/controllers/posts/controller.js
            $paths[] = $path.Inflector::underscore($controller).DS.'controller';
            // app/controllers/posts_controller.js
            $paths[] = $path.Inflector::underscore($controller).'_controller';
        }

        foreach ($paths as $filePath) {
            if (file_exists($absolutePath.$filePath.'.js')) {
                $this->_addDependency($pluginPrefix.$filePath.'.js');

                return true;
            }
        }

        return false;
    }

    /**
     * Include one or more JS components
     *
     * @param string|array $componentName CamelCased component name    (e.g. SelectorAddressList)
     *
     * @return bool
     */
    public function addComponent($componentName)
    {
        if (is_array($componentName)) {
            foreach ($componentName as $cn) {
                $this->addComponent($cn);
            }

            return true;
        }
        $componentFile = 'app/components/'.Inflector::underscore($componentName).'.js';

        if (file_exists(JS.DS.$componentFile)) {
            $this->_addDependency($componentFile);

            return true;
        }

        return false;
    }

    /**
     * Constructs the JS for setting the appData
     *
     * @param array $frontendData
     *
     * @return string    The rendered JS
     */
    public function getAppDataJs()
    {
        return $this->Html->scriptBlock('
			var appData = '.json_encode($this->_frontendData).';
		');
    }

    /**
     * Add a file to the frontend dependencies
     *
     * @param string $file
     *
     * @return void
     */
    protected function _addDependency($file)
    {
        if (!in_array($file, $this->_dependencies)) {
            $this->_dependencies[] = $file;
        }
    }

    /**
     * Check if we have an AppController, if not, include a stub
     * @return void
     */
    protected function _includeAppController()
    {
        $controller = null;
        if (file_exists(JS.'app/app_controller.js')) {
            $controller = 'app/app_controller.js';
        } else {
            $controller = '/frontend/js/lib/app_controller.js';
        }
        $this->_dependencies[] = $controller;
    }

    /**
     * Includes the needed components
     * @return void
     */
    protected function _includeComponents()
    {
        if (file_exists(WWW_ROOT.'js'.DS.'compressed_components.js') && ENVIRONMENT === Environments::PRODUCTION) {
            $this->_dependencies[] = '/js/compressed_components.js';
        } else {
            // for now, we just include all components
            $appComponentFolder = JS.'app/components/';
            $folder = new Folder($appComponentFolder);
            $files = $folder->find('.*\.js');
            if (!empty($files)) {
                foreach ($files as $file) {
                    $this->_dependencies[] = 'app/components/'.$file;
                }
            }

            // Add Plugin Components
            foreach (CakePlugin::loaded() as $pluginName) {
                $pluginJsComponentsFolder = OLD_APP.'Plugin/'.$pluginName.'/webroot/js/app/components/';
                if (is_dir($pluginJsComponentsFolder)) {
                    $folder = new Folder($pluginJsComponentsFolder);
                    $files = $folder->find('.*\.js');
                    foreach ($files as $file) {
                        $this->_dependencies[] = '/'.Inflector::underscore($pluginName).'/js/app/components/'.$file;
                    }
                }
            }
        }
    }
}
