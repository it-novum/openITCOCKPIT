<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

use Crate\PDO\PDO as PDO;
use Crate\PDO\PDOStatement;

require_once __DIR__ . DS . 'CrateDboSource.php';

class Crate extends CrateDboSource {

    private $Model;

    public $description = "CrateDB DBO Driver";

    protected $_baseConfig = [
        'host' => 'cratedb1.oitc.itn:4200',
        'timeout' => 1
    ];

    /**
     * @var array
     */
    private $tableMetaData;

    /**
     * @var string
     */
    private $tableName;

    public $fieldParameters = array(
        'charset' => array('value' => 'CHARACTER SET', 'quote' => false, 'join' => ' ', 'column' => false, 'position' => 'beforeDefault'),
        'collate' => array('value' => 'COLLATE', 'quote' => false, 'join' => ' ', 'column' => 'Collation', 'position' => 'beforeDefault'),
        'comment' => array('value' => 'COMMENT', 'quote' => true, 'join' => ' ', 'column' => 'Comment', 'position' => 'afterDefault'),
        'unsigned' => array(
            'value' => 'UNSIGNED', 'quote' => false, 'join' => ' ', 'column' => false, 'position' => 'beforeDefault',
            'noVal' => true,
            'options' => array(true),
            'types' => array('integer', 'float', 'decimal', 'biginteger')
        )
    );

    /**
     * List of table engine specific parameters used on table creating
     *
     * @var array
     */
    public $tableParameters = array(
        'charset' => array('value' => 'DEFAULT CHARSET', 'quote' => false, 'join' => '=', 'column' => 'charset'),
        'collate' => array('value' => 'COLLATE', 'quote' => false, 'join' => '=', 'column' => 'Collation'),
        'engine' => array('value' => 'ENGINE', 'quote' => false, 'join' => '=', 'column' => 'Engine'),
        'comment' => array('value' => 'COMMENT', 'quote' => true, 'join' => '=', 'column' => 'Comment'),
    );

    /**
     * MySQL column definition
     *
     * @var array
     */
    public $columns = array(
        'primary_key' => array('name' => 'NOT NULL AUTO_INCREMENT'),
        'string' => array('name' => 'varchar', 'limit' => '255'),
        'text' => array('name' => 'text'),
        'biginteger' => array('name' => 'bigint', 'limit' => '20'),
        'integer' => array('name' => 'int', 'limit' => '11', 'formatter' => 'intval'),
        'float' => array('name' => 'float', 'formatter' => 'floatval'),
        'decimal' => array('name' => 'decimal', 'formatter' => 'floatval'),
        'datetime' => array('name' => 'datetime', 'format' => 'Y-m-d H:i:s', 'formatter' => 'date'),
        'timestamp' => array('name' => 'timestamp', 'format' => 'Y-m-d H:i:s', 'formatter' => 'date'),
        'time' => array('name' => 'time', 'format' => 'H:i:s', 'formatter' => 'date'),
        'date' => array('name' => 'date', 'format' => 'Y-m-d', 'formatter' => 'date'),
        'binary' => array('name' => 'blob'),
        'boolean' => array('name' => 'tinyint', 'limit' => '1')
    );

    public function __construct($config = null, $autoConnect = true) {
        $this->config = Hash::merge($this->_baseConfig, $config);
        parent::__construct($config, $autoConnect);
    }

    public function listSources($data = null){
        return null;
    }

    function describe($model) {
        [];
    }



    public function connect(){

        $dsn = 'crate:cratedb1.oitc.itn:4200';
        $flags = [
            PDO::ATTR_TIMEOUT => $this->config['timeout']
        ];
        try {
            $this->_connection = new PDO(
                $dsn,
                null,
                null,
                $flags
            );
            $this->connected = true;

        } catch (PDOException $e) {
            throw new MissingConnectionException(array(
                'class' => get_class($this),
                'message' => $e->getMessage()
            ));
        }


        return $this->connected;

    }


    protected function _execute($sql, $params = array(), $prepareOptions = array()) {
        $sql = trim($sql);
        if (preg_match('/^(?:CREATE|ALTER|DROP)\s+(?:TABLE|INDEX)/i', $sql)) {
            $statements = array_filter(explode(';', $sql));
            if (count($statements) > 1) {
                $result = array_map(array($this, '_execute'), $statements);
                return array_search(false, $result) === false;
            }
        }

        try {
            $query = $this->_connection->prepare($sql, $prepareOptions);
            var_dump($sql);
            var_dump($prepareOptions);
            $query->setFetchMode(PDO::FETCH_ASSOC);
            if (!$query->execute($params)) {
                $this->_results = $query;
                $query->closeCursor();
                return false;
            }
            if (!$query->columnCount()) {
                $query->closeCursor();
                if (!$query->rowCount()) {
                    return true;
                }
            }
            return $query;
        } catch (PDOException $e) {
            if (isset($query->queryString)) {
                $e->queryString = $query->queryString;
            } else {
                $e->queryString = $sql;
            }
            throw $e;
        }
    }
    
    public function read(Model $Model, $queryData = array(), $recursive = null) {
        $this->modelName = $Model->alias;
        $this->tableName = $Model->table;
        $this->Model = $Model;
        $this->getTableMetaInformation($Model->table);


        return parent::read($Model, $queryData, $recursive);
    }

    public function getTableMetaInformation($tableName){
        $query = $this->_connection->prepare(sprintf('SHOW COLUMNS FROM %s', $tableName));
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute();
        $this->tableMetaData = $query->fetchAll();
    }

    public function resultSet($results) {

        $this->map = array();
        $numFields = $results->columnCount();

        $index = 0;

        foreach($this->tableMetaData as $table){
            $this->map[$index++] = array($this->tableName, $table['column_name'], $table['data_type']);
        }

    }

    public function fetchAll($sql, $params = array(), $options = array()) {
        if (is_string($options)) {
            $options = array('modelName' => $options);
        }
        if (is_bool($params)) {
            $options['cache'] = $params;
            $params = array();
        }
        $options += array('cache' => true);
        $cache = $options['cache'];
        if ($cache && ($cached = $this->getQueryCache($sql, $params)) !== false) {
            return $cached;
        }
        $result = $this->execute($sql, array(), $params);
        if ($result) {
            $out = array();

            if ($this->hasResult()) {
                foreach($this->_result->fetchAll() as $record){
                    $out[] = [
                        $this->modelName => $record
                    ];
                }
                /*$first = $this->fetchRow();
                if ($first) {
                    $out[] = $first;
                }
                while ($item = $this->fetchResult()) {
                    if (isset($item[0])) {
                        $this->fetchVirtualField($item);
                    }
                    $out[] = $item;
                }*/
            }

            if (!is_bool($result) && $cache) {
                $this->_writeQueryCache($sql, $out, $params);
            }

            if (empty($out) && is_bool($this->_result)) {
                return $this->_result;
            }
            return $out;
        }
        return false;
    }

/*
    public function fetchAll(PDOStatement $query){
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
*/
}