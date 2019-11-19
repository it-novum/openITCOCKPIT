<?php

$DbConfig = new \DATABASE_CONFIG();


return [
    'Datasources' => [
        'default' => [
            'className'        => 'Cake\Database\Connection',
            'driver'           => 'Cake\Database\Driver\Mysql',
            'persistent'       => false,
            'host'             => $DbConfig->default['host'],
            /*
             * CakePHP will use the default DB port based on the driver selected
             * MySQL on MAMP uses port 8889, MAMP users will want to uncomment
             * the following line and set the port accordingly
             */
            //'port' => 'non_standard_port_number',
            'username'         => $DbConfig->default['login'],
            'password'         => $DbConfig->default['password'],
            'database'         => $DbConfig->default['database'],
            /*
             * You do not need to set this flag to use full utf-8 encoding (internal default since CakePHP 3.6).
             */
            //'encoding' => 'utf8mb4',
            'timezone'         => 'UTC',
            'flags'            => [],
            'cacheMetadata'    => true,
            'log'              => false,

            /**
             * Set identifier quoting to true if you are using reserved words or
             * special characters in your table or column names. Enabling this
             * setting will result in queries built using the Query Builder having
             * identifiers quoted when creating SQL. It should be noted that this
             * decreases performance because each query needs to be traversed and
             * manipulated before being executed.
             */
            'quoteIdentifiers' => true,
        ],

        /**
         * The test connection is used during the test suite.
         */
        'test'    => [
            'className'        => 'Cake\Database\Connection',
            'driver'           => 'Cake\Database\Driver\Mysql',
            'persistent'       => false,
            'host'             => $DbConfig->default['host'],
            //'port' => 'non_standard_port_number',
            'username'         => $DbConfig->default['login'],
            'password'         => $DbConfig->default['password'],
            'database'         => $DbConfig->default['database'].'_test',
            //'encoding' => 'utf8mb4',
            'timezone'         => 'UTC',
            'cacheMetadata'    => true,
            'quoteIdentifiers' => false,
            'log'              => false,
            //'init' => ['SET GLOBAL innodb_stats_on_metadata = 0'],
            'url'              => env('DATABASE_TEST_URL', null),
        ],
    ]
];

