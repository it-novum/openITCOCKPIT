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
            <i class="fa fa-retweet fa-fw "></i>
            <?php echo __('Map'); ?>
            <span>>
                <?php echo __('Rotation'); ?>
			</span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-retweet"></i> </span>
        <h2><?php echo __('Edit map rotation'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(); ?>
            <?php echo $this->Utils->deleteButton(null, $rotation['Rotation']['id']); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <?php
                    echo $this->Form->create('Rotation', [
                        'class' => 'form-horizontal clear',
                    ]);

                    echo $this->Form->input('id', [
                        'type'  => 'hidden',
                        'value' => $rotation['Rotation']['id'],
                    ]);

                    echo $this->Form->input('name', [
                        'label'     => ['text' => __('Name'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                        'wrapInput' => 'col-xs-10 col-md-10 col-lg-10',
                        'value'     => $rotation['Rotation']['name'],
                    ]);

                    echo $this->Form->input('interval', [
                        'label'     => ['text' => __('Interval'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                        'wrapInput' => 'col-xs-10 col-md-10 col-lg-10',
                        'value'     => $rotation['Rotation']['interval'],
                        'help'      => __('Interval in seconds'),
                    ]);

                    echo $this->Form->input('Rotation.Map', [
                        'options'   => $maps,
                        'multiple'  => true,
                        'class'     => 'chosen',
                        'style'     => 'width:100%;',
                        'label'     => ['text' => __('Maps'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                        'wrapInput' => 'col-xs-10 col-md-10 col-lg-10',
                        'selected'  => Hash::extract($rotation['Map'], '{n}.id'),
                    ]);
                    ?>
                </div>
            </div>
            <?php echo $this->Form->formActions(); ?>
        </div>
    </div>
</div>

