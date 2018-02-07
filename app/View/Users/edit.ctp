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
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-user fa-fw "></i>
            <?php echo __('Administration'); ?>
            <span>>
                <?php echo __('Manage Users'); ?>
			</span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-user"></i> </span>
        <h2><?php echo $this->action == 'edit' ? __('Edit') : __('Add') ?><?php echo __('User'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('delete')): ?>
                <?php echo $this->Utils->deleteButton(null, $permissionsUser['User']['id']); ?>
            <?php endif; ?>
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            $readonly = false;
            $disabled = false;
            $help = null;
            if ($type == 'ldap') {
                $readonly = true;
                $disabled = true;
                $help = __('This field is not required, due to LDAP');
            }

            echo $this->Form->create('User', [
                'class' => 'form-horizontal clear',
            ]);
            if ($this->action == 'edit') {
                echo $this->Form->input('id');
            }

            /*echo $this->Form->input('container_id', array(
                'label' => 'Tenant',
                'options' => $tenants,
                'class' => 'select2 chosen',
                'style' => 'width: 100%'
            ));*/
            echo $this->Form->input('Container', [
                'label'    => __('Container'),
                'options'  => $containers,
                'class'    => 'select2 chosen',
                'style'    => 'width: 100%',
                'multiple' => true,
                'selected' => $selectedContainers,
            ]);
            ?>
            <div id="rightLevels" class="required col-lg-offset-2 padding-10 hidden">
            </div>
            <?php
            echo $this->Form->input('usergroup_id', [
                'label'   => __('User role'),
                'options' => $usergroups,
                'class'   => 'select2 chosen',
                'style'   => 'width: 100%'
                //'default' =>
            ]);
            echo $this->Form->input('status', [
                'label'   => __('Status'),
                'options' => User::getStates(),
                'class'   => 'select2 chosen',
                'style'   => 'width: 100%',
            ]);

            if ($type == 'ldap'):
                echo $this->Form->input('samaccountname', [
                    'label'    => 'Username',
                    'readonly' => $readonly,
                    'help'     => __('This is the username, you need to for the login!'),
                ]);
            endif;

            echo $this->Form->input('email', [
                'label' => __('Email Address'),
            ]);
            echo $this->Form->input('firstname', [
                'label' => __('First name'),
            ]);
            echo $this->Form->input('lastname', [
                'label' => __('Last name'),
            ]);
            echo $this->Form->input('company', [
                'label' => __('Company'),
            ]);
            echo $this->Form->input('position', [
                'label' => __('Company Position'),
            ]);

            echo $this->Form->input('phone', [
                'label' => __('Phone Number'),
            ]);
            echo $this->Form->input('linkedin_id', [
                'type'  => 'text',
                'label' => __('LinkedIn ID'),
            ]);
            echo $this->Form->input('new_password', [
                'label'    => 'New Password',
                'type'     => 'password',
                'disabled' => $disabled,
                'help'     => $help,
            ]);
            echo $this->Form->input('confirm_new_password', [
                'label'    => 'Confirm new Password',
                'type'     => 'password',
                'disabled' => $disabled,
                'help'     => $help,
            ]);
            echo $this->Form->formActions();
            ?>
        </div>
    </div>
</div>

