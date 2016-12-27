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

?>
<div class="widget-body notice-body">
    <?php
    echo $this->Form->create('noticeForm', [
        'class' => 'notice_form clear',
        'id'    => '',
    ]);

    echo $this->Form->input('notice', [
        'type'  => 'textarea',
        'class' => 'notice_textarea',
        'label' => 'Your notice (Markdown allowed)',
        'value' => '',
    ]);

    $options_button = [
        'label' => 'Save',
        'class' => 'notice_save btn btn-sm btn-primary',
    ];
    echo $this->Form->end($options_button);

    ?>

    <div class="widget-notice-title"><i class="fa fa-cog "></i></div>
    <div class="widget-notice"></div>
</div>
