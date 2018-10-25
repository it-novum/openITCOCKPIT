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

class ChangelogHelper extends AppHelper {

    public function singleArray($models, $data) {
        if (!is_array($models)) {
            $models = [$models];
        }
        $html = '';
        foreach ($models as $model) {
            if (!empty($data[$model])) {
                foreach ($data[$model]['before'] as $fieldName => $fieldValue) {
                    $html .= '<p class="font-sm">' . __($fieldName) . '</p>';
                    $html .= '<small><span class="txt-color-red">' . __($fieldValue) . '</span> <i class="fa fa-caret-right"></i> <span class="txt-color-green">' . __($data[$model]['after'][$fieldName]) . '</span></small>';
                }
            }
        }

        return $html;
    }

    public function multiArray($models, $data) {
        $html = '';
        foreach ($models as $model => $fields) {
            if (!empty($data[$model])) {
                $hasChanges = false;
                $old = [];
                if (!empty($data[$model]['before'])) {
                    $hasChanges = true;
                    $_old = Set::classicExtract($data[$model], 'before.{n}.{(' . implode('|', $fields) . ')}');
                    foreach ($_old as $__old):
                        $old[] = $__old[$fields[0]] . ' => ' . $__old[$fields[1]];
                    endforeach;
                }
                $new_args = [];
                if (!empty($data[$model]['after'])) {
                    $hasChanges = true;
                    $_new_args = Set::classicExtract($data[$model], 'after.{n}.{(name|human_name)}');
                    foreach ($_new_args as $new_arg):
                        $new_args[] = $new_arg[$fields[0]] . ' => ' . $new_arg[$fields[1]];
                    endforeach;
                }
                if ($hasChanges) {
                    $html .= '<p class="font-sm">' . __('Commandarguments') . '</p>';
                    $html .= '<small><span class="txt-color-red">' . implode(', ', $old) . '</span> <i class="fa fa-caret-right"></i> <span class="txt-color-green">' . implode(', ', $new_args) . '</span></small>';
                }
            }
        }

        return $html;
    }

    public function getActionIcon($action = 'edit') {
        switch ($action) {
            case 'add':
                return 'fa-plus';

            case 'delete':
                return 'fa-trash-o ';
            case 'copy':
                return 'fa-files-o';
            default:
                return 'fa-pencil';
        }
    }

    public function getActionColors($action = 'edit') {
        switch ($action) {
            case 'add':
                return 'greenLight';

            case 'delete':
                return 'red';

            case 'deactivate':
                return 'orange';

            default:
                return 'blue';
        }
    }
}