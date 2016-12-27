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

namespace itnovum\openITCOCKPIT\Core;


class CustomMacroReplacer
{

    /**
     * @var array
     */
    private $customvariables;

    /**
     * @var array
     */
    private $servicestatus;

    /**
     * @var int
     */
    private $objecttype_id;

    /**
     * @var array
     */
    private $mapping = [
        'search'  => [],
        'replace' => [],
    ];


    /**
     * CustomMacroReplacer constructor.
     *
     * @param $customvariables
     * @param $objecttype_id
     */
    public function __construct($customvariables, $objecttype_id)
    {
        $this->customvariables = $customvariables;
        $this->objecttype_id = $objecttype_id;
        $this->buildMapping();
    }

    /**
     * @return string
     */
    public function getMacroPrefix()
    {
        switch ($this->objecttype_id) {
            case OBJECT_HOSTTEMPLATE:
            case OBJECT_HOST:
                return '$_HOST';
                break;

            case OBJECT_SERVICETEMPLATE:
            case OBJECT_SERVICE:
                return '$_SERVICE';
                break;
        }
    }

    /**
     * Try to replace all known macros
     *
     * @param $msg
     *
     * @return string mixed
     */
    public function replaceAllMacros($msg)
    {
        if (empty($this->mapping['search']) || empty($this->mapping['replace'])) {
            return $msg;
        }

        return str_replace($this->mapping['search'], $this->mapping['replace'], $msg);
    }


    /**
     * @return array
     */
    public function buildMapping()
    {
        $mapping = [
            'search'  => [],
            'replace' => [],
        ];

        foreach ($this->customvariables as $customvariable) {
            $name = sprintf('%s%s$', $this->getMacroPrefix(), $customvariable['name']);
            $mapping['search'][] = $name;
            $mapping['replace'][] = $customvariable['value'];
        }

        $this->mapping = $mapping;

        return $this->mapping;
    }
}
