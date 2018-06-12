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
<?php
$flapDetection_settings = [
    'flap_detection_on_up'          => 'fa-square txt-color-greenLight',
    'flap_detection_on_down'        => 'fa-square txt-color-redLight',
    'flap_detection_on_unreachable' => 'fa-square txt-color-blueDark',
];
$notification_settings = [
    'notify_on_recovery'    => 'fa-square txt-color-greenLight',
    'notify_on_down'        => 'fa-square txt-color-redLight',
    'notify_on_unreachable' => 'fa-square txt-color-blueDark',
    'notify_on_flapping'    => 'fa-random',
    'notify_on_downtime'    => 'fa-clock-o',
];
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-pencil-square-o fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Host'); ?>
			</span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('Add Host'); ?></h2>
        <div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
            <?php echo $this->Utils->backButton() ?>
        </div>
        <div class="widget-toolbar" role="menu">
            <span class="onoffswitch-title" rel="tooltip" data-placement="top"
                  data-original-title="<?php echo __('auto DNS lookup'); ?>"><i class="fa fa-search"></i></span>
            <span class="onoffswitch">
                <input type="checkbox" id="autoDNSlookup" checked="checked" class="onoffswitch-checkbox"
                       name="onoffswitch">
                <label for="autoDNSlookup" class="onoffswitch-label">
                    <span data-swchoff-text="<?php echo __('Off'); ?>" data-swchon-text="<?php echo __('On'); ?>"
                          class="onoffswitch-inner"></span>
                    <span class="onoffswitch-switch"></span>
                </label>
            </span>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Host', [
                'class' => 'form-horizontal clear',
            ]); ?>
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="tab-content">
                        <!-- basic settings -->
                        <span class="note"><?php echo __('Basic configuration'); ?>:</span>

                        <div class="form-group required" ng-class="{'has-error': errors.container_id}">
                            <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                                <?php echo __('Container'); ?>
                            </label>
                            <div class="col col-xs-10 col-md-10 col-lg-10">
                                <select
                                        id="HostContainer"
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="containers"
                                        ng-options="container.key as container.value for container in containers"
                                        ng-model="post.Container.container_id"
                                        ng-change="containerSelected()"
                                >
                                </select>
                                <div ng-repeat="error in errors.container_id">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group required" ng-class="{'has-error': errors.hosttemplate_id}">
                            <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                                <?php echo __('Hosttemplate'); ?>
                            </label>
                            <div class="col col-xs-10 col-md-10 col-lg-10">
                                <select
                                        id="Hosttemplate"
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="hosttemplates"
                                        ng-options="hosttemplate.key as hosttemplate.value for hosttemplate in hosttemplates"
                                        ng-model="post.Host.hosttemplate_id">
                                </select>
                                <div ng-repeat="error in errors.hosttemplate_id">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.name}">
                            <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                                <?php echo __('Host Name'); ?>
                            </label>
                            <div class="col col-xs-10 col-md-10 col-lg-10">
                                <input
                                        class="form-control"
                                        type="text"
                                        ng-model="post.Host.name"
                                        ng-model-options="{debounce: 500}">
                                <div ng-repeat="error in errors.name">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': errors.address}">
                            <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                                <?php echo __('Address'); ?>
                            </label>
                            <div class="col col-xs-10 col-md-10 col-lg-10">
                                <input
                                        class="form-control"
                                        type="text"
                                        ng-model="post.Host.address"
                                        ng-model-options="{debounce: 500}">
                                <div ng-repeat="error in errors.address">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group required" ng-class="{'has-error': errors.Contact}">
                            <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                                <?php echo __('Contacts'); ?>
                            </label>
                            <div class="col col-xs-10 col-md-10 col-lg-10">
                                <select
                                        id="Hosttemplate"
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="contacts"
                                        ng-options="contact.key as contact.value for contact in contacts"
                                        ng-model="post.Host.Contact"
                                        multiple>
                                </select>
                                <div ng-repeat="error in errors.Contact">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group required" ng-class="{'has-error': errors.Contactgroup}">
                            <label class="col-xs-1 col-md-1 col-lg-1 control-label">
                                <?php echo __('Contactgroups'); ?>
                            </label>
                            <div class="col col-xs-10 col-md-10 col-lg-10">
                                <select
                                        id="Hosttemplate"
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="contactgroups"
                                        ng-options="contactgroup.key as contactgroup.value for contactgroup in contactgroups"
                                        ng-model="post.Host.Contactgroup"
                                        multiple>
                                </select>
                                <div ng-repeat="error in errors.Contactgroup">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>


                        <?php

                        /*       $containers = [];
                               $_hosttemplates = [];
                               $_contacts = [];
                               $_contactgroups = [];

                               echo $this->Form->input('container_id', [
                                       'options'          => $this->Html->chosenPlaceholder($containers),
                                       'data-placeholder' => __('Please select...'),
                                       'multiple'         => false,
                                       'class'            => 'chosen',
                                       'style'            => 'width: 100%',
                                       'label'            => ['text' => __('Container'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                       'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                                   ]
                               );

                               echo $this->Form->input('hosttemplate_id', [
                                   'label'            => ['text' => __('Hosttemplate'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                   'options'          => $this->Html->chosenPlaceholder($_hosttemplates),
                                   'data-placeholder' => __('Please select...'),
                                   'class'            => 'chosen',
                                   'style'            => 'width:100%;',
                                   'wrapInput'        => 'col col-xs-10 col-md-10 col-lg-10',
                               ]);
                               echo $this->Form->input('name', [
                                   'label'     => ['text' => __('Host Name'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                   'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                               ]);

                               echo $this->Form->input('address', [
                                   'label'     => ['text' => __('Address'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                   'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                               ]);

                               echo $this->Form->input('Host.Contact', [
                                   'options'   => $_contacts,
                                   'multiple'  => true,
                                   'class'     => 'chosen',
                                   'style'     => 'width:100%;',
                                   'label'     => ['text' => __('Contact'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                   'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                               ]);
                               echo $this->Form->input('Host.Contactgroup', [
                                   'options'   => $_contactgroups,
                                   'multiple'  => true,
                                   'class'     => 'chosen',
                                   'style'     => 'width:100%;',
                                   'label'     => ['text' => __('Contactgroups'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                                   'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                               ]);;
                        */ ?>
                    </div>
                </div> <!-- close col -->
            </div> <!-- close row-->
            <br/>
            <?php echo $this->Form->formActions(); ?>
        </div> <!-- close widget body -->
    </div>
</div> <!-- end jarviswidget -->
