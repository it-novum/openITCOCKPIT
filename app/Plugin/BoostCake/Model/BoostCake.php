<?php
App::uses('AppModel', 'Model');

class BoostCake extends AppModel
{

    public $useTable = false;

    protected $_schema = [
        'id'       => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => '10'],
        'text'     => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'email'    => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'password' => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'price'    => ['type' => 'integer', 'null' => '', 'default' => '', 'length' => '10'],
        'textarea' => ['type' => 'string', 'null' => '', 'default' => '', 'length' => '255'],
        'checkbox' => ['type' => 'boolean', 'null' => false, 'default' => false],
        'remember' => ['type' => 'boolean', 'null' => false, 'default' => false],
        'select'   => ['type' => 'integer', 'length' => '10', 'null' => true],
        'radio'    => ['type' => 'integer', 'length' => '10', 'null' => true],
        'datetime' => ['type' => 'datetime'],
    ];

}
