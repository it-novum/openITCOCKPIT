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

        <div class="widget-toolbar" role="menu">
            <div class="btn-group">
                <button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
                    <?php echo __('Grid size'); ?>
                    <i class="fa fa-caret-down"></i>
                </button>
                <ul class="dropdown-menu pull-right">
                    <?php
                    $gridSizes = [5, 10, 15, 20, 25, 30, 50, 80];
                    foreach ($gridSizes as $size): ?>
                        <li>
                            <a href="javascript:void(0)" ng-click="changeGridSize(<?php echo $size; ?>)">
                                <?php printf('%sx%spx', $size, $size); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="widget-toolbar" role="menu">
            <div class="form-group smart-form no-padding">
                <label class="checkbox small-checkbox-label">
                    <input type="checkbox" name="checkbox" checked="checked"
                           ng-model="grid.enabled">
                    <i class="checkbox-primary"></i>
                    <?php echo __('Enable grid'); ?>
                </label>
            </div>
        </div>


    </header>
    <div id="map-editor">
        <div class="widget-body" style="overflow: auto; min-height:600px; " ng-click="addNewObjectFunc($event)"
             id="mainMapContainer">
            <img ng-src="/map_module/img/backgrounds/{{map.Map.background}}" ng-if="map.Map.background"/>


            <div ng-repeat="item in map.Mapitem" class="draggable" ng-dblclick="editItem(item)"
                 style="position:absolute; top: {{item.y}}px; left: {{item.x}}px;  z-index: {{item.z_index}}; cursor: move;"
                 data-id="{{item.id}}" data-type="item">
                <map-item item="item" refresh-interval="0"></map-item>
            </div>

            <div ng-repeat="textItem in map.Maptext" class="draggable"
                 style="position:absolute; top: {{textItem.y}}px; left: {{textItem.x}}px;  z-index: {{textItem.z_index}}; cursor: move;">
                <map-text item="textItem"></map-text>
            </div>

            <div ng-repeat="lineItem in map.Mapline" ng-dblclick="editLine(lineItem)">
                <map-line item="lineItem" refresh-interval="0"></map-line>
            </div>

            <div ng-repeat="iconItem in map.Mapicon"
                 style="position:absolute; top: {{iconItem.y}}px; left: {{iconItem.x}}px;  z-index: {{iconItem.z_index}}; cursor: move;"
                 class="draggable">
                <map-icon item="iconItem"></map-icon>
            </div>

            <div ng-repeat="gadgetItem in map.Mapgadget" class="draggable"
                 style="position:absolute; top: {{gadgetItem.y}}px; left: {{gadgetItem.x}}px;  z-index: {{gadgetItem.z_index}}; cursor: move;">
                <graph-item item="gadgetItem" ng-if="gadgetItem.gadget === 'RRDGraph'"
                            refresh-interval="0"></graph-item>

                <perfdata-text-item item="gadgetItem" ng-if="gadgetItem.gadget === 'Text'"
                                    refresh-interval="0"></perfdata-text-item>
                <tacho-item item="gadgetItem" ng-if="gadgetItem.gadget === 'Tacho'"
                            refresh-interval="0"></tacho-item>

                <cylinder-item item="gadgetItem" ng-if="gadgetItem.gadget === 'Cylinder'"
                               refresh-interval="0"></cylinder-item>

                <trafficlight-item item="gadgetItem"
                                   ng-if="gadgetItem.gadget === 'TrafficLight'"
                                   refresh-interval="0"></trafficlight-item>
            </div>

            <div id="graph_data_tooltip"></div>


        </div>

        <div id="mapToolbar">
            <div id="mapToolsDragger"></div>

            <div class="mapToolbarTool" title="<?php echo __('Add item'); ?>" ng-click="addItem()">
                <i class="fa fa-lg fa-desktop"></i>
            </div>

            <div class="mapToolbarTool" title="<?php echo __('Add line'); ?>" ng-click="addLine()">
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
                <i class="fa fa-lg fa-diamond"></i>
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
                        <div class="form-group smart-form" ng-class="{'has-error': errors.type}">
                            <span class="hintmark_red">
                                <?php echo __('Select object type'); ?>
                            </span>
                            <label class="select">
                                <select ng-model="currentItem.type">
                                    <?php if ($this->Acl->hasPermission('index', 'hosts', '')): ?>
                                        <option value="host"><?php echo __('Host'); ?></option>
                                    <?php endif; ?>
                                    <?php if ($this->Acl->hasPermission('index', 'services', '')): ?>
                                        <option value="service"><?php echo __('Service'); ?></option>
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
                            <div ng-repeat="error in errors.type">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group smart-form hintmark_red">
                            <?php echo __('Select object'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group" ng-class="{'has-error': errors.object_id}">
                            <select
                                    id="AddEditItemObjectSelect"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="itemObjects"
                                    callback="loadMoreItemObjects"
                                    ng-options="itemObject.key as itemObject.value for itemObject in itemObjects"
                                    ng-model="currentItem.object_id">
                            </select>
                            <div ng-repeat="error in errors.object_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form hintmark_red">
                            <?php echo __('Select iconset'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12" ng-if="iconsets">
                        <div class="row" style="max-height: 200px; overflow: auto;"
                             ng-class="{'has-error-border': errors.iconset}">
                            <div class="col-xs-12 col-md-6 col-lg-3" ng-repeat="iconset in iconsets">
                                <div class="thumbnail"
                                     style="height: 175px; width: 175px;display: flex; align-items: center; overflow: hidden;"
                                     ng-click="setCurrentIconset(iconset.MapUpload.saved_name)"
                                     ng-class="{ 'selectedMapItem': iconset.MapUpload.saved_name === currentItem.iconset }">
                                    <img class="image_picker_selector"
                                         ng-src="/map_module/img/items/{{iconset.MapUpload.saved_name}}/ok.png">
                                </div>
                            </div>
                        </div>
                        <div ng-repeat="error in errors.iconset" class="row">
                            <div class="col-xs-12">
                                <div class="help-block text-danger" style="color: #a94442;">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>

                <div class="row">
                    <div class="col-xs-12 col-lg-6 smart-form">
                        <div class="form-group smart-form" ng-class="{'has-error': errors.x}">
                            <label class="label hintmark_red"><?php echo __('Position X'); ?></label>
                            <label class="input"> <b class="icon-prepend">X</b>
                                <input type="number" min="0" class="input-sm"
                                       placeholder="<?php echo __('0'); ?>"
                                       ng-model="currentItem.x">
                            </label>
                            <div ng-repeat="error in errors.x">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-lg-6 smart-form" ng-class="{'has-error': errors.y}">
                        <div class="form-group smart-form">
                            <label class="label hintmark_red"><?php echo __('Position Y'); ?></label>
                            <label class="input"> <b class="icon-prepend">Y</b>
                                <input type="number" min="0" class="input-sm"
                                       placeholder="<?php echo __('0'); ?>"
                                       ng-model="currentItem.y">
                            </label>
                        </div>
                        <div ng-repeat="error in errors.y">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>
                <br/>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form hintmark_red">
                            <?php echo __('Select layer'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-10">
                        <div class="form-group required" ng-class="{'has-error': errors.z_index}">
                            <select
                                    id="selectItemLayerSelect"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="layers"
                                    ng-options="key as layerNo for (key , layerNo) in layers"
                                    ng-model="currentItem.z_index">
                            </select>
                            <div ng-repeat="error in errors.z_index">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <span class="help-block">
                                <?php echo __('Layers could be used to stack items on a map. Empty layers will be deleted automatically.'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-2">
                        <button class="btn btn-block btn-default" ng-click="addNewLayer()">
                            <?php echo __('Add new layer'); ?>
                        </button>
                    </div>
                </div>
                <br/>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form">
                            <?php echo __('Label options'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group smart-form no-padding">
                            <label class="checkbox small-checkbox-label">
                                <input type="checkbox" name="checkbox"
                                       ng-model="currentItem.show_label"
                                       ng-true-value="1"
                                       ng-false-value="0">
                                <i class="checkbox-primary"></i>
                                <?php echo __('Show label'); ?>
                            </label>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group smart-form">
                            <?php echo __('Label possition'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="btn-toolbar" role="toolbar">
                            <button type="button" class="btn btn-default"
                                    ng-class="{ 'btn-primary': currentItem.label_possition === 4 }"
                                    ng-click="currentItem.label_possition = 4">
                                <i class="fa fa-arrow-circle-left"></i>
                                <?php echo __('Left'); ?>
                            </button>
                            <button type="button" class="btn btn-default"
                                    ng-class="{ 'btn-primary': currentItem.label_possition === 1 }"
                                    ng-click="currentItem.label_possition = 1">
                                <i class="fa fa-arrow-circle-up"></i>
                                <?php echo __('Top'); ?>
                            </button>
                            <button type="button" class="btn btn-default"
                                    ng-class="{ 'btn-primary': currentItem.label_possition === 2 }"
                                    ng-click="currentItem.label_possition = 2">
                                <i class="fa fa-arrow-circle-down"></i>
                                <?php echo __('Bottom'); ?>
                            </button>
                            <button type="button" class="btn btn-default"
                                    ng-class="{ 'btn-primary': currentItem.label_possition === 3 }"
                                    ng-click="currentItem.label_possition = 3">
                                <i class="fa fa-arrow-circle-right"></i>
                                <?php echo __('Right'); ?>
                            </button>
                        </div>
                    </div>

                </div>


            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-danger pull-left" ng-click="deleteItem()">
                    <?php echo __('Delete'); ?>
                </button>

                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>

                <button type="button" class="btn btn-primary" ng-click="saveItem()">
                    <?php echo __('Save'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit map line modal -->
<div id="addEditMapLineModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-desktop"></i>
                    <?php echo __('Add or edit line'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form" ng-class="{'has-error': errors.type}">
                            <span class="hintmark_red">
                                <?php echo __('Select object type'); ?>
                            </span>
                            <label class="select">
                                <select ng-model="currentItem.type">
                                    <?php if ($this->Acl->hasPermission('index', 'hosts', '')): ?>
                                        <option value="host"><?php echo __('Host'); ?></option>
                                    <?php endif; ?>
                                    <?php if ($this->Acl->hasPermission('index', 'services', '')): ?>
                                        <option value="service"><?php echo __('Service'); ?></option>
                                    <?php endif; ?>
                                    <?php if ($this->Acl->hasPermission('index', 'hostgroups', '')): ?>
                                        <option value="hostgroup"><?php echo __('Hostgroup'); ?></option>
                                    <?php endif; ?>
                                    <?php if ($this->Acl->hasPermission('index', 'servicegroups', '')): ?>
                                        <option value="servicegroup"><?php echo __('Servicegroup'); ?></option>
                                    <?php endif; ?>
                                    <option value="map"><?php echo __('Map'); ?></option>
                                    <option value="stateless"><?php echo __('Stateless line'); ?></option>
                                </select> <i></i>
                            </label>
                            <div ng-repeat="error in errors.type">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12" ng-show="currentItem.type !== 'stateless'">
                        <div class="form-group smart-form hintmark_red">
                            <?php echo __('Select object'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12" ng-show="currentItem.type !== 'stateless'">
                        <div class="form-group" ng-class="{'has-error': errors.object_id}">
                            <select
                                    id="AddEditItemObjectSelect"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="itemObjects"
                                    callback="loadMoreItemObjects"
                                    ng-options="itemObject.key as itemObject.value for itemObject in itemObjects"
                                    ng-model="currentItem.object_id">
                            </select>
                            <div ng-repeat="error in errors.object_id">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>

                <div class="row">
                    <div class="col-xs-12 col-lg-6 smart-form">
                        <div class="form-group smart-form" ng-class="{'has-error': errors.startX}">
                            <label class="label hintmark_red"><?php echo __('Start X'); ?></label>
                            <label class="input"> <b class="icon-prepend">X</b>
                                <input type="number" min="0" class="input-sm"
                                       placeholder="<?php echo __('0'); ?>"
                                       ng-model="currentItem.startX">
                            </label>
                            <div ng-repeat="error in errors.startX">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-lg-6 smart-form" ng-class="{'has-error': errors.startY}">
                        <div class="form-group smart-form">
                            <label class="label hintmark_red"><?php echo __('Start Y'); ?></label>
                            <label class="input"> <b class="icon-prepend">Y</b>
                                <input type="number" min="0" class="input-sm"
                                       placeholder="<?php echo __('0'); ?>"
                                       ng-model="currentItem.startY">
                            </label>
                        </div>
                        <div ng-repeat="error in errors.startY">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-xs-12 col-lg-6 smart-form">
                        <div class="form-group smart-form" ng-class="{'has-error': errors.endX}">
                            <label class="label hintmark_red"><?php echo __('End X'); ?></label>
                            <label class="input"> <b class="icon-prepend">X</b>
                                <input type="number" min="0" class="input-sm"
                                       placeholder="<?php echo __('0'); ?>"
                                       ng-model="currentItem.endX">
                            </label>
                            <div ng-repeat="error in errors.endX">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-lg-6 smart-form" ng-class="{'has-error': errors.endY}">
                        <div class="form-group smart-form">
                            <label class="label hintmark_red"><?php echo __('End Y'); ?></label>
                            <label class="input"> <b class="icon-prepend">Y</b>
                                <input type="number" min="0" class="input-sm"
                                       placeholder="<?php echo __('0'); ?>"
                                       ng-model="currentItem.endY">
                            </label>
                        </div>
                        <div ng-repeat="error in errors.endY">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>
                <br/>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form hintmark_red">
                            <?php echo __('Select layer'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-10">
                        <div class="form-group required" ng-class="{'has-error': errors.z_index}">
                            <select
                                    id="selectItemLayerSelect"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="layers"
                                    ng-options="key as layerNo for (key , layerNo) in layers"
                                    ng-model="currentItem.z_index">
                            </select>
                            <div ng-repeat="error in errors.z_index">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <span class="help-block">
                                <?php echo __('Layers could be used to stack items on a map. Empty layers will be deleted automatically.'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-2">
                        <button class="btn btn-block btn-default" ng-click="addNewLayer()">
                            <?php echo __('Add new layer'); ?>
                        </button>
                    </div>
                </div>
                <br/>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form">
                            <?php echo __('Label options'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group smart-form no-padding">
                            <label class="checkbox small-checkbox-label">
                                <input type="checkbox" name="checkbox"
                                       ng-model="currentItem.show_label"
                                       ng-true-value="1"
                                       ng-false-value="0">
                                <i class="checkbox-primary"></i>
                                <?php echo __('Show label'); ?>
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-danger pull-left" ng-click="deleteLine()">
                    <?php echo __('Delete'); ?>
                </button>

                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>

                <button type="button" class="btn btn-primary" ng-click="saveLine()">
                    <?php echo __('Save'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Change background image modal -->
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
                        <div class="row" style="max-height: 400px; overflow: auto;">
                            <div class="col-xs-12 col-md-6 col-lg-3" ng-repeat="background in backgrounds">
                                <div class="thumbnail"
                                     style="height: 155px; width: 175px;display: flex; align-items: center; overflow: hidden;"
                                     ng-click="changeBackground(background)"
                                     ng-class="{ 'selectedMapItem': background.image === map.Map.background }">
                                    <button class="btn btn-xs btn-danger"
                                            style="position: absolute; top: 11px; left: 158px;"
                                            ng-click="deleteBackground(background)">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                    <img class="image_picker_selector"
                                         ng-src="{{background.thumbnail}}">
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
</div>
