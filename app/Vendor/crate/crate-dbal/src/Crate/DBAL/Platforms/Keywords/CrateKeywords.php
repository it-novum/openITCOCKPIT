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
namespace Crate\DBAL\Platforms\Keywords;

use Doctrine\DBAL\Platforms\Keywords\KeywordList;

class CrateKeywords extends KeywordList
{
    public function getName()
    {
        return 'Crate';
    }

    protected function getKeywords()
    {
        return array(
            'ALL',
            'ANALYSE',
            'ANALYZE',
            'AND',
            'ANY',
            'AS',
            'ASC',
            'AUTHORIZATION',
            'BETWEEN',
            'BINARY',
            'BOTH',
            'CASE',
            'CAST',
            'CHECK',
            'COLLATE',
            'COLUMN',
            'CONSTRAINT',
            'CREATE',
            'CURRENT_DATE',
            'CURRENT_TIME',
            'CURRENT_TIMESTAMP',
            'CURRENT_USER',
            'DEFAULT',
            'DEFERRABLE',
            'DESC',
            'DISTINCT',
            'DO',
            'ELSE',
            'END',
            'EXCEPT',
            'FALSE',
            'FOR',
            'FOREIGN',
            'FREEZE',
            'FROM',
            'FULL',
            'GRANT',
            'GROUP',
            'HAVING',
            'ILIKE',
            'IN',
            'INITIALLY',
            'INNER',
            'INTERSECT',
            'INTO',
            'IS',
            'ISNULL',
            'JOIN',
            'LEADING',
            'LEFT',
            'LIKE',
            'LIMIT',
            'LOCALTIME',
            'LOCALTIMESTAMP',
            'NATURAL',
            'NEW',
            'NOT',
            'NOTNULL',
            'NULL',
            'OFF',
            'OFFSET',
            'OLD',
            'ON',
            'ONLY',
            'OR',
            'ORDER',
            'OUTER',
            'OVERLAPS',
            'PLACING',
            'PRIMARY',
            'REFERENCES',
            'SELECT',
            'SESSION_USER',
            'SIMILAR',
            'SOME',
            'TABLE',
            'THEN',
            'TO',
            'TRAILING',
            'TRUE',
            'UNION',
            'UNIQUE',
            'USER',
            'USING',
            'VERBOSE',
            'WHEN',
            'WHERE'
        );
    }
}
