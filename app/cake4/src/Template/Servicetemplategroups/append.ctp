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
    <div class="col-xs-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-pencil-square-o fa-fw "></i>
            <?php echo __('Service template groups'); ?>
            <span>>
                <?php echo __('Append service templates to service template group'); ?>
            </span>
        </h1>
    </div>
</div>



<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2><?php echo __('Append to service template group'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <a back-button fallback-state='ServicetemplategroupsIndex' class="btn btn-default btn-xs">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal"
                  ng-init="successMessage=
            {objectName : '<?php echo __('Service template group'); ?>' , message: '<?php echo __('saved successfully'); ?>'}">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="row">

                            <div class="form-group required" ng-class="{'has-error': errors.servicetemplategroup}">
                                <label class="col col-md-2 control-label">
                                    <?php echo __('Service template group'); ?>
                                </label>
                                <div class="col col-xs-10">
                                    <select
                                        id="ServicetemplategroupSelect"
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="servicetemplategroups"
                                        callback="loadServicetemplategroups"
                                        ng-options="servicetemplategroup.key as servicetemplategroup.value for servicetemplategroup in servicetemplategroups"
                                        ng-model="post.Servicetemplategroup.id">
                                    </select>
                                    <div class="text-danger" ng-show="errors.servicetemplategroup">
                                        {{errors.servicetemplategroup}}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="text-info">
                            <i class="fa fa-info-circle"></i>
                            <?php echo __('Please notice:'); ?>
                            <?php echo __('Service templates that could not be assigned to the selected service tempalte group due to container permissions, will be removed automatically'); ?>
                        </div>
                    </div>


                    <div class="col-xs-12 margin-top-10 margin-bottom-10">
                        <div class="well formactions ">
                            <div class="pull-right">

                                <input class="btn btn-primary" type="submit"
                                       value="<?php echo __('Update service template group'); ?>">

                                <a back-button fallback-state='ServicetemplategroupsIndex'
                                   class="btn btn-default"><?php echo __('Cancel'); ?></a>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
