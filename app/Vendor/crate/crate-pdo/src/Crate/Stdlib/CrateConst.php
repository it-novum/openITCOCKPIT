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

final class CrateConst
{
    /**
     * Ansi error code: 42000
     *
     * @var int
     */
    const ERR_INVALID_SQL = 4000;
    const ERR_INVALID_ANALYZER_DEF = 4001;
    const ERR_INVALID_TABLE_NAME = 4002;
    const ERR_FIELD_TYPE_VALIDATION = 4003;
    const ERR_FEATURE_NOT_AVAILABLE = 4004;
    const ERR_ALTER_TABLE_USING_ALIAS = 4005;
    const ERR_AMBIGUOUS_COLUMN = 4006;
    const ERR_UNKNOWN_TABLE = 4041;
    const ERR_UNKNOWN_ANALYZER = 4042;
    const ERR_UNKNOWN_COLUMN = 4043;
    const ERR_UNKNOWN_TYPE = 4044;
    const ERR_UNKNOWN_SCHEMA = 4045;
    const ERR_UNKNOWN_PARTITION = 4046;
    const ERR_PRIMARY_EXISTS = 4091;
    const ERR_VERSION_CONFLICT = 4092;
    const ERR_TABLE_EXISTS = 4093;
    const ERR_TABLE_SCHEMA_MISS_MATCH = 4095;
    const ERR_SERVER_ERROR = 5000;
    const ERR_TASK_EXECUTION = 5001;
    const ERR_SHARD_UNAVAILABLE = 5002;
}
