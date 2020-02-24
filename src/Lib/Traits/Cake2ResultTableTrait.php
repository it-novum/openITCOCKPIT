<?php
// Copyright (C) <2018>  <it-novum GmbH>
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

namespace App\Lib\Traits;


use Cake\I18n\FrozenTime;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\Utility\Inflector;

/**
 * Trait Cake2ResultTrait
 * @package App\Lib\Traits
 */
trait Cake2ResultTableTrait {


    /**
     * @param array $records
     * @param bool $contain
     * @return array
     */
    public function formatResultAsCake2($records = [], $contain = true) {
        if (empty($records) || is_null($records)) {
            return [];
        }


        $modelName = ucfirst(Inflector::singularize($this->getAlias()));
        /** @var \Cake\ORM\AssociationCollection $AssociationCollection */
        $AssociationCollection = $this->associations();
        $associations = array_flip($AssociationCollection->keys());
        $cake2Result = [];


        foreach ($records as $row) {
            $record = [];
            foreach ($row as $key => $value) {
                if (isset($associations[strtolower($key)]) && is_array($value)) {
                    //associated model
                    $assoc = $AssociationCollection->get($key);
                    $assocRecords = [];
                    if ($assoc instanceof HasMany) {
                        foreach ($value as $assocRow) {
                            $assocRecord = [];
                            foreach ($assocRow as $assocKey => $assocValue) {
                                $assocRecord[$assocKey] = $this->asString($assocValue);
                            }
                            $assocRecords[] = $assocRecord;
                        }
                    } else {
                        //hasOne
                        foreach ($value as $assocKey => $assocValue) {
                            $assocRecords[$assocKey] = $this->asString($assocValue);
                        }
                    }
                    $assocModelName = ucfirst(Inflector::singularize($key));
                    $record[$assocModelName] = $assocRecords;
                } else {
                    //Model itself
                    $record[$modelName][$key] = $this->asString($value);
                }

                //Add missing associations
                if ($contain === true) {
                    foreach ($associations as $association => $index) {
                        $assocName = ucfirst(Inflector::singularize($association));
                        if (!isset($record[$assocName])) {
                            $record[$assocName] = [];
                        }
                    }
                }
            }
            $cake2Result[] = $record;
        }
        return $cake2Result;
    }

    /**
     * @param array $records
     * @param bool $contain
     * @return array
     */
    public function formatFirstResultAsCake2($row = [], $contain = true) {
        if (empty($row) || is_null($row)) {
            return [];
        }


        $modelName = ucfirst(Inflector::singularize($this->getAlias()));
        /** @var \Cake\ORM\AssociationCollection $AssociationCollection */
        $AssociationCollection = $this->associations();
        $associations = array_flip($AssociationCollection->keys());

        $record = [];
        foreach ($row as $key => $value) {
            if (isset($associations[strtolower($key)]) && is_array($value)) {
                //associated model
                $assoc = $AssociationCollection->get($key);
                $assocRecords = [];
                if ($assoc instanceof HasMany || $assoc instanceof BelongsToMany) {
                    foreach ($value as $assocRow) {
                        $assocRecord = [];
                        foreach ($assocRow as $assocKey => $assocValue) {
                            $assocRecord[$assocKey] = $this->asString($assocValue);
                        }
                        $assocRecords[] = $assocRecord;
                    }
                } else {
                    //hasOne
                    foreach ($value as $assocKey => $assocValue) {
                        $assocRecords[$assocKey] = $this->asString($assocValue);
                    }
                }
                $assocModelName = ucfirst(Inflector::singularize($key));
                $record[$assocModelName] = $assocRecords;
            } else {
                //Model itself
                $record[$modelName][$key] = $this->asString($value);
            }

            //Add missing associations
            if ($contain === true) {
                foreach ($associations as $association => $index) {
                    $assocName = ucfirst(Inflector::singularize($association));
                    if (!isset($record[$assocName])) {
                        $record[$assocName] = [];
                    }
                }
            }
        }

        return $record;
    }

    /**
     * @param array $records
     * @param string $key
     * @param string $value
     * @return array
     */
    public function formatListAsCake2($records = [], $key = 'id', $value = 'name') {
        if (empty($records) || is_null($records)) {
            return [];
        }

        $result = [];
        foreach ($records as $row) {
            $result[$row[$key]] = $row[$value];
        }

        return $result;

    }

    private function asString($value) {
        if ($value instanceof FrozenTime) {
            /** @var FrozenTime $value */
            return date('Y-m-d H:i:s', $value->getTimestamp());
        }

        if (is_array($value)) {
            /** @var array $value */
            return $value;
        }

        /** @var string|mixed $value */
        return $value;
    }
}