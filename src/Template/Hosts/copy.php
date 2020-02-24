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
            <i class="fa fa-clock-o fa-fw "></i>
            <?php echo __('Hosts'); ?>
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
            <?php echo __('Copy hosts/s'); ?>
        </h2>
        <div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'hosts')): ?>
                <a class="btn btn-default" ui-sref="HostsIndex">
                    <i class="fa fa-arrow-left"></i>
                    <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <div class="row form-horizontal" ng-repeat="sourceHost in sourceHosts">
                <div class="col-xs-12 col-md-9 col-lg-7">
                    <fieldset>
                        <legend>
                            <span class="text-info"><?php echo __('Source host:'); ?></span>
                            {{sourceHost.Source.name}}
                            <span class="italic">({{sourceHost.Source.address}})</span>
                        </legend>

                        <div class="form-group required" ng-class="{'has-error': sourceHost.Error.name}">
                            <label for="Host{{$index}}Name" class="col col-md-2 control-label">
                                <?php echo('Host name'); ?>
                            </label>
                            <div class="col col-xs-10 required">
                                <input
                                    class="form-control"
                                    type="text"
                                    ng-model="sourceHost.Host.name"
                                    id="Host{{$index}}Name">
                                <span class="help-block">
                                    <?php echo __('Name of the new host'); ?>
                                </span>
                                <div ng-repeat="error in sourceHost.Error.name">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Host{{$index}}Description" class="col col-md-2 control-label">
                                <?php echo('Description'); ?>
                            </label>
                            <div class="col col-xs-10">
                                <input
                                    class="form-control"
                                    type="text"
                                    ng-model="sourceHost.Host.description"
                                    id="Host{{$index}}Description">
                            </div>
                        </div>
                        <div class="form-group required" ng-class="{'has-error': sourceHost.Error.address}">
                            <label for="Host{{$index}}Address" class="col col-md-2 control-label">
                                <?php echo('Address'); ?>
                            </label>
                            <div class="col col-xs-10 required">
                                <input
                                    class="form-control"
                                    type="text"
                                    ng-model="sourceHost.Host.address"
                                    id="Host{{$index}}Address">
                                <span class="help-block">
                                    <?php echo __('Address of the new host'); ?>
                                </span>
                                <div ng-repeat="error in sourceHost.Error.address">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="Host{{$index}}Url" class="col col-md-2 control-label">
                                <?php echo('Host URL'); ?>
                            </label>
                            <div class="col col-xs-10">
                                <input
                                    class="form-control"
                                    type="text"
                                    ng-model="sourceHost.Host.host_url"
                                    id="Host{{$index}}Url">
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
                    <?php if ($this->Acl->hasPermission('index', 'hosts')): ?>
                        <a ui-sref="HostsIndex" class="btn btn-default"><?php echo __('Cancel'); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
