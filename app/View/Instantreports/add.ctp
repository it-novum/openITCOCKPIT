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
            <i class="fa fa-file-image-o fa-fw "></i>
            <?php echo __('Adhoc Reports'); ?>
            <span>>
                <?php echo __('Instant Report'); ?>
            </span>
        </h1>
    </div>
</div>
<div id="error_msg"></div>
<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2><?php echo __('Create Instant Report'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Instantreport', [
                'class' => 'form-horizontal clear',
            ]);?>
            <div class="form-group required" ng-class="{'has-error': errors.Container.id}">
                <label class="col col-md-2 control-label">
                    <?php echo __('Container'); ?>
                </label>
                <div class="col col-xs-10">
                    <select
                            id="ContainerId"
                            data-placeholder="<?php echo __('Please choose'); ?>"
                            class="form-control"
                            chosen="containers"
                            ng-options="container.key as container.value for container in containers"
                            ng-model="post.Instantreport.container_id" >
                    </select>
                    <div ng-repeat="error in errors.Container.id">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
            </div>
            <?php
            echo $this->Form->input('name', [
                'label'     => ['text' => __('Name')],
                'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
            ]);

            echo $this->Form->input('Instantreport.evaluation', [
                'before'  => '<label class="col col-md-2 text-right">'.__('Evaluation').'</label>',
                'type'    => 'radio',
                'options' => [
                    __('%s Hosts', '<i class="fa fa-desktop"></i>'),
                    __('%s Host and Services', '<i class="fa fa-cogs"></i>'),
                    __('%s Services', '<i class="fa fa-cog"></i>')
                ],
                'class'   => 'padding-right-10',
                'default' => '1'
            ]);
            
            echo $this->Form->input('Instantreport.type', [
                    'options' => [
                        '1' => 'Host groups',
                        '2' => 'Hosts',
                        '3' => 'Service groups',
                        '4' => 'Services'
                    ],
                    'class'   => 'chosen form-control',
                    'style'   => 'width: 100%',
                    'label'   => __('Type'),
                    'ng-model' => 'post.Instantreport.type'
                ]
            );?>
            <div ng-switch="post.Instantreport.type">
                <div class="form-group required" ng-class="{'has-error': errors.Hostgroup.id}" ng-switch-when="1">
                    <label class="col col-md-2 control-label">
                        <i class="fa fa-sitemap"></i>
                        <?php echo __('Host groups'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <select multiple
                                id="HostgroupId"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="hostgroups"
                                ng-options="hostgroup.Hostgroup.id as hostgroup.Container.name for hostgroup in hostgroups"
                                ng-model="post.Instantreport.hostgroup_id" >
                        </select>
                        <div ng-repeat="error in errors.Hostgroup.id">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>
                <div class="form-group required" ng-class="{'has-error': errors.Host.id}" ng-switch-when="2">
                    <label class="col col-md-2 control-label">
                        <i class="fa fa-desktop"></i>
                        <?php echo __('Hosts'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <select multiple
                                id="HostId"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="hosts"
                                ng-options="host.key as host.value for host in hosts"
                                ng-model="post.Instantreport.host_id" >
                        </select>
                        <div ng-repeat="error in errors.Host.id">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>
                <div class="form-group required" ng-class="{'has-error': errors.Servicegroup.id}" ng-switch-when="3">
                    <label class="col col-md-2 control-label">
                        <i class="fa fa-cogs"></i>
                        <?php echo __('Service groups'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <select multiple
                                id="ServicegroupId"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="servicegroups"
                                ng-options="servicegroup.Servicegroup.id as servicegroup.Container.name for servicegroup in servicegroups"
                                ng-model="post.Instantreport.servicegroup_id" >
                        </select>
                        <div ng-repeat="error in errors.Servicegroup.id">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>

                <div class="form-group required" ng-class="{'has-error': errors.Service.id}" ng-switch-when="4">
                    <label class="col col-md-2 control-label">
                        <i class="fa fa-cog"></i>
                        <?php echo __('Services'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <select multiple
                                id="ServiceId"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="services"
                                ng-options="service.key as service.value for service in services"
                                ng-model="post.Instantreport.service_id" >
                        </select>
                        <div ng-repeat="error in errors.Service.id">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
/*
            echo $this->Form->input('Instantreport.Hostgroup', [
                'div'      => 'form-group checkbox-group multiple-select select-type select-type-'.Instantreport::TYPE_HOSTGROUPS,
                'options'  => Hash::combine($hostgroups, '{n}.Hostgroup.id', '{n}.Container.name'),
                'class'    => 'chosen',
                'multiple' => true,
                'style'    => 'width:100%;',
                'label' => __('<i class="fa fa-desktop"></i> Host groups'),
                'data-placeholder' => __('Please choose a host group'),
                'wrapInput' => ['tag' => 'div', 'class' => 'col col-xs-10']
            ]);

            echo $this->Form->input('Instantreport.Servicegroup', [
                'div'      => 'form-group checkbox-group multiple-select select-type select-type-'.Instantreport::TYPE_SERVICEGROUPS,
                'options'  => Hash::combine($servicegroups, '{n}.Servicegroup.id', '{n}.Container.name'),
                'class'    => 'chosen',
                'multiple' => true,
                'style'    => 'width:100%;',
                'label'    => __('<i class="fa fa-gears"></i> Service groups'),
                'data-placeholder' => __('Please choose a service group'),
                'wrapInput'=> ['tag'   => 'div', 'class' => 'col col-xs-10']
            ]);

            echo $this->Form->input('Instantreport.Host', [
                'div'      => 'form-group checkbox-group multiple-select select-type select-type-'.Instantreport::TYPE_HOSTS,
                'options'  => $hosts,
                'class'    => 'chosen',
                'multiple' => true,
                'style'    => 'width:100%;',
                'label' => __('<i class="fa fa-desktop"></i> Hosts'),
                'data-placeholder' => __('Please choose a host'),
                'wrapInput' => ['tag' => 'div', 'class' => 'col col-xs-10'],
            ]);

            echo $this->Form->input('Instantreport.Service', [
                'div'      => 'form-group checkbox-group multiple-select select-type select-type-'.Instantreport::TYPE_SERVICES,
                'options'  => Hash::combine($services, ['%s', '{n}.Service.id'], ['%s/%s', '{n}.Host.name', '{n}.{n}.ServiceDescription'], '{n}.Host.name'),
                'class'    => 'chosen',
                'multiple' => true,
                'style'    => 'width:100%;',
                'label'    => __('<i class="fa fa-gears"></i> Services'),
                'data-placeholder' => __('Please choose a service'),
                'wrapInput'        => ['tag'   => 'div', 'class' => 'col col-xs-10'],
            ]);

*/
?>
            <div class="form-group required" ng-class="{'has-error': errors.Timeperiod.id}">
                <label class="col col-md-2 control-label">
                    <?php echo __('Timeperiod'); ?>
                </label>
                <div class="col col-xs-10">
                    <select
                            id="TimeperiodId"
                            data-placeholder="<?php echo __('Please choose'); ?>"
                            class="form-control"
                            chosen="timeperiods"
                            ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                            ng-model="post.Instantreport.timeperiod_id" >
                    </select>
                    <div ng-repeat="error in errors.Container.id">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
            </div>
            <?php

            echo $this->Form->input('Instantreport.reflection', [
                'options'          => [
                    __('soft and hard state'),
                    __('only hard state')
                ],
                'data-placeholder' => __('Please select...'),
                'class'            => 'chosen form-control',
                'label'            => __('Reflection state'),
                'style'            => 'width:100%;',
            ]);
            ?>
            <div class="form-group">
                <?php
                echo $this->Form->fancyCheckbox('Instantreport.downtimes', [
                    'caption'          => __('Consider downtimes'),
                    'wrapGridClass'    => 'col col-md-1',
                    'captionGridClass' => 'col col-md-2',
                    'captionClass'     => 'control-label',
                    'checked'          => isset($this->request->data['Instantreport']['downtimes']) && $this->request->data['Instantreport']['downtimes'] === '1',
                ]);
                ?>
            </div>
            <div class="form-group">
                <?php
                echo $this->Form->fancyCheckbox('Instantreport.summary', [
                    'caption'          => __('Summary display'),
                    'wrapGridClass'    => 'col col-md-1',
                    'captionGridClass' => 'col col-md-2',
                    'captionClass'     => 'control-label',
                    'checked'          => isset($this->request->data['Instantreport']['summary']) && $this->request->data['Instantreport']['summary'] === '1',
                ]);
                ?>
            </div>
            <div class="form-group">
                <?php
                echo $this->Form->fancyCheckbox('Instantreport.send_email', [
                    'caption'          => __('Send email'),
                    'wrapGridClass'    => 'col col-md-1',
                    'captionGridClass' => 'col col-md-2',
                    'captionClass'     => 'control-label',
                    'ng-model' => 'post.Instantreport.send_email'
                ]);
                ?>
            </div>
            <div class="send-interval-holder"  ng-if="post.Instantreport.send_email">
                <?php
                echo $this->Form->input('Instantreport.send_interval', [
                    'div'              => 'form-group',
                    'options'          => [
                        'NEVER',
                        'DAY',
                        'WEEK',
                        'MONTH',
                        'YEAR'
                    ],
                    'data-placeholder' => __('Please select...'),
                    'class'            => 'chosen form-control',
                    'label'            => __('Send interval'),
                    'style'            => 'width:100%;',
                ]);
                ?>
                <div class="form-group required" ng-class="{'has-error': errors.User.id}">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Users to send'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <select multiple
                                id="UserId"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="users"
                                ng-options="user.key as user.value for user in users"
                                ng-model="post.Instantreport.user_id" >
                        </select>
                        <div ng-repeat="error in errors.Container.id">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>

                <?php
                /*
                echo $this->Form->input('Instantreport.User', [
                    'div'      => 'form-group checkbox-group multiple-select',
                    'options'  => [],
                    'class'    => 'chosen',
                    'multiple' => true,
                    'style'    => 'width:100%;',
                    'label'    => __('Users to send'),
                    'data-placeholder' => __('Please choose users'),
                    'wrapInput'        => ['tag'   => 'div', 'class' => 'col col-xs-10']
                ]);
                */
                ?>
            </div>
        </div> <!-- close row-->
        <br/>
        <?php echo $this->Form->formActions(); ?>
    </div> <!-- close widget body -->
</div> <!-- end jarviswidget -->
