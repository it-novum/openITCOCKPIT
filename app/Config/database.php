<?php
class DATABASE_CONFIG {

	public function __construct(){
		switch(ENVIRONMENT) {
			case 'development':
            case 'development_test':
				$this->default = $this->development;
				$this->test = $this->development_test;
				break;
			case 'production':
				$this->default = $this->production;
				break;
			case 'staging':
				$this->default = $this->staging;
				$this->test = $this->staging_test;
				break;
			default:
				throw new Exception('Invalid Environment ' . ENVIRONMENT);
		}
	}

	public $default = array();

	private $development = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'root',
		'password' => 'ludmilla',
		'database' => 'openitcockpit',
		'prefix' => '',
		'encoding' => 'utf8'
	);

	private $production = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'root',
		'password' => 'ludmilla',
		'database' => 'openitcockpit',
		'prefix' => '',
		'encoding' => 'utf8'
	);

	private $staging = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'root',
		'password' => 'ludmilla',
		'database' => 'openitcockpit',
		'prefix' => '',
		'encoding' => 'utf8'
	);

	private $development_test = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'root',
		'password' => 'ludmilla',
		'database' => 'test_openitcockpit',
		'prefix' => '',
		'encoding' => 'utf8'
	);

	private $staging_test = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'root',
		'password' => 'ludmilla',
		'database' => 'openitcockpit',
		'prefix' => '',
		'encoding' => 'utf8'
	);

	public $test = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'root',
		'password' => 'ludmilla',
		'database' => 'php_testing'
	);
}

