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
$timezones = CakeTime::listTimezones();
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-link fa-fw "></i>
            <?php echo __('System'); ?>
            <span>>
                <?php echo __('Containers'); ?>
            </span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>


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
                        <?php echo __('Container'); ?>
                    </label>
                    <div class="col col-xs-11">
                        <select
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="containers"
                                ng-model="selectedContainer.id"
                                ng-options="container.key as container.value for container in containers">
                        </select>
                    </div>
                </div>
            </div>
            <div>
                <span class="ajax_loader text-center">
                    <h1>
                        <i class="fa fa-cog fa-lg fa-spin"></i>
                    </h1>
                    <br/>
                </span>
            </div>
            <div class="row padding-top-15">
                <div class="row">
                    <div class="col-sm-12 col-lg-12">
                        <div class="jarviswidget" id="wid-id-0">
                            <header>
                                <span class="widget-icon"> <i class="fa fa-link"></i> </span>
                                <h2><?php echo __('Tree'); ?></h2>
                            </header>

                            <div class="widget-body">
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
                                <div ng-if="subcontainers" ng-nestable ng-model="subcontainers">
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
                                            <span ng-if="$Container.allow_edit === true">
                                                <a ng-if="$Container.containertype_id == <?php echo CT_NODE; ?> ||
                                                    $Container.containertype_id == <?php echo CT_TENANT; ?> ||
                                                    $Container.containertype_id == <?php echo CT_LOCATION; ?>"
                                                   class="txt-color-red padding-left-10 font-xs pointer"
                                                   ng-click="openEditNodeModal($Container)">
                                                    <i class="fa fa-pencil"></i>
                                                    <?php echo __('Edit'); ?>
                                                </a>
                                            </span>
                                        <?php endif; ?>

                                        <?php if ($this->Acl->hasPermission('add', 'containers')): ?>
                                            <a ng-if="$Container.containertype_id == <?php echo CT_GLOBAL; ?> ||
                                            $Container.containertype_id == <?php echo CT_NODE; ?> ||
                                            $Container.containertype_id == <?php echo CT_TENANT; ?> ||
                                            $Container.containertype_id == <?php echo CT_LOCATION; ?>"
                                               class="txt-color-green padding-left-10 font-xs pointer"
                                               ng-click="openAddNodeModal($Container)">
                                                <i class="fa fa-plus"></i>
                                                <?php echo __('Add'); ?>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($this->Acl->hasPermission('showDetails', 'containers')): ?>
                                            <a ng-if="$Container.containertype_id == <?php echo CT_NODE; ?> ||
                                            $Container.containertype_id == <?php echo CT_TENANT; ?> ||
                                            $Container.containertype_id == <?php echo CT_LOCATION; ?>"
                                               class="text-info padding-left-10 font-xs pointer"
                                               ui-sref="ContainersShowDetails({id:$Container.id, tenant:selectedContainer.id})">
                                                <i class="fa fa-info"></i>
                                                <?php echo __('Show details'); ?>
                                            </a>
                                        <?php endif; ?>


                                        <i class="note pull-right"
                                           ng-if="(($Container.rght-$Container.lft)/2-0.5) == 0">empty</i>
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
</div>


<div id="angularAddNodeModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form onsubmit="return false;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo __('Add new container'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('Select container type'); ?>
                            </label>
                            <div class="col-xs-12" ng-show="selectedContainerTypeId == <?php echo CT_GLOBAL; ?>">
                                <select class="form-control" ng-model="post.Container.containertype_id">
                                    <?php if ($this->Acl->hasPermission('add', 'tenants')): ?>
                                        <option value="<?php echo CT_TENANT; ?>">
                                            <?php echo __('Tenant'); ?>
                                        </option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-xs-12" ng-show="selectedContainerTypeId !== <?php echo CT_GLOBAL; ?>">
                                <select class="form-control" ng-model="post.Container.containertype_id">
                                    <?php if ($this->Acl->hasPermission('add', 'locations')): ?>
                                        <option value="<?php echo CT_LOCATION; ?>">
                                            <?php echo __('Location'); ?>
                                        </option>
                                    <?php endif; ?>
                                    <?php if ($this->Acl->hasPermission('add', 'containers')): ?>
                                        <option value="<?php echo CT_NODE; ?>" selected="selected">
                                            <?php echo __('Node'); ?>
                                        </option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" ng-class="{'has-error': errors.Container.name || errors.name}" ng-show="post.Container.containertype_id==5">
                        <label class="col-xs-12 control-label">
                            <?php echo __('Name'); ?>
                        </label>
                        <div class="col-xs-12">
                            <div class="form-group smart-form">
                                <label class="input"> <i class="icon-prepend fa fa-folder-open"></i>
                                    <input type="text" class="input-sm"
                                           placeholder="<?php echo __('Container name'); ?>"
                                           ng-model="post.Container.name">
                                </label>
                                <div ng-repeat="error in errors.Container.name">
                                    <div class="help-block font-xs text-danger">{{ error }}</div>
                                </div>
                                <div ng-repeat="error in errors.name">
                                    <div class="help-block font-xs text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <fieldset class="margin-top-10" ng-show="post.Container.containertype_id==3">
                        <div class="row" ng-class="{'has-error': errors.container.name || errors.name}">
                            <label class="col-xs-12 control-label">
                                <?php echo __('Name'); ?>
                            </label>
                            <div class="col-xs-12">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-folder-open"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('Container name'); ?>"
                                               ng-model="post.Location.container.name">
                                    </label>
                                    <div ng-repeat="error in errors.container.name">
                                        <div class="help-block font-xs text-danger">{{ error }}</div>
                                    </div>
                                    <div ng-repeat="error in errors.name">
                                        <div class="help-block font-xs text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <legend><?php echo __('Optional fields for location'); ?></legend>
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('Description'); ?>
                            </label>
                            <div class="col-xs-12">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-info"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('Description'); ?>"
                                               ng-model="post.Location.description">
                                    </label>
                                    <div ng-repeat="error in errors.name">
                                        <div class="help-block font-xs text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="row" ng-class="{'has-error': errors.latitude}">
                                <label class="col-xs-12 control-label">
                                    <?php echo __('Latitude'); ?>
                                </label>
                                <div class="col-xs-12">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-map-marker"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo '50.5558095'; ?>"
                                                   ng-model="post.Location.latitude">
                                        </label>
                                        <div class="info-block-helptext font-xs">
                                            <?php echo __(' Latitude must be a number between -90 and 90 degree inclusive.'); ?>
                                        </div>
                                        <div ng-repeat="error in errors.latitude">
                                            <div class="help-block font-xs text-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" ng-class="{'has-error': errors.longitude}">
                                <label class="col-xs-12 control-label">
                                    <?php echo __('Longitude'); ?>
                                </label>
                                <div class="col-xs-12">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-map-marker"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo '9.6808449'; ?>"
                                                   ng-model="post.Location.longitude">
                                        </label>
                                        <div class="info-block-helptext font-xs">
                                            <?php echo __('Longitude must be a number -180 and 180 degree inclusive.'); ?>
                                        </div>
                                        <div ng-repeat="error in errors.longitude">
                                            <div class="help-block font-xs text-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('Timezone'); ?>
                            </label>
                            <div class="col col-xs-12">
                                <select class="form-control"
                                        chosen="{}"
                                        ng-model="post.Location.timezone">
                                    <?php foreach ($timezones as $continent => $continentTimezons): ?>
                                        <optgroup label="<?php echo h($continent); ?>">
                                            <?php foreach ($continentTimezons as $timezoneKey => $timezoneName): ?>
                                                <option value="<?php echo h($timezoneKey); ?>"><?php echo h($timezoneName); ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach;; ?>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="margin-top-10" ng-show="post.Container.containertype_id==2">
                        <div class="row" ng-class="{'has-error': errors.container.name || errors.name}">
                            <label class="col-xs-12 control-label">
                                <?php echo __('Name'); ?>
                            </label>
                            <div class="col-xs-12">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-folder-open"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('Container name'); ?>"
                                               ng-model="post.Tenant.container.name">
                                    </label>
                                    <div ng-repeat="error in errors.container.name">
                                        <div class="help-block font-xs text-danger">{{ error }}</div>
                                    </div>
                                    <div ng-repeat="error in errors.name">
                                        <div class="help-block font-xs text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <legend><?php echo __('Optional fields for tenant'); ?></legend>
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('Description'); ?>
                            </label>
                            <div class="col-xs-12">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-info"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('Info text ...'); ?>"
                                               ng-model="post.Tenant.description">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('First name'); ?>
                            </label>
                            <div class="col-xs-12">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('John'); ?>"
                                               ng-model="post.Tenant.firstname">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('Last name'); ?>
                            </label>
                            <div class="col-xs-12">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('Doe'); ?>"
                                               ng-model="post.Tenant.lastname">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('Street'); ?>
                            </label>
                            <div class="col-xs-12">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-road"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('Any street'); ?>"
                                               ng-model="post.Tenant.street">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('Zip code'); ?>
                            </label>
                            <div class="col-xs-12">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-building-o"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('12345'); ?>"
                                               ng-model="post.Tenant.zipcode">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('City'); ?>
                            </label>
                            <div class="col-xs-12">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-building-o"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('Any city'); ?>"
                                               ng-model="post.Tenant.city">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <div class="pull-left" ng-repeat="error in errors.id">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                    <button type="submit" class="btn btn-primary" ng-click="saveNode()"
                            ng-show="post.Container.containertype_id==<?php echo CT_NODE; ?>">
                        <?php echo __('Create new node'); ?>
                    </button>
                    <button type="submit" class="btn btn-primary" ng-click="saveTenant()"
                            ng-show="post.Container.containertype_id==<?php echo CT_TENANT; ?>">
                        <?php echo __('Create new tenant'); ?>
                    </button>
                    <button type="submit" class="btn btn-primary" ng-click="saveLocation()"
                            ng-show="post.Container.containertype_id==<?php echo CT_LOCATION; ?>">
                        <?php echo __('Create new location'); ?>
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?php echo __('Cancel'); ?>
                    </button>
                </div>
            </form>
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
                                <?php echo __('Edit node name '); ?>
                            </label>
                        </div>
                        <div class="col-xs-10">
                            <input type="text"
                                   class="form-control"
                                   maxlength="255"
                                   required="required"
                                   placeholder="<?php echo __('Node name'); ?>"
                                   ng-model="post.Container.name">
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
