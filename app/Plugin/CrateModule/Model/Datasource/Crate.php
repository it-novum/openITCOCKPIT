<?php
//Licensed under The MIT License

use Crate\PDO\PDO as PDO;
use Crate\PDO\PDOStatement;

require_once __DIR__ . DS . 'CrateDboSource.php';

class Crate extends CrateDboSource {

    /**
     * @var string
     */
    private $findType;

    /**
     * @var string
     */
    private $modelName;


    /**
     * @var Model
     */
    private $Model;

    /**
     * @var string
     */
    public $description = "CrateDB DBO Driver";

    protected $_baseConfig = [
        'host' => '127.0.0.1:4200',
        'timeout' => 1
    ];

    /**
     * @var string
     */
    public $startQuote = '';

    /**
     * @var string
     */
    public $endQuote = '';

    /**
     * The set of valid SQL operations usable in a WHERE statement
     *
     * @var array
     */
    protected $_sqlOps = array('like', 'ilike', 'rlike', 'or', 'not', 'in', 'between', 'regexp', 'similar to');

    /**
     * @var array
     */
    private $tableMetaData = null;

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var string
     */
    private $tablePrefix;

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

    public function __construct($config = null, $autoConnect = true){
        $this->config = Hash::merge($this->_baseConfig, $config);
        parent::__construct($config, $autoConnect);
    }

    public function listSources($data = null){
        return null;
    }

    function describe($model){
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

    /**
     * @param PDOStatement $query
     * @param string $sql
     * @return bool|PDOStatement
     */
    protected function __execute(PDOStatement $query, $sql){
        try {
            //$query->setFetchMode(PDO::FETCH_ASSOC);
            if (!$query->execute()) {
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


    /**
     * @param Model $Model
     * @param array $queryData
     * @param null $recursive
     * @return array|int
     */
    public function read(Model $Model, $queryData = array(), $recursive = null){
        $this->findType = $Model->findQueryType;
        $this->modelName = $Model->alias;
        $this->tableName = $Model->table;
        $this->tablePrefix = $Model->tablePrefix;

        $this->Model = $Model;

        if($this->tableMetaData === null) {
            $this->getTableMetaInformation($this->tablePrefix.$this->tableName);
        }

        if (empty($queryData['fields'])) {
            $queryData['fields'] = ['*'];
        }

        if (!empty($queryData['joins'])) {
            throw new NotImplementedException('joins are not implemented now');
        }
debug($queryData);
        $this->buildSelectQuery($queryData);
        return $this->fetchAllCrate();
    }

    public function buildSelectQuery($queryData){
        $queryTemplate = 'SELECT %s FROM %s AS %s ';
        if ($this->findType === 'count') {
            $queryTemplate = sprintf($queryTemplate, 'COUNT(*) as count', $this->tablePrefix.$this->tableName, $this->modelName);
        } else {
            $queryTemplate = sprintf($queryTemplate, implode(',', $queryData['fields']), $this->tablePrefix.$this->tableName, $this->modelName);
        }

        if (!empty($queryData['conditions'])) {
            $i = 1;
            foreach ($queryData['conditions'] as $column => $condition) {
                if ($this->columnExists($column)) {
                    $result = $this->_parseKey($column, $condition, $this->Model);
                    if ($i === 1) {
                        if (is_array($result['value'])) {
                            $placeholders = [];
                            foreach ($result['value'] as $value) {
                                $placeholders[] = '?';
                            }
                            $queryTemplate = sprintf('%s WHERE %s %s (%s)', $queryTemplate, $result['key'], $result['operator'], implode(', ', $placeholders));
                        } else {
                            $queryTemplate = sprintf('%s WHERE %s %s ?', $queryTemplate, $result['key'], $result['operator']);
                        }
                    } else {
                        if (is_array($result['value'])) {
                            $placeholders = [];
                            foreach ($result['value'] as $value) {
                                $placeholders[] = '?';
                            }
                            $queryTemplate = sprintf('%s AND %s %s (%s)', $queryTemplate, $result['key'], $result['operator'], implode(', ', $placeholders));
                        } else {
                            $queryTemplate = sprintf('%s AND %s %s ?', $queryTemplate, $result['key'], $result['operator']);
                        }
                    }
                    $i++;
                }
            }
        }

        if (!empty($queryData['group'])) {
            $groupBy = [];
            foreach ($queryData['group'] as $column) {
                if ($this->columnExists($column)) {
                    $groupBy[] = $column;
                }
            }

            if (!empty($groupBy)) {
                $queryTemplate = sprintf('%s GROUP BY %s', $queryTemplate, implode(', ', $groupBy));
            }
        }

        if (!empty($queryData['order']) && $this->findType !== 'count') {
            $orderBy = [];
            foreach ($queryData['order'] as $column => $direction) {
                if ($this->columnExists($column)) {
                    $direction = $this->getDirection($direction);
                    $orderBy[] = sprintf('%s %s', $column, $direction);
                }
            }

            if (!empty($orderBy)) {
                $queryTemplate = sprintf('%s ORDER BY %s', $queryTemplate, implode(', ', $orderBy));
            }
        }

        if (!empty($queryData['limit']) && $this->findType !== 'count') {
            $queryTemplate = sprintf('%s LIMIT ?', $queryTemplate);
        }


        if (!empty($queryData['offset']) && $this->findType !== 'count') {
            $queryTemplate = sprintf('%s OFFSET ?', $queryTemplate);
        }

        $attachedParameters = [];
        $query = $this->_connection->prepare($queryTemplate);
        $i = 1;
        if (!empty($queryData['conditions'])) {
            foreach ($queryData['conditions'] as $column => $condition) {
                if ($this->columnExists($column)) {
                    $result = $this->_parseKey($column, $condition, $this->Model);
                    if (is_array($result['value'])) {
                        foreach ($result['value'] as $value) {
                            $query->bindValue($i++, $value);
                            $attachedParameters[] = $value;
                        }
                    } else {
                        $query->bindValue($i++, $result['value']);
                        $attachedParameters[] = $result['value'];
                    }
                }
            }
        }

        if (!empty($queryData['limit']) && $this->findType !== 'count') {
            $query->bindValue($i++, $queryData['limit'], PDO::PARAM_INT);
            $attachedParameters[] = $queryData['limit'];
        }


        if (!empty($queryData['offset']) && $this->findType !== 'count') {
            $offset = $queryData['offset'];
            if (!empty($queryData['page']) && $queryData['page'] > 1 && !empty($queryData['limit'])) {
                $offset = (int)$queryData['page'] * $queryData['limit'];
            }
            $query->bindValue($i++, $offset, PDO::PARAM_INT);
            $attachedParameters[] = $offset;
        }

        debug($queryTemplate);

        return $this->executeQuery($query, $queryTemplate, [], $attachedParameters);
    }

    /**
     * @param string $columnName
     * @return bool
     */
    public function columnExists($columnName){
        $key = $this->modelName . '.';
        if (strpos($columnName, $key, 0) === 0) {
            $columnName = substr($columnName, strlen($key));
        }

        foreach ($this->tableMetaData as $column) {
            if ($column['column_name'] === $columnName) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $direction
     * @return string
     */
    public function getDirection($direction){
        $direction = strtoupper($direction);
        if (!in_array($direction, ['ASC', 'DESC'], true)) {
            return 'ASC';
        }
        return $direction;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param Model|null $Model
     * @return array
     */
    protected function _parseKey($key, $value, Model $Model = null){
        $operatorMatch = '/^(((' . implode(')|(', $this->_sqlOps);
        $operatorMatch .= ')\\x20?)|<[>=]?(?![^>]+>)\\x20?|[>=!]{1,3}(?!<)\\x20?)/is';
        $bound = (strpos($key, '?') !== false || (is_array($value) && strpos($key, ':') !== false));

        $key = trim($key);
        if (strpos($key, ' ') === false) {
            $operator = '=';
        } else {
            list($key, $operator) = explode(' ', $key, 2);

            if (!preg_match($operatorMatch, trim($operator)) && strpos($operator, ' ') !== false) {
                $key = $key . ' ' . $operator;
                $split = strrpos($key, ' ');
                $operator = substr($key, $split);
                $key = substr($key, 0, $split);
            }
        }

        $null = $value === null || (is_array($value) && empty($value));

        if (!preg_match($operatorMatch, trim($operator))) {
            $operator .= is_array($value) ? ' IN' : ' =';
        }
        $operator = trim($operator);

        if (is_array($value)) {
            switch ($operator) {
                case '=':
                    $operator = 'IN';
                    break;
                case '!=':
                case '<>':
                    $operator = 'NOT IN';
                    break;
            }
        } elseif ($null || $value === 'NULL') {
            switch ($operator) {
                case '=':
                    $operator = 'IS';
                    break;
                case '!=':
                case '<>':
                    $operator = 'IS NOT';
                    break;
            }
        }

        return [
            'key' => $key,
            'value' => $value,
            'operator' => $operator,
            'bound' => $bound
        ];
    }

    /**
     * @param string $tableName
     */
    public function getTableMetaInformation($tableName){
        $sql = sprintf('SHOW COLUMNS FROM %s', $tableName);
        $query = $this->_connection->prepare($sql);
        $query = $this->executeQuery($query, $sql, [], []);
        $this->_result->setFetchMode(PDO::FETCH_ASSOC);
        $this->tableMetaData = $this->_result->fetchAll();
    }


    /**
     * @param PDOStatement $query
     * @param string $sql
     * @param array $options
     * @param array $params
     * @return array|bool|PDOStatement
     */
    public function executeQuery(PDOStatement $query, $sql, $options = array(), $params = array()){
        $options += array('log' => $this->fullDebug);

        $t = microtime(true);
        $this->_result = $this->__execute($query, $sql);


        if ($options['log']) {
            $this->took = round((microtime(true) - $t) * 1000, 0);
            $this->numRows = $this->affected = $this->lastAffected();
            $this->logQuery($sql, $params);
        }

        return $this->_result;
    }

    /**
     * @return array|int
     */
    public function fetchAllCrate(){
        if ($this->hasResult()) {
            $this->_result->setFetchMode(PDO::FETCH_ASSOC);
            $dbResult = $this->_result->fetchAll();

            if ($this->findType === 'count') {
                $count = 0;
                if (isset($dbResult[0]['count'])) {
                    $count = (int)$dbResult[0]['count'];
                }
                return [
                    0 => [
                        $this->modelName => [
                            'count' => $count
                        ]
                    ]
                ];
            }

            if ($this->findType === 'first' && isset($dbResult[0])) {
                return [
                    $this->modelName => $dbResult[0]
                ];
            }

            $result = [];
            foreach ($dbResult as $dbRecord) {
                $result[] = [
                    $this->modelName => $dbRecord
                ];
            }
            return $result;

        }

        return [];
    }

    /**
     * Log given SQL query.
     *
     * @param string $sql SQL statement
     * @param array $params Values binded to the query (prepared statements)
     * @return void
     */
    public function logQuery($sql, $params = array()){
        $queryParts = explode('?', $sql);
        debug($queryParts);
        debug($params);
        $_sql = '';
        foreach($queryParts as $key => $part){
            if($key === 0){
                $_sql .= $part;
                //This is the "base part" where no parameters are inside.
                continue;
            }
            $key = $key - 1;
            if(isset($params[$key])){
                $param = $params[$key];
                if (!is_numeric($param)) {
                    $param = sprintf('\'%s\'', $param);
                }
                $_sql.= $param;
            }else{
                $_sql.= '?';
            }
            $_sql.= $part;
        }

        $sql = $_sql;
        //$params = [];

        $this->_queriesCnt++;
        $this->_queriesTime += $this->took;
        $this->_queriesLog[] = array(
            'query' => $sql,
            'params' => $params,
            'affected' => $this->affected,
            'numRows' => $this->numRows,
            'took' => $this->took
        );
        if (count($this->_queriesLog) > $this->_queriesLogMax) {
            array_shift($this->_queriesLog);
        }
    }

}