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
            <i class="fa fa-sitemap fa-rotate-270 fa-fw "></i>
            <?php echo __('Host'); ?>
            <span>>
                <?php echo __('Shared containers'); ?>
            </span>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-sitemap fa-rotate-270"></i> </span>
        <h2>
            <?php echo __('Sharing for'); ?>:
            {{host.name}}
        </h2>
        <div class="widget-toolbar" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'hosts')): ?>
                <a back-button fallback-state='HostsIndex' class="btn btn-default btn-xs">
                    <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
        </div>

        <div class="widget-toolbar text-muted cursor-default hidden-xs hidden-sm hidden-md">
            UUID: {{host.uuid}}
        </div>

    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal" ng-init="successMessage=
            {objectName : '<?php echo __('Host sharing'); ?>' , message: '<?php echo __('edit successfully'); ?>'}">
                <div class="row">

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="row">

                            <fieldset>
                                <legend>
                                    <span class="text-info"><?php echo __('Host:'); ?></span>
                                    {{host.name}}
                                </legend>
                                <div class="form-group required" ng-class="{'has-error': errors.container_id}">
                                    <label class="col-xs-12 col-lg-2 control-label">
                                        <?php echo __('Primary container'); ?>
                                    </label>
                                    <div class="col-xs-12 col-lg-10">
                                        <select
                                                id="HostContainers"
                                                data-placeholder="<?php echo __('Please choose'); ?>"
                                                class="form-control"
                                                chosen="containers"
                                                disabled="disabled"
                                                ng-options="container.key as container.value for container in primaryContainerPathSelect"
                                                ng-model="host.container_id">
                                        </select>

                                        <div class="text-info">
                                            <i class="fa fa-info-circle"></i>
                                            <?php echo __('Due to dependencies it is not possible to change the primary container in this view.'); ?>
                                        </div>

                                        <div ng-repeat="error in errors.container_id">
                                            <div class="help-block text-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" ng-class="{'has-error': errors.container_id}">
                                    <label class="col-xs-12 col-lg-2 control-label">
                                        <?php echo __('Shared containers'); ?>
                                    </label>
                                    <div class="col-xs-12 col-lg-10">
                                        <select
                                                id="HostSharedContainers"
                                                data-placeholder="<?php echo __('Please choose'); ?>"
                                                class="form-control"
                                                chosen="sharingContainers"
                                                multiple
                                                ng-options="container.key as container.value for container in sharingContainers"
                                                ng-model="post.Host.hosts_to_containers_sharing._ids">
                                        </select>
                                        <div ng-repeat="error in errors.container_id">
                                            <div class="help-block text-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                        </div> <!-- /row -->
                    </div> <!-- /col -->


                    <div class="col-xs-12 margin-top-10 margin-bottom-10">
                        <div class="well formactions ">
                            <div class="pull-right">

                                <button type="submit" class="btn btn-primary">
                                    <?php echo __('Update sharing'); ?>
                                </button>

                                <a back-button fallback-state='HostsIndex'
                                   class="btn btn-default"><?php echo __('Cancel'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
