<?php
// Copyright (C) <2019>  <it-novum GmbH>
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

                        <select id="TenantSelect"
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
                <div class="col-sm-12 col-lg-12">
                    <div class="jarviswidget" id="wid-id-0">

                        <header>
                            <span class="widget-icon"> <i class="fa fa-book"></i> </span>
                            <h2><?php echo __('Legend'); ?>:</h2>
                        </header>

                        <div class="padding-bottom-10">
                            <div class="col col-xs-12 col-md-2 col-lg-3">
                                <i class="fa fa-globe"></i> <?php echo __('Global'); ?>
                            </div>
                            <div class="col col-xs-12 col-md-2 col-lg-3">
                                <i class="fa fa-home"></i> <?php echo __('Tenant'); ?>
                            </div>
                            <div class="col col-xs-12 col-md-2 col-lg-3">
                                <i class="fa fa-location-arrow"></i> <?php echo __('Location'); ?>
                            </div>
                            <div class="col col-xs-12 col-md-2 col-lg-3">
                                <i class="fa fa-link"></i> <?php echo __('Node'); ?>
                            </div>
                            <div class="col col-xs-12 col-md-2 col-lg-3">
                                <i class="fa fa-users"></i> <?php echo __('Contactgroup'); ?>
                            </div>
                            <div class="col col-xs-12 col-md-2 col-lg-3">
                                <i class="fa fa-sitemap"></i> <?php echo __('Hostgroup'); ?>
                            </div>
                            <div class="col col-xs-12 col-md-2 col-lg-3">
                                <i class="fa fa-cogs"></i> <?php echo __('Servicegroup'); ?>
                            </div>
                            <div class="col col-xs-12 col-md-2 col-lg-3">
                                <i class="fa fa-pencil-square-o"></i> <?php echo __('Servicetemplategroup'); ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <div class="jarviswidget" id="wid-id-0">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-link"></i> </span>
                            <h2><?php echo __('Tree'); ?></h2>
                        </header>

                        <div class="widget-body">
                            <div ng-if="containers" ng-nestable ng-model="containers">
                                <div>
                                    <i class="fa fa-globe"
                                       ng-if="$Container.containertype_id == <?php echo CT_GLOBAL; ?>"></i>
                                    <i class="fa fa-home"
                                       ng-if="$Container.containertype_id == <?php echo CT_TENANT; ?>"></i>
                                    <i class="fa fa-location-arrow"
                                       ng-if="$Container.containertype_id == <?php echo CT_LOCATION; ?>"></i>
                                    <i class="fa fa-link"
                                       ng-if="$Container.containertype_id == <?php echo CT_NODE; ?>"></i>
                                    <i class="fa fa-users"
                                       ng-if="$Container.containertype_id == <?php echo CT_CONTACTGROUP; ?>"></i>
                                    <i class="fa fa-sitemap"
                                       ng-if="$Container.containertype_id == <?php echo CT_HOSTGROUP; ?>"></i>
                                    <i class="fa fa-cogs"
                                       ng-if="$Container.containertype_id == <?php echo CT_SERVICEGROUP; ?>"></i>
                                    <i class="fa fa-pencil-square-o"
                                       ng-if="$Container.containertype_id == <?php echo CT_SERVICETEMPLATEGROUP; ?>"></i>

                                    <div class="nodes-container-name" title="{{ $Container.name }}">
                                        <span class="ellipsis"">{{ $Container.name }}</span>
                                    </div>

                                    <?php if ($this->Acl->hasPermission('edit', 'containers')): ?>
                                        <a ng-if="$Container.allow_edit === true && $Container.containertype_id == <?php echo CT_NODE; ?>"
                                           class="txt-color-red padding-left-10 font-xs pointer"
                                           ng-click="openEditNode($Container)"
                                        >
                                            <i class="fa fa-pencil"></i>
                                            <?php echo __('Edit'); ?>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($this->Acl->hasPermission('add', 'containers')): ?>
                                        <a ng-if="$Container.allow_edit === true && ($Container.containertype_id == <?php echo CT_NODE; ?> ||
                                                  $Container.containertype_id == <?php echo CT_TENANT; ?> ||
                                                  $Container.containertype_id == <?php echo CT_LOCATION; ?>)"
                                           class="txt-color-green padding-left-10 font-xs pointer"
                                           ng-click="openAddNode($Container.id)"
                                        >
                                            <i class="fa fa-plus"></i>
                                            <?php echo __('Add'); ?>
                                        </a>
                                    <?php endif; ?>

                                    <?php if ($this->Acl->hasPermission('showDetails', 'containers')): ?>
                                        <a ng-if="$Container.allow_edit === true && ($Container.containertype_id == <?php echo CT_NODE; ?> ||
                                                  $Container.containertype_id == <?php echo CT_TENANT; ?> ||
                                                  $Container.containertype_id == <?php echo CT_LOCATION; ?>)"
                                           class="text-info padding-left-10 font-xs pointer"
                                           href="/containers/showDetails/{{ $Container.id }}"
                                           target="_blank"
                                        >
                                            <i class="fa fa-info"></i>
                                            <?php echo __('Show details'); ?>
                                        </a>
                                    <?php endif; ?>


                                    <i class="note pull-right" ng-if="(($Container.rght-$Container.lft)/2-0.5) == 0">empty</i>
                                    <span class="badge bg-color-blue txt-color-white pull-right"
                                          ng-if="(($Container.rght-$Container.lft)/2-0.5) > 0">{{ ($Container.rght-$Container.lft)/2-0.5 }}</span>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="angularEditNodeModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form onsubmit="return false;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo __('Edit Node'); ?></h4>
                </div>
                <div class="modal-body" ng-class="{'has-error': errors.name}">
                    <div class="row">
                        <div class="col-xs-2">
                            <label class="control-label">
                                <?php echo __('Edit node name: '); ?>
                            </label>
                        </div>
                        <div class="col-xs-10">
                            <input type="text"
                                   class="form-control"
                                   maxlength="255"
                                   required="required"
                                   placeholder="<?php echo __('Node name'); ?>"
                                   ng-model="edit.Container.name"
                            >
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" ng-click="deleteNode()"
                            ng-class="{'has-error': errors.id}">
                        <i class="fa fa-refresh fa-spin" ng-show="isDeleting"></i>
                        <?php echo __('Delete'); ?>
                    </button>
                    <div class="pull-left" ng-repeat="error in errors.id">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                    <button type="submit" class="btn btn-primary" ng-click="updateNode()">
                        <?php echo __('Save'); ?>
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?php echo __('Cancel'); ?>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<div id="angularAddNodeModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form onsubmit="return false;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo __('Add new Node'); ?></h4>
                </div>
                <div class="modal-body" ng-class="{'has-error': errors.name}">
                    <div class="row">
                        <div class="col-xs-2">
                            <label class="control-label">
                                <?php echo __('New node name: '); ?>
                            </label>
                        </div>
                        <div class="col-xs-10">
                            <input type="text"
                                   class="form-control"
                                   maxlength="255"
                                   required="required"
                                   placeholder="<?php echo __('Node name'); ?>"
                                   ng-model="add.Container.name"
                            >
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="modal-footer">
                    <div class="pull-left" ng-repeat="error in errors.id">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                    <button type="submit" class="btn btn-primary" ng-click="saveNewNode()">
                        <?php echo __('Save'); ?>
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?php echo __('Cancel'); ?>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>