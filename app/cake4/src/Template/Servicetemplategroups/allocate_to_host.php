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
            <?php echo __('Allocate service template group'); ?>
            <span>>
                <?php echo __('to host'); ?>
            </span>
        </h1>
    </div>
</div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2><?php echo __('Allocate service template group to host'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'servicetemplategroups')): ?>
                <a back-button fallback-state='ServicetemplategroupsIndex' class="btn btn-default btn-xs">
                    <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
        </div>
    </header>

    <div>
        <div class="widget-body" ng-init="successMessage=
            {objectName : '<?php echo __('Services'); ?>' , message: '<?php echo __('created successfully'); ?>'}">

            <div class="row form-horizontal">
                <div class="col-xs-12 col-md-9 col-lg-7 padding-top-15">
                    <div class="form-group required">
                        <label for="ServiceTemplateGroupsSelect" class="col col-md-2 control-label">
                            <?php echo('Service template group'); ?>
                        </label>
                        <div class="col col-xs-10 required">
                            <select
                                    id="ServiceTemplateGroupsSelect"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="servicetemplategroups"
                                    callback="loadServicetemplategroups"
                                    ng-options="servicetemplategroup.key as servicetemplategroup.value for servicetemplategroup in servicetemplategroups"
                                    ng-model="id">
                            </select>
                            <div ng-show="id < 1" class="warning-glow">
                                <?php echo __('Please select a service template group.'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row form-horizontal">
                <div class="col-xs-12 col-md-9 col-lg-7">
                    <fieldset>
                        <legend>
                            <?php echo __('Target host'); ?>
                        </legend>

                        <div class="form-group required">
                            <label for="ServiceHosts" class="col col-md-2 control-label">
                                <?php echo('Host'); ?>
                            </label>
                            <div class="col col-xs-10 required">
                                <select
                                        id="ServiceHosts"
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="hosts"
                                        callback="loadHosts"
                                        ng-options="host.key as host.value for host in hosts"
                                        ng-model="hostId">
                                </select>
                                <div ng-show="hostId < 1" class="warning-glow">
                                    <?php echo __('Please select a host.'); ?>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row form-horizontal" ng-show="hostId > 0">
                <div class="col-xs-12 col-md-9 col-lg-7">
                    <div class="col col-md-2 control-label">
                        <!-- Fancy layout -->
                    </div>
                    <div class="col col-xs-10">
                        <div class="text-info">
                            <i class="fa fa-info-circle"></i>
                            <?php echo __('Please notice:'); ?>
                            <?php echo __('Services which use a service template that could not be assigned to the selected host due to container permissions, will be removed automatically.'); ?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row form-horizontal" ng-show="hostId > 0">
                <div class="col-xs-12 col-md-9 col-lg-7">
                    <fieldset>
                        <legend>
                            <span><?php echo __('Service/s to deploy on target host:'); ?></span>
                        </legend>

                        <div ng-repeat="serviceToDeploy in servicesToDeploy" class="padding-bottom-5">

                            <input type="checkbox" ng-model="serviceToDeploy.createServiceOnTargetHost">

                            {{serviceToDeploy.servicetemplate.name}}
                            <span class="text-info"
                                  ng-show="serviceToDeploy.servicetemplate.description">
                                ({{serviceToDeploy.servicetemplate.description}})
                            </span>

                            <span class="text-info"
                                  ng-show="serviceToDeploy.doesServicetemplateExistsOnTargetHost"
                                  data-original-title="<?php echo __('Service already exist on selected host. Tick the box to create a duplicate.'); ?>"
                                  data-placement="right"
                                  rel="tooltip"
                                  data-container="body">
                                <i class="fa fa-info-circle"></i>
                            </span>

                            <span
                                    ng-show="serviceToDeploy.doesServicetemplateExistsOnTargetHostAndIsDisabled"
                                    data-original-title="<?php echo __('Service already exist on selected host but is disabled. Tick the box to create a duplicate.'); ?>"
                                    data-placement="right"
                                    rel="tooltip"
                                    data-container="body">
                                <i class="fa fa-plug"></i>
                            </span>

                        </div>

                        <div class="row padding-top-15">
                            <div class="col-xs-6 col-md-2 no-padding">
                                <span ng-click="selectAll()" class="pointer">
                                    <i class="fa fa-lg fa-check-square-o"></i>
                                    <?php echo __('Select all'); ?>
                                </span>
                            </div>
                            <div class="col-xs-6 col-md-2 no-padding">
                                <span ng-click="undoSelection()" class="pointer">
                                    <i class="fa fa-lg fa-square-o"></i>
                                    <?php echo __('Undo selection'); ?>
                                </span>
                            </div>
                        </div>

                        <div class="row padding-top-15"><!-- spacer--></div>

                    </fieldset>
                </div>
            </div>

            <div class="well formactions">
                <div class="pull-right">
                    <button class="btn btn-primary" ng-click="submit()">
                        <?php echo __('Allocate service template group'); ?>
                    </button>
                    <?php if ($this->Acl->hasPermission('index', 'servicetemplategroups')): ?>
                        <a back-button fallback-state='ServicetemplategroupsIndex'
                           class="btn btn-default"><?php echo __('Cancel'); ?></a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

