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

namespace Crate\Stdlib;

use Countable;
use Iterator;

final class Collection implements CollectionInterface
{
    /**
     * @var array
     */
    private $rows;

    /**
     * @var string[]
     */
    private $columnsAsKeys;

    /**
     * @var string[]
     */
    private $columnsAsValues;

    /**
     * @var int
     */
    private $duration;

    /**
     * @var int
     */
    private $rowCount;

    /**
     * @param array    $rows
     * @param string[] $columns
     * @param int      $duration
     * @param int      $rowCount
     */
    public function __construct(array $rows, array $columns, $duration, $rowCount)
    {
        $this->rows            = $rows;
        $this->columnsAsKeys   = array_flip($columns);
        $this->columnsAsValues = $columns;
        $this->duration        = $duration;
        $this->rowCount        = $rowCount;
    }

    /**
     * {@Inheritdoc}
     */
    public function map(callable $callback)
    {
        return array_map($callback, $this->rows);
    }

    /**
     * {@Inheritdoc}
     */
    public function getColumnIndex($column)
    {
        if (isset($this->columnsAsKeys[$column])) {
            return $this->columnsAsKeys[$column];
        }

        return null;
    }

    /**
     * {@Inheritdoc}
     */
    public function getColumns($columnsAsKeys = true)
    {
        return $columnsAsKeys ? $this->columnsAsKeys : $this->columnsAsValues;
    }

    /**
     * {@Inheritdoc}
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * {@Inheritdoc}
     */
    public function current()
    {
        return current($this->rows);
    }

    /**
     * {@Inheritdoc}
     */
    public function next()
    {
        next($this->rows);
    }

    /**
     * {@Inheritdoc}
     */
    public function key()
    {
        return key($this->rows);
    }

    /**
     * {@Inheritdoc}
     */
    public function valid()
    {
        return $this->key() !== null;
    }

    /**
     * {@Inheritdoc}
     */
    public function rewind()
    {
        reset($this->rows);
    }

    /**
     * {@Inheritdoc}
     */
    public function count()
    {
        return $this->rowCount;
    }
}
