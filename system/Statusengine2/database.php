<?php
/**
 * Statusengine 2 Database Configuration
 */

class DATABASE_CONFIG {

    /**
     * @var array
     */
    public $legacy = [];

    public function __construct() {
        $mcp = new \App\itnovum\openITCOCKPIT\Database\MysqlConfigFileParser();
        $ini_file = $mcp->parse_mysql_cnf('/opt/openitc/etc/mysql/mysql.cnf');

        $this->legacy = [
            'datasource' => 'Database/Mysql',
            'persistent' => false,
            'host'       => $ini_file['host'],
            'login'      => $ini_file['user'],
            'password'   => $ini_file['password'],
            'database'   => $ini_file['database'],
            'prefix'     => 'nagios_',
            'encoding'   => 'utf8mb4',
            'port'       => 3306
        ];
    }
}
