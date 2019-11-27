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
 * Class CustomVariablesHelper
 * @deprecated
 */
class CustomVariablesHelper extends AppHelper {

    /**
     * Initialize the Helper and set the needed variables
     *
     * @param string $macrotype (host or service)
     * @param array $customMacros an array of existing custom macros (if there are any)
     *
     * @return void
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     * @deprecated
     */
    public function setup($macrotype = 'HOST', $objecttype_id = null, $customMacros = []) {
        $this->macrotype = $macrotype;
        $this->customMacros = $customMacros;
        $this->macroPrefix = '$_';
        $this->macroSuffix = '$';
        $this->objecttype_id = $objecttype_id;
        //$this->Controller = $this->params->params['controller'];
        //$this->Model = Inflector::singularize($this->Controller);
    }

    /**
     * Returns the container for ajax and the add button. Use in views
     * ### Options
     * Check this::addButton for the options
     *
     * @param array $options Array of options
     *
     * @return string `<div />` and `<a />` HTML objects
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     * @deprecated
     */
    public function prepare($macrotype = 'HOST', $options = []) {
        $html = $this->__startWrap();
        $html .= $this->fetchHtml();
        $html .= $this->__endWrap();
        $html .= $this->addButton($options);

        return $html;
    }


    /**
     * Returns a string with the add new custom variable/macro button
     * ### Options
     * - `class`   The CSS classes of the <a /> object
     * - `href`   The href link attribute
     * - `style`   CSS style properties
     * - `label`   Label/Caption of the Button
     * - `jsSelector`   The selector for javascript event bindings
     *
     * @param array $options Array of options
     *
     * @return string `<a />` HTML object
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     * @deprecated
     */
    public function addButton($options = []) {
        $_options = [
            'class'      => 'btn btn-success btn-xs pull-right margin-top-10',
            'href'       => 'javascript:void(0);',
            'style'      => '',
            'label'      => '<i class="fa fa-plus"></i> ' . __('Add new macro'),
            'jsSelector' => 'addCustomMacro',
        ];
        $options = Hash::merge($_options, $options);

        //return '<a href="'.$options['href'].'" class="'.$options['class'].' '.$options['jsSelector'].'" style="'.$options['style'].'">'.$options['label'].'</a>';
        return '<button type="button" class="' . $options['class'] . ' ' . $options['jsSelector'] . '" style="' . $options['style'] . '">' . $options['label'] . '</button>';
    }

    /**
     * Returns a string with the scaffold in HTML for macros
     * ### Options
     * - `name`   Name of the macro
     * - `value` Value of the macro
     * - `macrotype` Type of the macro (default: $this->macrotype)
     *
     * @param integer $counter count of the current macro
     *
     * @return string $html with the scaffold in HTML for macros
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     * @deprecated
     */
    public function html($counter = 0, $options = []) {

        $_options = [
            'name'                => '',
            'value'               => '',
            'macrotype'           => $this->macrotype,
            'id'                  => null,
            'objecttype_id'       => $this->objecttype_id,
            'macro_objecttype_id' => null,
        ];
        $options = Hash::merge($_options, $options);
        $html = '<div class="col-xs-12">
            <div class="col-md-3 hidden-mobile ' . $this->getColor($options['macro_objecttype_id'], $options) . '">
                <div style="padding-top: 29px; width: 100%;"><!-- spacer for nice layout --></div>
                <span>' . $this->macroPrefix . $options['macrotype'] . $options['name'] . $this->macroSuffix . '</span>
            </div>
            <div class="col-md-4 col-xs-5">
                <label class="control-label">' . __('Name') . '</label>
                <input class="form-control macroName" style="width:100%; text-transform: uppercase;" type="text" name="data[Customvariable][' . $counter . '][name]" value="' . h($options['name']) . '" counter="' . $counter . '" />
            </div>
            <div class="col-md-4 col-xs-4">
                <label class="control-label">' . __('Value') . '</label>
                <input class="form-control macroValue" style="width:100%" type="text" name="data[Customvariable][' . $counter . '][value]" value="' . h($options['value']) . '" />
            </div>';
        if ($options['id'] !== null) {
            $html .= '<input type="hidden" name="data[Customvariable][' . $counter . '][id]" value="' . $options['id'] . '" />';
        }
        if ($options['objecttype_id'] !== null) {
            $html .= '<input type="hidden" name="data[Customvariable][' . $counter . '][objecttype_id]" value="' . $options['objecttype_id'] . '" />';
        }
        $html .= '<div class="col-md-1 col-xs-1">
                <label><!-- just a spacer for a nice layout --> &nbsp;</label>
                <br />
                <a class="btn btn-default btn-sx txt-color-red deleteMacro" href="javascript:void(0);">
                    <i class="fa fa-trash-o fa-lg"></i>
                </a>
            </div>
        </div>';

        return $html;
    }

    /**
     * @param $objecttype_id
     * @param $options
     * @return mixed
     * @deprecated
     */
    public function getColor($objecttype_id, $options) {
        if ($objecttype_id === null) {
            $objecttype_id = $options['objecttype_id'];
        }

        $colors = [
            OBJECT_HOSTTEMPLATE    => 'text-success',
            OBJECT_HOST            => 'text-primary',
            OBJECT_SERVICETEMPLATE => 'text-success',
            OBJECT_SERVICE         => 'text-primary',
            OBJECT_CONTACT         => 'text-primary',
        ];

        return $colors[$objecttype_id];
    }

    /**
     * Returns a `<div>`HTML Object for wrappig the customvariables
     * @return string `<div>` Object for wraping the customvariables
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     * @deprecated
     */
    public function __startWrap() {
        return '<div id="customVariablesContainer">';
    }

    /**
     * Returns a `</div>` HTML Object and clsoe the wrapper div
     * @return string `</div>` closing div
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     * @deprecated
     */
    public function __endWrap() {
        return '</div>';
    }

    /**
     * Returns the HTML of the custom variables (input fields)
     * @return array $_customvariables if you dont want to use $this->customMacros and know what you are doint
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     * @deprecated
     */
    public function fetchHtml($_customvariables = []) {
        $html = '';
        $customvariables = $this->customMacros;
        if (!empty($_customvariables)) {
            $customvariables = $_customvariables;
        }
        if (!empty($customvariables)) {
            $i = max(array_keys($customvariables)) + 1;
            foreach ($customvariables as $macro) {
                if (!isset($macro['id'])) {
                    $macro['id'] = null;
                }
                $html .= $this->html($i, [
                    'name'                => $macro['name'],
                    'value'               => $macro['value'],
                    'id'                  => $macro['id'],
                    'objecttype_id'       => $this->objecttype_id,
                    'macro_objecttype_id' => $macro['objecttype_id'],
                ]);
                $i++;
            }
        }

        return $html;
    }
}
