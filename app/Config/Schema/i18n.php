<?php
/**
 * This is i18n Schema file
 * Use it to configure database for i18n
 * PHP 5
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config.Schema
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Using the Schema command line utility
 * Use it to configure database for i18n
 * cake schema run create i18n
 */
class I18nSchema extends CakeSchema {

    public $name = 'i18n';

    public function before($event = []) {
        return true;
    }

    public function after($event = []) {
    }

    public $i18n = [
        'id'          => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'key' => 'primary'],
        'locale'      => ['type' => 'string', 'null' => false, 'length' => 6, 'key' => 'index'],
        'model'       => ['type' => 'string', 'null' => false, 'key' => 'index'],
        'foreign_key' => ['type' => 'integer', 'null' => false, 'length' => 10, 'key' => 'index'],
        'field'       => ['type' => 'string', 'null' => false, 'key' => 'index'],
        'content'     => ['type' => 'text', 'null' => true, 'default' => null],
        'indexes'     => ['PRIMARY' => ['column' => 'id', 'unique' => 1], 'locale' => ['column' => 'locale', 'unique' => 0], 'model' => ['column' => 'model', 'unique' => 0], 'row_id' => ['column' => 'foreign_key', 'unique' => 0], 'field' => ['column' => 'field', 'unique' => 0]],
    ];

}
