<?php

class ListFilterComponent extends Component
{
    // Einstellungen
    public $settings = [];

    // Referenz auf den aufrufenden Controller
    private $Controller;

    public function initialize(Controller $controller, $settings = [])
    {
        $this->settings = Set::merge($this->settings, $settings);
        $this->Controller = $controller;
    }

    public $defaultListFilter = [
        // Typ des Eingabefelds
        'type'           => 'text',
        // Wenn es ein SELECT ist, dann hier die möglichen Werte als K=>V einfügen
        'options'        => [],
        // Formularfeld anzeigen. Bei Specials dieses einfach auf false setzen
        'showFormField'  => true,
        // In Selects auch einen leeren Eintrag anzeigen
        'empty'          => true,
        // Wenn der Wert mit einem speziellen DB-Feld verglichen werden soll (z.B. 'DATE(Log.created)')
        'conditionField' => '',
        // Zusätzliche Optionen für $this->Form->input(). Bei betweenDates können getrennte Optionen für from/to übergeben werden
        'inputOptions'   => [],
        'searchType'     => 'wildcard',
    ];

    public function startup(Controller $controller)
    {
        //debug($this->Controller->data);
        if (isset($this->Controller->listFilters[$this->Controller->action])) {

            $this->listFilters = $this->Controller->listFilters[$this->Controller->action];
            // POST-Daten in URL umwandeln und weiterleiten
            if (!empty($this->Controller->data['Filter'])) {
                $urlParams = [];
                foreach ($this->Controller->data['Filter'] as $model => $fields) {
                    foreach ($fields as $field => $value) {
                        if (is_array($value)) {
                            if (isset($value['year']) && isset($value['month'])) {
                                $value = "{$value['year']}-{$value['month']}-{$value['day']}";
                            } else {
                                /* Fixx für Arrays im $_GET
                                 * Daniel Ziegler <daniel.ziegler@it-novum.com>
                                 */
                                //debug($value);
                                foreach ($value as $k => $v) {
                                    if ($v == 1) {
                                        //$urlParams["Filter.{$model}.{$field}[key_{$k}]"] = trim($k);
                                        $urlParams["Filter.{$model}.{$field}[{$k}]"] = $v;
                                    }
                                }

                                continue;
                            }
                            if ($value == '--') continue;
                        }
                        $value = trim($value);

                        /*if($value !== 0 && $value !== '0' && empty($value)) {
                            continue;
                        }*/

                        if (strlen($value) > 0) {
                            $urlParams["Filter.{$model}.{$field}"] = $value;
                        }

                    }
                }

                //Fix by Daniel Ziegler <daniel.ziegler@it-novum.com> to avoid lost URL parameters - 26.08.2014
                if (isset($this->Controller->request->params['pass']) && !empty($this->Controller->request->params['pass'])) {
                    $urlParams = Hash::merge($urlParams, $this->Controller->request->params['pass']);
                }

                if (isset($this->Controller->request->params['named']) && !empty($this->Controller->request->params['named'])) {
                    $_namedParameters = [];
                    foreach ($this->Controller->request->params['named'] as $_key => $_param) {
                        if (substr($_key, 0, 7) == 'Filter.') {
                            //Ignoring ListFilter parameters
                            continue;
                        }

                        //remove the page key, so php will not throw an not found exeption!
                        if ($_key == 'page') {
                            continue;
                        }

                        $_namedParameters[$_key] = $_param;
                    }
                    $urlParams = Hash::merge($urlParams, $_namedParameters);
                }

                //debug($this->Controller->request->params);
                //die();

                //debug($this->Controller->request->params);
                //debug($urlParams);die();

                $this->Controller->redirect(Router::url($urlParams));
            }
            // Filtereinstellungen aus URL aufbereiten
            $filterActive = false;

            if (!empty($this->Controller->passedArgs)) {
                $filters = [];
                foreach ($this->Controller->passedArgs as $arg => $value) {
                    if (substr($arg, 0, 7) == 'Filter.') {
                        unset($betweenDate);

                        //list() is dangerous in php7: http://php.net/manual/de/function.list.php
                        $list = explode('.', $arg);
                        if(isset($list[0])){
                            $filter = $list[0];
                        }
                        if(isset($list[1])){
                            $model = $list[1];
                        }
                        if(isset($list[2])){
                            $field = $list[2];
                        }
                        if (substr($arg, -1) == ']') {
                            if (preg_match('/^(.*)\[\d+\]$/', $arg, $matches)) {
                                $fieldArg = $matches[1];
                                $value = [];
                                foreach ($this->Controller->passedArgs as $a2 => $v2) {
                                    if (substr($a2, 0, strlen($fieldArg)) == $fieldArg) {
                                        $value[] = $v2;
                                    }
                                }
                                list($filter, $model, $field) = explode('.', $fieldArg);
                            }
                        }
                        // if betweenDate
                        if (preg_match("/([a-z_\-\.]+)_(from|to)$/i", $field, $matches)) {
                            $betweenDate = $matches[2];
                            $field = $matches[1];
                        }
                        if (isset($this->listFilters['fields']["{$model}.{$field}"])) {
                            //Skip hidden fields, they are only for URL search
                            // Daniel Ziegler <daniel.ziegler@it-novum.com>
                            if (isset($this->listFilters['fields']["{$model}.{$field}"]['hidden'])) {
                                $this->Controller->request->data['Filter'][$model][$field] = $value;
                                continue;
                            }
                            $options = $this->listFilters['fields']["{$model}.{$field}"];

                            if (is_string($value)) {
                                $value = trim($value);
                            }

                            // Der Wert, der ins Formularfeld kommt
                            $viewValue = $value;
                            $conditionField = "{$model}.{$field}";

                            // Wenn der Wert leer ist, rausnehmen
                            if (empty($value) && $value != 0) {
                                continue;
                            }
                            // Wenn der Wert nicht in den erlaubten Werten definiert ist
                            if (!is_array($value)) {
                                if ($options['searchType'] != 'multipleselect' && !empty($options['options']) && !isset($options['options'][$value])) {
                                    continue;
                                }
                            }
                            // Wenn wildcards erlaubt sind, dann LIKE-Condition
                            if ($options['searchType'] == 'wildcard') {
                                $value = "%{$value}%";
                                $value = str_replace('*', '%', $value);
                                $conditionField = $conditionField.' LIKE';
                            } // Zwischen 2 Daten suchen
                            else if ($options['searchType'] == 'betweenDates') {
                                $conditionField = 'DATE('.$conditionField.')';
                                if ($betweenDate == 'from') {
                                    $operator = '>=';
                                    #$this->Controller->data['Filter'][$model][$field . '_to'] = '';
                                } else if ($betweenDate == 'to') {
                                    $operator = '<=';
                                    #$this->Controller->data['Filter'][$model][$field . '_from'] = '';
                                }
                                if (!empty($options['conditionField'])) {
                                    $conditionField = $options['conditionField'];
                                }
                                $conditionField .= ' '.$operator;

                                // Workaround für FormHelper-Notices (Ticket #218)
                                $otherKey = $betweenDate == 'from' ? '_to' : '_from';
                                if (empty($this->Controller->data['Filter'][$model][$field.$otherKey])) {
                                    // $this->Controller->data['Filter'][$model][$field . $otherKey] = array('year' => null, 'month' => null, 'day' => null);
                                }

                                list($year, $month, $day) = explode('-', $value);
                                $viewValue = compact('year', 'month', 'day');
                                $field .= '_'.$betweenDate;
                            }
                            $filters[$conditionField] = $value;
                            $this->Controller->request->data['Filter'][$model][$field] = $viewValue;
                        }
                    }
                }
                $filterActive = !empty($filters);
                $conditions = isset($this->Controller->paginate['conditions']) ? $this->Controller->paginate['conditions'] : [];

                $this->Controller->paginate = Hash::merge($this->Controller->paginate, [
                    'conditions' => Set::merge($conditions, $filters),
                ]);

            }

            foreach ($this->listFilters['fields'] as $field => $options) {
                // Workaround, da Set::merge numerisch indizierte Arrays, wie die von find(list), neu indiziert
                if (!empty($this->listFilters['fields'][$field]['options'])) {
                    $tmpOptions = $this->listFilters['fields'][$field]['options'];
                }
                $this->listFilters['fields'][$field] = Set::merge($this->defaultListFilter, $options);
                if (isset($tmpOptions)) {
                    $this->listFilters['fields'][$field]['options'] = $tmpOptions;
                }
                unset($tmpOptions);
            }
            $this->Controller->set('filters', $this->listFilters['fields']);
            $this->Controller->set('filterActive', $filterActive);
        }
    }

    /*
     * By Daniel Ziegler <daniel.ziegler@it-novum.com>
     */
    public function buildConditions($request = [], $_conditions = [])
    {
        $filterArray = $this->Controller->request->data('Filter');
        if (!empty($filterArray)) {
            if (isset($this->Controller->listFilters[$this->Controller->action]['fields'])) {
                $listFilterSettings = $this->Controller->listFilters[$this->Controller->action]['fields'];
            } else {
                // No listfilter fields defind for this action, i dont know what i should merge as conditions
                return $_conditions;
            }

            $conditions = [];
            foreach ($filterArray as $model => $_field) {
                foreach ($_field as $field => $value) {
                    $searchType = 'wildcard';
                    if (isset($listFilterSettings[$model.'.'.$field]['searchType'])) {
                        $searchType = $listFilterSettings[$model.'.'.$field]['searchType'];
                    }
                    switch ($searchType) {
                        case 'wildcard':
                            if (is_array($value)) {
                                foreach ($value as $_value) {
                                    $conditions['OR'][] = $model.'.'.$field.' LIKE "%'.$_value.'%"';
                                }
                                debug($conditions);
                            } else {
                                $conditions[$model.'.'.$field.' LIKE'] = '%'.$value.'%';
                            }

                            break;

                        case 'wildcardMulti':
                            if(!is_array($value)){
                                $value = [$value];
                            }
                            $conditions[$model.'.'.$field.' rlike'] = implode('|', $value);
                            break;
                        case 'greater':
                            $conditions[$model.'.'.$field.' >='] = $value[0];
                            break;

                        case 'lesser':
                            $conditions[$model.'.'.$field.' <='] = $value[0];
                            break;

                        default:
                            if (is_array($value)) {
                                $_value = [];
                                foreach ($value as $k => $v) {
                                    //Flip key with value, because the value is only 1 on checkboxes
                                    $_value[] = $k;
                                }
                                $value = $_value;
                            }
                            $conditions[$model.'.'.$field] = $value;
                            break;
                    }
                }

            }

            return Hash::merge($_conditions, $conditions);
        }

        return $_conditions;
    }
}

?>