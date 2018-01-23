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
                            <div class="dd dd-nodrag containers-dd" id="nestable">
                                <ol class="dd-list" ng-repeat="container in containers">
                                    <nested-list container="container" callback="load"></nested-list>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
