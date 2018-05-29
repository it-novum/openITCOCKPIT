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

namespace itnovum\openITCOCKPIT\Core\ValueObjects;


class CustomVariable {

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $objecttype_id;

    /**
     * CustomVariableKeyValue constructor.
     *
     * @param string $key
     * @param string $value
     */
    public function __construct($name, $value, $id = 0, $objecttype_id = 0) {
        $this->name = $name;
        $this->value = $value;
        $this->id = $id;
        $this->objecttype_id = $objecttype_id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getObjecttypeId() {
        return $this->objecttype_id;
    }

    /**
     * reset Id -> to avoid overwriting from custom variables (host templates)
     */
    public function resetId() {
        if (isset($this->id)) {
            $this->id = 0;
        }
    }


    /**
     * @return array
     */
    public function asArray() {
        $customVariable = [
            'name' => $this->name,
            'value' => $this->value,
        ];

        if ($this->id > 0) {
            $customVariable['id'] = $this->id;
        }
        if ($this->objecttype_id > 0) {
            $customVariable['objecttype_id'] = $this->objecttype_id;
        }

        return $customVariable;
    }
}
