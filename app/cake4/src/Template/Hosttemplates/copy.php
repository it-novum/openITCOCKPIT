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
            <?php echo __('Host templates'); ?>
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
            <?php echo __('Copy host template/s'); ?>
        </h2>
        <div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'hosttemplates')): ?>
                <a back-button fallback-state='HosttemplatesIndex' class="btn btn-default btn-xs">
                    <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <div class="row form-horizontal" ng-repeat="sourceHosttemplate in sourceHosttemplates">
                <div class="col-xs-12 col-md-9 col-lg-7">
                    <fieldset>
                        <legend>
                            <span class="text-info"><?php echo __('Source host template:'); ?></span>
                            {{sourceHosttemplate.Source.name}}
                        </legend>

                        <div class="form-group required" ng-class="{'has-error': sourceHosttemplate.Error.name}">
                            <label for="Hosttemplate{{$index}}Name" class="col col-md-2 control-label">
                                <?php echo('Host template name'); ?>
                            </label>
                            <div class="col col-xs-10 required">
                                <input
                                        class="form-control"
                                        type="text"
                                        ng-model="sourceHosttemplate.Hosttemplate.name"
                                        id="Hosttemplate{{$index}}Name">
                                <span class="help-block">
                                    <?php echo __('Name of the new host template'); ?>
                                </span>
                                <div ng-repeat="error in sourceHosttemplate.Error.name">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" ng-class="{'has-error': sourceHosttemplate.Error.description}">
                            <label for="Hosttemplate{{$index}}Description" class="col col-md-2 control-label">
                                <?php echo('Description'); ?>
                            </label>
                            <div class="col col-xs-10">
                                <input
                                        class="form-control"
                                        type="text"
                                        ng-model="sourceHosttemplate.Hosttemplate.description"
                                        id="Hosttemplate{{$index}}Description">
                                <div ng-repeat="error in sourceHosttemplate.Error.description">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required"
                             ng-class="{'has-error': sourceHosttemplate.Error.command_id}">
                            <label for="Hosttemplate{{$index}}CommandId" class="col col-md-2 control-label">
                                <?php echo __('Check command'); ?>
                            </label>
                            <div class="col-xs-12 col-lg-10">
                                <select
                                        id="Hosttemplate{{$index}}CommandId"
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        chosen="commands"
                                        ng-options="command.key as command.value for command in commands"
                                        ng-change="loadCommandArguments(sourceHosttemplate.Source.id, sourceHosttemplate.Hosttemplate.command_id, $index)"
                                        ng-model="sourceHosttemplate.Hosttemplate.command_id">
                                </select>
                                <div class="help-block" ng-hide="sourceHosttemplate.Hosttemplate.active_checks_enabled">
                                    <?php echo __('Due to active checking is disabled, this command will only be used as freshness check command.'); ?>
                                </div>
                                <div ng-repeat="error in sourceHosttemplate.Error.command_id">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group"
                             ng-class="{'has-error': sourceHosttemplate.Error.hosttemplatecommandargumentvalues}"
                             ng-repeat="hosttemplatecommandargumentvalue in sourceHosttemplate.Hosttemplate.hosttemplatecommandargumentvalues">
                            <label class="col-xs-12 col-lg-offset-2 col-lg-2 control-label text-primary">
                                {{hosttemplatecommandargumentvalue.commandargument.human_name}}
                            </label>
                            <div class="col-xs-12 col-lg-8">
                                <input
                                        class="form-control"
                                        type="text"
                                        ng-model="hosttemplatecommandargumentvalue.value">
                                <div ng-repeat="error in sourceHosttemplate.Error.hosttemplatecommandargumentvalues">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                                <div class="help-block">
                                    {{hosttemplatecommandargumentvalue.commandargument.name}}
                                </div>
                            </div>
                        </div>

                        <div class="form-group"
                             ng-show="sourceHosttemplate.Hosttemplate.hosttemplatecommandargumentvalues.length == 0">
                            <div class="col-xs-12 col-lg-offset-2 text-info">
                                <i class="fa fa-info-circle"></i>
                                <?php echo __('This command does not have any parameters.'); ?>
                            </div>
                        </div>

                    </fieldset>
                </div>
            </div>

            <div class="well formactions ">
                <div class="pull-right">
                    <button class="btn btn-primary" ng-click="copy()">
                        <?php echo __('Copy host templates'); ?>
                    </button>
                    <?php if ($this->Acl->hasPermission('index', 'hosttemplates')): ?>
                        <a back-button fallback-state='HosttemplatesIndex'
                           class="btn btn-default"><?php echo __('Cancel'); ?></a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

