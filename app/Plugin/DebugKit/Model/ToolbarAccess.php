<?php
/**
 * DebugKit ToolbarAccess Model
 * Contains logic for accessing DebugKit specific information.
 * PHP 5
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       DebugKit.Model
 * @since         DebugKit 1.3
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 **/

App::uses('ConnectionManager', 'Model');

/**
 * Class ToolbarAccess
 * @package       DebugKit.Model
 * @since         DebugKit 1.3
 */
class ToolbarAccess extends CakeObject
{

    /**
     * Runs an explain on a query if the connection supports EXPLAIN.
     * currently only PostgreSQL and MySQL are supported.
     *
     * @param string $connection Connection name
     * @param string $query      SQL query to explain / find query plan for.
     *
     * @return array Array of explain information or empty array if connection is unsupported.
     */
    public function explainQuery($connection, $query)
    {
        $db = ConnectionManager::getDataSource($connection);
        $datasource = $db->config['datasource'];

        $return = [];
        if (preg_match('/(Mysql|Postgres)$/', $datasource)) {
            $explained = $db->query('EXPLAIN '.$query);
            if (preg_match('/Postgres$/', $datasource)) {
                $queryPlan = [];
                foreach ($explained as $postgreValue) {
                    $queryPlan[] = [$postgreValue[0]['QUERY PLAN']];
                }
                $return = array_merge([['']], $queryPlan);
            } else {
                $keys = array_keys($explained[0][0]);
                foreach ($explained as $mysqlValue) {
                    $queryPlan[] = array_values($mysqlValue[0]);
                }
                $return = array_merge([$keys], $queryPlan);
            }
        }

        return $return;
    }

}
