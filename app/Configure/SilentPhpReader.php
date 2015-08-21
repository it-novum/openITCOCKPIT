<?php

/**
 * The exact same reader as the class PhpReader but without an Exception if the file couldn't be found.
 */
class SilentPhpReader extends PhpReader{

	/**
	 * Read a config file and return its contents. Does not complains when a configuration file does not exist!
	 *
	 * @param string $key The identifier to read from. If the key has a . it will be treated as a plugin prefix.
	 * @return array Parsed configuration values.
	 * @throws ConfigureException when files don't exist or they don't contain `$config`.
	 *  Or when files contain '..' as this could lead to abusive reads.
	 */
	public function read($key){
		if(strpos($key, '..') !== false){
			throw new ConfigureException(__d('cake_dev', 'Cannot load configuration files with ../ in them.'));
		}

		$file = $this->_getFilePath($key);
		if(!is_file($file)){
			return [];
		}

		include $file;
		if(!isset($config)){
			throw new ConfigureException(__d('cake_dev', 'No variable %s found in %s', '$config', $file));
		}

		return $config;
	}
}
