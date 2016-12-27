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
        <h2><?php echo $this->action == 'edit' ? 'Edit' : 'Add' ?> User</h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton() ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('User', [
                'class' => 'form-horizontal clear',
            ]);
            if ($this->action == 'edit') {
                echo $this->Form->input('id');
            }

            echo $this->Form->input('container_id', [
                'label'   => 'Tenant',
                'options' => $tenants,
                'class'   => 'select2 chosen',
                'style'   => 'width: 100%',
            ]);
            echo $this->Form->input('role', [
                'label'   => 'Role',
                'options' => User::getRoles(),
                'class'   => 'select2 chosen',
                'style'   => 'width: 100%',
            ]);
            echo $this->Form->input('status', [
                'label'   => 'Status',
                'options' => User::getStates(),
                'class'   => 'select2 chosen',
                'style'   => 'width: 100%',
            ]);
            echo $this->Form->input('email', [
                'label' => 'Email Address',
            ]);
            echo $this->Form->input('firstname', [
                'label' => 'First name',
            ]);
            echo $this->Form->input('lastname', [
                'label' => 'Last name',
            ]);
            echo $this->Form->input('company', [
                'label' => 'Company',
            ]);
            echo $this->Form->input('position', [
                'label' => 'Company Position',
            ]);

            echo $this->Form->input('phone', [
                'label' => 'Phone Number',
            ]);
            echo $this->Form->input('linkedin_id', [
                'type'  => 'text',
                'label' => 'LinkedIn ID',
            ]);
            echo $this->Form->input('new_password', [
                'label' => 'New Password',
                'type'  => 'password',
            ]);
            echo $this->Form->input('confirm_new_password', [
                'label' => 'Confirm new Password',
                'type'  => 'password',
            ]);
            echo $this->Form->formActions(null, [
                'delete' => ($this->action == 'edit' ? $this->Form->value('User.id') : null),
            ]);
            ?>
        </div>
    </div>
</div>

