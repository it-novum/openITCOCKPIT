<?php
/**
 * ClearCache Panel for DebugKit.Toolbar
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

App::uses('DebugPanel', 'DebugKit.Lib');
App::uses('ClearCache', 'ClearCache.Lib');

/**
 * ClearCache Panel for DebugKit.Toolbar
 *
 * Provides cache information and possibility to clear it/them
 *
 * @package       ClearCache.Lib.Panel
 */
class ClearCachePanel extends DebugPanel {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'ClearCache';

/**
 * Panel element name
 *
 * @var string
 */
	public $elementName = 'clear_cache_panel';

/**
 * Allowed folder names
 *
 * @var array
 */
	public $folders = array('_all_');

/**
 * Allowed cache engines
 *
 * @var array
 */
	public $engines = array('_all_');

/**
 * Allowed cache groups
 *
 * @var array
 */
	public $groups = array('_all_');

/**
 * Constructor
 *
 * @param array $settings Array of settings.
 * @return void
 */
	public function __construct($settings) {
		parent::__construct();

		$this->title = __d('clear_cache', 'Clear Cache');

		foreach (glob(CACHE . '*', GLOB_ONLYDIR) as $folder) {
			$length = strrpos($folder, DS) + 1;
			$this->folders[] = substr($folder, $length);
		}

		$engines = array_diff(Cache::configured(), array('debug_kit'));
		$this->engines = array_merge($this->engines, $engines);

		$groups = array_keys(ClearCache::getGroups());
		$this->groups = empty($groups) ? array() : array_merge($this->groups, $groups);

		foreach (array('folders', 'engines', 'groups') as $property) {
			if (isset($settings['clear_cache'][$property])) {
				$this->{$property} = (array)$settings['clear_cache'][$property];
			}
		}
	}

/**
 * beforeRender callback function
 *
 * @return array contents for panel
 */
	public function beforeRender(Controller $controller) {
		return array(
			'folders' => $this->folders,
			'engines' => $this->engines,
			'groups' => $this->groups,
		);
	}
}
