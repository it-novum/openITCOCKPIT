<?php
/**
 * Environment Panel
 * Provides information about your PHP and CakePHP environment to assist with debugging.
 * PHP 5
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       DebugKit.Lib.Panel
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('DebugPanel', 'DebugKit.Lib');

/**
 * Class EnvironmentPanel
 * @package       DebugKit.Lib.Panel
 */
class EnvironmentPanel extends DebugPanel
{

    /**
     * beforeRender - Get necessary data about environment to pass back to controller
     *
     * @param Controller $controller
     *
     * @return array
     */
    public function beforeRender(Controller $controller)
    {
        parent::beforeRender($controller);

        $return = [];

        // PHP Data
        $phpVer = phpversion();
        $return['php'] = array_merge(['PHP_VERSION' => $phpVer], $_SERVER);
        unset($return['php']['argv']);

        // CakePHP Data
        $return['cake'] = [
            'APP'                    => OLD_APP,
            'APP_DIR'                => OLD_APP_DIR,
            'APPLIBS'                => APPLIBS,
            'CACHE'                  => OLD_CACHE,
            'CAKE'                   => OLD_CAKE,
            'CAKE_CORE_INCLUDE_PATH' => OLD_CAKE_CORE_INCLUDE_PATH,
            'CORE_PATH'              => OLD_CORE_PATH,
            'CAKE_VERSION'           => Configure::version(),
            'CSS'                    => CSS,
            'CSS_URL'                => CSS_URL,
            'DS'                     => DS,
            'FULL_BASE_URL'          => FULL_BASE_URL,
            'IMAGES'                 => IMAGES,
            'IMAGES_URL'             => IMAGES_URL,
            'JS'                     => JS,
            'JS_URL'                 => JS_URL,
            'LOGS'                   => OLD_LOGS,
            'ROOT'                   => OLD_ROOT,
            'TESTS'                  => OLD_TESTS,
            'TMP'                    => OLD_TMP,
            'VENDORS'                => VENDORS,
            'WEBROOT_DIR'            => WEBROOT_DIR,
            'WWW_ROOT'               => WWW_ROOT,
        ];

        $cakeConstants = array_fill_keys(['DS', 'ROOT', 'FULL_BASE_URL', 'TIME_START', 'SECOND',
            'MINUTE', 'HOUR', 'DAY', 'WEEK', 'MONTH', 'YEAR', 'LOG_ERROR', 'FULL_BASE_URL'], '');
        $var = get_defined_constants(true);
        $return['app'] = array_diff_key($var['user'], $return['cake'], $cakeConstants);

        if (isset($var['hidef'])) {
            $return['hidef'] = $var['hidef'];
        }

        return $return;
    }
}
