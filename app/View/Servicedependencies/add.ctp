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
                <?php echo __('Servicedependency'); ?>
			</span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-sitemap"></i> </span>
        <h2><?php echo __('Add Servicedependency'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Servicedependency', [
                'class' => 'form-horizontal clear',
            ]);

            echo $this->Form->input('Servicedependency.container_id', [
                'options'       => $this->Html->chosenPlaceholder($containers),
                'class'         => 'chosen',
                'style'         => 'width: 100%;',
                'label'         => __('Container'),
                'SelectionMode' => 'single',
            ]);

            echo $this->Form->input('Servicedependency.Service', [
                'options'          => $services,
                'class'            => 'chosen',
                'multiple'         => true,
                'style'            => 'width:100%;',
                'label'            => '<i class="fa fa-square class-default"></i> ' . __('Services'),
                'data-placeholder' => __('Please choose a service'),
                'wrapInput'        => [
                    'tag'   => 'div',
                    'class' => 'col col-xs-10',
                ],
                'target'           => '#ServicedependencyServiceDependent'
            ]);

            echo $this->Form->input('Servicedependency.ServiceDependent', [
                'options'          => $services,
                'class'            => 'chosen test',
                'multiple'         => true,
                'style'            => 'width:100%;',
                'label'            => '<i class="fa fa-square class-info"></i> ' . __('Dependent services'),
                'data-placeholder' => __('Please choose a service'),
                'wrapInput'        => [
                    'tag'   => 'div',
                    'class' => 'col col-xs-10 info',
                ],
                'target'           => '#ServicedependencyService'
            ]);

            echo $this->Form->input('Servicedependency.Servicegroup', [
                'options'          => $servicegroups,
                'class'            => 'chosen',
                'multiple'         => true,
                'style'            => 'width:100%;',
                'label'            => '<i class="fa fa-square class-default"></i> ' . __('Servicegroups'),
                'data-placeholder' => __('Please choose a servicegroup'),
                'wrapInput'        => [
                    'tag'   => 'div',
                    'class' => 'col col-xs-10',
                ],
                'target'           => '#ServicedependencyServicegroupDependent',
            ]);

            echo $this->Form->input('Servicedependency.ServicegroupDependent', [
                'options'          => $servicegroups,
                'class'            => 'chosen',
                'multiple'         => true,
                'style'            => 'width:100%;',
                'label'            => '<i class="fa fa-square class-info"></i> ' . __('Dependent Servicegroups'),
                'data-placeholder' => __('Please choose a servicegroup'),
                'wrapInput'        => [
                    'tag'   => 'div',
                    'class' => 'col col-xs-10 info',
                ],
                'target'           => '#ServicedependencyServicegroup',
            ]);

            echo $this->Form->input('Servicedependency.timeperiod_id', [
                'options'          => $this->Html->chosenPlaceholder($timeperiods),
                'class'            => 'chosen',
                'multiple'         => false,
                'style'            => 'width:100%;',
                'label'            => __('Timeperiod'),
                'data-placeholder' => __('Please choose a timeperiod'),
            ]);
            ?>
            <br/>
            <?php
            echo $this->Form->fancyCheckbox('inherits_parent', [
                'div'              => 'form-group',
                'caption'          => __('Inherits parent'),
                'wrapGridClass'    => 'col col-xs-10',
                'captionGridClass' => 'col col-md-2 no-padding',
                'captionClass'     => 'col col-md-2 control-label',
                'icon'             => '<i class="fa fa-link"></i> ',
                'checked'          => $this->CustomValidationErrors->refill('inherits_parent', false),
            ]);
            ?>
            <br class="clearfix"/>
            <fieldset>
                <legend class="font-sm">
                    <label><?php echo __('Execution failure criteria'); ?></label>
                </legend>
                <?php
                $dependency_options = [
                    'execution_fail_on_ok'       => 'fa-square txt-color-greenLight',
                    'execution_fail_on_warning'  => 'fa-square txt-color-orange',
                    'execution_fail_on_critical' => 'fa-square txt-color-redLight',
                    'execution_fail_on_unknown'  => 'fa-square txt-color-blueDark',
                    'execution_fail_on_pending'  => 'fa-square-o',
                    'execution_none'             => 'fa-minus-square-o',
                ];
                foreach ($dependency_options as $dependency_option => $icon):?>
                    <div style="border-bottom:1px solid lightGray;">
                        <?php echo $this->Form->fancyCheckbox($dependency_option, [
                            'caption' => ucfirst(preg_replace('/execution_fail_on_/', '', $dependency_option)),
                            'icon'    => '<i class="fa ' . $icon . '"></i> ',
                            'checked' => $this->CustomValidationErrors->refill($dependency_option, false),
                        ]); ?>
                        <div class="clearfix"></div>
                    </div>
                <?php endforeach; ?>
            </fieldset>
            <br class="clearfix"/>
            <fieldset>
                <legend class="font-sm">
                    <label><?php echo __('Notification failure criteria'); ?></label>
                </legend>
                <?php
                $dependency_options = [
                    'notification_fail_on_ok'       => 'fa-square txt-color-greenLight',
                    'notification_fail_on_warning'  => 'fa-square txt-color-orange',
                    'notification_fail_on_critical' => 'fa-square txt-color-redLight',
                    'notification_fail_on_unknown'  => 'fa-square txt-color-blueDark',
                    'notification_fail_on_pending'  => 'fa-square-o',
                    'notification_none'             => 'fa-minus-square-o',
                ];
                foreach ($dependency_options as $dependency_option => $icon):?>
                    <div style="border-bottom:1px solid lightGray;">
                        <?php echo $this->Form->fancyCheckbox($dependency_option, [
                            'caption' => ucfirst(preg_replace('/notification_fail_on_/', '', $dependency_option)),
                            'icon'    => '<i class="fa ' . $icon . '"></i> ',
                            'checked' => $this->CustomValidationErrors->refill($dependency_option, false),
                        ]); ?>
                        <div class="clearfix"></div>
                    </div>
                <?php endforeach; ?>
            </fieldset>
            <br/>
            <br/>
            <?php echo $this->Form->formActions(); ?>
        </div>
    </div>
</div>