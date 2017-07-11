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

namespace Crate\DBAL\Platforms;


class Crate057Platform extends CratePlatform
{
    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    public function getListTablesSQL()
    {
        return "SELECT table_name, table_schema FROM information_schema.tables " .
        "WHERE table_schema = 'doc' OR table_schema = 'blob'";
    }

    /**
     * {@inheritDoc}
     */
    public function getListTableColumnsSQL($table, $database = null)
    {
        $t = explode('.', $table);
        if (count($t) == 1) {
            array_unshift($t, 'doc');
        }
        // todo: make safe
        return "SELECT * from information_schema.columns " .
            "WHERE table_name = '$t[1]' AND table_schema = '$t[0]'";
    }

    /**
     * {@inheritDoc}
     */
    public function getListTableConstraintsSQL($table, $database = null)
    {
        $t = explode('.', $table);
        if (count($t) == 1) {
            array_unshift($t, 'doc');
        }
        // todo: make safe
        return "SELECT constraint_name, constraint_type from information_schema.table_constraints " .
        "WHERE table_name = '$t[1]' AND table_schema = '$t[0]' AND constraint_type = 'PRIMARY_KEY'";
    }
}