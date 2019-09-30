<?php

class ListFilterHelper extends AppHelper
{
    public $helpers = ['Html', 'Form', 'Utils'];
    private $filters = [];

    protected $_options = [
        'formActionParams' => [],
    ];

    public function setFilters(&$filters)
    {
        $this->filters = $filters;
    }

    public function renderFilterbox(&$filters = null, $options = [], $title = null, $showButton = true, $hide = false, $isPlugin = false)
    {
        if ($filters) {
            $this->setFilters($filters);
        }
        if (!empty($options)) {
            $this->_options = Set::merge($this->_options, $options);
        }
        $ret = $this->open($title, $showButton, $hide);
        $ret .= $this->renderAll();
        $ret .= $this->close(true, true, $isPlugin);

        return $ret;
    }

    public function renderAll()
    {
        $ret = '<div class="row">';
        $i = 0;

        $rowWidgets = 0;
        foreach ($this->filters as $field => $options) {
            if ($options['type'] === 'checkbox') {
                $ret .= '<div class="col-md-3">';
            } else {
                $ret .= '<div class="col-md-6">';
            }

            $ret .= $this->filterWidget($field, $options);
            $ret .= '</div>';
            if ($options['searchType'] == 'betweenDates') {
                $i++;
            }
            $i++;

            if ($options['type'] === 'checkbox') {
                if ($i % 4 === 0) {
                    $ret .= '</div><div class="row">';
                }
            } else {
                if ($i % 2 === 0) {
                    $ret .= '</div><div class="row">';
                }
            }

        }
        $ret .= '</div>';

        return $ret;
    }

    public function filterWidget($field, $options = [])
    {
        if (empty($options)) {
            $options = $this->filters['field'];
        }
        //if(!$options['showFormField']) continue;

        $ret = '';
        if (isset($options['hidden'])) {
            return '';
        }
        switch ($options['searchType']) {
            case 'betweenDates':
                $fromOptions = [
                    'label' => __($options['label']).' '.__('from'),
                    'empty' => $options['empty'],
                ];
                $toOptions = [
                    'label' => __($options['label']).' '.__('to'),
                    'empty' => $options['empty'],
                ];
                if (!empty($options['inputOptions']['from'])) {
                    $fromOptions = Set::merge($fromOptions, $options['inputOptions']['from']);
                }
                if (!empty($options['inputOptions']['to'])) {
                    $toOptions = Set::merge($toOptions, $options['inputOptions']['to']);
                }

                // $ret.= $this->Form->input('Filter.' . $field . '_from', $fromOptions);
                // $ret.= '</div><div class="span4">';
                // $ret.= $this->Form->input('Filter.' . $field . '_to', $toOptions);

                $ret .= $this->Utils->datepickerInput('Filter.'.$field.'_from', $fromOptions);
                $ret .= '</div><div class="span4">';
                $ret .= $this->Utils->datepickerInput('Filter.'.$field.'_to', $toOptions);

                break;
            case 'multipleselect':
                $inputOptions = Set::merge([
                    'label'    => __($options['label']),
                    'type'     => 'select',
                    'options'  => $options['options'],
                    'empty'    => $options['empty'],
                    'multiple' => true,
                    'class'    => 'select2',
                ], $options['inputOptions']);
                $ret .= $this->Form->input('Filter.'.$field, $inputOptions);
                break;
            case 'chosenDropdown':
                /* Extension Maximilian Pappert <maximilian.pappert@it-novum.com> 22.07.2014
                 * chosen boxes are now available for list filter boxes
                 * make sure you have added a function for the box width in your XXXXX_controller.js!
                 */
                //debug($options);
                $inputOptions = Set::merge([
                    //'type' => $options['type'],
                    'id'        => 'chosen_'.$options['label'],
                    'options'   => $options['options'],
                    'class'     => 'select2 col-xs-8 chosen',
                    'wrapInput' => 'col col-md-10',
                    'label'     => ['class' => 'col col-md-2 control-label text-left'],
                ], $options['inputOptions']);

                $ret .= $this->Form->input($options['label'], $inputOptions);
                break;
            default:
                /* Erweiterung Daniel Ziegler <daniel.ziegler@it-novum.com> 07.03.2014
                 * Dem Typ Checkbox kann nu ein Array übergeben werden, so werden automatisch Checkboxes erstellt.
                 */
                //debug($options);
                /*if($options['type'] == 'checkbox' && isset($options['options']) && !empty($options['options']) && is_array($options['options'])){
                    $checkedAsArray = array();
                    if(isset($this->_View->viewVars[$field])){
                        $checkedAsArray = $this->_View->viewVars[$field];
                    }

                    foreach($options['options'] as $checkbox_name){
                        //debug($options['options']);

                        $checked = '';
                        $value = 0;
                        if(in_array($checkbox_name, $checkedAsArray)){
                            $checked = 'checked';
                        }
                        $inputOptions = Set::merge(array(
                            //'label' => __($options['label'].'_'.$checkbox_name),
                            'type' => $options['type'],
                            //'empty' => $options['empty'],
                            'id' => 'auto_checkbox_'.$checkbox_name,
                            'checked' => $checked,
                            //'checked' => 'checked',
                            'value' => $value,
                        ), $options['inputOptions']);
                        //$ret.= $this->Form->input('Filter.' . $field.'.'.$checkbox_name, $inputOptions);
                        //debug($inputOptions);
                        $ret.= $this->Form->fancyCheckbox($field.'.'.$checkbox_name, $inputOptions);
                    }
                    break;
                }*/

                if ($options['type'] == 'checkbox' && isset($options['options']) && !empty($options['options']) && is_array($options['options'])) {
                    $checkedAsArray = [];
                    if (isset($this->_View->viewVars[$field])) {
                        $checkedAsArray = $this->_View->viewVars[$field];
                    }


                    foreach ($options['options'] as $key => $checkbox) {
                        $checked = '';
                        //debug($this->request->params['named'][$checkbox['data']][$key]);
                        if (isset($checkbox['data']) && (isset($this->request->params['named'][$checkbox['data']][$key]))) {
                            if ($this->request->params['named'][$checkbox['data']][$key] == 1) {
                                $checked = 'checked';
                            }
                        } else {
                            if (in_array($checkbox['name'], $checkedAsArray)) {
                                $checked = 'checked';
                            }
                        }


                        $inputOptions = Set::merge([
                            //'label' => __($options['label'].'_'.$checkbox_name),
                            'type'    => $options['type'],
                            //'empty' => $options['empty'],
                            'id'      => 'auto_checkbox_'.$checkbox['name'],
                            'checked' => $checked,
                            //'checked' => 'checked',
                            'value'   => $checkbox['value'],
                            'label'   => __($checkbox['label']),
                        ], $options['inputOptions']);

                        if (!isset($checkbox['data'])) {
                            $checkbox['data'] = 'Filter.'.$field.'.'.$checkbox['name'];
                        } else {
                            $checkbox['data'] .= '.'.$key;
                        }
                        //debug($inputOptions);
                        $ret .= $this->Form->input($checkbox['data'], $inputOptions);
                    }
                    break;
                }

                //set a variable for the form to not add required class listfilter = true

                $inputOptions = Set::merge([
                    'label'      => __($options['label']),
                    'type'       => $options['type'],
                    'options'    => $options['options'],
                    'listfilter' => true,
                    'empty'      => $options['empty'],
                ], $options['inputOptions']);
                $ret .= $this->Form->input('Filter.'.$field, $inputOptions);
                break;
        }

        return $ret;
    }

    public function open($title = null, $showButton = true, $hide = false)
    {
        $filterActive = (isset($this->_View->viewVars['filterActive'])
            && $this->_View->viewVars['filterActive'] === true);
        $classes = 'list-filter well';

        $display = '';
        if ($hide) {
            $display = 'style="display:none;"';
        }

        if ($title == null) {
            $title = __('Filter List');
        }

        if ($filterActive) {
            $classes .= ' opened';
        } else {
            $classes .= ' closed';
        }

        $ret = "<div class='{$classes}' {$display}>";

        $ret .= "<div class='pull-left'><h3>{$title}</h3></div>";
        $ret .= "<div class='pull-right'>";
        if ($showButton) {
            $ret .= $this->Html->link($filterActive ? __('close') : __('open'), 'javascript:', [
                'class' => 'btn btn-xs btn-primary toggle'
            ]);
        }
        $ret .= "</div>";
        $ret .= "<hr style='clear:both'><div class='content'>";

        if (isset($this->_options['formActionParams']['merge']) && $this->_options['formActionParams']['merge'] == false) {
            $options = $this->_options['formActionParams'];
        } else {
            $here = $this->here;
            //CakePHP remove index action from url by default
            //This cause some strange behavior...
            if ($this->request->params['action'] == 'index' || $this->request->params['action'] == '') {
                //Avoiding duplicate appearance of /index
                if (!strpos($here, "/index")) {
                    $here = $here.'/index';
                }
            }

            //This is for avoiding cut off IP-Addresses
            if (isset($this->_options['avoid_cut']) && $this->_options['avoid_cut']) {
                //Avoiding duplicate appearance of /q:1
                if (!strpos($here, "/q:1")) {
                    $here = $here.'/q:1';
                }
            }

            $options = Set::merge(['url' => $here], $this->_options['formActionParams']);
        }
        // var_dump($options);exit;

        $ret .= $this->Form->create('Filter', $options);

        return $ret;
    }

    public function close($includeButton = true, $includeResetLink = true, $isPlugin = false)
    {
        $ret = '<div class="well formactions" style="margin-top: 15px;"><div class="pull-right">';
        $ret .= '<span></span>';
        if ($includeButton) {
            $ret .= $this->button();
        }
        if ($includeResetLink) {
            $ret .= ' '.$this->resetLink(null, [], $isPlugin);
        }
        $ret .= '</div></div>';
        $ret .= $this->Form->end();
        $ret .= '</div></div>';

        return $ret;
    }

    public function button($title = null)
    {
        if (!$title) {
            $title = __('Filter');
        }

        return $this->Form->submit(__($title), ['div' => false, 'class' => 'btn btn-mini btn-primary']);
    }

    public function resetLink($title = null, $options = [], $isPlugin = false)
    {
        $_options = ['class' => 'btn-default btn-mini', 'icon' => '', 'url' => ''];
        $options = Hash::merge($_options, $options);
        if (!$title) {
            $title = __('Reset filter');
        }
        $params = $this->params['named'];
        //Fix by Daniel Ziegler <daniel.ziegler@it-novum.com> to avoid lost URL parameters - 26.08.2014
        if (isset($this->params['pass']) && !empty($this->params['pass'])) {
            $params = Hash::merge($params, $this->params['pass']);
        }
        if (!empty($params)) {
            foreach ($params as $field => $value) {
                if (substr($field, 0, 7) == 'Filter.') {
                    unset($params[$field]);
                }
            }
        }

        $params['controller'] = Inflector::underscore($this->params->controller);
        $redirectUrl = '/'.$params['controller'];
        if ($isPlugin) {
            $params['plugin'] = Inflector::underscore($this->params->plugin);
            $redirectUrl = '/'.$params['plugin'].'/'.$params['controller'];
        }

        $params['action'] = $this->params->action;
        if($options['url']){
            return $this->Html->link($title, $options['url'], ['class' => 'btn '.$options['class'], 'icon' => $options['icon']]);
        }
        if(!empty($this->params['pass']) && $params['action'] == 'index'){
            return $this->Html->link($title, $redirectUrl.'/index/'.implode('/', $this->params['pass']), ['class' => 'btn '.$options['class'], 'icon' => $options['icon']]);
        }elseif ($params['action'] == 'index') {
            return $this->Html->link($title, $redirectUrl.'/index', ['class' => 'btn '.$options['class'], 'icon' => $options['icon']]);
        }

        return $this->Html->link($title, Router::url($params), ['class' => 'btn '.$options['class'], 'icon' => $options['icon']]);
    }

}
