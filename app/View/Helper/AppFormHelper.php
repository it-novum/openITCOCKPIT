<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

App::uses('BoostCakeFormHelper', 'BoostCake.View/Helper');
App::uses('FormHelper', 'View/Helper');

/**
 * Class AppFormHelper
 * @deprecated
 */
class AppFormHelper extends BoostCakeFormHelper {

    /**
     * overwrites create method to prevent the validation by Google Chrome to interfere
     *
     * @param  string $model
     * @param  array $options
     *
     * @return function          parent::create()
     * @deprecated
     */
    protected $_isHorizontal = false;
    protected $_disableFieldset = false;

    /**
     * @param null $model
     * @param array $options
     * @return string
     * @deprecated
     */
    public function create($model = null, $options = []) {
        $this->_isHorizontal = isset($options['class']) && (strpos($options['class'], 'form-horizontal') !== false);

        $defaultOptions = [
            'inputDefaults' => [
                'div'        => [
                    'class' => 'form-group',
                ],
                'errorClass' => 'has-error',
                'label'      => [
                    'class' => 'col col-md-2 control-label',
                ],
                'wrapInput'  => 'col col-md-10',
                'class'      => 'form-control',
            ],
            'novalidate',
        ];

        if ($this->_isHorizontal && empty($options['wrapInput'])) {
            $defaultOptions['inputDefaults']['wrapInput'] = 'col col-xs-10';
        } else if (!empty($options['wrapInput'])) {
            $defaultOptions['inputDefaults']['wrapInput'] = $options['wrapInput'];
        }

        $options = Hash::merge($defaultOptions, $options);

        return parent::create($model, $options);
    }

    /**
     * Sets defaults for standard input controls, before passing them to BoostCake.
     *
     * @param string $fieldName
     * @param array $options
     *
     * @return string
     * @deprecated
     */
    public function input($fieldName, $options = []) {
        $this->_storedInputDefaults = $this->_inputDefaults;
        $this->Html->disableFieldset = false;
        $this->setEntity($fieldName);
        $options = $this->_parseOptions($options);

        if ($options['type'] == 'radio') {
            $baseOptions = [
                'legend' => false,
                'class'  => false,
                'div'    => [
                    'class' => 'form-group radio-group',
                ],
            ];
            if (!empty($options['label']) && is_string($options['label'])) {
                $options['before'] = '<label class="col col-md-2 control-label">' . $options['label'] . '</label>';
            }
            $options = Hash::merge($baseOptions, $options);
        }
        if (isset($options['multiple']) && $options['multiple'] == 'checkbox') {
            if (isset($options['div']['class']) && is_string($options['div']['class'])) {
                $options['div']['class'] = $options['div']['class'] . ' checkbox-group';
            } else if (isset($options['div']) && is_string($options['div'])) {
                $options['div'] = $options['div'] . ' checkbox-group';
            }
        }

        if (isset($options['multiple']) && ($options['multiple'] === true || $options['multiple'] == 'multiple')) {
            if (isset($options['div']['class']) && is_string($options['div']['class'])) {
                $options['div']['class'] = $options['div']['class'] . ' multiple-select';
            } else if (isset($options['div']) && is_string($options['div'])) {
                $options['div'] = $options['div'] . ' multiple-select';
            }
        }
        if ($options['type'] == 'checkbox') {
            //if($options['wrapInput'] != false){
            //	$options['wrapInput'] = 'col-md-offset-2 col-md-10';
            //}

            unset($this->_inputDefaults['label']);
            if (isset($options['class']) && $options['class'] !== false) {
                $options['class'] = str_replace('form-control', '', $options['class']);
            }
        }

        if (isset($options['prepend']) && isset($options['append'])) {
            $baseOptions = [
                'beforeInput' => '<div class="input-group"><span class="input-group-addon">' . $options['prepend'] . '</span>',
                'afterInput'  => '<span class="input-group-addon">' . $options['append'] . '</span></div>',
            ];
            $options = Hash::merge($baseOptions, $options);
        } else if (isset($options['prepend'])) {
            $baseOptions = [
                'beforeInput' => '<div class="input-group"><span class="input-group-addon">' . $options['prepend'] . '</span>',
                'afterInput'  => '</div>',
            ];
            unset($options['prepend']);
            $options = Hash::merge($baseOptions, $options);
        } else if (isset($options['append'])) {
            $baseOptions = [
                'beforeInput' => '<div class="input-group">',
                'afterInput'  => '<span class="input-group-addon">' . $options['append'] . '</span>',
            ];
            if (isset($options['afterInputGroup'])) {
                $baseOptions['afterInput'] .= $options['afterInputGroup'];
            }
            $baseOptions['afterInput'] .= '</div>';
            unset($options['append']);
            $options = Hash::merge($baseOptions, $options);
        }
        if (isset($options['help'])) {
            if (!isset($options['afterInput'])) {
                $options['afterInput'] = '';
            }
            $options['afterInput'] .= '<span class="help-block">' . $options['help'] . '</span>';
        }
        if ($options['type'] == 'datetime') {
            if (isset($options['div']['class']) && is_string($options['div']['class'])) {
                $options['div']['class'] = $options['div']['class'] . ' datetime';
            } else if (isset($options['div']) && is_string($options['div'])) {
                $options['div'] = $options['div'] . ' datetime';
            }
        }

        $out = parent::input($fieldName, $options);
        $this->_inputDefaults = $this->_storedInputDefaults;

        return $out;
    }

    /**
     * Returns an input field which can trigger the CKFinder with a button, and shows a thumbnail
     * of the chosen image.
     *
     * @param string $field
     * @param array $options
     *
     * @return string
     * @deprecated
     */
    public function imageChooserInput($field, array $options = []) {
        $options = Hash::merge([
            'label' => Inflector::humanize($field),
        ], $options);

        $hiddenInput = $this->input($field, [
            'type'  => 'hidden',
            'class' => 'image-name-input',
        ]);

        $imageName = $this->value($field);
        $imageStyle = empty($imageName) ? 'display: none' : '';
        $label = $options['label'];

        if ($imageName == '') {
            $selectedImage = '';
            $buttonTitle = __('Select Image');
        } else {
            $buttonTitle = __('Change Image');
            $selectedImage = 'Path: <span class="image-name">:imageName</span><br>';
        }

        $errorOptions = $this->_extractOption('error', $this->_inputOptions, null);
        $error = $this->error($this->_fieldName, $errorOptions);
        $errorClass = $error ? 'has-error' : '';
        $markup = '
			<div class="row form-group image-chooser-input :errorClass">
				<label class="col col-md-2 control-label">:label</label>
				<div class="col col-xs-10">
					:hiddenInput
					<div class="thumb">
						<img src=":imageName" style=":imageStyle"/>
					</div>
					' . $selectedImage . '
					:error
					<a class="btn btn-xs btn-default choose-image">' . $buttonTitle . '</a>';
        if ($imageName != '') {
            $markup .= '<a class="btn btn-xs btn-default remove-image">Remove Image</a>';
        }
        $markup .= '</div></div>';

        return String::insert($markup, compact(
            'hiddenInput',
            'label',
            'imageName',
            'imageStyle',
            'error',
            'errorClass'
        ));
    }

    /**
     * Returns an input field which can trigger the CKFinder with a button, and shows the path of the chosen file
     *
     * @param string $field
     * @param array $options
     *
     * @return string
     * @deprecated
     */
    public function fileChooserInput($field, array $options = []) {
        $options = Hash::merge([
            'label' => Inflector::humanize($field),
        ], $options);

        $hiddenInput = $this->input($field, [
            'type'  => 'hidden',
            'class' => 'image-name-input',
        ]);

        $imageName = $this->value($field);
        $imageStyle = empty($imageName) ? 'display: none' : '';
        $label = $options['label'];


        if ($imageName == '') {
            $selectedImage = '';
            $buttonTitle = __('Select File');
        } else {
            $buttonTitle = __('Change File');
            $selectedImage = 'Path: <span class="image-name">:imageName</span><br>';
        }

        $errorOptions = $this->_extractOption('error', $this->_inputOptions, null);
        $error = $this->error($this->_fieldName, $errorOptions);
        $errorClass = $error ? 'has-error' : '';
        $markup = '
			<div class="form-group file-chooser-input :errorClass">
				<label class="col col-md-2 control-label">:label</label>
				<div class="col col-xs-10">
					:hiddenInput
					<span class="selected-file">' . $selectedImage . '</span>
					:error
					<a class="btn btn-xs btn-default choose-image">' . $buttonTitle . '</a>';
        if ($imageName != '') {
            $markup .= '<a class="btn btn-xs btn-default remove-image">Remove File</a>';
        }
        $markup .= '</div></div>';

        return String::insert($markup, compact(
            'hiddenInput',
            'label',
            'imageName',
            'imageStyle',
            'error',
            'errorClass'
        ));
    }

    /**
     * Returns a form action block
     *
     * @param string $saveText
     * @param array $options
     *
     * @internal param string $cancelButton
     * @internal param string $delete
     * @return string
     * @deprecated
     */
    public function formActions($saveText = null, array $options = []) {

        if (empty($saveText)) {
            $saveText = __('Save');
        }
        $options = Set::merge([
            'cancelButton' => true,
            'delete'       => null,
            'endForm'      => true,
            'class'        => '',
            'saveClass'    => 'btn btn-primary',
        ], $options);

        $out = '';
        $out .= '<div class="well formactions ' . $options['class'] . '"><div class="pull-right">';
        $out .= $this->submit($saveText, ['div' => false, 'class' => $options['saveClass']]);
        if ($options['cancelButton']) {
            if (!is_array($options['cancelButton'])) {
                $options['cancelButton'] = [];
                $options['cancelButton']['title'] = __('Cancel');
                $options['cancelButton']['url'] = ['action' => 'index'];
            }
            $out .= '&nbsp;' . $this->Html->link($options['cancelButton']['title'], $options['cancelButton']['url'], ['class' => 'btn btn-default']);
        }
        $out .= '</div>';

        if (!empty($options['delete'])) {
            if (is_numeric($options['delete']) || is_string($options['delete'])) {
                $options['delete'] = [
                    'id'    => $options['delete'],
                    'title' => __('delete'),
                ];
            }
            $out .= '<div class="pull-right">';
            $id = 1;
            $out .= $this->Utils->deleteButton($options['delete']['title'], $options['delete']['id']);
            $out .= '</div>';
        }

        $out .= '</div>';
        if ($options['endForm']) {
            $out .= $this->end();
        }

        return $out;
    }

    /**
     * @param $fieldName
     * @param array $options
     * @return string
     * @deprecated
     */
    public function fancyCheckbox($fieldName, $options = []) {
        $this->_storedInputDefaults = $this->_inputDefaults;
        $this->Html->disableFieldset = false;
        $this->setEntity($fieldName);
        $__options = Hash::merge([
            'on'  => __('On'),
            'off' => __('Off'),
        ], $options);

        $html = '';
        $_options = [
            'type'             => 'checkbox',
            'div'              => ['class' => ''],
            'error'            => false,
            'wrapInput'        => false,
            'label'            => false,
            'caption'          => $this->domId(),
            'captionGridClass' => 'col col-md-4',
            'captionClass'     => 'control-label text-left',
            'wrapClass'        => '',
            'wrapGridClass'    => 'col col-md-8',
            'icon'             => '',
            'checkboxDiv'      => false,
            'class'            => 'onoffswitch-checkbox',
            'before'           => '<span class="onoffswitch">',
            'after'            => '<label for="' . $this->domId() . '" class="onoffswitch-label">
				<span data-swchoff-text="' . $__options['off'] . '" data-swchon-text="' . $__options['on'] . '" class="onoffswitch-inner"></span>
				<span class="onoffswitch-switch"></span>
			</label></span>',
            'checked'          => '',
            'value'            => '',
            'showLabel'        => true,
        ];
        $options = Hash::merge($_options, $options);
        if ($options['checked'] === true || in_array($options['checked'], [1, 'checked']) || $options['value'] >= 1) {
            $options['checked'] = 'checked';
        }
        if ($options['showLabel'] === true) {
            $html .= '<label for="' . $this->domId() . '" class="' . $options['captionGridClass'] . ' ' . $options['captionClass'] . '">' . $options['icon'] . $options['caption'] . '</label>';
        }
        $html .= '<div class="' . $options['wrapGridClass'] . ' ' . $options['wrapClass'] . '">';
        if ($options['value'] == '') {
            //Cake >= 2.5 fix
            $options['value'] = 1;
        }
        $html .= $this->input($fieldName, $options);
        $html .= '</div>';

        return $html;
    }

    /**
     * @param string $fieldName
     * @param array $options
     *
     * @return string
     * @deprecated
     */
    public function fancyCheckboxWithValue($fieldName, $options = []) {
        $this->_storedInputDefaults = $this->_inputDefaults;
        $this->Html->disableFieldset = false;
        $this->setEntity($fieldName);
        $__options = Hash::merge([
            'on'  => __('On'),
            'off' => __('Off'),
        ], $options);

        $html = '';
        $_options = [
            'type'             => 'checkbox',
            'div'              => ['class' => ''],
            'error'            => false,
            'wrapInput'        => false,
            'label'            => false,
            'caption'          => $this->domId(),
            'captionGridClass' => 'col col-md-4',
            'captionClass'     => 'control-label text-left',
            'wrapClass'        => '',
            'wrapGridClass'    => 'col col-md-8',
            'icon'             => '',
            'checkboxDiv'      => false,
            'class'            => 'onoffswitch-checkbox',
            'before'           => '<span class="onoffswitch">',
            'after'            => '<label for="' . $this->domId() . '" class="onoffswitch-label">
				<span data-swchoff-text="' . $__options['off'] . '" data-swchon-text="' . $__options['on'] . '" class="onoffswitch-inner"></span>
				<span class="onoffswitch-switch"></span>
			</label></span>',
            'checked'          => '',
            'value'            => '',
            'showLabel'        => true,
        ];
        $options = Hash::merge($_options, $options);
        if ($options['checked'] === true || in_array($options['checked'], [1, 'checked'])) {
            $options['checked'] = 'checked';
        }
        if ($options['showLabel'] === true) {
            $html .= '<label for="' . $this->domId() . '" class="' . $options['captionGridClass'] . ' ' . $options['captionClass'] . '">' . $options['icon'] . $options['caption'] . '</label>';
        }
        $html .= '<div class="' . $options['wrapGridClass'] . ' ' . $options['wrapClass'] . '">';
        $html .= $this->input($fieldName, $options);
        $html .= '</div>';

        return $html;
    }

    /**
     * @param $fieldName
     * @param array $options
     * @return string
     * @deprecated
     */
    public function hostAndServiceSelectOptiongroup($fieldName, $options = []) {
        $_options = [
            'divClass'    => 'col col-xs-10',
            'label'       => $fieldName,
            'labelClass'  => 'col col-md-2 control-label',
            'placeholder' => 'Please choose a service',
            'class'       => 'chosen optgroup_show',
            'required'    => false,
            'options'     => [],
            'selected'    => [],
            'escape'      => true,
        ];
        $options = Hash::merge($_options, $options);
        $modelAndField = explode('.', $fieldName);
        if (sizeof($modelAndField) == 2) {
            $model = $modelAndField[0];
            $field = $modelAndField[1];
        } else {
            $model = $this->model();
            $field = $modelAndField[0];
        }

        if (empty($options['selected']) && isset($this->request->data[$model][$field])) {
            $options['selected'] = $this->request->data[$model][$field];
        }

        if (!is_array($options['selected'])) {
            $options['selected'] = [$options['selected']];
        }
        $hasError = false;
        $validationError = [];
        if (isset($this->validationErrors[$model][$field])) {
            $hasError = true;
            $validationError = $this->validationErrors[$model][$field];
        }

        $this->Html->disableFieldset = false;
        $this->setEntity($fieldName);

        $html = '';

        $target = '';
        if (isset($options['target'])) {
            $target = 'target="' . $options['target'] . '"';
        }

        if ($options['escape'] == true) {
            $options['label'] = h($options['label']);
        }

        $html .= '<div class="form-group ' . ($options['required'] ? 'required' : '') . ' ' . ($hasError ? 'has-error' : '') . ' ">';
        $html .= '<label for="' . $this->domId() . '" class="' . $options['labelClass'] . '">' . $options['label'] . '</label>';
        $html .= '<div class="' . $options['divClass'] . '">';
        $html .= '<select name="data[' . $model . '][' . $field . '][]" class="' . $options['class'] . ' ' . ($hasError ? 'form-error' : '') . '" multiple="multiple" style="width: 100%; display: none;" data-placeholder="' . __($options['placeholder']) . '" id="' . $this->domId() . '" ' . ($options['required'] ? 'required="required"' : '') . ' ' . $target . ' >';
        foreach ($options['options'] as $host_id => $host):
            foreach ($host as $hostName => $hostServices):
                $html .= '<optgroup label="' . h($hostName) . '">';
                foreach ($hostServices as $service_id => $serviceName):
                    $html .= '<option value="' . h($service_id) . '" ' . ((in_array($service_id, $options['selected'])) ? 'selected="selected"' : '') . '>' . h($serviceName) . '</option>';
                endforeach;
                $html .= '</optgroup>';
            endforeach;
        endforeach;
        $html .= '</select>';
        if ($hasError):
            foreach ($validationError as $error):
                $html .= '<span class="help-block text-danger">' . h($error) . '</span>';
            endforeach;
        endif;
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}
