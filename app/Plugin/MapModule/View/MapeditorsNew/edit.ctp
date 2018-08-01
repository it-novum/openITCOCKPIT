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
            <i class="fa fa-map-marker fa-fw "></i>
            <?php echo __('Map'); ?>
            <span>>
                <?php echo __('Edit'); ?>
            </span>
        </h1>
    </div>
</div>

<div class="jarviswidget bg-color-white" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
        <h2>
            <?php echo __('Edit map:'); ?>
            {{map.Map.name}}
        </h2>
        <div class="widget-toolbar" role="menu">
            <a class="btn btn-xs btn-default" href="/map_module/maps">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i>
                <?php echo __('Back to list'); ?>
            </a>
            <?php if ($this->Acl->hasPermission('view', 'mapeditors', 'mapmodule')): ?>
                <a class="btn btn-xs btn-default" ng-href="/map_module/mapeditors_new/view/{{map.Map.id}}">
                    <i class="fa fa-eye"></i>
                    <?php echo __('View'); ?>
                </a>
            <?php endif; ?>
        </div>
    </header>
    <div id="map-editor">
        <div class="widget-body" style="overflow: auto; min-height:600px; " ng-click="addNewObjectFunc($event)">
            <img ng-src="/map_module/img/backgrounds/{{map.Map.background}}" ng-if="map.Map.background"/>


            Map content hier!


        </div>

        <div id="mapToolbar">
            <div id="mapToolsDragger"></div>

            <div class="mapToolbarTool" title="<?php echo __('Add item'); ?>" ng-click="addItem()">
                <i class="fa fa-lg fa-desktop"></i>
            </div>

            <div class="mapToolbarTool" title="<?php echo __('Add line'); ?>">
                <i class="fa fa-lg fa-pencil"></i>
            </div>

            <div class="mapToolbarLine"></div>

            <div class="mapToolbarTool" title="<?php echo __('Add gadget'); ?>">
                <i class="fa fa-lg fa-dashboard"></i>
            </div>

            <div class="mapToolbarLine"></div>

            <div class="mapToolbarTool" title="<?php echo __('Change background image'); ?>"
                 ng-click="openChangeMapBackgroundModal()">
                <i class="fa fa-lg fa-picture-o"></i>
            </div>

            <div class="mapToolbarLine"></div>

            <div class="mapToolbarTool" title="<?php echo __('Add stateless text'); ?>">
                <i class="fa fa-lg fa-font"></i>
            </div>

            <div class="mapToolbarTool" title="<?php echo __('Add stateless icon'); ?>">
                <i class="fa fa-lg fa-object-group"></i>
            </div>

        </div>
    </div>
</div>

<!-- Add/Edit map item modal -->
<div id="addEditMapItemModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-desktop"></i>
                    <?php echo __('Add or edit map item'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form">
                            <?php echo __('Select object type'); ?>
                            <label class="select">
                                <select ng-model="currentItem.itemObjectType">
                                    <?php if ($this->Acl->hasPermission('index', 'hosts', '')): ?>
                                    <option value="host"><?php echo __('Host'); ?></option>
                                    <?php endif; ?>
                                    <?php if ($this->Acl->hasPermission('index', 'services', '')): ?>
                                        <option value="service"><?php echo __('services'); ?></option>
                                    <?php endif; ?>
                                    <?php if ($this->Acl->hasPermission('index', 'hostgroups', '')): ?>
                                        <option value="hostgroup"><?php echo __('Hostgroup'); ?></option>
                                    <?php endif; ?>
                                    <?php if ($this->Acl->hasPermission('index', 'servicegroups', '')): ?>
                                        <option value="servicegroup"><?php echo __('Servicegroup'); ?></option>
                                    <?php endif; ?>
                                    <option value="map"><?php echo __('Map'); ?></option>
                                </select> <i></i>
                            </label>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group smart-form">
                            <?php echo __('Select object'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group required">
                            <select
                                    id="AddEditItemObjectSelect"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="itemObjects"
                                    callback="loadMoreItemObjects"
                                    ng-options="itemObject.key as itemObject.value for itemObject in itemObjects"
                                    ng-model="currentItem.object_id">
                            </select>

                        </div>
                    </div>
                </div>
                <br/>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form">
                            <?php echo __('Select iconset'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12" ng-if="iconsets">
                        <div class="row" style="max-height: 200px; overflow: auto;">
                            <div class="col-xs-12 col-md-6 col-lg-3" ng-repeat="iconset in iconsets">
                                <div class="thumbnail"
                                     ng-class="{ 'selectedMapItem': iconset.MapUpload.saved_name === currentItem.iconset }">
                                    <img class="image_picker_selector"
                                         ng-click="setCurrentIconset(iconset.MapUpload.saved_name)"
                                         ng-src="/map_module/img/items/{{iconset.MapUpload.saved_name}}/ok.png">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>

                <div class="row">
                    <div class="col-xs-12 col-lg-6 smart-form">
                        <div class="form-group smart-form">
                            <label class="label"><?php echo __('Position X'); ?></label>
                            <label class="input"> <b class="icon-prepend">X</b>
                                <input type="number" min="0" class="input-sm"
                                       placeholder="<?php echo __('0'); ?>"
                                       ng-model="currentItem.x">
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-12 col-lg-6 smart-form">
                        <div class="form-group smart-form">
                            <label class="label"><?php echo __('Position Y'); ?></label>
                            <label class="input"> <b class="icon-prepend">Y</b>
                                <input type="number" min="0" class="input-sm"
                                       placeholder="<?php echo __('0'); ?>"
                                       ng-model="currentItem.y">
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Change background image moddal -->
<div id="changeBackgroundModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-picture-o"></i>
                    <?php echo __('Change background image'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-12">
                        <?php echo __('Choose background image'); ?>
                    </div>
                    <div class="col-xs-12">
                        <div class="row" style="max-height: 400px; overflow: auto;"
                        ">
                        <div class="col-xs-12 col-md-6 col-lg-3" ng-repeat="background in backgrounds">
                            <div class="thumbnail"
                                 ng-class="{ 'selectedMapItem': background.image === map.Map.background }">
                                <button class="btn btn-xs btn-danger pull-right"
                                        ng-click="deleteBackground(background)">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                                <img class="image_picker_image"
                                     ng-src="{{background.thumbnail}}"
                                     ng-click="changeBackground(background)">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <?php echo __('Upload new background image'); ?>
                </div>
                <div class="col-xs-12 text-info">
                    <i class="fa fa-info-circle"></i>
                    <?php echo __('Max allowed file size: '); ?>
                    {{ maxUploadLimit.string }}
                </div>
                <div class="col-xs-12">
                    <div class="background-dropzone dropzone"
                         action="/map_module/backgroundUploads/upload/.json">
                    </div>


                </div>
            </div>


        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
                <?php echo __('Close'); ?>
            </button>
        </div>
    </div>
</div>

