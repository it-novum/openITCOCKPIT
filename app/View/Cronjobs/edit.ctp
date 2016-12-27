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
            <i class="fa fa-terminal fa-fw "></i>
            <?php echo __('Administration'); ?>
            <span>>
                <?php echo __(' Cronjobs'); ?>
			</span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-terminal"></i> </span>
        <h2><?php echo __('Edit cronjob'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('delete')): ?>
                <?php echo $this->Utils->deleteButton(null, $cronjob['Cronjob']['id']); ?>
            <?php endif; ?>
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <?php
                    echo $this->Form->create('Cronjob', [
                        'class' => 'form-horizontal clear',
                    ]);

                    echo $this->Form->input('id', [
                        'type'  => 'hidden',
                        'value' => $cronjob['Cronjob']['id'],
                    ]);


                    echo $this->Form->input('plugin', [
                        'label'     => ['text' => __('Plugin'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                        'wrapInput' => 'col col-xs-8',
                        'options'   => $plugins,
                        'selected'  => $cronjob['Cronjob']['plugin'],
                        'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                    ]);

                    echo $this->Form->input('task', [
                        'label'     => ['text' => __('Task'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                        'wrapInput' => 'col col-xs-8',
                        'options'   => $pluginTasks,
                        'selected'  => $cronjob['Cronjob']['task'],
                        'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                    ]);

                    echo $this->Form->input('interval', [
                        'label'     => ['text' => __('Interval'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                        'wrapInput' => 'col col-xs-8',
                        'value'     => $cronjob['Cronjob']['interval'],
                        'help'      => __('Cronjob schedule interval in minutes'),
                        'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                    ]);
                    ?>
                </div>
            </div>
            <?php echo $this->Form->formActions(); ?>
        </div>
    </div>
</div>

