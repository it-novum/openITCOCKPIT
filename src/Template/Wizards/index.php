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
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fas fa-magic"></i> <?php echo __('Wizards'); ?>
    </li>
</ol>


<div class="row">
    <div class="col-xl-12">
        <div id="widget-container" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Configuration Wizards'); ?>
                </h2>
                <div class="panel-toolbar">
                    <div class="btn-group btn-group-xs padding-top-5 margin-right-10" data-toggle="buttons">
                        <label class="btn btn-default">
                            <input class="invisible" type="checkbox" name="linux"
                                   id="linux" value="linux">
                            <i class="fab fa-linux"></i> <?= ('Linux'); ?>
                        </label>
                        <label class="btn btn-default">
                            <input class="invisible" type="checkbox" name="windows" id="windows"
                                   value="windows">
                            <i class="fab fa-windows"></i> <?= ('Windows'); ?>
                        </label>
                        <label class="btn btn-default">
                            <input class="invisible" type="checkbox" name="database" id="database"
                                   value="database">
                            <i class="fas fa-database"></i> <?= ('Database'); ?>
                        </label>
                        <label class="btn btn-default">
                            <input class="invisible" type="checkbox" name="mail" id="mail"
                                   value="mail">
                            <i class="fas fa-mail-bulk"></i> <?= ('Email'); ?>
                        </label>
                        <label class="btn btn-default">
                            <input class="invisible" type="checkbox" name="network" id="network"
                                   value="network">
                            <i class="fas fa-sitemap"></i> <?= ('Network'); ?>
                        </label>
                        <label class="btn btn-default">
                            <input class="invisible" type="checkbox" name="docker" id="docker"
                                   value="docker">
                            <i class="fab fa-docker"></i> <?= ('Docker'); ?>
                        </label>
                        <label class="btn btn-default">
                            <input class="invisible" type="checkbox" name="docker" id="macos"
                                   value="macos">
                            <i class="fab fa-apple"></i> <?= ('macOS'); ?>
                        </label>
                    </div>
                    <button class="btn btn-danger btn-xs">
                        <i class="fas fa-undo"></i> <?= ('Reset'); ?>
                    </button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-3" ng-repeat="wizard in wizards">
                            <div class="card mb-2 wizard-logo-card-height-150">
                                <div class="card-body">
                                    <a ui-sref="{{wizard.state}}" class="d-flex flex-row align-items-start">
                                        <div class="wizard-logo-image">
                                            <img src="/img/wizards/{{wizard.image}}"/>
                                        </div>
                                        <div class="ml-3">
                                            <strong class="font-md">
                                                {{wizard.title}}
                                            </strong>
                                            <br>
                                            {{wizard.description}}
                                        </div>
                                    </a>
                                </div>
                                <div class="card-footer no-border border-bottom">
                                    <div
                                        class="text-muted font-italic font-xs text-right padding-top-10 notify-label-small">
                                        <i class="fas fa-tags text-muted"></i>
                                        <i ng-repeat="category in wizard.category">{{category}}{{$last ? '' : ', '}}</i>
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












