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
            <i class="fa fa-link fa-fw "></i>
            <?php echo __('System'); ?>
            <span>>
                <?php echo __('Nodes'); ?>
            </span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-link"></i> </span>
        <h2><?php echo __('Edit containers'); ?></h2>
        <div class="widget-toolbar" role="menu"></div>
    </header>
    <div>
        <div class="widget-body">


            <div class="row">

                <div class="form-group">
                    <label class="col col-md-1 control-label">
                        <?php echo __('Tenant'); ?>
                    </label>
                    <div class="col col-xs-11">

                        <select
                                id="TenantSelect"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="tenants"
                                ng-options="tenant.Container.id as tenant.Container.name for tenant in tenants"
                                ng-model="selectedTenant"

                        >
                        </select>

                    </div>
                </div>

            </div>

            <br/>
            <div>
                <span class="ajax_loader text-center">
                    <h1>
                        <i class="fa fa-cog fa-lg fa-spin"></i>
                    </h1>
                    <br/>
                </span>
            </div>
            <div class="row">
                <div class="col-sm-12 col-lg-6">
                    <div class="jarviswidget" id="wid-id-0">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-link"></i> </span>
                            <h2><?php echo __('Tree'); ?></h2>
                        </header>

                        <div class="widget-body">

                            <div class="dd dd-nodrag" id="nestable">

                                <ol class="dd-list"
                                    id=""
                                    ng-if="container.children.length > 0"
                                    ng-repeat="container in containers"
                                >
                                    <nested-list container="container" callback="load"></nested-list>
                                </ol>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-lg-6">
                    <?php if ($this->Acl->hasPermission('add', 'containers')): ?>
                        <div class="jarviswidget" id="wid-id-0">

                            <header>
                                <span class="widget-icon"> <i class="fa fa-link"></i> </span>
                                <h2><?php echo __('Add new node'); ?>:</h2>
                            </header>

                            <div class="widget-body">

                                <div class="form-group" ng-class="{'has-error': errors.parent_id}">
                                    <label for="TenantSelect" class="col col-md-2 control-label">
                                        <?php echo __('Parent Node'); ?>
                                    </label>
                                    <div class="col col-xs-10">
                                        <select
                                                id="TenantForNodeSelect"
                                                data-placeholder="<?php echo __('Please choose'); ?>"
                                                class="form-control"
                                                chosen="containerlist"
                                                ng-options="key as value for (key, value) in containerlist"
                                                ng-model="post.Container.parent_id"
                                        >
                                        </select>
                                        <div ng-repeat="error in errors.parent_id">
                                            <div class="help-block text-danger">{{ error }}</div>
                                        </div>
                                    </div>

                                </div>
                                <br><br>

                                <div class="form-group required" ng-class="{'has-error': errors.name}">
                                    <label class="col col-md-2 control-label">
                                        <?php echo __('Name'); ?>
                                    </label>
                                    <div class="col col-xs-10 required">

                                        <input type="text"
                                               class="form-control"
                                               maxlength="255"
                                               required="required"
                                               placeholder="<?php echo __('Node name'); ?>"
                                               ng-model="post.Container.name"
                                        >
                                        <div ng-repeat="error in errors.name">
                                            <div class="help-block text-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="well formactions ">
                                <div id="nodeCreatedFlashMessage" class="alert alert-success" style="display:none;">
                                    <?php echo __('Node created successfully'); ?>
                                </div>
                                <div class="pull-right">
                                    <input type="button"
                                           class="btn btn-primary"
                                           value="<?php echo __('Save'); ?>"
                                           ng-click="saveNewNode()"
                                    >
                                    &nbsp;
                                    <a href="/containers" class="btn btn-default">
                                        <?php echo __('Cancel'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>


                    <div class="jarviswidget" id="wid-id-0">

                        <header>
                            <span class="widget-icon"> <i class="fa fa-book"></i> </span>
                            <h2><?php echo __('Legend'); ?>:</h2>
                        </header>

                        <div class="widget-body">
                            <div class="col col-xs-12"><br>
                                <dl>
                                    <dt><i class="fa fa-globe"></i> Global</dt>
                                    <dt><i class="fa fa-home"></i> Tenant</dt>
                                    <dt><i class="fa fa-location-arrow"></i> Location</dt>
                                    <dt><i class="fa fa-link"></i> Node</dt>
                                    <dt><i class="fa fa-users"></i> Contactgroup</dt>
                                    <dt><i class="fa fa-sitemap"></i> Hostgroup</dt>
                                    <dt><i class="fa fa-cogs"></i> Servicegroup</dt>
                                    <dt><i class="fa fa-pencil-square-o"></i> Servicetemplategroup</dt>
                                </dl>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
