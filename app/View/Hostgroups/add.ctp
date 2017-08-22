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
            <i class="fa fa-sitemap fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Host Groups'); ?>
			</span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-sitemap"></i> </span>
        <h2><?php echo __('Add Host Group'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Hostgroup', [
                'class' => 'form-horizontal clear',
            ]);
            echo $this->Form->input('Container.parent_id', [
                'options' => $this->Html->chosenPlaceholder($containers),
                'class' => 'chosen',
                'style' => 'width: 100%;',
                'label' => __('Container'),
                'SelectionMode' => 'single',
            ]);
            echo $this->Form->input('Container.name', ['label' => __('Host Group Name')]);
            echo $this->Form->input('Hostgroup.description', ['label' => __('Description')]);
            echo $this->Form->input('hostgroup_url', ['label' => __('Host Group URL')]);
            echo $this->Form->input('Hostgroup.Host', [
                'options' => $hosts,
                'class' => 'chosen',
                'multiple' => true,
                'style' => 'width:100%;',
                'label' => __('Hosts'),
                'data-placeholder' => __('Please, start typing...'),
                'itn-ajax' => '/Hosts/ajaxGetByTerm',
                'itn-ajax-container' => '#ContainerParentId',
            ]);
            echo $this->Form->input('Hostgroup.Hosttemplate', [
                'options' => $hosttemplates,
                'class' => 'chosen',
                'multiple' => true,
                'style' => 'width:100%;',
                'label' => __('Host templates'),
                'data-placeholder' => __('Please choose a host template')
            ]);
            ?>
            <br/>
            <br/>
            <?php echo $this->Form->formActions(); ?>
        </div>
    </div>
</div>
