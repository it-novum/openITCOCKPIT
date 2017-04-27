<?php
/**
 * Licensed to CRATE Technology GmbH("Crate") under one or more contributor
 * license agreements.  See the NOTICE file distributed with this work for
 * additional information regarding copyright ownership.  Crate licenses
 * this file to you under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.  You may
 * obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.  See the
 * License for the specific language governing permissions and limitations
 * under the License.
 *
 * However, if you have executed another commercial license agreement
 * with Crate these terms will supersede the license and you may use the
 * software solely pursuant to the terms of the relevant commercial agreement.
 */

namespace Crate\PDO;

use ArrayIterator;
use Closure;
use Crate\Stdlib\ArrayUtils;
use Crate\Stdlib\CollectionInterface;
use Crate\Stdlib\CrateConst;
use InvalidArgumentException;
use IteratorAggregate;
use PDOStatement as BasePDOStatement;

class PDOStatement extends BasePDOStatement implements IteratorAggregate
{
    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var string|null
     */
    private $errorCode;

    /**
     * @var string|null
     */
    private $errorMessage;

    /**
     * @var string
     */
    private $sql;

    /**
     * @var array
     */
    private $options = [
        'fetchMode'          => null,
        'fetchColumn'        => 0,
        'fetchClass'         => 'array',
        'fetchClassCtorArgs' => null,
    ];

    /**
     * Used for the {@see PDO::FETCH_BOUND}
     *
     * @var array
     */
    private $columnBinding = [];

    /**
     * @var CollectionInterface|null
     */
    private $collection;

    /**
     * @var PDOInterface
     */
    private $pdo;

    /**
     * @var Closure
     */
    private $request;

    private $namedToPositionalMap = array();

    /**
     * @param PDOInterface $pdo
     * @param Closure      $request
     * @param string       $sql
     * @param array        $options
     */
    public function __construct(PDOInterface $pdo, Closure $request, $sql, array $options)
    {
        $this->sql     = $this->replaceNamedParametersWithPositionals($sql);
        $this->pdo     = $pdo;
        $this->options = array_merge($this->options, $options);
        $this->request = $request;
    }

    private function replaceNamedParametersWithPositionals($sql)
    {
        if (strpos($sql, ':') === false) {
            return $sql;
        }
        $pattern = '/:((?:[\w|\d|_](?=([^\'\\\]*(\\\.|\'([^\'\\\]*\\\.)*[^\'\\\]*\'))*[^\']*$))*)/';

        $idx = 1;
        $callback = function ($matches) use (&$idx) {
            $value = $matches[1];
            if (empty($value)) {
                return $matches[0];
            }
            $this->namedToPositionalMap[$idx] = $value;
            $idx++;
            return '?';
        };

        return preg_replace_callback($pattern, $callback, $sql);
    }

    /**
     * Determines if the statement has been executed
     *
     * @internal
     *
     * @return bool
     */
    private function hasExecuted()
    {
        return ($this->collection !== null || $this->errorCode !== null);
    }

    /**
     * Internal pointer to mark the state of the current query
     *
     * @internal
     *
     * @return bool
     */
    private function isSuccessful()
    {
        if (!$this->hasExecuted()) {
            // @codeCoverageIgnoreStart
            throw new Exception\LogicException('The statement has not been executed yet');
            // @codeCoverageIgnoreEnd
        }

        return $this->collection !== null;
    }

    /**
     * Get the fetch style to be used
     *
     * @internal
     *
     * @return int
     */
    private function getFetchStyle()
    {
        return $this->options['fetchMode'] ?: $this->pdo->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE);
    }

    /**
     * Update all the bound column references
     *
     * @internal
     *
     * @param array $row
     *
     * @return void
     */
    private function updateBoundColumns(array $row)
    {
        foreach ($this->columnBinding as $column => &$metadata) {

            $index = $this->collection->getColumnIndex($column);
            if ($index === null) {
                // todo: I would like to throw an exception and tell someone they screwed up
                // but i think that would violate the PDO api
                continue;
            }

            // Update by reference
            $value = $this->typedValue($row[$index], $metadata['type']);
            $metadata['ref'] = $value;
        }

    }

    /**
     * {@inheritDoc}
     */
    public function execute($input_parameters = null)
    {
        $input_parameters_array = ArrayUtils::toArray($input_parameters);
        $zero_based = isset($input_parameters_array[0]);
        foreach ($input_parameters_array as $parameter => $value) {
            if (is_int($parameter) && $zero_based) {
                $parameter++;
            }
            $this->bindValue($parameter, $value);
        }

        // parameter binding might be unordered, so sort it before execute
        ksort($this->parameters);

        $result = $this->request->__invoke($this, $this->sql, array_values($this->parameters));

        if (is_array($result)) {
            $this->errorCode    = $result['code'];
            $this->errorMessage = $result['message'];

            return false;
        }

        $this->collection = $result;
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function fetch($fetch_style = null, $cursor_orientation = PDO::FETCH_ORI_NEXT, $cursor_offset = 0)
    {
        if (!$this->hasExecuted()) {
            $this->execute();
        }

        if (!$this->isSuccessful()) {
            return false;
        }

        if (!$this->collection->valid()) {
            return false;
        }

        // Get the current row
        $row = $this->collection->current();

        // Traverse
        $this->collection->next();

        $fetch_style = $fetch_style ?: $this->getFetchStyle();

        switch ($fetch_style)
        {
            case PDO::FETCH_NAMED:
            case PDO::FETCH_ASSOC:
                return array_combine($this->collection->getColumns(false), $row);

            case PDO::FETCH_BOTH:
                return array_merge($row, array_combine($this->collection->getColumns(false), $row));

            case PDO::FETCH_BOUND:
                $this->updateBoundColumns($row);
                return true;

            case PDO::FETCH_NUM:
                return $row;

            default:
                throw new Exception\UnsupportedException('Unsupported fetch style');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function bindParam(
        $parameter,
        & $variable,
        $data_type = PDO::PARAM_STR,
        $length = null,
        $driver_options = null
    ) {
        if (is_numeric($parameter)) {
            if ($parameter == 0) {
                throw new Exception\UnsupportedException("0-based parameter binding not supported, use 1-based");
            }
            $this->parameters[$parameter-1] = &$variable;
        } else {
            $namedParameterKey = substr($parameter, 0, 1) === ':' ? substr($parameter, 1) : $parameter;
            if (in_array($namedParameterKey, $this->namedToPositionalMap, true)) {
                foreach ($this->namedToPositionalMap as $key => $value) {
                    if ($value == $namedParameterKey) {
                        $this->parameters[$key] = &$variable;
                    }
                }
            } else {
                throw new Exception\OutOfBoundsException(
                    sprintf('The named parameter "%s" does not exist', $parameter)
                );
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function bindColumn($column, &$param, $type = null, $maxlen = null, $driverdata = null)
    {
        $type = $type ?: PDO::PARAM_STR;

        $this->columnBinding[$column] = [
            'ref'        => &$param,
            'type'       => $type,
            'maxlen'     => $maxlen,
            'driverdata' => $driverdata
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function bindValue($parameter, $value, $data_type = PDO::PARAM_STR)
    {
        $value = $this->typedValue($value, $data_type);
        $this->bindParam($parameter, $value, $data_type);
    }

    /**
     * {@inheritDoc}
     */
    public function rowCount()
    {
        if (!$this->hasExecuted()) {
            $this->execute();
        }

        if (!$this->isSuccessful()) {
            return 0;
        }

        return $this->collection->count();
    }

    /**
     * {@inheritDoc}
     */
    public function fetchColumn($column_number = 0)
    {
        if (!is_int($column_number)) {
            throw new Exception\InvalidArgumentException('column_number must be a valid integer');
        }

        if (!$this->hasExecuted()) {
            $this->execute();
        }

        if (!$this->isSuccessful()) {
            return false;
        }

        if (!$this->collection->valid()) {
            return false;
        }

        $row = $this->collection->current();
        $this->collection->next();

        if ($column_number >= count($row)) {
            throw new Exception\OutOfBoundsException(
                sprintf('The column "%d" with the zero-based does not exist', $column_number)
            );
        }

        return $row[$column_number];
    }

    /**
     * {@inheritDoc}
     */
    public function fetchAll($fetch_style = null, $fetch_argument = null, $ctor_args = [])
    {
        if (!$this->hasExecuted()) {
            $this->execute();
        }

        if (!$this->isSuccessful()) {
            return false;
        }

        $fetch_style = $fetch_style ?: $this->getFetchStyle();

        switch ($fetch_style)
        {
            case PDO::FETCH_NUM:
                return $this->collection->getRows();

            case PDO::FETCH_NAMED:
            case PDO::FETCH_ASSOC:
                $columns = $this->collection->getColumns(false);
                return $this->collection->map(function (array $row) use ($columns) {
                    return array_combine($columns, $row);
                });

            case PDO::FETCH_BOTH:
                $columns = $this->collection->getColumns(false);

                return $this->collection->map(function (array $row) use ($columns) {
                    return array_merge($row, array_combine($columns, $row));
                });

            case PDO::FETCH_FUNC:
                if (!is_callable($fetch_argument)) {
                    throw new Exception\InvalidArgumentException('Second argument must be callable');
                }

                return $this->collection->map(function (array $row) use ($fetch_argument) {
                    return call_user_func_array($fetch_argument, $row);
                });

            case PDO::FETCH_COLUMN:
                $columnIndex = $fetch_argument ?: $this->options['fetchColumn'];

                if (!is_int($columnIndex)) {
                    throw new Exception\InvalidArgumentException('Second argument must be a integer');
                }

                $columns = $this->collection->getColumns(false);
                if (!isset($columns[$columnIndex])) {
                    throw new Exception\OutOfBoundsException(
                        sprintf('Column with the index %d does not exist.', $columnIndex)
                    );
                }

                return $this->collection->map(function (array $row) use ($columnIndex) {
                    return $row[$columnIndex];
                });

            default:
                throw new Exception\UnsupportedException('Unsupported fetch style');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function fetchObject($class_name = null, $ctor_args = null)
    {
        throw new Exception\UnsupportedException;
    }

    /**
     * {@inheritDoc}
     */
    public function errorCode()
    {
        return $this->errorCode;
    }

    /**
     * {@inheritDoc}
     */
    public function errorInfo()
    {
        if ($this->errorCode === null) {
            return null;
        }

        switch ($this->errorCode)
        {
            case CrateConst::ERR_INVALID_SQL:
                $ansiErrorCode = 42000;
                break;

            default:
                $ansiErrorCode = 'Not available';
                break;
        }

        return [
            $ansiErrorCode,
            $this->errorCode,
            $this->errorMessage
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function setAttribute($attribute, $value)
    {
        throw new Exception\UnsupportedException('This driver doesn\'t support setting attributes');
    }

    /**
     * {@inheritDoc}
     */
    public function getAttribute($attribute)
    {
        throw new Exception\UnsupportedException('This driver doesn\'t support getting attributes');
    }

    /**
     * {@inheritDoc}
     */
    public function columnCount()
    {
        if (!$this->hasExecuted()) {
            $this->execute();
        }

        return count($this->collection->getColumns(false));
    }

    /**
     * {@inheritDoc}
     */
    public function getColumnMeta($column)
    {
        throw new Exception\UnsupportedException;
    }

    /**
     * {@inheritDoc}
     */
    public function setFetchMode($mode, $params = null)
    {
        $args     = func_get_args();
        $argCount = count($args);

        switch ($mode)
        {
            case PDO::FETCH_COLUMN:
                if ($argCount != 2) {
                    throw new Exception\InvalidArgumentException('fetch mode requires the colno argument');
                }

                if (!is_int($params)) {
                    throw new Exception\InvalidArgumentException('colno must be an integer');
                }

                $this->options['fetchMode']   = $mode;
                $this->options['fetchColumn'] = $params;
                break;

            case PDO::FETCH_ASSOC:
            case PDO::FETCH_NUM:
            case PDO::FETCH_BOTH:
            case PDO::FETCH_BOUND:
            case PDO::FETCH_NAMED:
                if ($params !== null) {
                    throw new Exception\InvalidArgumentException('fetch mode doesn\'t allow any extra arguments');
                }

                $this->options['fetchMode'] = $mode;
                break;

            default:
                throw new Exception\UnsupportedException('Invalid fetch mode specified');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function nextRowset()
    {
        if (!$this->hasExecuted()) {
            $this->execute();
        }

        if (!$this->isSuccessful()) {
            return false;
        }

        $this->collection->next();
        return $this->collection->valid();
    }

    /**
     * {@inheritDoc}
     */
    public function closeCursor()
    {
        $this->errorCode = 0;
        $this->collection = null;
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function debugDumpParams()
    {
        throw new Exception\UnsupportedException('Not supported, use var_dump($stmt) instead');
    }

    /**
     * {@Inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->fetchAll());
    }

    private function typedValue($value, $data_type)
    {
        switch ($data_type)
        {
            case PDO::PARAM_FLOAT:
            case PDO::PARAM_DOUBLE:
                return (float) $value;

            case PDO::PARAM_INT:
            case PDO::PARAM_LONG:
                return (int) $value;

            case PDO::PARAM_NULL:
                return null;

            case PDO::PARAM_BOOL:
                return (bool) $value;

            case PDO::PARAM_STR:
            case PDO::PARAM_IP:
                return (string) $value;

            case PDO::PARAM_OBJECT:
            case PDO::PARAM_ARRAY:
                return (array) $value;

            case PDO::PARAM_TIMESTAMP:
                if (is_numeric($value)) {
                    return (int) $value;
                }
                return (string) $value;

            default:
                throw new Exception\InvalidArgumentException(sprintf('Parameter type %s not supported', $data_type));
        }

    }
}
