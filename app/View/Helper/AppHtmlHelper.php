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

App::uses('BoostCakeHtmlHelper', 'BoostCake.View/Helper');

/**
 * Class AppHtmlHelper
 * @deprecated
 */
class AppHtmlHelper extends BoostCakeHtmlHelper {

    public $disableFieldset = false;

    /**
     * @param string $tag
     * @return mixed|string
     * @deprecated
     */
    public function useTag($tag) {
        $args = func_get_args();
        if ($this->disableFieldset && $tag == 'legend') {
            return '';
        }
        if ($this->disableFieldset && $tag == 'fieldset') {
            return '<div class="col col-xs-9">' . $args[2] . '</div>';
        }

        if ($tag === 'radio') {
            $class = (isset($args[3]['class'])) ? $args[3]['class'] : 'radio';
            unset($args[3]['class']);
        }

        $html = call_user_func_array(['parent', 'useTag'], $args);

        if ($tag === 'radio') {

            $regex = '/(<label)(.*?>)/';
            if (preg_match($regex, $html, $match)) {
                $html = $match[1] . ' class="' . $class . '"' . $match[2] . preg_replace($regex, ' ', $html);
            }
        }

        return $html;
    }

    /**
     * Creates an HTML link.
     * If $url starts with "http://" this is treated as an external link. Else,
     * it is treated as a path to controller/action and parsed with the
     * HtmlHelper::url() method.
     * If the $url is empty, $title is used instead.
     * ### Options
     * - `escape` Set to false to disable escaping of title and attributes.
     * - `escapeTitle` Set to false to disable escaping of title. (Takes precedence over value of `escape`)
     * - `confirm` JavaScript confirmation message.
     *
     * @param string $title The content to be wrapped by <a> tags.
     * @param string|array $url Cake-relative URL or array of URL parameters, or external URL (starts with
     *                                     http://)
     * @param array $options Array of options and HTML attributes.
     * @param string $confirmMessage JavaScript confirmation message.
     *
     * @return string An `<a />` element.
     * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/html.html#HtmlHelper::link
     * Changelog:
     * Erweitert um die Option "icon" - Daniel Ziegler <daniel.ziegler@it-novum.com> 07.03.2014
     * @deprecated
     */
    public function link($title, $url = null, $options = [], $confirmMessage = false) {
        $escapeTitle = true;
        if ($url !== null) {
            $url = $this->url($url);
        } else {
            $url = $this->url($title);
            $title = htmlspecialchars_decode($url, ENT_QUOTES);
            $title = h(urldecode($title));
            $escapeTitle = false;
        }

        if (isset($options['escapeTitle'])) {
            $escapeTitle = $options['escapeTitle'];
            unset($options['escapeTitle']);
        } else if (isset($options['escape'])) {
            $escapeTitle = $options['escape'];
        }

        $icon = '';
        if (isset($options['icon']) && $options['icon'] != '') {
            $icon = '<i class="' . $options['icon'] . '"></i> ';
        }

        if ($escapeTitle === true) {
            $title = $icon . h($title);
        } else if (is_string($escapeTitle)) {
            $title = $icon . htmlentities($title, ENT_QUOTES, $escapeTitle);
        }

        if (!empty($options['confirm'])) {
            $confirmMessage = $options['confirm'];
            unset($options['confirm']);
        }
        if ($confirmMessage) {
            $options['onclick'] = $this->_confirm($confirmMessage, 'return true;', 'return false;', $options);
        } else if (isset($options['default']) && !$options['default']) {
            if (isset($options['onclick'])) {
                $options['onclick'] .= ' ';
            } else {
                $options['onclick'] = '';
            }
            $options['onclick'] .= 'event.returnValue = false; return false;';
            unset($options['default']);
        }

        return sprintf($this->_tags['link'], $url, $this->_parseAttributes($options), $title);
    }


    /**
     * Creates an HTML progressbar
     * ### Options
     * - `value`   Set the value that the progressbar will display
     * - `caption` Set the text that the progressbar will display
     * - `bgColor` Is the default background color
     * - `unit`    The unit of the value (default %)
     * - `useThresholds` If true progressbar can handel default thresholds and warning and critical background colors
     * - `style`   Plain CSS properties
     *
     * @param integer $value Int with the value of the geraph
     * @param array $options Array of options and HTML attributes.
     *
     * @return string An `<div />` element.
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     * @deprecated
     */
    public function progressbar($value = 50, $options = []) {
        $_options = [
            'caption'            => null,
            'bgColor'            => 'bg-color-green',
            'value'              => $value,
            'unit'               => '%',
            'max'                => 100,
            'style'              => '',
            'color'              => '#000',
            'useThresholds'      => true,
            'id'                 => 'progressbar_' . rand(1, 9999),
            'display_as_percent' => false,
            'thresholds'         => [
                1 => [
                    'value'   => 80,
                    'bgColor' => 'bg-color-orange',
                ],
                2 => [
                    'value'   => 90,
                    'bgColor' => 'bg-color-red',
                ],
            ],
        ];
        $options = Hash::merge($_options, $options);
        if ($options['max'] > 0) {

            if ($options['unit'] != '%') {
                $value = $options['value'] / $options['max'] * 100;
            }


            if ($options['caption'] == null) {
                if ($options['unit'] == '%') {
                    $options['caption'] = $options['value'] . $options['unit'];
                } else {
                    if ($options['display_as_percent'] == true) {
                        $__value = (int)$value;
                        $options['caption'] = $__value . '%';
                    } else {
                        $options['caption'] = $options['value'] . ' / ' . $options['max'];
                    }
                }
            }

            if ($options['useThresholds'] === true) {
                if ($value >= $options['thresholds'][1]['value']) {
                    $options['bgColor'] = $options['thresholds'][1]['bgColor'];
                }

                if ($value >= $options['thresholds'][2]['value']) {
                    $options['bgColor'] = $options['thresholds'][2]['bgColor'];
                }
            }
        } else {
            $value = 100;
            $options['unit'] = '%';
            $options['caption'] = $options['value'] . ' / &infin;';
            $options['bgColor'] = 'bg-color-blue';
        }

        return '<div class="progress" style="margin-bottom: 0px;' . $options['style'] . '" innerdiv="' . $options['id'] . '"><div aria-valuetransitiongoal="' . $value . '" class="progress-bar ' . $options['bgColor'] . '" style="width: ' . $value . '%;" aria-valuenow="' . $value . '"></div><div style="position: relative; top: 0px; left:0px; width: 100%; text-align: center;font-size: 11px;font-weight: 700;padding-top:3px;color:' . $options['color'] . ';" id="' . $options['id'] . '">' . $options['caption'] . '</div></div>';
    }

    /**
     * Creates the input fields for the command args (if you add or edit a command)
     *
     * @param string $arg of your current argument (Example 'ARG1')
     * @param array $values Array with data of the arguent (name => 'warning_value')
     *
     * @return string A `<div />` element.
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     * @deprecated
     */
    public function createCommandArgHtml($arg, $values) {
        $value = '';
        if (isset($values['name'])) {
            $value = $values['name'];
        }

        $placeholder = '';
        if (isset($values['placeholder'])) {
            $placeholder = 'placeholder="' . $values['placeholder'] . '"';
        }
        $html = '<div class="col-xs-12 padding-top-10">
						<div class="col-xs-1 text-primary">
							' . $arg . '
						</div>
						<div class="col-xs-10">
							<label class="control-label">' . __('Name') . '</label>
							<input class="form-control" style="width:100%" type="text" ' . $placeholder . ' name="data[human_args][' . $arg . '][name]" value="' . $value . '" />
						</div>
						<div class="col-xs-1">
							<label><!-- just a spacer for a nice layout --> &nbsp;</label>
							<br />
							<a class="btn btn-default btn-sx txt-color-red deleteARG" href="javascript:void(0);" delete="' . $arg . '">
								<i class="fa fa-trash-o fa-lg"></i>
							</a>
						</div>
					</div>';

        return $html;
    }

    /**
     * Creates the input fields for the command args (if you add or edit a command)
     *
     * @param string $arg of your current argument (Example 'ARG1')
     * @param array $values Array with data of the arguent (name => 'warning_value')
     *
     * @return string A `<div />` element.
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     * @deprecated
     */
    public function displayCommandArg($arg, $values) {
        $value = '';
        if (isset($values['name'])) {
            $value = $values['name'];
        }

        $placeholder = '';
        if (isset($values['placeholder'])) {
            $placeholder = 'placeholder="' . $values['placeholder'] . '"';
        }
        $html = '<div class="col-xs-12 padding-top-10">
						<div class="col-xs-1 text-primary">
							' . $arg . '
						</div>
						<div class="col-xs-10">
							<label class="control-label">' . __('Name') . '</label>
							<input class="form-control" style="width:100%" type="text" ' . $placeholder . ' name="data[human_args][' . $arg . '][name]" value="' . $value . '" />
						</div>
						<div class="col-xs-1">
							<label><!-- just a spacer for a nice layout --> &nbsp;</label>
							<br />
							<a class="btn btn-default btn-sx txt-color-red deleteARG" href="javascript:void(0);" delete="' . $arg . '">
								<i class="fa fa-trash-o fa-lg"></i>
							</a>
						</div>
					</div>';

        return $html;
    }

    /**
     * Returns a array with an empty option for jquery chosen placeholder
     *
     * @param array $select with the options
     *
     * @return array $select and key 0 is empty for data-placeholder
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     * @deprecated
     */
    public function chosenPlaceholder($select = []) {
        if (!array_key_exists(0, $select)) {
            //Yes right, php can use the + operator on arrays
            if (is_array($select)) {
                $select += [0 => ''];
            }
            asort($select);
        }

        return $select;
    }

    /**
     * Wrap $letter with an underline class
     *
     * @param string $letter the letter you want to replace (needle)
     * @param string $target the stirng you want to search for $letter (haystack)
     * @param bool $caseSensitive if replacement will be case sensitive or not
     *
     * @return string a ´<span />´ object
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     * @deprecated
     */
    public function underline($letter, $target, $caseSensitive = false) {
        $_letter = $letter;
        $_target = $target;
        if (!$caseSensitive) {
            $_letter = strtolower($letter);
            $_target = strtolower($target);
        }
        $replacement = '<span class="underline">' . $letter . '</span>';
        $return = [];
        $firstReplacement = false;
        foreach (str_split($_target) as $key => $value) {
            if ($_letter == $value && $firstReplacement === false) {
                $replace = $_letter;
                if (ctype_upper($target[$key])) {
                    $replace = strtoupper($_letter);
                }
                $return[$key] = '<span class="underline">' . $replace . '</span>';
                $firstReplacement = true;
                continue;
            }
            $return[$key] = $target[$key]; //get original char because of case sensitive...
        }

        return implode('', $return);
    }

    /**
     * @param $options
     * @param $data
     * @param string $selected
     * @param string $class
     * @return string
     * @deprecated
     */
    public function createSelect($options, $data, $selected = '', $class = 'form-control systemsetting-input') {
        $html = '<select class="' . $class . '" name="' . $data . '">';
        foreach ($options as $_value => $_html):
            $_selected = '';
            if ($_value == $selected):
                $_selected = 'selected="selected"';
            endif;
            $html .= '<option value="' . $_value . '" ' . $_selected . '>' . $_html . '</option>';
        endforeach;
        $html .= '</select>';

        return $html;
    }

    /**
     * @param $parameter
     * @param null $default
     * @return CakeRequest|mixed|null
     * @deprecated
     */
    public function getParameter($parameter, $default = null) {
        $request = $this->request->data($parameter);
        if ($request !== null) {
            return $request;
        }

        return $default;
    }
}
