<?php
// Copyright (C) <2015>  <it-novum GmbH>
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
    <div class="col-xs-12 col-lg-10">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-database fa-fw "></i>
            <?php echo __('Databases query log'); ?>
        </h1>
    </div>
</div>

<article class="row">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget" role="widget">
            <header role="heading">
                <h2>
                    <i class="fa fa-database"></i>
                    <?php echo __('Databases query log'); ?>
                </h2>

                <div class="widget-toolbar text-success" role="menu" ng-show="connected">
                    <i class="fa fa-check"></i>
                    <?php echo __('Successfully connected'); ?>
                </div>

                <div class="widget-toolbar" role="menu">
                    <button class="btn btn-default btn-xs" ng-click="truncate()">
                        <i class="fa fa-trash-o"></i>
                        <?php echo __('Truncate log'); ?>
                    </button>
                </div>

                <div class="widget-toolbar text-info" role="menu">
                    <?php echo __('Used query log slots'); ?>
                    ({{queryLog.length}}/15)
                </div>
            </header>

            <div role="content">

                <div class="widget-body">

                    <div class="alert alert-danger alert-block" ng-show="connectionError">
                        <a href="#" data-dismiss="alert" class="close">×</a><h5 class="alert-heading"><i
                                    class="fa fa-warning"></i> <?php echo __('Connection error'); ?></h5>
                        <?php echo __('Could not connect to Query Log WebSocket Server. Did you execute'); ?>
                        <code>oitc query_log --websocket-server --pretty --hide-acl</code>?
                    </div>

                    <div ng-repeat="queries in queryLog" class="margin-bottom-10">
                        <div class="row">
                            <div class="col-xs-12 text-info">
                                <h2>
                                    Database "{{queries.datasource}}"
                                    {{queries.count}} queries took {{queries.time}} ms
                                </h2>
                            </div>
                        </div>

                        <div class="row bold">
                            <div class="col-xs-12 col-md-9">
                                Query
                            </div>
                            <div class="col-xs-12 col-md-1">
                                Affected
                            </div>
                            <div class="col-xs-12 col-md-1">
                                num. Rows
                            </div>
                            <div class="col-xs-12 col-md-1">
                                Took ms
                            </div>
                        </div>
                        <div class="row" ng-repeat="query in queries.queries">
                            <div ng-bind-html="query.query | trustAsHtml" class="col-xs-12 col-md-9"></div>
                            <div class="col-xs-12 col-md-1">
                                {{query.affected}}
                            </div>
                            <div class="col-xs-12 col-md-1">
                                {{query.numRows}}
                            </div>
                            <div class="col-xs-12 col-md-1">
                                {{query.took}}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </article>
</article>