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

namespace itnovum\openITCOCKPIT\Filter;

use CakeRequest;
use NotImplementedException;

abstract class Filter {

    private $Request;

    public function __construct(CakeRequest $Request) {
        $this->Request = $Request;
    }

    /**
     * @param $filters
     * @return array
     * @throws NotImplementedException
     */
    public function getConditionsByFilters($filters) {
        $conditions = [];
        foreach ($filters as $operator => $fields) {
            foreach ($fields as $field) {
                if ($this->queryHasField($field)) {
                    switch ($operator) {
                        case 'bool':
                            $value = $this->getQueryFieldValue($field);
                            if($value === '1' || $value === 1 || $value === 'true'){
                                $conditions[$field] = 1;
                            }

                            if($value === '0' || $value === 0 || $value === 'false'){
                                $conditions[$field] = 0;
                            }
                            break;
                        case 'like':
                            $value = $this->getQueryFieldValue($field);
                            if ($value) {
                                $value = str_replace('\\', '\\\\', $value);

                                $conditions[sprintf('%s LIKE', $field)] = sprintf(
                                    '%%%s%%',
                                    $value
                                );
                            }
                            break;

                        case 'rlike':
                            $value = $this->getQueryFieldValue($field, true);
                            if ($value) {
                                if (!is_array($value)) {
                                    $value = [$value];
                                }
                                $conditions[sprintf('%s rlike', $field)] = sprintf('.*(%s).*', implode('|', $value));
                            }
                            break;
                        case 'equals':
                            $values = $this->getQueryFieldValue($field);
                            if (is_array($values) && !empty($values)) {
                                $conditions[sprintf('%s IN', $field)] = $values;
                            } else {
                                if ($values || $values === '0') {
                                    $conditions[$field] = $values;
                                }
                            }
                            break;

                        case 'greater':
                            $values = $this->getQueryFieldValue($field);
                            if ($values || $values === '0') {
                                $conditions[sprintf('%s >', $field)] = $values;
                            }
                            break;

                        case 'greater_equals':
                            $values = $this->getQueryFieldValue($field);
                            if ($values || $values === '0') {
                                $conditions[sprintf('%s >=', $field)] = $values;
                            }
                            break;

                        case 'lesser':
                            $values = $this->getQueryFieldValue($field);
                            if ($values || $values === '0') {
                                $conditions[sprintf('%s <', $field)] = $values;
                            }
                            break;

                        case 'lesser_equals':
                            $values = $this->getQueryFieldValue($field);
                            if ($values || $values === '0') {
                                $conditions[sprintf('%s <=', $field)] = $values;
                            }
                            break;

                        case 'state':
                            $values = $this->mapStateNameToStateId($field);
                            if (is_array($values) && !empty($values)) {
                                $conditions[sprintf('%s IN', $field)] = $values;
                            } else {
                                if ($values) {
                                    $conditions[$field] = $values;
                                }
                            }

                            break;

                        case 'downtime':
                            $value = $this->getQueryFieldValue($field);
                            if($value === '1' || $value === 1 || $value === 'true'){
                                $conditions[sprintf('%s >=', $field)] = 1;
                            }
                            if($value === '0' || $value === 0 || $value === 'false'){
                                $conditions[sprintf('%s =', $field)] = 0;
                            }
                            break;
                        default:
                            throw new NotImplementedException('This filter type is not implemented yet');
                    }
                }
            }
        }
        return $conditions;
    }

    /**
     * @param $field
     * @return bool
     */
    public function queryHasField($field) {
        return isset($this->Request->query['filter'][$field]);
    }

    /**
     * @param $field
     * @return null|mixed
     */
    public function getQueryFieldValue($field, $strict = false) {
        if ($this->queryHasField($field)) {
            if($strict === false) {
                return $this->Request->query['filter'][$field];
            }

            if($strict === true){
                $value = $this->Request->query['filter'][$field];
                if(is_array($value)){
                    $value = array_filter($value, function($val){
                        if($val === null || $val === ''){
                            return false;
                        }
                        return true;
                    });
                }
                if(!empty($value)){
                    return $value;
                }
            }

        }
        return null;
    }

    public function mapStateNameToStateId($field) {
        $values = $this->getQueryFieldValue($field);
        if (!is_array($values)) {
            $values = [$values];
        }
        $return = [];
        foreach ($values as $value) {
            switch ($value) {
                case 'up':
                case 'ok':
                    $return[] = 0;
                    break;
                case 'down':
                case 'warning':
                    $return[] = 1;
                    break;
                case 'unreachable':
                case 'critical':
                    $return[] = 2;
                    break;
                case 'unknown':
                    $return[] = 3;
                    break;
            }
        }

        return $return;
    }

    /**
     * @param string $default
     * @return string|array
     */
    public function getSort($default = '') {
        if (isset($this->Request->query['sort']) && $this->Request->query['sort'] !== '') {
            return $this->Request->query['sort'];
        }
        return $default;
    }

    /**
     * @param string $default
     * @return string
     */
    public function getDirection($default = '') {
        if (isset($this->Request->query['direction'])) {
            if($this->Request->query['direction'] === 'desc'){
                return 'desc';
            }
            return 'asc';
        }
        return $default;
    }

    /**
     * @param array $sortAsArray
     * return array
     */
    public function validateArrayDirection($sortAsArray = []){
        $validatedSort = [];
        foreach($sortAsArray as $sortField => $sortDirection){
            $validatedSort[$sortField] = ($sortDirection === 'desc')?'desc':'asc';
        }
        return $validatedSort;
    }

    /**
     * @param string $defaultSort
     * @param string $defaultDirection
     * @return array
     */
    public function getOrderForPaginator($defaultSort = '', $defaultDirection = '') {
        if(is_array($this->getSort($defaultSort))){
            return $this->validateArrayDirection($this->getSort($defaultSort));
        }

        return [
            $this->getSort($defaultSort) => $this->getDirection($defaultDirection)
        ];
    }

    /**
     * @param int $default
     * @return int
     */
    public function getPage($default = 1) {
        if (isset($this->Request->query['page'])) {
            return (int)$this->Request->query['page'];
        }
        return $default;
    }

}
