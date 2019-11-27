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
 * Class UtilsHelper
 * @deprecated
 */
class UtilsHelper extends AppHelper {
    /**
     * Used helpers
     * @var string
     */
    public $helpers = ['Form', 'Html', 'Paginator', 'Text', 'Auth'];

    /**
     * Returns a nicely formatted date and time
     *
     * @param string $date
     *
     * @return string
     * @deprecated
     */
    public function niceDate($date) {
        return utf8_encode(strftime("%A, %e. %B %Y", strtotime($date)));
    }

    /**
     * Returns a nicely formatted date and time
     *
     * @param string $date
     *
     * @return string
     * @deprecated
     */
    public function niceDateAndTime($date) {
        return utf8_encode(strftime("%A, %e. %B %Y at %R", strtotime($date)));
    }

    /**
     * Formats a given number to a price, e.g. 5.000 €
     * @return void
     * @deprecated
     */
    public function price($number, $decimals = 2, $suffix = ' &euro;') {
        if (empty($number)) {
            $number = 0;
        }

        return number_format($number, $decimals, ',', '.') . $suffix;
    }

    /**
     * Takes a DB date value and formats it correctly
     *
     * @param string $date
     *
     * @return string
     * @deprecated
     */
    public function date($date) {
        if (empty($date)) {
            return '';
        } else {
            return $this->formatDate($date, 'd.m.Y');
        }
    }

    /**
     * Takes a DB datetime value and formats it correctly
     *
     * @param string $date
     *
     * @return string
     * @deprecated
     */
    public function dateAndTime($date) {
        if (empty($date)) {
            return '';
        } else {
            return $this->formatDate($date, 'd.m.Y \a\t H:i');
        }
    }

    /**
     * Parses the given $date and formats it by $format. If $date is null,
     * the current date is used.
     *
     * @param string $date
     * @param string $format
     *
     * @return string
     * @deprecated
     */
    public function formatDate($date = null, $format = 'd.m.Y H:i') {
        if ($date) {
            $time = strtotime($date);
        } else {
            $time = time();
        }
        // Datum ist nicht parsebar
        if ($time === false) {
            return $date;
        }

        return date($format, $time);
    }

    /**
     * Renders a checked or unchecked checkbox icon, based on the value. Value can be
     * boolean or numeric (0/1)
     *
     * @param mixed $value
     *
     * @return string
     * @deprecated
     */
    public function graphicalCheckbox($value) {
        return sprintf('<i class="glyphicon glyphicon-%s"></i>', ($value ? 'ok' : 'remove'));
    }

    /**
     * Renders the customer's buttons in his showreels
     *
     * @param array $buttons
     *
     * @return string
     * @deprecated
     */
    public function customerButtons($buttons = []) {
        $return = '';
        for ($i = 1; $i < 4; $i++) {
            if (isset($buttons["homepage_button_{$i}_caption"], $buttons["homepage_button_{$i}_link"])) {
                $return .= $this->button($buttons["homepage_button_{$i}_caption"], $buttons["homepage_button_{$i}_link"], ['target' => '_blank']) . ' ';
            }
        }

        return $return;
    }

    /**
     * Renders an edit button
     *
     * @param string $title
     * @param string $url
     * @param array $options
     *
     * @return void
     * @deprecated
     */
    public function editButton($title = null, $url = null, $options = []) {
        if (!$title) {
            $title = __('edit');
        }
        if (is_numeric($url) || is_string($url)) {
            $url = ['action' => 'edit', $url];
        }
        $options = Set::merge([
            'class'  => 'btn btn-primary btn-xs',
            'escape' => false,
        ], $options);
        $title = '<i class="fa fa-edit"></i> ' . $title;

        return $this->Html->link($title, $url, $options);
    }

    /**
     * renders an add button
     *
     * @param string $title
     * @param array $options
     *
     * @return string
     * @deprecated
     */
    public function addButton($title = null, $options = []) {
        if (!$title) {
            $title = __('add');
        }
        $options = Set::merge([
            'url'    => ['action' => 'add'],
            'class'  => 'btn btn-success',
            'escape' => false,
        ], $options);

        if ($options['url'] === null) {
            $options['url'] = ['action' => 'add'];
        }
        $url = $options['url'];
        unset($options['url']);
        $title = '<i class="fa fa-plus"></i> ' . $title;

        return $this->Html->link($title, $url, $options);
    }


    /**
     * Returns a mailto link
     *
     * @param string $email
     * @param string $subject
     * @param string $body
     *
     * @return string
     * @deprecated
     */
    public function emailLink($email = null, $subject = null, $body = null) {
        $all = '';
        if (isset($email)) {
            $all .= $email;
        }
        $_subject = isset($subject);
        $_body = isset($body);
        if ($_subject || $_body) {
            $all .= '?';
            if ($_subject && $_body) {
                $all .= 'subject=' . $subject . '&body=' . $body;
            } else {
                if ($_body) {
                    $all .= 'body=' . $body;
                } else {
                    $all .= 'subject=' . $subject;
                }
            }
        }

        return 'mailto:' . $all;
    }

    /**
     * Renders a delete button
     *
     * @param string $title
     * @param mixed $url Either a string or array url, or an ID
     * @param array $options
     *
     * @return string
     * @deprecated
     */
    public function deleteButton($title = null, $url = null, $options = [], $confirm = true, $postText = '') {
        $_options = ['icon' => 'fa fa-trash-o'];
        $options = Hash::merge($options, $_options);
        if (is_numeric($title)) {
            $url = $title;
            $title = null;
        }
        if (!$title) {
            $title = __('Delete');
        }
        if (is_numeric($url) || is_string($url) || (is_array($url) && !isset($url['action']))) {
            if (is_array($url)) {
                $url = Hash::merge($url, ['action' => 'delete']);
            } else {
                $url = ['action' => 'delete', $url];

            }

        }
        $options = Set::merge([
            'class'  => 'btn btn-danger btn-xs',
            'escape' => false,
        ], $options);
        $title = '<i class="' . $options['icon'] . '"></i> ' . $title;
        if ($confirm) {
            return $this->Form->postLink($title, $url, $options, __('Really delete?') . ' ' . $postText);
        }

        return $this->Form->postLink($title, $url, $options);
    }

    /**
     * Renders a back button
     *
     * @param string $title
     * @param mixed $url Either a string or array url
     * @param array $options
     *
     * @return string
     * @deprecated
     */
    public function backButton($title = null, $url = null, $options = []) {
        if (!$title) {
            $title = __('Back to list');
        }
        if (!$url) {
            $url = ['action' => 'index'];
        }
        $options = Set::merge([
            'class'     => 'btn btn-default btn-xs',
            'escape'    => false,
            'iconColor' => 'white',
        ], $options);

        $title = '<i class="glyphicon glyphicon-' . $options['iconColor'] . ' glyphicon-arrow-left"></i> ' . $title;

        return $this->Html->link($title, $url, $options);
    }

    /**
     * Returns a form action block
     *
     * @param string $saveText
     * @param string $options
     *
     * @return void
     * @deprecated
     */
    public function formActions($saveText = null, array $options = []) {
        if (!$saveText) {
            $saveText = __('Save');
        }
        $options = Set::merge([
            'cancelButton' => [
                'title' => __('Cancel'),
                'url'   => ['action' => 'index'],
            ],
            'delete'       => null,
        ], $options);

        $out = '';
        $out .= '<div class="form-actions"><div class="pull-left">';
        if ($options['cancelButton']) {
            $out .= $this->Html->link($options['cancelButton']['title'], $options['cancelButton']['url'], ['class' => 'btn btn-cancel']) . '&nbsp;';
        }
        $out .= $this->Form->submit($saveText, ['div' => false, 'class' => 'btn btn-primary']);
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
            $out .= $this->deleteButton($options['delete']['title'], $options['delete']['id']);
            $out .= '</div>';
        }

        $out .= '</div>';

        return $out;
    }

    /**
     * Renders a view button
     *
     * @param string $title
     * @param string $url
     *
     * @return void
     * @deprecated
     */
    public function viewButton($title = null, $url = null) {
        if (!$title) {
            $title = __('details');
        }
        if (is_numeric($url) || is_string($url)) {
            $url = ['action' => 'view', $url];
        }
        $title = '<i class="glyphicon glyphicon-white glyphicon-search"></i> ' . $title;

        return $this->Html->link($title, $url, ['class' => 'btn btn-default btn-xs', 'escape' => false]);
    }

    /**
     * Renders a print button
     *
     * @param bool $mini
     *
     * @return void
     * @deprecated
     */
    public function printButton($mini = false) {
        $title = '<i class="glyphicon glyphicon-print"></i> ';
        $title .= __('print');

        return $this->Html->link(
            $title,
            'javascript:window.print()',
            ['class' => 'btn print-button' . ($mini ? ' btn-xs' : ''), 'escape' => false]
        );
    }

    /**
     * Renders a standard button
     *
     * @param string $title
     * @param array $url
     * @param array $options
     *
     * @return void
     * @deprecated
     */
    public function button($title, $url, $options = []) {
        $options = Set::merge([
            'class' => 'btn btn-default',
        ], $options);

        return $this->Html->link($title, $url, $options);
    }

    /**
     * Returns the name of the given country code
     *
     * @param  string $code
     *
     * @return string
     * @deprecated
     */
    public function country($code) {
        return Configure::read('countries.' . $code);
    }

    /**
     * @param $size
     * @param null $retstring
     * @param bool $round
     * @return string
     * @deprecated
     */
    public function readableFilesize($size, $retstring = null, $round = true) {
        $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        if ($retstring === null) {
            $retstring = '%01.0f %s';
        }
        $lastsizestring = end($sizes);
        foreach ($sizes as $sizestring) {
            if ($size < 1024) {
                break;
            }
            if ($sizestring != $lastsizestring) {
                $size /= 1024;
            }
        }
        if ($sizestring == $sizes[0]) {
            $retstring = '%01d %s';
        }

        $size = ($round) ? floor($size) : $size;

        return sprintf($retstring, $size, $sizestring);
    }

    /**
     * converts the seconds of a comment to minutes /hours if appropiate
     *
     * @param  int $seconds
     *
     * @return string          'Sekunde x', 'Minute y:xx' or 'Stunde z:yy:xx'
     * @deprecated
     */
    public function secToTime($seconds) {
        $seconds = str_replace(',', '.', $seconds);
        if ($seconds < 60) {
            return 'Sekunde ' . round($seconds, 0);
        }

        $minutes = bcdiv($seconds, '60', 0);
        $seconds = bcmod($seconds, '60');

        if ($minutes < 60) {
            if ($seconds < 10) {
                $seconds = '0' . $seconds;
            }

            return 'Minute ' . $minutes . ':' . $seconds;
        }

        $hours = bcdiv($minutes, '60', 0);
        $minutes = bcmod($minutes, '60');

        //delete this line if you uncomment the end for transformation in days if needed
        return 'Stunde ' . $hours . ':' . $minutes . ':' . $seconds;
        /*
                if (!($hours >= 24)) {
                    return $hours . ' Stunden ' . $minutes . ' Minuten ' . $seconds . ' Sekunden';
                }

                $days = bcdiv($hours, '24', 0);
                $hours = bcmod($hours, '24');

                return $days . ' Tage ' . $hours . ' Stunden ' . $minutes . ' Minuten ' . $seconds . ' Sekunden';
        */
    }

    /**
     * converts the seconds of a comment to minutes /hours if appropiate
     *
     * @param  int $seconds
     *
     * @return string          'Sekunde x', 'Minute y:xx' or 'Stunde z:yy:xx'
     * @deprecated
     */
    public function secToTimestamp($seconds) {
        $seconds = str_replace(',', '.', $seconds);
        if ($seconds < 60) {
            return '00:00:' . str_pad(round($seconds, 0), 2, 0, STR_PAD_LEFT);
        }

        $minutes = bcdiv($seconds, '60', 0);
        $seconds = bcmod($seconds, '60');

        $seconds = str_pad($seconds, 2, 0, STR_PAD_LEFT);

        if ($minutes < 60) {
            return '00:' . str_pad($minutes, 2, 0, STR_PAD_LEFT) . ':' . $seconds;
        }

        $hours = bcdiv($minutes, '60', 0);
        $minutes = bcmod($minutes, '60');

        $minutes = str_pad($minutes, 2, 0, STR_PAD_LEFT);

        //delete this line if you uncomment the end for transformation in days if needed
        return str_pad($hours, 2, 0, STR_PAD_LEFT) . ':' . $minutes . ':' . $seconds;
        /*
                if (!($hours >= 24)) {
                    return $hours . ' Stunden ' . $minutes . ' Minuten ' . $seconds . ' Sekunden';
                }

                $days = bcdiv($hours, '24', 0);
                $hours = bcmod($hours, '24');

                return $days . ' Tage ' . $hours . ' Stunden ' . $minutes . ' Minuten ' . $seconds . ' Sekunden';
        */
    }

    /**
     * creates a classes string for the body
     *
     * @param  int $backgroundImage number of background image
     *
     * @return string                  classes string
     * @deprecated
     */
    public function getEpicBgClasses() {
        if (isset($this->_View->viewVars['backgroundImage']) && is_numeric($this->_View->viewVars['backgroundImage'])) {
            $backgroundImage = $this->_View->viewVars['backgroundImage'];
        } else {
            $backgroundImage = 1;
        }
        $BgClasses = 'epic-bg';
        if (isset($backgroundImage) && $backgroundImage >= 0 && $backgroundImage <= 8) {
            $BgClasses .= ' epic-bg-' . $backgroundImage;
        } else {
            $BgClasses .= ' epic-bg-1';
        }

        return $BgClasses;
    }

    /**
     * returns user role dependet classes. It's also possible to only set one of both classes.
     *
     * @param  string $userClass class to be returned if user has user role
     * @param  string $adminClass class to be returned if user has admin role
     *
     * @return string             class string
     * @deprecated
     */
    public function getRightAccessClass($userClass = '', $adminClass = '') {
        if (empty($userClass) && empty($adminClass)) {
            return '';
        }
        if ($this->Auth->isLoggedIn() && $this->Auth->hasRight('accessAdministration')) {
            if (!empty($adminClass)) {
                $returnClass = ' ' . $adminClass . ' ';
            }
        } else {
            if (!empty($userClass)) {
                $returnClass = ' ' . $userClass . ' ';
            } else {
                $returnClass = '';
            }
        }

        return $returnClass;
    }

    /**
     * renders a flag
     *
     * @param  string $countryCode 2 characters country code
     * @param  string $class
     *
     * @return string
     * @deprecated
     */
    public function flag($countryCode, $class = 'flag-small') {
        $countries = Configure::read('countries');
        $flag = '';
        if (in_array($countryCode, array_keys($countries))) {
            $flag = $this->Html->image('/files/flags/' . $countryCode . '.png', [
                'class' => $class,
            ]);
        }

        return $flag;
    }

    /**
     * returns a formatted final score
     *
     * @param  array $match
     *
     * @return string        team_home_total : team_away_total
     * @deprecated
     */
    public function getFinalScore($match) {
        $scores = Utils::calculateFinalScore($match);

        return $scores['teamHomeTotal'] . ' : ' . $scores['teamAwayTotal'];
    }

    /**
     * append English Ordinal Suffix
     *
     * @param  int $n
     *
     * @return string
     * @deprecated
     */
    public function appendEnglishOrdinalSuffix($n) {
        if (!in_array(($n % 100), [11, 12, 13])) {
            switch ($n % 10) {
                case 1:
                    return $n . 'st';
                case 2:
                    return $n . 'nd';
                case 3:
                    return $n . 'rd';
            }
        }

        return $n . 'th';
    }

    /**
     * returns terms and conditions link
     * @return string
     * @deprecated
     */
    public function termsAndConditionsLink() {
        return __('register.i_accept_the') . ' ' . $this->Html->link(__('terms_and_conditions'),
                [
                    'controller' => 'contents',
                    'action'     => 'content',
                    'page.terms_of_use',
                ],
                [
                    'target' => '_blank',
                ]
            ) . '.';
    }

    /**
     * Formats a key/value array to a table
     *
     * @param array $data
     * @param array $options
     *
     * @return string
     * @deprecated
     */
    public function captionTable($data, array $options = []) {
        $options = Set::merge([
            'additionalClasses' => '',
        ], $options);

        return $this->_View->element('common/caption_table', [
            'map'     => $data,
            'options' => $options,
        ]);
    }

    /**
     * @param $order
     * @param $key
     * @return string
     * @deprecated
     */
    public function getDirection($order, $key) {
        if (!is_array($order))
            $order = [];

        if (array_key_exists($key, $order)):
            if ($order[$key] == 'asc'):
                return '<i class="fa fa-sort-asc">&nbsp;</i>';
            endif;

            return '<i class="fa fa-sort-desc">&nbsp;</i>';
        endif;

        return '<i class="fa fa-sort">&nbsp;</i>';
    }

    /**
     * @param $count
     * @return string
     * @deprecated
     */
    public function formatExportCount($count) {
        if ($count <= 9) {
            return "&nbsp" . $count . "&nbsp;";
        }

        return $count;
    }


    /**
     * Formats a given value in seconds to a human readable string of time
     * Example 125 will return:
     * 2 minutes and 5 seconds
     *
     * @param integer $seconds to format
     *
     * @return string $ as human date
     * @author     Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since      3.0
     * @deprecated This function is deprecated and will be removed in the next version!
     */
    public function secondsInWords($seconds) {
        //$min = (int)($seconds / 60);
        //$sec = (int)($seconds % 60);
        //return $min.' '.__('minutes').' '.__('and').' '.$sec.' '.__('seconds');
        return $this->secondsInHuman($seconds);
    }

    /**
     * Formats a given value in seconds to a human readable string of time
     * Example 58536006 will return:
     * 1 years, 10 months, 8 days, 12 hours, 0 minutes and 6 seconds
     *
     * @param integer $seconds to format
     *
     * @return string $ as human date
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     * @deprecated
     */
    public function secondsInHuman($duration) {
        if ($duration == '') {
            $duration = 0;
        }
        $zero = new DateTime("@0");
        $seconds = new DateTime("@$duration");

        $closure = function ($duration) {
            //Check how mutch "time" we need
            if ($duration >= 31536000) {
                // 1 year or more
                return '%y ' . __('years') . ', %m ' . __('months') . ', %d ' . __('days') . ', %h ' . __('hours') . ', %i ' . __('minutes') . ' and %s ' . __('seconds');
            } else if ($duration >= 2678400) {
                // 1 month or more
                return '%m ' . __('months') . ', %d ' . __('days') . ', %h ' . __('hours') . ', %i ' . __('minutes') . ' and %s ' . __('seconds');
            } else if ($duration >= 86400) {
                // 1 day or more
                return '%a ' . __('days') . ', %h ' . __('hours') . ', %i ' . __('minutes') . ' and %s ' . __('seconds');
            } else if ($duration >= 3600) {
                // 1 hour or more
                return '%h ' . __('hours') . ', %i ' . __('minutes') . ' and %s ' . __('seconds');
            } else if ($duration >= 60) {
                // 1 minute or more
                return '%i ' . __('minutes') . ' and %s ' . __('seconds');
            } else if ($duration >= 0) {
                // 0 second or more
                return '%s ' . __('seconds');
            }
        };

        $format = $closure($duration);

        return $zero->diff($seconds)->format($format);
    }

    /**
     * @param $items
     * @param $singular
     * @param $plural
     * @return mixed
     * @deprecated
     */
    public function pluralize($items, $singular, $plural) {
        if (is_array($items)) {
            if (sizeof($items) > 1) {
                return $plural;
            }

            return $singular;
        }

        if (is_numeric($items)) {
            if ($items > 1) {
                return $plural;
            }

            return $singular;
        }
    }


    /**
     * Formats a given value in seconds to a human short readable string with time units
     * Example 58536006 will return:
     * 1Y 10M 8D 12h 0m 6s
     *
     * @param integer $seconds to format
     *
     * @return string $ as human date
     * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
     * @since  3.0
     * @deprecated
     */
    public function secondsInHumanShort($duration) {

        if ($duration == '') {
            $duration = 0;
        }

        $zero = new DateTime("@0");
        $seconds = new DateTime("@$duration");
        $closure = function ($duration) {
            //Check how much "time" we need
            if ($duration >= 31536000) {
                // 1 year or more
                return '%y' . __('Y') . ' %m' . __('M') . ' %d' . __('D') . ' %h' . __('h') . ' %i' . __('m') . ' %s' . __('s');
            } else if ($duration >= 2678400) {
                // 1 month or more
                return '%m' . __('M') . ' %d' . __('D') . ' %h' . __('h') . ' %i' . __('m') . ' %s' . __('s');
            } else if ($duration >= 86400) {
                // 1 day or more
                return '%a' . __('D') . ' %h' . __('h') . ' %i' . __('m') . ' %s' . __('s');
            } else if ($duration >= 3600) {
                // 1 hour or more
                return '%h' . __('h') . ' %i' . __('m') . ' %s' . __('s');
            } else if ($duration >= 60) {
                // 1 minute or more
                return '%i' . __('m') . ' %s' . __('s');
            } else if ($duration >= 0) {
                // 0 second or more
                return '%s' . __('s');
            }
        };

        $format = $closure($duration);

        return $zero->diff($seconds)->format($format);
    }
}