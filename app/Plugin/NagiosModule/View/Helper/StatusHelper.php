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

class StatusHelper extends AppHelper
{

    public function humanHostStatus($state = 2, $href = 'javascript:void(0)')
    {
        switch ($state) {
            case 0:
                return ['state' => 0, 'human_state' => __('Ok'), 'html_icon' => '<a href="'.$href.'" class="btn btn-success status-circle"></a>', 'icon' => 'glyphicon glyphicon-ok'];
                break;

            case 1:
                return ['state' => 1, 'human_state' => __('Down'), 'html_icon' => '<a href="'.$href.'" class="btn btn-danger status-circle" ></a>', 'icon' => 'fa fa-exclamation'];
                break;

            default:
                return ['state' => 2, 'human_state' => __('Unreachable'), 'html_icon' => '<a href="'.$href.'" class="btn btn-default status-circle" ></a>', 'icon' => 'fa fa-warning'];
        }
    }

    public function humanServiceStatus($state = 3, $href = 'javascript:void(0)')
    {
        switch ($state) {
            case 0:
                return ['state' => 0, 'human_state' => __('Ok'), 'html_icon' => '<a href="'.$href.'" class="btn btn-success status-circle"></a>', 'icon' => 'glyphicon glyphicon-ok'];
                break;

            case 1:
                return ['state' => 1, 'human_state' => __('Warning'), 'html_icon' => '<a href="'.$href.'" class="btn btn-warning status-circle" ></a>', 'icon' => 'fa fa-exclamation'];
                break;
            case 2:
                return ['state' => 2, 'human_state' => __('Critical'), 'html_icon' => '<a href="'.$href.'" class="btn btn-danger status-circle" ></a>', 'icon' => 'fa fa-exclamation'];
                break;
            default:
                return ['state' => 3, 'human_state' => __('Unreachable'), 'html_icon' => '<a href="'.$href.'" class="btn btn-default status-circle" ></a>', 'icon' => 'fa fa-warning'];
        }
    }

    function HostStatusColor($state = 2)
    {
        switch ($state) {
            case 0:
                return 'txt-color-green';

            case 1:
                return 'txt-color-red';

            default:
                return 'txt-color-blueLight';
        }
    }

    function ServiceStatusColor($state = 2)
    {
        switch ($state) {
            case 0:
                return 'txt-color-green';

            case 1:
                return 'txt-color-orangeDark';

            case 2:
                return 'txt-color-red';

            default:
                return 'txt-color-blueLight';
        }
    }
}