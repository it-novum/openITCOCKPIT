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
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-file-text-o fa-fw "></i>
            <?php echo __('Edit configuration file'); ?>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="alert alert-info">
            <i class="fa-fw fa fa-info"></i>
            <?php echo __('Configuration changes will be applied automatically with the next schedule of "ConfigGenerator" cron job.'); ?>
        </div>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-file-text-o"></i> </span>
        <h2>
            <?php echo __('Edit configuration file'); ?>
            {{ ConfigFile.linkedOutfile }}
        </h2>

        <div class="widget-toolbar" role="menu">
            <button type="button" class="btn btn-xs btn-primary" ng-click="askRestoreDefault()">
                <i class="fa fa-recycle"></i>
                <?php echo __('Restore default'); ?>
            </button>
        </div>

        <div class="widget-toolbar" role="menu">
            <a back-button fallback-state='ConfigurationFilesIndex' class="btn btn-default btn-xs">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
            </a>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <!-- Loading used AngularJs directives dynamically -->
            <ng-include
                    ng-if="ConfigFile.angularDirective"
                    src="'/ConfigurationFiles/dynamicDirective?directive='+ConfigFile.angularDirective"></ng-include>
        </div>
    </div>
</div>

<div id="angularConfirmRestoreDefault" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary txt-color-white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('Attention!'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <?php echo __('Do you really want to restore default configuration? All manual changes will be lost.'); ?>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" ng-click="restoreDefault(ConfigFile.dbKey)">
                    <i class="fa fa-refresh fa-spin" ng-show="isRestoring"></i>
                    <?php echo __('Restore default'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
        </div>

    </div>
</div>