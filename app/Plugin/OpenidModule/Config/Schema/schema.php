<?php

class OpenidModuleSchema extends CakeSchema {

  public function before($event = array()) {
    $db = ConnectionManager::getDataSource($this->connection);
    $db->cacheSources = false;
    return true;
  }

  public $openid = array(
      'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
      'my_domain' => array('type' => 'string', 'length' => 255, 'null' => false),
      'identity' => array('type' => 'string', 'length' => 255, 'null' => false),
      // 'return_url' => array('type' => 'string', 'length' => 255, 'null' => false),
      'client_secret' => array('type' => 'string', 'length' => 255, 'null' => false),
      'show_login_page' => array('type' => 'integer', 'null' => false, 'default' => 1),
      'button_icon' => array('type' => 'string', 'length' => 32, 'null' => false),
      'button_text' => array('type' => 'string', 'length' => 255, 'null' => false),
      'active' => array('type' => 'integer', 'null' => false, 'default' => 0),
      'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
      'indexes' => array(
          'PRIMARY' => array('column' => 'id', 'unique' => 1)
      ),
      'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
  );

  public $openid_logs = array(
      'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
      'openid_id' => array('type' => 'integer', 'null' => false, 'default' => null),
      'log' => array('type' => 'text', 'null' => false),
      'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
      'indexes' => array(
          'PRIMARY' => array('column' => 'id', 'unique' => 1)
      ),
      'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_swedish_ci', 'engine' => 'InnoDB')
  );

}
