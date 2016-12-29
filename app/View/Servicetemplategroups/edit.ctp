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
            <i class="fa fa-cogs fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Servicetemplategroup'); ?>
			</span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-cogs"></i> </span>
        <h2><?php echo __('Edit servicegroups'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('delete')): ?>
                <?php echo $this->Utils->deleteButton(null, $servicetemplategroup['Servicetemplategroup']['id']); ?>
            <?php endif; ?>
            <?php echo $this->Utils->backButton() ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Servicetemplategroup', [
                'class' => 'form-horizontal clear',
            ]);
            if ($hasRootPrivileges):
                echo $this->Form->input('Container.parent_id', ['options' => $containers, 'selected' => $this->request->data['Container']['parent_id'], 'class' => 'chosen', 'style' => 'width: 100%;', 'label' => __('Container')]);
            elseif (!$hasRootPrivileges && $servicetemplategroup['Container']['parent_id'] != ROOT_CONTAINER):
                echo $this->Form->input('Container.parent_id', ['options' => $containers, 'selected' => $this->request->data['Container']['parent_id'], 'class' => 'chosen', 'style' => 'width: 100%;', 'label' => __('Container')]);
            else:
                ?>
                <div class="form-group required">
                    <label class="col col-md-2 control-label"><?php echo __('Container'); ?></label>
                    <div class="col col-xs-10 required"><input type="text" value="/root" class="form-control" readonly>
                    </div>
                </div>
                <?php
                echo $this->Form->input('container_id', [
                        'value' => $servicetemplategroup['Container']['parent_id'],
                        'type'  => 'hidden',
                    ]
                );
            endif;
            echo $this->Form->input('Servicetemplategroup.id', ['type' => 'hidden', 'value' => $servicetemplategroup['Servicetemplategroup']['id']]);
            echo $this->Form->input('container_id', ['type' => 'hidden', 'value' => $servicetemplategroup['Servicetemplategroup']['container_id']]);
            echo $this->Form->input('Container.name', ['label' => __('Servicetemplategroup name'), 'value' => $this->request->data['Container']['name']]);
            echo $this->Form->input('Servicetemplategroup.Servicetemplate', ['options' => $servicetemplates, 'class' => 'chosen', 'multiple' => true, 'style' => 'width:100%;', 'label' => __('Servicetemplates'), 'data-placeholder' => __('Please choose a service'), 'selected' => $this->request->data['Servicetemplate']]);
            echo $this->Form->input('Servicetemplategroup.description', ['label' => __('Description'), 'value' => $this->request->data['Servicetemplategroup']['description']]);
            ?>
            <br/>
            <br/>
            <?php echo $this->Form->formActions(); ?>
        </div>
    </div>
</div>
