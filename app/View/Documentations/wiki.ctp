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
            <i class="fa fa-book fa-fw "></i>
            <?php echo __('Documentation') ?>
            <span>>
                <?php echo __('Overview'); ?>
            </span>
        </h1>
    </div>
</div>


<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-book"></i> </span>
        <h2><?php echo __('Documentation'); ?></h2>
    </header>
    <div>
        <div class="widget-body">

            <div class="row">
                <div class="col-xs-12">
                    <h2><?php echo __('New to openITCOCKPIT?'); ?></h2>
                </div>
                <div class="col-xs-12">
                    <?php echo __('We recommend every new user to read our'); ?>
                    <a href="https://openitcockpit.io/beginners/" target="'_blank">
                        <?php echo __('beginners guide.'); ?>
                    </a>
                    <?php echo __('This guide provides information about the basic concept of openITCOCKPIT and how to monitor your first host.'); ?>
                </div>
                <div class="col-xs-12 text-center">
                    <a href="https://openitcockpit.io/beginners/" class="btn btn-default" target="_blank">
                        <i class="fa fa-external-link-square"></i>
                        <?php echo __('Beginners guide.'); ?>
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h2><?php echo __('Technical documentation'); ?></h2>
                </div>
                <div class="col-xs-12">
                    <?php echo __('The technical documentation of openITCOCKPIT containers detailed information about additional Modules, background processes, the usage of the JSON-API and so on.'); ?>
                </div>
                <div class="col-xs-12 text-center">
                    <div class="btn-group" role="group">
                        <a href="https://docs.it-novum.com/display/ODE" class="btn btn-default" target="_blank">
                            <i class="fa fa-external-link-square"></i>
                            <?php echo __('Technical documentation (EN)'); ?>
                        </a>

                        <a href="https://docs.it-novum.com/display/ODD" class="btn btn-default" target="_blank">
                            <i class="fa fa-external-link-square"></i>
                            <?php echo __('Technische Dokumentation (DE)'); ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h2><?php echo __('Additional help'); ?></h2>
                </div>

                <div class="col-xs-12" ng-repeat="(key, documentation) in documentations.additional_help.children">
                    <h4><a href="javascript:void(0);"
                           ng-click="showDocumentation('additional_help', key)">
                            {{documentation.name}}
                        </a></h4>
                    <div>
                        <p class="description">
                            {{documentation.description}}
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>


<div id="angularDocumentationContentModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="{{currentDocumentation.icon}}"></i>
                    {{currentDocumentation.name}}
                </h4>
            </div>
            <div class="modal-body">
                <div
                        ng-bind-html="currentDocumentationHtml | trustAsHtml"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
