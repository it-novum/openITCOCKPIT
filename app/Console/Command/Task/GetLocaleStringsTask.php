<?php

class GetLocaleStringsTask extends AppShell {
    public function execute() {
        $ctrl = $this->_getControllerActions();
        $tpl = "__('%s');";
        foreach ($ctrl as $controllerName => $actions) {
            $identifier = Inflector::underscore(str_replace('Controller', '', $controllerName)) . '.%s.page_title';
            foreach ($actions as $action) {
                $ident = sprintf($identifier, $action);
                $this->out(sprintf($tpl, $ident));
            }
        }
    }


    protected function _getControllerActions() {
        $aCtrlClasses = App::objects('controller');
        foreach ($aCtrlClasses as $controller) {
            if ($controller != 'AppController') {
                // Load the controller
                App::import('Controller', str_replace('Controller', '', $controller));

                // Load its methods / actions
                $aMethods = get_class_methods($controller);

                foreach ($aMethods as $idx => $method) {

                    if ($method{0} == '_') {
                        unset($aMethods[$idx]);
                    }
                }

                // Load the ApplicationController (if there is one)
                App::import('Controller', 'AppController');
                $parentActions = get_class_methods('AppController');

                $controllers[$controller] = array_diff($aMethods, $parentActions);
            }
        }

        return $controllers;
    }
}