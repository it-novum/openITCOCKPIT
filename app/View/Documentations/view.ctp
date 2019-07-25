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

<host-browser-menu
        ng-if="type === 'host' && hostBrowserMenuConfig"
        config="hostBrowserMenuConfig"
        last-load-date="0"></host-browser-menu>

<service-browser-menu
        ng-if="type === 'service' && serviceBrowserMenuConfig"
        config="serviceBrowserMenuConfig"
        last-load-date="0"></service-browser-menu>

<div class="tab-content">
    <div ng-show="docu.displayView" class="tab-pane active">
        <div class="row no-padding">
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
                <div class="jarviswidget">
                    <header>
                        <span class="widget-icon">
                            <i class="fa fa-book"></i>
                        </span>
                        <h2><?php echo __('Object documentation'); ?></h2>

                        <div class="widget-toolbar pull-right" role="menu" ng-show="allowEdit">
                            <button type="button" class="btn btn-default" ng-click="showEdit()">
                                <i class="fa fa-pencil"></i> <?php echo __('Edit'); ?>
                            </button>
                        </div>

                        <div class="widget-toolbar text-muted cursor-default">
                            <?php echo __('Last update'); ?>: {{ lastUpdate }}
                        </div>
                    </header>
                    <div ng-hide="docuExists">
                        <div class="widget-body">
                            <i class="fa fa-exclamation-triangle fa-lg txt-color-red"></i>
                            <span class="italic">
                                <?php echo __('No documentation has been written yet for this object. Click on "Edit" to start writing.'); ?>
                            </span>
                        </div>
                    </div>

                    <div ng-show="docuExists">
                        <div class="widget-body">
                            <div
                                    style="word-wrap: break-word;"
                                    ng-bind-html="html | trustAsHtml"></div>
                        </div>
                    </div>


                </div>
            </article>
        </div>
    </div>

    <!-- Tab nummer 2 -->
    <div ng-show="!docu.displayView" class="tab-pane active">
        <div class="row no-padding">
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
                <div class="form-horizontal clear jarviswidget">
                    <header>
                        <div class="widget-toolbar pull-left" role="menu">
                            <div class="btn-group">
                                <a href="javascript:void(0);"
                                   class="btn btn-xs btn-default">
                                    <i class="fa fa-font"></i>
                                    <?php echo __('Font size'); ?>
                                </a>
                                <a href="javascript:void(0);" data-toggle="dropdown"
                                   class="btn btn-xs btn-default dropdown-toggle"><span
                                            class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="javascript:void(0);" select-fsize="true"
                                           fsize="xx-small"><?php echo __('Smallest'); ?></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" select-fsize="true"
                                           fsize="x-small"><?php echo __('Smaller'); ?></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" select-fsize="true"
                                           fsize="small"><?php echo __('Small'); ?></a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="javascript:void(0);" select-fsize="true"
                                           fsize="large"><?php echo __('Big'); ?></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" select-fsize="true"
                                           fsize="x-large"><?php echo __('Bigger'); ?></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" select-fsize="true"
                                           fsize="xx-large"><?php echo __('Biggest'); ?></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="widget-toolbar pull-left" style="border:0px;"
                                 role="menu">
                                <a href="javascript:void(0);"
                                   class="dropdown-toggle color-box selector bg-color-darken"
                                   id="currentColor" color="#404040"
                                   data-toggle="dropdown"></a>
                                <ul class="dropdown-menu arrow-box-up-right pull-right color-select">
                                    <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Green Grass'); ?>"
                                                                      data-placement="left" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-green"
                                                                      select-color="true" color="#356E35"
                                                                      class="bg-color-green"></span></li>
                                    <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Dark Green'); ?>"
                                                                      data-placement="top" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-greenDark"
                                                                      select-color="true" color="#496949"
                                                                      class="bg-color-greenDark"></span></li>
                                    <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Light Green'); ?>"
                                                                      data-placement="top" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-greenLight"
                                                                      select-color="true" color="#71843F"
                                                                      class="bg-color-greenLight"></span></li>
                                    <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Purple'); ?>"
                                                                      data-placement="top" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-purple"
                                                                      select-color="true" color="#6E587A"
                                                                      class="bg-color-purple"></span></li>
                                    <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Magenta'); ?>"
                                                                      data-placement="top" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-magenta"
                                                                      select-color="true" color="#6E3671"
                                                                      class="bg-color-magenta"></span></li>
                                    <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Pink'); ?>"
                                                                      data-placement="right" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-pink"
                                                                      select-color="true" color="#AC5287"
                                                                      class="bg-color-pink"></span></li>
                                    <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Fade Pink'); ?>"
                                                                      data-placement="left" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-pinkDark"
                                                                      select-color="true" color="#A8829F"
                                                                      class="bg-color-pinkDark"></span></li>
                                    <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Light Blue'); ?>"
                                                                      data-placement="top" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-blueLight"
                                                                      select-color="true" color="#92A2A8"
                                                                      class="bg-color-blueLight"></span></li>
                                    <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Teal'); ?>"
                                                                      data-placement="top" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-teal"
                                                                      select-color="true" color="#568A89"
                                                                      class="bg-color-teal"></span></li>
                                    <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Ocean Blue'); ?>"
                                                                      data-placement="top" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-blue"
                                                                      select-color="true" color="#57889C"
                                                                      class="bg-color-blue"></span></li>
                                    <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Night Sky'); ?>"
                                                                      data-placement="top" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-blueDark"
                                                                      select-color="true" color="#4C4F53"
                                                                      class="bg-color-blueDark"></span></li>
                                    <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Night'); ?>"
                                                                      data-placement="right" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-darken"
                                                                      select-color="true" color="#404040"
                                                                      class="bg-color-darken"></span></li>
                                    <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Day Light'); ?>"
                                                                      data-placement="left" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-yellow"
                                                                      select-color="true" color="#B09B5B"
                                                                      class="bg-color-yellow"></span></li>
                                    <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Orange'); ?>"
                                                                      data-placement="bottom" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-orange"
                                                                      select-color="true" color="#C79121"
                                                                      class="bg-color-orange"></span></li>
                                    <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Dark Orange'); ?>"
                                                                      data-placement="bottom" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-orangeDark"
                                                                      select-color="true" color="#A57225"
                                                                      class="bg-color-orangeDark"></span></li>
                                    <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Red Rose'); ?>"
                                                                      data-placement="bottom" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-red"
                                                                      select-color="true" color="#A90329"
                                                                      class="bg-color-red"></span></li>
                                    <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Light Red'); ?>"
                                                                      data-placement="bottom" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-redLight"
                                                                      select-color="true" color="#A65858"
                                                                      class="bg-color-redLight"></span></li>
                                    <li style="display: inline-block; margin:0; float: none;">
                                                                <span data-original-title="<?php echo __('Purity'); ?>"
                                                                      data-placement="right" rel="tooltip"
                                                                      data-widget-setstyle="jarviswidget-color-white"
                                                                      select-color="true" color="#FFFFFF"
                                                                      class="bg-color-white"></span></li>
                                </ul>
                            </div>
                            <span class="padding-left-10"></span>
                            <a href="javascript:void(0);" class="btn btn-default" wysiwyg="true"
                               task="bold"><i class="fa fa-bold"></i></a>
                            <a href="javascript:void(0);" class="btn btn-default" wysiwyg="true"
                               task="italic"><i class="fa fa-italic"></i></a>
                            <a href="javascript:void(0);" class="btn btn-default" wysiwyg="true"
                               task="underline"><i class="fa fa-underline"></i></a>
                            <span class="padding-left-10"></span>
                            <a href="javascript:void(0);" class="btn btn-default" wysiwyg="true"
                               task="left"><i class="fa fa-align-left"></i></a>
                            <a href="javascript:void(0);" class="btn btn-default" wysiwyg="true"
                               task="center"><i class="fa fa-align-center"></i></a>
                            <a href="javascript:void(0);" class="btn btn-default" wysiwyg="true"
                               task="right"><i class="fa fa-align-right"></i></a>
                            <a href="javascript:void(0);" class="btn btn-default" wysiwyg="true"
                               task="justify"><i class="fa fa-align-justify"></i></a>
                            <span class="padding-left-10"></span>
                            <a href="javascript:void(0);" class="btn btn-default" data-toggle="modal"
                               data-target="#hyerlinkModal" ng-click="prepareHyperlinkSelection()" id="insert-link"><i
                                        class="fa fa-link"></i></a>
                        </div>
                        <div class="widget-toolbar pull-right" role="menu">
                            <button type="button" class="btn btn-default" ng-click="showView()">
                                <i class="fa fa-times"></i> <?php echo __('Cancel'); ?>
                            </button>
                            <button type="button" class="btn btn-success" ng-click="saveText()">
                                <i class="fa fa-save"></i> <?php echo __('Save'); ?>
                            </button>
                        </div>
                    </header>
                    <div>
                        <div class="widget-body" ng-class="{'has-error': errors.text}">
                            <textarea class="form-control" ng-model="bbcode"
                                      style="width: 100%; height: 200px;" id="docuText"></textarea>
                        </div>
                        <div ng-repeat="error in errors.text">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>

<div class="modal fade" id="hyerlinkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Insert hyperlink'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="form-group">
                        <label for="url" class="col col-md-2 control-label">URL:</label>
                        <div class="col col-xs-10">
                            <input class="form-control" type="text" ng-model="docu.hyperlink"
                                   placeholder="<?php echo __('https://openitcockpit.io'); ?>" style="width: 100%;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col col-md-2 control-label">Description:</label>
                        <div class="col col-xs-10">
                            <input class="form-control" style="width: 100%;" ng-model="docu.hyperlinkDescription"
                                   placeholder="<?php echo __('Official page for openITCOCKPIT'); ?>" type="text">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form no-padding">
                            <label class="checkbox small-checkbox-label">
                                <input type="checkbox" name="checkbox"
                                       id="modalLinkNewTab">
                                <i class="checkbox-primary"></i>
                                <?php echo __('Open in new tab'); ?>
                            </label>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" ng-click="insertWysiwygHyperlink()" data-dismiss="modal">
                    <?php echo __('Insert'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
        </div>
    </div>
</div>