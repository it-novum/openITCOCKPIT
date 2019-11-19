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
            <i class="fa fa-pencil-square-o fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo $this->Utils->pluralize($hosts, __('Host'), __('Hosts')); ?>
            </span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('Copy'); ?><?php echo $this->Utils->pluralize($hosts, __('host'), __('hosts')); ?></h2>
        <div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
            <?php echo $this->Utils->backButton(__('Back'), $back_url); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Host', [
                'class' => 'form-horizontal clear',
            ]); ?>
            <?php foreach ($hosts as $key => $host): ?>
                <div class="row">
                    <div class="col-xs-12 col-md-9 col-lg-7">
                        <fieldset>
                            <legend><?php echo h($host['Host']['name']); ?></legend>
                            <?php
                            if (!isset($host['Host']['description']) || $host['Host']['description'] == null):
                                $description = $host['Hosttemplate']['description'];
                            else:
                                $description = $host['Host']['description'];
                            endif;

                            if (!isset($host['Host']['description']) || $host['Host']['description'] == null):
                                $host_url = $host['Hosttemplate']['host_url'];
                            else:
                                $host_url = $host['Host']['host_url'];
                            endif;

                            echo $this->Form->input('Host.' . $key . '.name', ['value' => $host['Host']['name'], 'label' => __('Host Name'), 'required' => true]);
                            echo $this->Form->input('Host.' . $key . '.description', ['value' => $description, 'label' => __('Description'), 'required' => false]);
                            echo $this->Form->input('Host.' . $key . '.address', ['value' => $host['Host']['address'], 'label' => __('Address'), 'required' => true]);
                            echo $this->Form->input('Host.' . $key . '.host_url', ['value' => $host_url, 'label' => __('Host URL'), 'required' => false]);
                            echo $this->Form->input('Host.' . $key . '.source', ['value' => $host['Host']['id'], 'type' => 'hidden']);
                            ?>
                        </fieldset>
                    </div>
                </div>
            <?php endforeach; ?>
            <br/>
            <?php echo $this->Form->formActions(); ?>
        </div>
    </div>
</div>

