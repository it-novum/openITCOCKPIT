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
        $ini_file = parse_ini_file('/opt/openitc/etc/mysql/mysql.cnf', false, INI_SCANNER_RAW);

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
