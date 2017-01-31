<?php
class UsersToContainerFixture extends CakeTestFixture {

	public $table = 'users_to_containers';

	public $fields = [
		'id'              => ['type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'],
		'user_id'      => ['type' => 'integer', 'null' => false, 'default' => null],
		'container_id'    => ['type' => 'integer', 'null' => false, 'default' => null],
		'indexes'         => [
			'PRIMARY'      => ['column' => 'id', 'unique' => 1],
			'user_id'   => ['column' => 'user_id', 'unique' => 0],
			'container_id' => ['column' => 'container_id', 'unique' => 0],
		],
		'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB'],
	];

	public function init() {
		$this->records = array(
			array(
				'id' => 1,
				'user_id' => '1',
				'container_id' => '1'
			)
		);
		parent::init();
	}
}