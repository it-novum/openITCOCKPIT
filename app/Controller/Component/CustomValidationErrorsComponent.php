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

class CustomValidationErrorsComponent extends Component {
    public $customFields = [];
    public $Model = null;

    public function initialize(Controller $controller) {
        $this->Controller = $controller;
    }

    public function loadModel(Model $model) {
        $this->Model = $model;
    }

    /**
     * Function to set the custom fields that errors should be displayed in the frontend
     *
     * @param array $fields
     */
    public function customFields($fields = []) {
        $this->customFields = $fields;
    }

    /**
     * Checks for validation errors on fields, that are not generated with $this->Form
     */
    public function fetchErrors() {
        foreach ($this->customFields as $fieldName) {
            if (isset($this->Model->validationErrors[$fieldName])) {
                $this->Controller->set($this->Model->name . '.validationError_' . $fieldName, $this->Model->validationErrors[$fieldName]);
            }
        }
    }

    public function fetchErrorsFromArray() {
        foreach ($this->customFields as $fieldName) {
            if (isset($this->Model->validationErrors[0][$fieldName])) {
                $this->Controller->set($this->Model->name . '.validationError_' . $fieldName, $this->Model->validationErrors[0][$fieldName]);
            }
        }
    }

    /**
     * Checks if a user submit a form with a validation error and if there are now custom fields (not echo
     * $this->Form->input) that needs to be refilled now
     *
     * @param  array $customFildsToRefill fields to check as array
     *
     * @return void
     */
    public function checkForRefill($customFildsToRefill = []) {
        $refill = [];
        foreach ($customFildsToRefill as $modelName => $fieldsArray) {
            foreach ($fieldsArray as $field) {
                if (isset($this->Controller->request->data[$modelName][$field])) {
                    $refill[$modelName][$field] = $this->Controller->request->data[$modelName][$field];
                }
            }
        }
        $this->Controller->set('customRefill', $refill);
    }
}
