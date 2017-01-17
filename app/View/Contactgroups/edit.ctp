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
            <i class="fa fa-users fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Contactgroups'); ?>
			</span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-users"></i> </span>
        <h2><?php echo __('Edit contact group'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('delete')): ?>
                <?php echo $this->Utils->deleteButton(null, $contactgroup['Contactgroup']['id']); ?>
            <?php endif; ?>
            <?php echo $this->Utils->backButton(); ?>
        </div>
        <div class="widget-toolbar text-muted cursor-default hidden-xs hidden-sm hidden-md">
            <?php echo __('UUID: %s', h($contactgroup['Contactgroup']['uuid'])); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Contactgroup', [
                'class' => 'form-horizontal clear',
            ]);

            if ($hasRootPrivileges):
                echo $this->Form->input('Container.parent_id', [
                    'options'  => $containers,
                    'selected' => $this->request->data['Container']['parent_id'],
                    'class'    => 'chosen',
                    'style'    => 'width: 100%;',
                    'label'    => __('Container'),
                ]);
            elseif (!$hasRootPrivileges && $contactgroup['Container']['parent_id'] != ROOT_CONTAINER):
                echo $this->Form->input('Container.parent_id', [
                    'options'  => $containers,
                    'selected' => $this->request->data['Container']['parent_id'],
                    'class'    => 'chosen',
                    'style'    => 'width: 100%;',
                    'label'    => __('Container'),
                ]);
            else:
                ?>
                <div class="form-group required">
                    <label class="col col-md-2 control-label"><?php echo __('Container'); ?></label>
                    <div class="col col-xs-10 required"><input type="text" value="/root" class="form-control" readonly>
                    </div>
                </div>
                <?php
                echo $this->Form->input('Container.parent_id', [
                        'value' => $contactgroup['Container']['parent_id'],
                        'type'  => 'hidden',
                    ]
                );
            endif;

            echo $this->Form->input('id', [
                'type'  => 'hidden',
                'value' => $contactgroup['Contactgroup']['id'],
            ]);

            echo $this->Form->input('container_id', [
                'type'  => 'hidden',
                'value' => $contactgroup['Contactgroup']['container_id'],
            ]);

            echo $this->Form->input('Container.name', [
                'label' => __('Contact group name'),
                'value' => $this->request->data['Container']['name'],
            ]);

            echo $this->Form->input('Contactgroup.description', [
                'label' => __('Description'),
                'value' => $this->request->data['Contactgroup']['description'],
            ]);

            echo $this->Form->input('Contactgroup.Contact', [
                'options'          => $contacts,
                'class'            => 'chosen',
                'multiple'         => true,
                'style'            => 'width:100%;',
                'label'            => __('Contacts'),
                'data-placeholder' => __('Please choose a contact'),
                'selected'         => $this->request->data['Contactgroup']['Contact'],
            ]);
            ?>
            <br/>
            <br/>
            <?php echo $this->Form->formActions(); ?>
        </div>
    </div>
</div>
