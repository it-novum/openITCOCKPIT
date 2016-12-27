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
            <i class="fa fa-home fa-fw "></i>
            <?php echo __('System'); ?>
            <span>>
                <?php echo __('Tenants'); ?>
			</span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-home"></i> </span>
        <h2><?php echo $this->action == 'edit' ? 'Edit' : 'Add' ?><?php echo __('tenant'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton() ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Tenant', [
                'class' => 'form-horizontal clear',
            ]);
            echo $this->Form->input('Tenant.id', ['type' => 'hidden']);
            echo $this->Form->input('Container.name');
            echo $this->Form->input('description', ['placeholder' => __('A description is not required but helpful. You should enter one')]);
            echo $this->Form->fancyCheckbox('is_active', [
                'captionGridClass' => 'col col-md-2',
                'captionClass'     => 'control-label',
                'wrapGridClass'    => 'col col-md-10',
                'caption'          => __('is active'),
                'checked'          => true,
                'on'               => __('Yes'),
                'off'              => __('No'),
            ]);
            ?>
            <br/><br/>
            <?php
            //add two years to now for a template expire date
            $future_expire = date(PHP_DATEFORMAT, time() + (60 * 60 * 24 * 365 * 2));

            echo $this->Form->input('Tenant.expires', [
                    'value'     => $future_expire,
                    'wrapInput' => 'col col-xs-10 col-md-3',
                    'label'     => __('Expiration date'),
                    'type'      => 'text',
                ]
            ); ?>
            <br/>
            <?php
            echo $this->Form->input('firstname');
            echo $this->Form->input('lastname');
            echo $this->Form->input('street');
            echo $this->Form->input('zipcode');
            echo $this->Form->input('city');
            ?>
            <br/>
            <?php
            echo $this->Form->input('max_users', ['value' => 0, 'label' => ['class' => 'col col-md-2 control-label hintmark_before']]);
            echo $this->Form->input('max_hosts', ['value' => 0, 'label' => ['class' => 'col col-md-2 control-label hintmark_before']]);
            echo $this->Form->input('max_services', ['value' => 0, 'label' => ['class' => 'col col-md-2 control-label hintmark_before']]);
            ?>
            <span class="note hintmark_before"><?php echo __('enter 0 for infinity'); ?></span>
            <br/ ><br/>
            <?php echo $this->Form->formActions(); ?>
        </div>
    </div>
</div>