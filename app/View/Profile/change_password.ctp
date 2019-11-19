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
<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-table"></i> </span>
        <h2>Change your password</h2>
    </header>
    <div>
        <div class="widget-body">
            <?php echo $this->Form->create('User', [
                'class' => 'form-horizontal',
            ]) ?>
            <?php echo $this->Form->input('current_password', [
                'type' => 'password',
            ]) ?>
            <hr>
            <?php echo $this->Form->input('new_password', [
                'type' => 'password',
                'help' => __('The password must consist of 6 alphanumeric characters and must contain at least one digit.'),
            ]) ?>
            <?php echo $this->Form->input('new_password_repeat', [
                'type'  => 'password',
                'label' => 'Confirm your new password',
            ]) ?>

            <?php echo $this->Form->formActions('Change your password', [
                'cancelButton' => [
                    'title' => 'Cancel',
                    'url'   => '/admin/dashboard',
                ]
            ]); ?>
        </div>
    </div>
</div>