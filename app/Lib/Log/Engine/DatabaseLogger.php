<?php
App::uses('ClassRegistry', 'Utility');
App::uses('CakeLogInterface','Log');
class DatabaseLogger implements CakeLogInterface {

	/**
	* @var SystemLog
	*/
	public $SystemLog = null;

	/**
	* Contruct the model class
	*/
	public function __construct($options = array()) {
		$this->SystemLog = ClassRegistry::init('SystemLog');
	}

	/**
	* Write the log to database
	*/
	public function write($type, $message) {
		$this->SystemLog->create();
		$this->SystemLog->save(array(
			'type' => $type,
			'message' => $message
		));
	}
}