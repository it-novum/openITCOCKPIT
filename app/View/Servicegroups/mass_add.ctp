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
                <?php echo __('Append'); ?> <?php echo $this->Utils->pluralize($servicesToAppend, __('service'), __('services')); ?> <?php echo __('to servicegroup'); ?>
			</span>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('Append'); ?><?php echo $this->Utils->pluralize($servicesToAppend, __('service'), __('services')); ?><?php echo __('to servicegroup'); ?></h2>
        <div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
            <?php echo $this->Utils->backButton(__('Back'), $back_url); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Servicegroup', [
                'class' => 'form-horizontal clear',
            ]);

            echo $this->Form->input('Servicegroup.id', [
                'options'          => $this->Html->chosenPlaceholder($servicegroups),
                'data-placeholder' => __('Please select...'),
                'class'            => 'chosen',
                'wrapInput'        => 'col col-xs-7',
                'style'            => 'width: 100%',
                'label'            => [
                    'class' => 'col col-xs-1 control-label',
                    'text'  => __('Servicegroup'),
                ],
            ]); ?>
            <div class="row">
                <div class="col-xs-12 col-md-9 col-lg-7">
                    <i class="fa fa-plus text-success"></i>
                    <strong><?php echo __('The following hosts will append to selected servicegroup'); ?>:</strong>
                    <ul>
                        <?php foreach ($servicesToAppend as $serviceToAppend): ?>
                            <li>
                                <?php
                                $serviceName = $serviceToAppend['Service']['name'];
                                if ($serviceToAppend['Service']['name'] == null || $serviceToAppend['Service']['name'] == '') {
                                    $serviceName = $serviceToAppend['Servicetemplate']['name'];
                                }

                                echo h($serviceName);
                                echo $this->Form->input('Service.id.' . $serviceToAppend['Service']['id'], ['value' => $serviceToAppend['Service']['id'], 'type' => 'hidden']);
                                ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div> <!-- close col -->
            </div> <!-- close row-->
            <br/>
            <?php echo $this->Form->formActions(); ?>
        </div> <!-- close widget body -->
    </div>
</div> <!-- end jarviswidget -->
