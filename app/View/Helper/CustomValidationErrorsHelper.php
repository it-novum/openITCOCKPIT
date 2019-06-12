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

/**
 * Class CustomValidationErrorsHelper
 * @deprecated
 */
class CustomValidationErrorsHelper extends AppHelper {

    /**
     * Initialize the Helper and set the needed variables
     *
     * @param string $viewFile
     * @deprecated
     */
    public function beforeRender($viewFile) {
        $this->Controller = ucfirst($this->params->params['controller']);
        $this->Model = Inflector::singularize($this->Controller);
        $this->View = $this->_View->viewVars;
    }

    /**
     * Returns a string with the css error class if the field has a validation error
     * ### Options
     * - `Model`   The name of the Model
     * - `Controller` The Name of the Controller
     * - `errorField` The name of the Variable with the error
     * - `errorClass` The CSS class for validation errors
     *
     * @param string $fieldName of the input field we want to display the error
     * @param array $options Array of options
     *
     * @return string $ with the error message of validate function
     * @deprecated
     */
    public function errorClass($fieldName, $options = []) {
        $_options = [
            'Model'      => $this->Model,
            'Controller' => $this->Controller,
            'errorField' => '.validationError_',
            'errorClass' => 'has-error',
        ];
        $options = Hash::merge($_options, $options);
        if ($this->hasError($fieldName, $options)) {
            return $options['errorClass'];
        }

        return '';
    }

    /**
     * Returns a string with the css error class if the field has a validation error
     * ### Options
     * - `Model`   The name of the Model
     * - `Controller` The Name of the Controller
     * - `errorField` The name of the Variable with the error
     * - `wrapper` The HTML wrapper element (div, span)
     * - `class` The CSS classes of the wrapper element
     * - `style` Style atributes of the wrapper element
     *
     * @param string $fieldName of the input field we want to display the error
     * @param array $options Array of options
     *
     * @return string a `span` HTML object with the error message
     * @deprecated
     */
    public function errorHTML($fieldName, $options = []) {
        $_options = [
            'Model'      => $this->Model,
            'Controller' => $this->Controller,
            'errorField' => '.validationError_',
            'wrapper'    => 'span',
            'class'      => 'help-block text-danger',
            'style'      => '',
        ];
        $options = Hash::merge($_options, $options);
        if ($this->hasError($fieldName, $options)) {
            return '<' . $options['wrapper'] . ' class="' . $options['class'] . '" style="' . $options['style'] . '">' . $this->returnError($fieldName, $options) . '</' . $options['wrapper'] . '>';
        }

        return '';
    }

    /**
     * Returns a string with the error message, if the field has a validation error
     * ### Options
     * - `Model`   The name of the Model
     * - `Controller` The Name of the Controller
     * - `errorField` The name of the Variable with the error
     *
     * @param string $fieldName of the input field we want to display the error
     * @param array $options Array of options
     *
     * @return string with the error message of validate function
     * @deprecated
     */
    public function returnError($fieldName, $options = []) {
        $_options = [
            'Model'      => $this->Model,
            'Controller' => $this->Controller,
            'errorField' => '.validationError_',
        ];

        $options = Hash::merge($_options, $options);
        if ($this->hasError($fieldName, $options)) {
            //$this->View[$options['Model'].$options['errorField'].$fieldName] will return a array
            //[0] => 'Error message' the current() convers this into a string
            return current($this->View[$options['Model'] . $options['errorField'] . $fieldName]);
        }

        return '';
    }


    /**
     * Check if the input field has a validation error
     * ### Options
     * - `Model`   The name of the Model
     * - `Controller` The Name of the Controller
     * - `errorField` The name of the Variable with the error
     *
     * @param string $fieldName of the input field we want to display the error
     * @param array $options Array of options
     *
     * @return Boolean
     * @deprecated
     */
    public function hasError($fieldName, $options = []) {
        $_options = [
            'Model'      => $this->Model,
            'Controller' => $this->Controller,
            'errorField' => '.validationError_',
        ];

        $options = Hash::merge($_options, $options);
        if (isset($this->View[$options['Model'] . $options['errorField'] . $fieldName])) {
            return true;
        }

        return false;
    }

    /**
     * Returns the value of an input box after form submit, if anny
     * ### Options
     * - `Model`   The name of the Model
     *
     * @param string $fieldName of the input field we want to display the error
     * @param string $default a default value
     * @param array $options Array of options
     *
     * @return mixed value from Controller->request->data[$Model][$Field]
     * @deprecated
     */
    public function refill($fieldName, $default = '', $options = []) {
        $_options = [
            'Model' => $this->Model,
        ];
        $options = Hash::merge($_options, $options);
        if (isset($this->View['customRefill'][$options['Model']][$fieldName])) {
            return $this->View['customRefill'][$options['Model']][$fieldName];
        }

        return $default;
    }
}
