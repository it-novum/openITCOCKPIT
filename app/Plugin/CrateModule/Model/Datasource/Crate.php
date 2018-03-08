<?php
//Licensed under The MIT License

use Crate\PDO\PDO as PDO;
use Crate\PDO\PDOStatement;


class Crate extends DboSource {

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
        'timeout' => 5
    ];

    /**
     * @var string
     */
    public $startQuote = '"';

    /**
     * @var string
     */
    public $endQuote = '"';

    /**
     * The set of valid SQL operations usable in a WHERE statement
     *
     * @var array
     */
    protected $_sqlOps = array('like', 'ilike', 'rlike', 'or', 'not', 'in', 'between', 'regexp', 'similar to', '~*', 'IS NULL');

    /**
     * @var array
     */
    private $tableMetaData = [];

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var array
     */
    protected $_queryDefaults = array(
        'conditions' => array(),
        'fields' => null,
        'table' => null,
        'alias' => null,
        'order' => null,
        'limit' => null,
        'joins' => array(),
        'group' => null,
        'offset' => null
    );

    /**
     * @var array
     */
    private $fieldsInQuery = [];


    /**
     * @var array
     */
    protected $joins = [];

    /**
     * @var array
     */
    protected $joinedModels = [];

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

        $vars = [
            'HTTP_PROXY',
            'http_proxy',
            'HTTPS_PROXY',
            'https_proxy',
            'FTP_PROXY',
            'ftp_proxy',
            'NO_PROXY',
            'no_proxy'
        ];
        foreach($vars as $var){
            putenv($var);
        }

        parent::__construct($config, $autoConnect);
    }

    public function listSources($data = null){
        return null;
    }


    /**
     * @param Model|string $model
     * @return array|bool
     */
    function describe($model){
        $table = $this->fullTableName($model, false);

        if (!isset($this->tableMetaData[$model->alias])) {
            $this->getTableMetaInformation($table, $model);
        }

        $fields = [];

        foreach ($this->tableMetaData[$model->alias] as $column) {
            $fields[$column['column_name']] = [
                'type' => $column['data_type'],
                'null' => null,
                'default' => null,
                'length' => null,
                'unsigned' => null
            ];
        }

        //Add virtual field as well
        foreach ($model->virtualFields as $virtualField => $realField) {
            $realField = $this->removeModelAlias($realField, $model);
            if (isset($fields[$realField])) {
                $fields[$virtualField] = [
                    'type' => $fields[$realField]['type'],
                    'null' => false,
                    'default' => false,
                    'length' => false,
                    'unsigned' => false,
                    'isVirtual' => true
                ];
            }
        }

        if (empty($fields)) {
            return false;
        }

        return $fields;
    }


    public function connect(){
        $dsn = sprintf('crate:%s', $this->config['host']);
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
        } catch (Exception $e) {
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
        $this->joins = $queryData['joins'];


        $this->Model = $Model;

        if (!isset($this->tableMetaData[$this->modelName])) {
            $this->getTableMetaInformation($this->tablePrefix . $this->tableName);
        }

        $this->joinedModels = [];
        foreach ($queryData['joins'] as $join) {
            $fakeModel = new stdClass();
            $fakeModel->alias = $join['alias'];
            $this->getTableMetaInformation($join['table'], $fakeModel);
            $this->joinedModels[] = $join['alias'];
        }

        if (empty($queryData['fields'])) {
            $queryData['fields'] = [
                sprintf('%s.*', $this->modelName)
            ];
        }

        if (!empty($this->Model->virtualFields) && $this->findType !== 'count') {
            foreach ($this->Model->virtualFields as $virtualField => $realField) {
                $queryData['fields'][] = sprintf(
                    '%s AS %s',
                    $realField,
                    $virtualField
                );
            }
        }

        if (!empty($queryData['sort']) && !empty($queryData['direction'])) {
            $queryData['order'] = [
                $queryData['sort'] => $queryData['direction']
            ];
        }

        $this->buildSelectQuery($queryData);
        return $this->fetchAllCrate();
    }

    public function buildSelectQuery($queryData){
        $queryTemplate = 'SELECT %s FROM %s AS %s ';
        if ($this->findType === 'count') {
            $queryTemplate = sprintf($queryTemplate, 'COUNT(*) as count', $this->tablePrefix . $this->tableName, $this->modelName);
        } else {
            $queryTemplate = sprintf($queryTemplate, implode(',', $queryData['fields']), $this->tablePrefix . $this->tableName, $this->modelName);
        }

        $this->fieldsInQuery = [];
        if ($this->findType !== 'count') {
            $modelsInQuery = array_merge($this->joinedModels, [$this->modelName]);

            foreach ($queryData['fields'] as $fieldInQuery) {

                $fieldInQuery = trim($fieldInQuery);

                foreach ($modelsInQuery as $modelInQuery) {
                    if (strpos($fieldInQuery, $modelInQuery . '.') !== 0) {
                        //Is this may be a virtual field?
                        //COUNT(DISTINCT Hoststatus.hostname) AS count
                        foreach($this->Model->virtualFields as $virtualField => $realField){
                            if ($fieldInQuery == sprintf('%s AS %s', $realField, $virtualField)) {
                                $this->fieldsInQuery[] = $virtualField;
                                continue 3;
                            }
                        }

                        continue;
                    }
                    if ($fieldInQuery === sprintf('%s.*', $modelInQuery)) {
                        //User run SELECT * FROM - add all fields
                        foreach ($this->tableMetaData[$modelInQuery] as $column) {
                            $this->fieldsInQuery[] = sprintf('%s.%s', $modelInQuery, $column['column_name']);
                        }
                    } else {
                        $isVirtualField = false;
                        foreach ($this->Model->virtualFields as $virtualField => $realField) {
                            if ($fieldInQuery === sprintf('%s AS %s', $realField, $virtualField)) {
                                $isVirtualField = true;
                                //Hoststatus.statetype => Hoststatus.is_hardstate
                                $this->fieldsInQuery[] = sprintf('%s.%s', $this->modelName, $virtualField);
                            }
                        }

                        if ($isVirtualField === false) {
                            $this->fieldsInQuery[] = $fieldInQuery;
                        }
                    }
                }
            }
        }

        foreach ($queryData['joins'] as $join) {
            //INNER JOIN statusengine_hoststatus as Hoststatus on Hoststatus.hostname = Host.uuid
            $queryTemplate = sprintf(
                '%s %s JOIN %s AS %s ON %s',
                $queryTemplate,
                $join['type'],
                $join['table'],
                $join['alias'],
                $join['conditions']
            );
        }

        $hasWhere = false;
        if (!empty($queryData['conditions'])) {
            $i = 1;
            foreach ($queryData['conditions'] as $column => $condition) {
                $result = $this->_parseKey($column, $condition, $this->Model);
                $column = $result['key'];

                $isVirtualField = false;
                if($this->isVirtualField($column)){
                    $isVirtualField = true;
                    $column = sprintf('%s%s%s', $this->startQuote, $column, $this->endQuote);
                }

                if ($this->columnExists($column) || $isVirtualField === true) {
                    if ($i === 1) {
                        if (is_array($result['value'])) {
                            $placeholders = [];
                            foreach ($result['value'] as $value) {
                                $placeholders[] = '?';
                            }
                            $hasWhere = true;
                            $queryTemplate = sprintf('%s WHERE %s %s (%s)', $queryTemplate, $column, $result['operator'], implode(', ', $placeholders));
                        } else {
                            $hasWhere = true;

                            if ($result['value'] !== null) {
                                $queryTemplate = sprintf('%s WHERE %s %s ?', $queryTemplate, $column, $result['operator']);
                            } else {
                                $queryTemplate = sprintf('%s WHERE %s %s', $queryTemplate, $column, $result['operator']);
                            }
                        }
                    } else {
                        if (is_array($result['value'])) {
                            $placeholders = [];
                            foreach ($result['value'] as $value) {
                                $placeholders[] = '?';
                            }
                            $queryTemplate = sprintf('%s AND %s %s (%s)', $queryTemplate, $column, $result['operator'], implode(', ', $placeholders));
                        } else {
                            if ($result['value'] !== null) {
                                $queryTemplate = sprintf('%s AND %s %s ?', $queryTemplate, $column, $result['operator']);
                            } else {
                                $queryTemplate = sprintf('%s AND %s %s', $queryTemplate, $column, $result['operator']);
                            }
                        }
                    }
                    $i++;
                }
            }
        }
        if (!empty($queryData['array_difference'])) {
            //WHERE array_difference([1,2], Host.container_ids) != [1,2]
            foreach ($queryData['array_difference'] as $field => $values) {
                if ($this->columnExists($field)) {
                    $queryTemplate = sprintf(
                        '%s %s array_difference(?, %s) != ?',
                        $queryTemplate,
                        ($hasWhere) ? 'AND' : 'WHERE',
                        $field
                    );
                    $hasWhere = true;
                }
            }
        }

        if (!empty($queryData['or'])) {
            //WHERE OR multiple 'OR' conditions
            $conditionValues = [];
            foreach ($queryData['or'] as $key => $value) {
                $conditionValues[] = key($queryData['or'][$key]);
            }

            $queryTemplate = sprintf(
                '%s %s (%s)',
                $queryTemplate,
                ($hasWhere) ? 'AND' : 'WHERE',
                implode(' OR ', $conditionValues)
            );

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
                if ($this->isVirtualField($column)) {
                    //Remove Modelname from virtual fields
                    $column = $this->removeModelAlias($column);
                }

                if ($this->columnExists($column)) {
                    $direction = $this->getDirection($direction);
                    $orderBy[] = sprintf('%s %s', $column, $direction);
                }

                if($this->isVirtualField($column)){
                    $column = sprintf('%s%s%s', $this->startQuote, $column, $this->endQuote);
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
                $result = $this->_parseKey($column, $condition, $this->Model);
                $column = $result['key'];

                $isVirtualField = false;
                if($this->isVirtualField($column)){
                    $isVirtualField = true;
                }

                if ($this->columnExists($column) || $isVirtualField === true) {
                    if (is_array($result['value'])) {
                        foreach ($result['value'] as $value) {
                            if (is_bool($value)) {
                                $query->bindValue($i++, $value, PDO::PARAM_BOOL);
                                $attachedParameters[] = $value ? 'true' : 'false';
                            } else {
                                $query->bindValue($i++, $value);
                                $attachedParameters[] = $value;
                            }
                        }
                    } elseif (is_bool($result['value'])) {
                        $query->bindValue($i++, $result['value'], PDO::PARAM_BOOL);
                        $attachedParameters[] = $result['value'] ? 'true' : 'false';
                    } else {
                        if ($result['value'] === null) {
                            continue;
                        }
                        $query->bindValue($i++, $result['value']);
                        $attachedParameters[] = $result['value'];
                    }
                }
            }
        }

        if (!empty($queryData['array_difference'])) {
            //WHERE array_difference([1,2], Host.container_ids) != [1,2]
            foreach ($queryData['array_difference'] as $field => $values) {
                if ($this->columnExists($field)) {
                    $values = array_values($values);
                    $query->bindValue($i++, $values, PDO::PARAM_ARRAY);
                    $query->bindValue($i++, $values, PDO::PARAM_ARRAY);
                    $attachedParameters[] = $values;
                    $attachedParameters[] = $values;
                }

            }
        }

        if (!empty($queryData['or'])) {
            //WHERE OR multiple 'OR' conditions
            foreach ($queryData['or'] as $conditionValues) {
                foreach($conditionValues as $condition => $bindValues){
                    foreach($bindValues as $key => $value){
                        $query->bindValue($i++, $value, PDO::PARAM_TIMESTAMP);
                        $attachedParameters[] = $value;
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
                $offset = (int)($queryData['page'] - 1) * $queryData['limit'];
            }
            $query->bindValue($i++, $offset, PDO::PARAM_INT);
            $attachedParameters[] = $offset;
        }

        return $this->executeQuery($query, $queryTemplate, [], $attachedParameters);
    }

    public function create(Model $Model, $fields = null, $values = null){

        $this->findType = $Model->findQueryType;
        $this->modelName = $Model->alias;
        $this->tableName = $Model->table;
        $this->tablePrefix = $Model->tablePrefix;

        $this->Model = $Model;

        if (!isset($this->tableMetaData[$this->modelName])) {
            $this->getTableMetaInformation($this->tablePrefix . $this->tableName);
        }

        return $this->buildInsertQuery($Model, $fields, $values);
    }

    public function buildInsertQuery($Model, $fields, $values){
        $placeHolders = [];
        foreach ($fields as $field) {
            $placeHolders[] = '?';
            if (!$this->columnExists($field)) {
                throw new Exception(sprintf('Field %s does not exists', $field));
            }
        }

        $queryTemplate = 'INSERT INTO %s (%s)VALUES(%s)';
        $queryTemplate = sprintf(
            $queryTemplate,
            $this->tablePrefix . $this->tableName,
            implode(',', $fields),
            implode(',', $placeHolders)
        );


        $query = $this->_connection->prepare($queryTemplate);
        $i = 1;
        foreach ($values as $key => $value) {
            $field = $fields[$key];
            switch ($this->getColumnType($field)) {
                case 'integer':
                    $query->bindValue($i++, $value, PDO::PARAM_INT);
                    break;

                case 'boolean':
                    $query->bindValue($i++, (bool)$value, PDO::PARAM_BOOL);
                    break;

                case 'array':
                    $query->bindValue($i++, $value, PDO::PARAM_ARRAY);
                    break;

                default:
                    $query->bindValue($i++, $value);
            }

        }

        return $this->executeQuery($query, $queryTemplate, [], $values);
    }

    /**
     * @param $columnName
     * @return bool
     */
    public function isVirtualField($columnName){
        if (isset($this->Model->virtualFields[$columnName])) {
            return true;
        }

        $columnNameQuoted = sprintf('%s%s%s',
            $this->startQuote,
            $columnName,
            $this->endQuote
        );
        if (isset($this->Model->virtualFields[$columnNameQuoted])) {
            return true;
        }


        $key = $this->modelName . '.';
        if (strpos($columnName, $key, 0) === 0) {
            $columnName = substr($columnName, strlen($key));
        }

        return isset($this->Model->virtualFields[$columnName]);
    }

    /**
     * @param $columnName
     * @param null $model
     * @return string
     */
    public function removeModelAlias($columnName, $model = null){
        if ($model === null) {
            $modelName = $this->modelName;
        } else {
            $modelName = $model->alias;
        }
        $key = $modelName . '.';
        if (strpos($columnName, $key, 0) === 0) {
            $columnName = substr($columnName, strlen($key));
        }
        return $columnName;
    }

    /**
     * @param $columnName
     * @return null|string
     */
    public function getColumnType($columnName){
        $columnType = null;
        foreach ($this->tableMetaData[$this->modelName] as $column) {
            if ($column['column_name'] === $columnName) {
                $columnType = $column['data_type'];
            }
        }

        if (strstr($columnType, 'array')) {
            $columnType = 'array';
        }

        return $columnType;
    }

    /**
     * @param string $columnNameSource
     * @param null|string $modelName
     * @param int $recursionLevel
     * @return bool
     */
    public function columnExists($columnNameSource, $modelName = null, $recursionLevel = 0){
        if ($modelName === null) {
            $modelName = $this->modelName;
        }
        $key = $modelName . '.';

        $columnName = $columnNameSource;
        if (strpos($columnName, $key, 0) === 0) {
            $columnName = substr($columnName, strlen($key));
        }

        foreach ($this->tableMetaData[$modelName] as $column) {
            if ($column['column_name'] === $columnName) {
                return true;
            }
        }

        if ($recursionLevel === 0) {
            foreach ($this->joinedModels as $modelName) {
                if ($this->columnExists($columnNameSource, $modelName, $recursionLevel + 1)) {
                    return true;
                }
            }
        }

        //check virtual fields
        //is_hardstate as state_type
        if (isset($this->Model->virtualFields[$columnName])) {
            return true;
        }
        //is_hardstate as Hostcheck.state_type
        if (isset($this->Model->virtualFields[$key])) {
            return true;
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
        //$query['conditions'][] = 'Hoststatus.hostname IS NULL';
        if (is_numeric($key)) {
            $key = $value;
            $value = null;
        }

        $operatorMatch = '/^(((' . implode(')|(', $this->_sqlOps);
        $operatorMatch .= ')\\x20?)|<[>=]?(?![^>]+>)\\x20?|[>=!]{1,3}(?!<)\\x20?)/is';
        $bound = (strpos($key, '?') !== false || (is_array($value) && strpos($key, ':') !== false));

        $key = trim($key);
        if (strpos($key, ' ') === false) {
            $operator = '=';
        } else {
            list($key, $operator) = explode(' ', $key, 2);

            if ($operator === 'LIKE' || $operator === 'like') {
                $operator = '~*';
            }

            if ($operator === 'RLIKE' || $operator === 'rlike') {
                $operator = '~*';
            }

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


        if ($operator === '~*') {
            //Replace first % if exists
            if (substr($value, 0, 1) === '%') {
                $value = sprintf('.*%s', substr($value, 1));
            }

            $len = strlen($value);
            if (substr($value, $len - 1, 1) === '%') {
                $value = sprintf('%s.*', substr($value, 0, $len - 1));
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
    public function getTableMetaInformation($tableName, $model = null){
        if ($model === null) {
            $modelName = $this->modelName;
        } else {
            $modelName = $model->alias;
        }


        $sql = sprintf('SHOW COLUMNS FROM %s', $tableName);
        $query = $this->_connection->prepare($sql);
        $query = $this->executeQuery($query, $sql, [], []);
        $this->_result->setFetchMode(PDO::FETCH_ASSOC);
        $this->tableMetaData[$modelName] = $this->_result->fetchAll();
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
            if ($this->findType === 'count') {
                $this->_result->setFetchMode(PDO::FETCH_ASSOC);
            } else {
                $this->_result->setFetchMode(PDO::FETCH_NUM);
            }
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
            $merge = [];
            foreach ($dbResult as $record) {
                $merge[] = array_combine($this->fieldsInQuery, $record);
            }

            $dbResult = $merge;
            unset($merge);

            if ($this->findType === 'first' && isset($dbResult[0])) {
                if (!empty($this->joins)) {
                    return $this->formatResultFindAllWithJoins($dbResult);
                }
                return $this->formatResultFindAll($dbResult);
            }

            if (!empty($this->joins)) {
                return $this->formatResultFindAllWithJoins($dbResult);
            }

            return $this->formatResultFindAll($dbResult);

        }

        return [];
    }

    /**
     * @param array $dbResult
     * @return array
     */
    public function formatResultFindAllWithJoins($dbResult = []){
        $results = [];
        $fields = [];


        foreach ($this->tableMetaData[$this->modelName] as $column) {
            $fields[$this->modelName][] = $column['column_name'];
        }

        foreach ($this->joins as $join) {
            foreach ($this->tableMetaData[$join['alias']] as $column) {
                $fields[$join['alias']][] = $column['column_name'];
            }
        }

        foreach ($this->Model->virtualFields as $virtualField => $realField) {
            $modelSplit = explode('.', $realField, 2);
            $modelName = $this->modelName;
            if (sizeof($modelSplit) == 2) {
                $modelName = $modelSplit[0];
            }

            $key = $modelName . '.';
            $realColumnName = substr($realField, strlen($key));

            $fields[$modelName][] = sprintf('%s AS %s', $realColumnName, $virtualField);
        }

        foreach ($dbResult as $record) {
            $result = [];
            foreach ($fields as $modelName => $fieldsFromModel) {
                foreach ($fieldsFromModel as $column) {
                    $keyInRecord = sprintf('%s.%s', $modelName, $column);

                    if (isset($record[$keyInRecord])) {
                        $result[$modelName][$column] = $record[$keyInRecord];
                    }else{
                        foreach($this->Model->virtualFields as $virtualField => $realField){
                            if($keyInRecord === sprintf('%s AS %s', $realField, $virtualField)){
                                $result[][$virtualField] = $record[$virtualField];
                            }

                        }
                    }
                }
            }
            if (!empty($result)) {
                $results[] = $result;
            }
        }

        return $results;
    }


    /**
     * @param array $dbResult
     * @return array
     */
    public function formatResultFindAll($dbResult = []){
        $result = [];
        $key = $this->modelName . '.';
        foreach ($dbResult as $i => $dbRecord) {
            $record = [];
            foreach ($dbRecord as $column => $value) {
                $realColumnName = substr($column, strlen($key));
                $record[$this->modelName][$realColumnName] = $value;
            }
            unset($dbRecord[$i]);
            $result[] = $record;
        }
        return $result;
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
        $_sql = '';
        foreach ($queryParts as $key => $part) {
            if ($key === 0) {
                $_sql .= $part;
                //This is the "base part" where no parameters are inside.
                continue;
            }
            $key = $key - 1;
            if (isset($params[$key])) {
                $param = $params[$key];
                if (!is_numeric($param) && !is_array($param)) {
                    $param = sprintf('\'%s\'', $param);
                }
                if (is_array($param)) {
                    $param = sprintf('[%s]', implode(',', $param));
                }
                $_sql .= $param;
            } else {
                $_sql .= '?';
            }
            $_sql .= $part;
        }

        $sql = $_sql;
        $params = [];

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

    /**
     * Translates between PHP boolean values and Database (faked) boolean values
     * CrateDB has support for boolean values, so we dont need this
     *
     * @param mixed $data Value to be translated
     * @param bool $quote Whether or not the field should be cast to a string.
     * @return bool Converted boolean value
     */
    public function boolean($data, $quote = false){
        return $data;
    }

}

