<?php
/**
 * ClearCache shell
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

App::uses('AppShell', 'Console/Command');
App::uses('ClearCache', 'ClearCache.Lib');

/**
 * Helps clear content of CACHE subfolders as well as content in cache engines from console
 *
 * @package       ClearCache.Console.Command
 */
class ClearCacheShell extends AppShell {

/**
 * ClearCache instance
 *
 * @var ClearCache
 */
	protected $_Cleaner;

/**
 * Disables cache and constructs this Shell instance.
 *
 * @param ConsoleOutput $stdout
 * @param ConsoleOutput $stderr
 * @param ConsoleInput $stdin
 */
	public function __construct($stdout = null, $stderr = null, $stdin = null) {
		Configure::write('Cache.disable', true);
		parent::__construct($stdout, $stderr, $stdin);
	}

/**
 * Main shell method
 *
 * Clears content of CACHE subfolders and configured cache engines
 *
 * @return array associative array with cleanup results
 */
	public function main() {
		$this->files();
		$this->engines();
	}

/**
 * Clears content of cache engines
 *
 * @return void
 */
	public function engines() {
		$output = call_user_func_array(array($this->_Cleaner, 'engines'), $this->args);

		$this->out(__('<success>Engines cleaned:</success> %d', count($output)), 2);
		foreach ($output as $key => $result) {
			$this->out("\t$key: " . ($result ? 'cleared' : 'error'), 1, Shell::VERBOSE);
		}
		$this->out(null, 1, Shell::VERBOSE);
	}

/**
 * Clears content of CACHE subfolders
 *
 * @return void
 */
	public function files() {
		$output = call_user_func_array(array($this->_Cleaner, 'files'), $this->args);

		$this->out(__('<success>Files cleaned:</success> %d', count($output['deleted'])), 2);
		foreach ($output as $result => $files) {
			foreach ($files as $file) {
				$this->out("\t$result: " . $file, 1, Shell::VERBOSE);
			}
		}
		$this->out(null, 1, Shell::VERBOSE);
	}

/**
 * Clears groups of cache engines
 *
 * @return void
 */
	public function groups() {
		$output = call_user_func_array(array($this->_Cleaner, 'groups'), $this->args);

		$cleaned = count(Hash::flatten($output));
		$this->out(__('<success>Groups cleaned:</success> %d', $cleaned), 2);
		foreach ($output as $group => $engines) {
			$this->out("\t$group:", 1, Shell::VERBOSE);
			foreach ($engines as $engine => $result) {
				$this->out("\t - $engine: " . ($result ? 'cleared' : 'error'), 1, Shell::VERBOSE);
			}
		}
		$this->out(null, 1, Shell::VERBOSE);
	}

/**
 * Shell startup
 *
 * Initializes $_Cleaner property
 *
 * @return void
 */
	public function startup() {
		$this->_Cleaner = new ClearCache();
	}

}
