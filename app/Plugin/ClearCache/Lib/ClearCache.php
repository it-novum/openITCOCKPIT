<?php
/**
 * ClearCache library class
 *
 * PHP 5
 *
 * Copyright 2010-2012, Marc Ypes, The Netherlands
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     2010-2012 Marc Ypes, The Netherlands
 * @author        Ceeram
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Helps clear content of CACHE subfolders as well as content in cache engines
 *
 * @package       ClearCache.Lib
 */
class ClearCache {

/**
 * Clears content of cache engines
 *
 * @param mixed any amount of strings - keys of configured cache engines
 * @return array associative array with cleanup results
 */
	public function engines() {
		if ($cacheDisabled = (bool)Configure::read('Cache.disable')) {
			Configure::write('Cache.disable', false);
		}

		$result = array();

		$engines = Cache::configured();

		if ($args = func_get_args()) {
			$engines = array_intersect($engines, $args);
		}

		foreach ($engines as $engine) {
			$result[$engine] = Cache::clear(false, $engine);
		}

		if ($cacheDisabled) {
			Configure::write('Cache.disable', $cacheDisabled);
		}

		return $result;
	}

/**
 * Clears content of CACHE subfolders
 *
 * @param mixed any amount of strings - names of CACHE subfolders or '.' (dot) for CACHE folder itself
 * @return array associative array with cleanup results
 */
	public function files() {
		$deleted = $error = array();

		$folders = func_get_args();
		if (empty($folders)) {
			$folders = array('.', '*');
		}

		if (count($folders) > 1) {
			$files = glob(CACHE . '{' . implode(',', $folders) . '}' . DS . '*', GLOB_BRACE);
		} else {
			$files = glob(CACHE . $folders[0] . DS . '*');
		}

		foreach ($files as $file) {
			if (is_file($file) && basename($file) !== 'empty') {
				if (unlink($file)) {
					$deleted[] = $file;
				} else {
					$error[] = $file;
				}
			}
		}
		return compact('deleted', 'error');
	}

/**
 * Clears groups of cache engines
 *
 * @param mixed any amount of strings - keys of configured cache groups
 * @return array associative array with cleanup results
 */
	public function groups() {
		if ($cacheDisabled = (bool)Configure::read('Cache.disable')) {
			Configure::write('Cache.disable', false);
		}

		$result = array();

		$groups = self::getGroups();

		if ($args = func_get_args()) {
			$groups = array_intersect_key($groups, array_fill_keys($args, null));
		}

		foreach ($groups as $group => $engines) {
			$result[$group] = array();

			foreach ($engines as $engine) {
				$result[$group][$engine] = Cache::clear(false, $engine);
			}
		}

		if ($cacheDisabled) {
			Configure::write('Cache.disable', $cacheDisabled);
		}

		return $result;
	}

/**
 * Clears content of CACHE subfolders and configured cache engines
 *
 * @return array associative array with cleanup results
 */
	public function run() {
		$files = $this->files();
		$engines = $this->engines();

		return compact('files', 'engines');
	}

/**
 * Get list of groups with their associated cache configurations
 *
 * @return array
 */
	public static function getGroups() {
		$groups = array();
		$keys = Cache::configured();

		foreach ($keys as $key) {
			$config = Cache::config($key);

			if (!empty($config['settings']['groups'])) {
				foreach ($config['settings']['groups'] as $group) {
					if (!isset($groups[$group])) {
						$groups[$group] = array();
					}

					if (!in_array($key, $groups[$group])) {
						$groups[$group][] = $key;
					}
				}
			}
		}

		return $groups;
	}
}
