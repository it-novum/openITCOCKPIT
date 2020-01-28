<?php
// Copyright (C) <2018>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-cogs fa-fw "></i>
            <?php echo __('Service groups'); ?>
            <span>>
                <?php echo __('Append services to service group'); ?>
            </span>
        </h1>
    </div>
</div>



<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-sitemap"></i> </span>
        <h2><?php echo __('Append to service group'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <a back-button fallback-state='ServicegroupsIndex' class="btn btn-default btn-xs">
                <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal"
                  ng-init="successMessage=
            {objectName : '<?php echo __('Service group'); ?>' , message: '<?php echo __('saved successfully'); ?>'}">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="row">

                            <div class="form-group required" ng-class="{'has-error': errors.servicegroup}">
                                <label class="col col-md-2 control-label">
                                    <?php echo __('Service group'); ?>
                                </label>
                                <div class="col col-xs-10">
                                    <select
                                            id="ServicegroupSelect"
                                            data-placeholder="<?php echo __('Please choose'); ?>"
                                            class="form-control"
                                            chosen="servicegroups"
                                            callback="loadServicegroups"
                                            ng-options="servicegroups.key as servicegroups.value for servicegroups in servicegroups"
                                            ng-model="post.Servicegroup.id">
                                    </select>
                                    <div class="text-danger" ng-show="errors.servicegroup">
                                        {{errors.servicegroup}}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="text-info">
                            <i class="fa fa-info-circle"></i>
                            <?php echo __('Please notice:'); ?>
                            <?php echo __('Services that could not be assigned to the selected service group due to container permissions, will be removed automatically'); ?>
                        </div>
                    </div>


                    <div class="col-xs-12 margin-top-10 margin-bottom-10">
                        <div class="well formactions ">
                            <div class="pull-right">

                                <input class="btn btn-primary" type="submit"
                                       value="<?php echo __('Update service group'); ?>">

                                <a back-button fallback-state='ServicegroupsIndex'
                                   class="btn btn-default"><?php echo __('Cancel'); ?></a>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
