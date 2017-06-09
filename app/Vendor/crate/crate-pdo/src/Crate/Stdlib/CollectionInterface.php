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

interface CollectionInterface extends Countable, Iterator
{
    /**
     * Get the columns as either an array where the keys point to the index or vice versa
     *
     * @param bool $columnsAsKeys
     *
     * @return string[]
     */
    public function getColumns($columnsAsKeys = true);

    /**
     * Get the column index
     *
     * @param string $column
     *
     * @return string|null
     */
    public function getColumnIndex($column);

    /**
     * Apply a callback to each item in the collection
     *
     * @param callable $callable
     *
     * @return array
     */
    public function map(callable $callable);

    /**
     * Get all the rows
     *
     * @return array
     */
    public function getRows();
}
