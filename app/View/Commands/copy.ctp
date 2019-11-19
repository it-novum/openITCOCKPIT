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
            <?php echo __('Commands'); ?>
            <span>>
                <?php echo __('Copy'); ?>
            </span>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-copy"></i> </span>
        <h2 class="hidden-mobile hidden-tablet">
            <?php echo __('Copy command/s'); ?>
        </h2>
        <div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'commands')): ?>
                <a class="btn btn-default" ui-sref="CommandsIndex">
                    <i class="fa fa-arrow-left"></i>
                    <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <div class="row form-horizontal" ng-repeat="sourceCommand in sourceCommands">
                <div class="col-xs-12 col-md-9 col-lg-7">
                    <fieldset>
                        <legend>
                            <span class="text-info"><?php echo __('Source command:'); ?></span>
                            {{sourceCommand.Source.name}}
                        </legend>

                        <div class="form-group required" ng-class="{'has-error': sourceCommand.Error.name}">
                            <label for="Command{{$index}}Name" class="col col-md-2 control-label">
                                <?php echo('Command name'); ?>
                            </label>
                            <div class="col col-xs-10 required">
                                <input
                                        class="form-control"
                                        type="text"
                                        ng-model="sourceCommand.Command.name"
                                        id="Command{{$index}}Name">
                                <span class="help-block">
                                    <?php echo __('Name of the new command'); ?>
                                </span>
                                <div ng-repeat="error in sourceCommand.Error.name">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group required" ng-class="{'has-error': sourceCommand.Error.command_line}">
                            <label for="Command{{$index}}CommandLine" class="col col-md-2 control-label">
                                <?php echo __('Command line'); ?>
                            </label>
                            <div class="col col-xs-10 required">
                                <textarea ng-model="sourceCommand.Command.command_line"
                                          class="form-control" cols="30" rows="6"
                                          id="Command{{$index}}CommandLine"></textarea>
                                <div ng-repeat="error in sourceCommand.Error.command_line">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Command{{$index}}Description" class="col col-md-2 control-label">
                                <?php echo __('Description'); ?>
                            </label>
                            <div class="col col-xs-10">
                                <textarea ng-model="sourceCommand.Command.description"
                                          class="form-control" cols="30" rows="6"
                                          id="Command{{$index}}Description"></textarea>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="well formactions ">
                <div class="pull-right">
                    <button class="btn btn-primary" ng-click="copy()">
                        <?php echo __('Copy'); ?>
                    </button>
                    <?php if ($this->Acl->hasPermission('index', 'commands')): ?>
                        <a ui-sref="CommandsIndex" class="btn btn-default"><?php echo __('Cancel'); ?></a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>