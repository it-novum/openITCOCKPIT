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
            <?php echo __('Service s'); ?>
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
            <?php echo __('Copy service/s to host'); ?>
        </h2>
        <div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'services')): ?>
                <a back-button fallback-state='ServicesIndex' class="btn btn-default btn-xs">
                    <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
        </div>
    </header>

    <div>
        <div class="widget-body">

            <div class="row form-horizontal">
                <div class="col-xs-12 col-md-9 col-lg-7">
                    <fieldset>
                        <legend>
                            <?php echo __('Target host'); ?>
                        </legend>

                        <div class="form-group required">
                            <label for="ServiceHosts" class="col col-md-2 control-label">
                                <?php echo('Host'); ?>
                            </label>
                            <div class="col col-xs-10 required">
                                <select
                                    id="ServiceHosts"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="hosts"
                                    callback="loadHosts"
                                    ng-options="host.key as host.value for host in hosts"
                                    ng-model="hostId">
                                </select>
                                <div ng-show="hostId < 1" class="warning-glow">
                                    <?php echo __('Please select a host.'); ?>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="row form-horizontal" ng-show="hostId > 0">
                <div class="col-xs-12 col-md-9 col-lg-7">
                    <div class="col col-md-2 control-label">
                        <!-- Fancy layout -->
                    </div>
                    <div class="col col-xs-10">
                        <div class="text-info">
                            <i class="fa fa-info-circle"></i>
                            <?php echo __('Please notice:'); ?>
                            <?php echo __('Services which use a service template that could not be assigned to the selected host due to container permissions, will be removed automatically.'); ?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row form-horizontal" ng-repeat="sourceService in sourceServices" ng-show="hostId > 0">
                <div class="col-xs-12 col-md-9 col-lg-7">
                    <fieldset>
                        <legend>
                            <span class="text-info"><?php echo __('Source service:'); ?></span>
                            {{sourceService.Source.hostname}} / {{sourceService.Source._name}}
                        </legend>

                        <div class="form-group required" ng-class="{'has-error': sourceService.Error.name}">
                            <label for="Service{{$index}}Name" class="col col-md-2 control-label">
                                <?php echo('Service name'); ?>
                            </label>
                            <div class="col col-xs-10 required">
                                <input
                                    class="form-control"
                                    type="text"
                                    ng-model="sourceService.Service.name"
                                    id="Service{{$index}}Name">
                                <div ng-repeat="error in sourceService.Error.name">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': sourceService.Error.description}">
                            <label for="Service{{$index}}Description" class="col col-md-2 control-label">
                                <?php echo('Description'); ?>
                            </label>
                            <div class="col col-xs-10">
                                <input
                                    class="form-control"
                                    type="text"
                                    ng-model="sourceService.Service.description"
                                    id="Service{{$index}}Description">
                                <div ng-repeat="error in sourceService.Error.description">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required"
                             ng-class="{'has-error': sourceService.Error.command_id}">
                            <label for="Service{{$index}}CommandId" class="col col-md-2 control-label">
                                <?php echo __('Check command'); ?>
                            </label>
                            <div class="col-xs-12 col-lg-10">
                                <select
                                    id="Service{{$index}}CommandId"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="commands"
                                    ng-options="command.key as command.value for command in commands"
                                    ng-change="loadCommandArguments(sourceService.Source.id, sourceService.Service.command_id, $index)"
                                    ng-model="sourceService.Service.command_id">
                                </select>
                                <div class="help-block"
                                     ng-hide="sourceService.Service.active_checks_enabled">
                                    <?php echo __('Due to active checking is disabled, this command will only be used as freshness check command.'); ?>
                                </div>
                                <div ng-repeat="error in sourceService.Error.command_id">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group"
                             ng-class="{'has-error': sourceService.Error.servicecommandargumentvalues}"
                             ng-repeat="servicecommandargumentvalue in sourceService.Service.servicecommandargumentvalues">
                            <label class="col-xs-12 col-lg-offset-2 col-lg-2 control-label text-primary">
                                {{servicecommandargumentvalue.commandargument.human_name}}
                            </label>
                            <div class="col-xs-12 col-lg-8">
                                <input
                                    class="form-control"
                                    type="text"
                                    ng-model="servicecommandargumentvalue.value">
                                <div ng-repeat="error in sourceService.Error.servicecommandargumentvalues">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                                <div class="help-block">
                                    {{servicecommandargumentvalue.commandargument.name}}
                                </div>
                            </div>
                        </div>

                        <div class="form-group"
                             ng-show="sourceService.Service.servicecommandargumentvalues.length == 0">
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
                        <?php echo __('Copy services'); ?>
                    </button>
                    <?php if ($this->Acl->hasPermission('index', 'services')): ?>
                        <a back-button fallback-state='ServicesIndex'
                           class="btn btn-default"><?php echo __('Cancel'); ?></a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

