<?php
/**
 * ClearCache Controller
 *
 * Allows clear cache from ClearCache panel for DebugKit
 *
 * PHP 5
 *
 * Copyright 2010-2012, Marc Ypes, The Netherlands
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     2010-2012 Marc Ypes, The Netherlands
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('ClearCacheAppController', 'ClearCache.Controller');
App::uses('ClearCache', 'ClearCache.Lib');

/**
 * ClearCache Controller
 *
 * @package       ClearCache.Controller
 */
class ClearCacheController extends ClearCacheAppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'ClearCache';

/**
 * This controller doesn't use any model
 *
 * @var array
 */
	public $uses = array();

/**
 * Used helpers
 *
 * @var array
 */
	public $helpers = array('Html');

/**
 * This controller doesn't need any layout
 *
 * @var boolean
 */
	public $autoLayout = false;

/**
 * ClearCache instance
 *
 * @var ClearCache
 */
	protected $_Cleaner;

/**
 * Disables cache and then constructs controller instance.
 *
 * @param CakeRequest $request
 * @param CakeResponse $response
 */
	public function __construct($request = null, $response = null) {
		Configure::write('Cache.disable', true);
		parent::__construct($request, $response);
	}

/**
 * Ensures that current request is allowed and initializes $_Cleaner property
 *
 * @return void
 * @throws NotFoundException when request is not allowed
 */
	public function beforeFilter() {
		parent::beforeFilter();

		if (!$this->_isAllowed()) {
			throw new NotFoundException();
		}

		$this->_Cleaner = new ClearCache();
	}

/**
 * Clears content of CACHE subfolders
 *
 * @param mixed any amount of strings - fileMasks for ClearCache::files()
 * @return void
 */
	public function files() {
		$output = call_user_func_array(array($this->_Cleaner, 'files'), $this->params['pass']);
		$files = array();
		$start = strlen(CACHE);
		foreach ($output as $result => $fullPaths) {
			foreach ($fullPaths as $fullPath) {
				$files[] = array(substr($fullPath, $start), $result);
			}
		}
		$this->set(compact('files'));
	}

/**
 * Clears content of cache engines
 *
 * @param mixed any amount of strings - keys of configured cache engines
 * @return void
 */
	public function engines() {
		$output = call_user_func_array(array($this->_Cleaner, 'engines'), $this->params['pass']);
		$engines = array();
		foreach ($output as $engine => $result) {
			$engines[] = array($engine, ($result ? 'cleared' : 'error'));
		}
		$this->set(compact('engines'));
	}

/**
 * Clears group of cache engines
 *
 * @param mixed any amount of strings - keys of configured cache groups
 * @return void
 */
	public function groups() {
		$output = call_user_func_array(array($this->_Cleaner, 'groups'), $this->params['pass']);
		$groups = array();
		foreach ($output as $group => $engines) {
			$cleared = $error = array();
			foreach ($engines as $engine => $result) {
				if ($result) {
					$cleared[] = $engine;
				} else {
					$error[] = $engine;
				}
			}
			$groups[] = array($group, join(', ', $cleared), join(', ', $error));
		}
		$this->set(compact('groups'));
	}

/**
 * Checks if clear cache request is allowed
 *
 * @return boolean
 */
	protected function _isAllowed() {
		return CakePlugin::loaded('DebugKit')
			&& (Configure::read('debug') > 0
				|| (isset($this->Toolbar)
					&& !empty($this->Toolbar->settings['forceEnable'])
				)
			)
			&& $this->request->is('ajax');
	}
}
