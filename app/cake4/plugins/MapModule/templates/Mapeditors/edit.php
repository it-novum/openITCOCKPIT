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
        <a ui-sref="MapsIndex">
            <i class="fa fa-map-marker"></i> <?php echo __('Maps'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-edit"></i> <?php echo __('Map editor'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Edit map:'); ?>
                    <span class="fw-300"><i>{{map.Map.name}}</i></span>
                </h2>
                <div class="panel-toolbar">
                    <label class="checkbox small-checkbox-label">
                        <input type="checkbox" name="checkbox" checked="checked"
                               ng-model="grid.enabled">
                        <i class="checkbox-primary"></i>
                        <?php echo __('Enable grid'); ?>
                    </label>
                    <button class="btn dropdown-toggle btn-default btn-xs mr-1 shadow-0" data-toggle="dropdown">
                        <i class="fas fa-th"></i>
                        <?php echo __('Grid size'); ?>
                    </button>
                    <ul class="dropdown-menu">
                        <?php
                        $gridSizes = [5, 10, 15, 20, 25, 30, 50, 80];
                        foreach ($gridSizes as $size): ?>
                            <button class="dropdown-item" ng-click="changeGridSize(<?php echo $size; ?>)">
                                <?php printf('%sx%spx', $size, $size); ?>
                            </button>
                        <?php endforeach; ?>
                    </ul>
                    <?php if ($this->Acl->hasPermission('index', 'maps', 'mapmodule')): ?>
                        <a back-button fallback-state='MapsIndex' class="btn btn-default btn-xs mr-1 shadow-0">
                            <i class="fas fa-long-arrow-alt-left"></i> <?php echo __('Back to list'); ?>
                        </a>
                    <?php endif; ?>
                    <?php if ($this->Acl->hasPermission('view', 'mapeditors', 'mapmodule')): ?>
                        <button class="btn btn-default btn-xs mr-1 shadow-0" ui-sref="MapeditorsView">
                            <i class="fa fa-eye"></i> <?php echo __('View'); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <!-- start map-editor -->
                    <div id="map-editor">
                        <!-- start mainMapContainer -->
                        <div style="overflow: auto; min-height:600px; " ng-click="addNewObjectFunc($event)"
                             id="mainMapContainer">
                            <img ng-src="/map_module/img/backgrounds/{{map.Map.background}}"
                                 ng-if="map.Map.background"/>
                            <div ng-repeat="item in map.Mapitems" class="draggable" ng-dblclick="editItem(item)"
                                 style="position:absolute; top: {{item.y}}px; left: {{item.x}}px;  z-index: {{item.z_index}}; cursor: move;"
                                 data-id="{{item.id}}" data-type="item" ng-show="item.display">
                                <map-item item="item" refresh-interval="0"></map-item>
                            </div>

                            <div ng-repeat="textItem in map.Maptexts" class="draggable"
                                 style="position:absolute; top: {{textItem.y}}px; left: {{textItem.x}}px;  z-index: {{textItem.z_index}}; cursor: move;"
                                 data-id="{{textItem.id}}" data-type="text" ng-dblclick="editText(textItem)"
                                 ng-show="textItem.display">
                                <map-text item="textItem"></map-text>
                            </div>

                            <div ng-repeat="lineItem in map.Maplines" ng-dblclick="editLine(lineItem)"
                                 style="position: absolute; top: {{lineItem.startY}}px; left: {{lineItem.startX}}px; cursor: move;"
                                 data-id="{{lineItem.id}}" data-type="line"
                                 data-oldstartx="{{lineItem.startX}}" data-oldstarty="{{lineItem.startY}}"
                                 data-oldendx="{{lineItem.endX}}" data-oldendy="{{lineItem.endY}}"
                                 class="draggable" ng-show="lineItem.display">
                                <map-line item="lineItem" refresh-interval="0"></map-line>
                            </div>

                            <div ng-repeat="iconItem in map.Mapicons"
                                 style="position:absolute; top: {{iconItem.y}}px; left: {{iconItem.x}}px;  z-index: {{iconItem.z_index}}; cursor: move;"
                                 class="draggable"
                                 data-id="{{iconItem.id}}" data-type="icon" ng-dblclick="editIcon(iconItem)"
                                 ng-show="iconItem.display">
                                <map-icon item="iconItem"></map-icon>
                            </div>

                            <div ng-repeat="gadgetItem in map.Mapgadgets" class="draggable resizable"
                                 style="position:absolute; top: {{gadgetItem.y}}px; left: {{gadgetItem.x}}px;  z-index: {{gadgetItem.z_index}}; cursor: move;"
                                 data-id="{{gadgetItem.id}}" data-type="gadget" ng-dblclick="editGadget(gadgetItem)"
                                 ng-show="gadgetItem.display" ng-if="gadgetItem.gadget !== 'ServiceOutput'">
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

                                <temperature-item item="gadgetItem"
                                                  ng-if="gadgetItem.gadget === 'Temperature'"
                                                  refresh-interval="0"></temperature-item>
                            </div>

                            <div ng-repeat="gadgetItem in map.Mapgadgets" class="draggable resizable-no-aspect-ratio"
                                 style="position:absolute; top: {{gadgetItem.y}}px; left: {{gadgetItem.x}}px;  z-index: {{gadgetItem.z_index}}; cursor: move;"
                                 data-id="{{gadgetItem.id}}" data-type="gadget" ng-dblclick="editGadget(gadgetItem)"
                                 ng-show="gadgetItem.display" ng-if="gadgetItem.gadget === 'ServiceOutput'">

                                <service-output-item item="gadgetItem"
                                                     ng-if="gadgetItem.gadget === 'ServiceOutput'"
                                                     refresh-interval="0"></service-output-item>
                            </div>

                            <div ng-repeat="summaryItem in map.Mapsummaryitems"
                                 style="position:absolute; top: {{summaryItem.y}}px; left: {{summaryItem.x}}px;  z-index: {{summaryItem.z_index}};"
                                 class="draggable resizable"
                                 data-id="{{summaryItem.id}}" data-type="summaryItem"
                                 ng-dblclick="editSummaryItem(summaryItem)"
                                 ng-show="summaryItem.display">
                                <map-summary-item item="summaryItem" refresh-interval="0"></map-summary-item>
                            </div>

                            <div id="graph_data_tooltip"></div>
                        </div>
                        <!-- end mainMapContainer -->
                        <!-- start mapToolbar -->
                        <div id="mapToolbar">
                            <div id="mapToolsDragger"></div>

                            <div class="mapToolbarTool" title="<?php echo __('Add item'); ?>" ng-click="addItem()">
                                <i class="fa fa-lg fa-desktop"></i>
                            </div>

                            <div class="mapToolbarTool" title="<?php echo __('Add line'); ?>" ng-click="addLine()">
                                <i class="fas fa-lg fa-pencil-alt"></i>
                            </div>

                            <div class="mapToolbarTool" title="<?php echo __('Add summary status item'); ?>"
                                 ng-click="addSummaryItem()">
                                <i class="fa fa-lg fa-circle"></i>
                            </div>

                            <div class="mapToolbarLine"></div>

                            <div class="mapToolbarTool" title="<?php echo __('Add gadget'); ?>" ng-click="addGadget()">
                                <i class="fas fa-lg fa-tachometer-alt"></i>
                            </div>

                            <div class="mapToolbarLine"></div>

                            <div class="mapToolbarTool" title="<?php echo __('Change background image'); ?>"
                                 ng-click="openChangeMapBackgroundModal()">
                                <i class="fa fa-lg fa-picture-o"></i>
                            </div>

                            <div class="mapToolbarLine"></div>

                            <div class="mapToolbarTool" title="<?php echo __('Add stateless text'); ?>"
                                 ng-click="addText()">
                                <i class="fa fa-lg fa-font"></i>
                            </div>

                            <div class="mapToolbarTool" title="<?php echo __('Add stateless icon'); ?>"
                                 ng-click="addIcon()">
                                <i class="fa fa-lg fa-object-ungroup"></i>
                            </div>
                        </div>
                        <!-- end mapToolbar -->
                        <!-- start layersBox -->
                        <div id="layersBox">
                            <div id="layersBoxDragger"></div>
                            <div class="layersContainer" style="overflow-y: auto;">
                                <div class="mapLayer" ng-repeat="(key, value) in layers"
                                     ng-class="{ 'selectedLayer': key == defaultLayer }">
                                    <span class="cursor-pointer"
                                          ng-click="setDefaultLayer(key)">
                                        {{value}}
                                    </span>
                                    <i class="fa fa-eye pull-right padding-right-5 cursor-pointer"
                                       ng-show="visableLayers['layer_'+key]"
                                       ng-click="hideLayer(key)"
                                       title="<?php echo __('Click to hide layer'); ?>"></i>
                                    <i class="fa fa-eye-slash pull-right padding-right-5 cursor-pointer"
                                       ng-hide="visableLayers['layer_'+key]"
                                       ng-click="showLayer(key)"
                                       title="<?php echo __('Click to show layer'); ?>"></i>
                                </div>
                            </div>
                        </div>
                        <!-- end layersBox -->
                    </div>
                    <!-- end map-editor -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Example modal -->
<div id="exampleModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-plus"></i>
                    <?php echo __('Example Heading'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="form-group padding-bottom-10">
                            <label class="control-label">
                                <?php echo __('Tab name'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-model="data.newTabName">
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary float-right" ng-click="addNewTab()">
                    <?php echo __('Create new tab'); ?>
                </button>
                <hr/>
                <div class="row">
                    <h5 class="modal-title">
                        <i class="fa fa-plus"></i>
                        <?php echo __('Create from shared tab'); ?>
                    </h5>
                </div>
                <div class="row">
                    <div class="form-group col-lg-12 margin-top-20">
                        <label class="control-label">
                            <?php echo __('Select shared tab'); ?>
                        </label>
                        <select
                            data-placeholder="<?php echo __('Please choose'); ?>"
                            class="form-control"
                            chosen="sharedTabs"
                            ng-options="sharedTab.id as sharedTab.name for sharedTab in sharedTabs"
                            ng-model="data.createTabFromSharedTabId">
                        </select>
                    </div>


                </div>
                <div class="col-lg-12 padding-top-10">
                    <button type="button" class="btn btn-primary float-right" ng-click="addFromSharedTab()">
                        <?php echo __('Create from shared tab'); ?>
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" ng-click="renameWidget()">
                    <?php echo __('Save widget Title'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- END Example modal -->

<!-- Add/Edit map item modal -->
<div id="addEditMapItemModal" class="modal" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-desktop"></i>
                    <?php echo __('Add or edit map item'); ?>
                </h5>
                <div class="ml-auto">
                    <button class="btn btn-default btn-xs" ng-click="uploadIconSet = true">
                        <i class="fa fa-upload"></i>
                        <?php echo __('Upload iconset'); ?>
                    </button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-times"></i></span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div ng-hide="uploadIconSet">
                    <div class="row">
                        <div class="form-group col-lg-12" ng-class="{'has-error': errors.type}">
                            <label class="control-label">
                                <?php echo __('Select object type'); ?>
                            </label>
                            <select
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="{}"
                                ng-model="currentItem.type">
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
                            </select>
                            <div ng-repeat="error in errors.type">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12 margin-top-10" ng-class="{'has-error': errors.object_id}">
                            <label class="control-label">
                                <?php echo __('Select object'); ?>
                            </label>
                            <select
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
                    <div class="row">
                        <div class="col-lg-12 margin-top-10">
                            <div class="form-group hintmark_red">
                                <?php echo __('Select iconset'); ?>
                            </div>
                        </div>
                        <div class="col-lg-12" ng-if="iconsets">
                            <div class="row" style="max-height: 200px; overflow: auto;"
                                 ng-class="{'has-error-border': errors.iconset}">
                                <div class="col-xs-12 col-md-6 col-lg-2" ng-repeat="iconset in iconsets">
                                    <div class="card"
                                         style="height: 175px; width: 175px;margin-bottom:20px;"
                                         ng-click="setCurrentIconset(iconset.MapUpload.saved_name)"
                                         ng-class="{ 'selectedMapItem': iconset.MapUpload.saved_name === currentItem.iconset }">
                                        <img class="mx-auto my-auto"
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

                    <div class="row">
                        <div class="col-lg-6">
                            <label class="control-label" for="addEditElPosX">
                                <?php echo __('Position X'); ?>
                            </label>
                            <div class="input-group" ng-class="{'has-error': errors.x}">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i
                                            class="icon-prepend fas fa-map-marked-alt"></i></span>
                                </div>
                                <input type="text"
                                       class="form-control"
                                       id="addEditElPosX"
                                       placeholder="<?php echo __('0'); ?>"
                                       ng-model="currentItem.x">
                            </div>
                            <div ng-repeat="error in errors.x">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="control-label" for="addEditElPosY">
                                <?php echo __('Position Y'); ?>
                            </label>
                            <div class="input-group" ng-class="{'has-error': errors.y}">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i
                                            class="icon-prepend fas fa-map-marked-alt"></i></span>
                                </div>
                                <input type="text"
                                       class="form-control"
                                       id="addEditElPosY"
                                       placeholder="<?php echo __('0'); ?>"
                                       ng-model="currentItem.y">
                            </div>
                            <div ng-repeat="error in errors.y">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-10 margin-top-10" ng-class="{'has-error': errors.z_index}">
                            <label class="control-label">
                                <?php echo __('Select layer'); ?>
                            </label>
                            <select
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
                        <div class="col-lg-2">
                            <button class="btn btn-block btn-default margin-top-30" ng-click="addNewLayer()">
                                <?php echo __('Add new layer'); ?>
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-4" ng-class="{'has-error': errors.show_label}">
                            <div class="custom-control custom-checkbox custom-control-down margin-bottom-10"
                                 ng-class="{'has-error': errors.show_label}">

                                <input type="checkbox"
                                       class="custom-control-input"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       id="addEditElShowLabel"
                                       ng-model="currentItem.show_label">
                                <label class="custom-control-label" for="addEditElShowLabel">
                                    <?php echo __('Show Label'); ?>
                                </label>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <label class="control-label margin-left-12" for="addEditElLabelPos">
                            <?php echo __('Label position'); ?>
                        </label>
                        <div class="btn-toolbar col-lg-12">
                            <div class="btn-group mr-2" role="group" id="addEditElLabelPos">
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
                <div ng-show="uploadIconSet">
                    <div class="col col-lg-12 no-padding padding-top-10">
                        <div class="col-lg-12">
                            <?php echo __('Upload new iconset (as .zip package)'); ?>
                        </div>
                        <div class="col-lg-12 text-info">
                            <i class="fa fa-info-circle"></i>
                            <?php echo __('Max allowed file size: '); ?>
                            {{ maxUploadLimit.string }}
                        </div>
                        <div class="col-lg-12">
                            <div class="profileImg-dropzone dropzone dropzoneStyle"
                                 action="/map_module/backgroundUploads/iconset/.json">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-5x text-muted mb-3"></i> <br>
                                    <span class="text-uppercase">Drop files here or click to upload.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 padding-top-10 text-info">
                        <i class="fa fa-info-circle"></i>
                        <?php echo __('To upload a own iconset, you need to compress all required icons into a .zip archive.'); ?>
                        <br/>
                        <?php echo __('All icons needs to be PNG images. In addition you should follow the'); ?>
                        <a href="/map_module/img/Map_Status_Colors_Guidelines.svg">
                            <?php echo __('openITCOCKPIT color guidelines.'); ?>
                        </a>
                        <br/>
                        <?php
                        echo __('Required icons: ');
                        echo implode(', ', $requiredIcons);
                        ?>
                    </div>
                    <div class="col-lg-12 padding-top-10">
                        <button class="btn btn-primary pull-right" ng-click="uploadIconSet = false">
                            <i class="fa fa-arrow-left"></i>
                            <?php echo __('Go back to settings'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger mr-auto" ng-click="deleteItem()">
                    <?php echo __('Delete'); ?>
                </button>
                <button type="button" class="btn btn-success" ng-click="saveItem()">
                    <?php echo __('Save'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit map line modal -->
<div id="addEditMapLineModal" class="modal" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-desktop"></i>
                    <?php echo __('Add or edit line'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-12" ng-class="{'has-error': errors.type}">
                        <label class="control-label">
                            <?php echo __('Select object type'); ?>
                        </label>
                        <select
                            data-placeholder="<?php echo __('Please choose'); ?>"
                            class="form-control"
                            chosen="{}"
                            ng-model="currentItem.type">
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
                        </select>
                        <div ng-repeat="error in errors.type">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-12 margin-top-10" ng-class="{'has-error': errors.object_id}">
                        <label class="control-label">
                            <?php echo __('Select object'); ?>
                        </label>
                        <select
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

                <div class="row">
                    <div class="col-lg-6">
                        <label class="control-label" for="addEditLinePosStartX">
                            <?php echo __('Start X'); ?>
                        </label>
                        <div class="input-group" ng-class="{'has-error': errors.startX}">
                            <div class="input-group-prepend">
                                    <span class="input-group-text"><i
                                            class="icon-prepend fas fa-map-marked-alt"></i></span>
                            </div>
                            <input type="text"
                                   class="form-control"
                                   id="addEditLinePosStartX"
                                   placeholder="<?php echo __('0'); ?>"
                                   ng-model="currentItem.startX">
                        </div>
                        <div ng-repeat="error in errors.startX">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label class="control-label" for="addEditLinePosStartY">
                            <?php echo __('Start Y'); ?>
                        </label>
                        <div class="input-group" ng-class="{'has-error': errors.startY}">
                            <div class="input-group-prepend">
                                    <span class="input-group-text"><i
                                            class="icon-prepend fas fa-map-marked-alt"></i></span>
                            </div>
                            <input type="text"
                                   class="form-control"
                                   id="addEditLinePosStartY"
                                   placeholder="<?php echo __('0'); ?>"
                                   ng-model="currentItem.startY">
                        </div>
                        <div ng-repeat="error in errors.startY">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <label class="control-label" for="addEditLinePosEndX">
                            <?php echo __('End X'); ?>
                        </label>
                        <div class="input-group" ng-class="{'has-error': errors.endX}">
                            <div class="input-group-prepend">
                                    <span class="input-group-text"><i
                                            class="icon-prepend fas fa-map-marked-alt"></i></span>
                            </div>
                            <input type="text"
                                   class="form-control"
                                   id="addEditLinePosEndX"
                                   placeholder="<?php echo __('0'); ?>"
                                   ng-model="currentItem.endX">
                        </div>
                        <div ng-repeat="error in errors.endX">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label class="control-label" for="addEditLinePosEndY">
                            <?php echo __('End Y'); ?>
                        </label>
                        <div class="input-group" ng-class="{'has-error': errors.endY}">
                            <div class="input-group-prepend">
                                    <span class="input-group-text"><i
                                            class="icon-prepend fas fa-map-marked-alt"></i></span>
                            </div>
                            <input type="text"
                                   class="form-control"
                                   id="addEditLinePosEndY"
                                   placeholder="<?php echo __('0'); ?>"
                                   ng-model="currentItem.endY">
                        </div>
                        <div ng-repeat="error in errors.endY">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-10 margin-top-10" ng-class="{'has-error': errors.z_index}">
                        <label class="control-label">
                            <?php echo __('Select layer'); ?>
                        </label>
                        <select
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
                    <div class="col-lg-2">
                        <button class="btn btn-block btn-default margin-top-30" ng-click="addNewLayer()">
                            <?php echo __('Add new layer'); ?>
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-4" ng-class="{'has-error': errors.show_label}">
                        <div class="custom-control custom-checkbox custom-control-down margin-bottom-10"
                             ng-class="{'has-error': errors.show_label}">

                            <input type="checkbox"
                                   class="custom-control-input"
                                   ng-true-value="1"
                                   ng-false-value="0"
                                   id="addEditElShowLabel"
                                   ng-model="currentItem.show_label">
                            <label class="custom-control-label" for="addEditElShowLabel">
                                <?php echo __('Show Label'); ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger mr-auto" ng-click="deleteLine()">
                    <?php echo __('Delete'); ?>
                </button>
                <button type="button" class="btn btn-success" ng-click="saveLine()">
                    <?php echo __('Save'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit map gadget modal -->
<div id="addEditMapGadgetModal" class="modal" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-desktop"></i>
                    <?php echo __('Add or edit gadget'); ?>
                </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-12 margin-top-10" ng-class="{'has-error': errors.object_id}">
                        <label class="control-label">
                            <?php echo __('Select service'); ?>
                        </label>
                        <select
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


                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group hintmark_red">
                            <?php echo __('Select gadget type'); ?>
                        </div>
                    </div>
                    <div class="col-lg-12" ng-if="iconsets">
                        <div class="row" style="max-height: 200px; overflow: auto;"
                             ng-class="{'has-error-border': errors.iconset}">
                            <?php foreach ($gadgetPreviews as $gadgetName => $gadgetPreview): ?>
                                <div class="col-xs-12 col-md-6 col-lg-2">
                                    <div class="card"
                                         style="height: 175px; width: 175px;"
                                         ng-click="currentItem.gadget = '<?php echo $gadgetName; ?>'; currentItem.size_x = null; currentItem.size_y = null;"
                                         ng-class="{ 'selectedMapItem': currentItem.gadget === '<?php echo $gadgetName; ?>' }">
                                        <img class="mx-auto my-auto"
                                             ng-src="/map_module/img/gadget_previews/<?php echo h($gadgetPreview); ?>">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div ng-repeat="error in errors.iconset" class="row">
                            <div class="col-xs-12">
                                <div class="help-block text-danger" style="color: #a94442;">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row"
                     ng-show="currentItem.gadget !== 'TrafficLight' && currentItem.gadget !== 'ServiceOutput'">
                    <div class="form-group col-lg-12 margin-top-10" ng-class="{'has-error': errors.metric}">
                        <label class="control-label hintmark_red">
                            <?php echo __('Select metric'); ?>
                        </label>
                        <select
                            data-placeholder="<?php echo __('Please choose'); ?>"
                            class="form-control"
                            chosen="metrics"
                            ng-options="key as value for (key , value) in metrics"
                            ng-model="currentItem.metric">
                        </select>
                        <div ng-repeat="error in errors.metric">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>

                <div class="row" ng-show="currentItem.gadget == 'ServiceOutput'">
                    <div class="form-group col-lg-12 margin-top-10" ng-class="{'has-error': errors.output_type}">
                        <label class="control-label hintmark_red">
                            <?php echo __('Select output type'); ?>
                        </label>
                        <select
                            data-placeholder="<?php echo __('Please choose'); ?>"
                            class="form-control"
                            chosen=""
                            ng-model="currentItem.output_type">
                            <option value="service_output"><?php echo __('Service output'); ?></option>
                            <option value="service_long_output"><?php echo __('Service long output'); ?></option>
                        </select>
                        <div ng-repeat="error in errors.output_type">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>

                <div class="row" ng-show="currentItem.gadget == 'ServiceOutput'">
                    <div class="col-lg-12">
                        <label class="control-label hintmark_red" for="addEditGadgetFontSize">
                            <?php echo __('Font size'); ?>
                        </label>
                        <div class="input-group" ng-class="{'has-error': errors.font_size}">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i
                                        class="icon-prepend fas fa-font"></i>
                                </span>
                            </div>
                            <input type="number"
                                   class="form-control"
                                   min="1"
                                   id="addEditGadgetFontSize"
                                   placeholder="<?php echo __('13'); ?>"
                                   ng-model="currentItem.font_size">
                        </div>
                        <div ng-repeat="error in errors.font_size">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>


                <div class="row margin-top-10">
                    <div class="col-lg-6">
                        <label class="control-label" for="addEditElPosX">
                            <?php echo __('Position X'); ?>
                        </label>
                        <div class="input-group" ng-class="{'has-error': errors.x}">
                            <div class="input-group-prepend">
                                    <span class="input-group-text"><i
                                            class="icon-prepend fas fa-map-marked-alt"></i></span>
                            </div>
                            <input type="number"
                                   class="form-control"
                                   min="0"
                                   id="addEditElPosX"
                                   placeholder="<?php echo __('0'); ?>"
                                   ng-model="currentItem.x">
                        </div>
                        <div ng-repeat="error in errors.x">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label class="control-label" for="addEditElPosY">
                            <?php echo __('Position Y'); ?>
                        </label>
                        <div class="input-group" ng-class="{'has-error': errors.y}">
                            <div class="input-group-prepend">
                                    <span class="input-group-text"><i
                                            class="icon-prepend fas fa-map-marked-alt"></i></span>
                            </div>
                            <input type="number"
                                   class="form-control"
                                   min="0"
                                   id="addEditElPosY"
                                   placeholder="<?php echo __('0'); ?>"
                                   ng-model="currentItem.y">
                        </div>
                        <div ng-repeat="error in errors.y">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <label class="control-label" for="addEditGadgetHeight">
                            <?php echo __('Width'); ?>
                        </label>
                        <div class="input-group" ng-class="{'has-error': errors.size_x}">
                            <div class="input-group-prepend">
                                    <span class="input-group-text"><i
                                            class="icon-prepend fas fa-arrows-alt-h"></i></span>
                            </div>
                            <input type="number"
                                   class="form-control"
                                   min="0"
                                   id="addEditGadgetHeight"
                                   placeholder="<?php echo __('0'); ?>"
                                   ng-model="currentItem.size_x">
                        </div>
                        <div ng-repeat="error in errors.size_x">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                        <div class="help-block">
                            <?php echo __('Keep blank for default width'); ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label class="control-label" for="addEditGadgetWidth">
                            <?php echo __('Height'); ?>
                        </label>
                        <div class="input-group" ng-class="{'has-error': errors.size_y}">
                            <div class="input-group-prepend">
                                    <span class="input-group-text"><i
                                            class="icon-prepend fas fa-arrows-alt-v"></i></span>
                            </div>
                            <input type="number"
                                   class="form-control"
                                   min="0"
                                   id="addEditGadgetWidth"
                                   placeholder="<?php echo __('0'); ?>"
                                   ng-model="currentItem.size_y">
                        </div>
                        <div ng-repeat="error in errors.size_y">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                        <div class="help-block">
                            <?php echo __('Keep blank for default width'); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-10 margin-top-10" ng-class="{'has-error': errors.z_index}">
                        <label class="control-label">
                            <?php echo __('Select layer'); ?>
                        </label>
                        <select
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
                    <div class="col-lg-2">
                        <button class="btn btn-block btn-default margin-top-30" ng-click="addNewLayer()">
                            <?php echo __('Add new layer'); ?>
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-4" ng-class="{'has-error': errors.show_label}">
                        <div class="custom-control custom-checkbox custom-control-down margin-bottom-10"
                             ng-class="{'has-error': errors.show_label}">

                            <input type="checkbox"
                                   class="custom-control-input"
                                   ng-true-value="1"
                                   ng-false-value="0"
                                   id="addEditElShowLabel"
                                   ng-model="currentItem.show_label">
                            <label class="custom-control-label" for="addEditElShowLabel">
                                <?php echo __('Show Label'); ?>
                            </label>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger mr-auto" ng-click="deleteGadget()">
                    <?php echo __('Delete'); ?>
                </button>
                <button type="button" class="btn btn-success" ng-click="saveGadget()">
                    <?php echo __('Save'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Change background image modal -->
<div id="changeBackgroundModal" class="modal" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-desktop"></i>
                    <?php echo __('Change background image'); ?>
                </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <?php echo __('Choose background image'); ?>
                    </div>
                    <div class="col-lg-12">
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

                    <div class="col-lg-12">
                        <?php echo __('Upload new background image'); ?>
                    </div>
                    <div class="col-lg-12 text-info">
                        <i class="fa fa-info-circle"></i>
                        <?php echo __('Max allowed file size: '); ?>
                        {{ maxUploadLimit.string }}
                    </div>
                    <div class="col-lg-12">
                        <div class="profileImg-dropzone dropzone dropzoneStyle"
                             action="/map_module/backgroundUploads/upload/.json">
                            <div class="dz-message">
                                <i class="fas fa-cloud-upload-alt fa-5x text-muted mb-3"></i> <br>
                                <span class="text-uppercase">Drop files here or click to upload.</span>
                            </div>
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


<!-- Add/Edit stateless text modal -->
<div id="AddEditStatelessTextModal" class="modal" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-font"></i>
                    <?php echo __('Add or edit stateless text'); ?>
                </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div ng-show="addLink">
                    <div class="row">


                        <div class="col-lg-12">
                            <label class="control-label hintmark_red" for="modalLinkUrl">
                                <?php echo __('URL'); ?>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="icon-prepend fas fa-external-link-alt"></i>
                                    </span>
                                </div>
                                <input type="text"
                                       class="form-control"
                                       id="modalLinkUrl"
                                       placeholder="https://openitcockpit.io">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <label class="control-label hintmark_red" for="modalLinkDescription">
                                <?php echo __('Description Text'); ?>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="icon-prepend fa fa-tag"></i>
                                    </span>
                                </div>
                                <input type="text"
                                       class="form-control"
                                       placeholder="<?php echo __('Official page for openITCOCKPIT'); ?>"
                                       id="modalLinkDescription">
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col-lg-12">
                            <div class="custom-control custom-checkbox custom-control-down margin-bottom-10">

                                <input type="checkbox"
                                       class="custom-control-input"
                                       name="checkbox"
                                       id="modalLinkNewTab">
                                <label class="custom-control-label" for="modalLinkNewTab">
                                    <?php echo __('Open in new tab'); ?>
                                </label>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-12 text-right margin-top-5">
                            <button id="cancel-insert-link" type="button" class="btn btn-default"
                                    ng-click="addLink = false">
                                <?php echo __('Cancel'); ?>
                            </button>
                            <button id="perform-insert-link" type="button" class="btn btn-primary">
                                <?php echo __('Insert link'); ?>
                            </button>
                        </div>
                    </div>
                    <br/>
                    <br/>
                </div>
                <div class="row" ng-hide="addLink">
                    <div class="col-lg-12">
                        <div class="form-horizontal card">
                            <div class="card-header d-flex">
                                <div class="dropdown">
                                    <button type="button"
                                            class="btn btn-xs btn-default"
                                            id="currentColor"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                            color="#404040">
                                        <i class="fas fa-palette"></i>
                                    </button>
                                    <div class="dropdown-menu flex-wrap" style="width: 10.2rem; padding: 0.5rem"
                                         aria-labelledby="currentColor">
                                        <button type="button"
                                                class="btn d-inline-block bg-widget-statusGreen-gradient width-2 height-2 p-0 rounded-0 js-panel-color hover-effect-dot waves-effect waves-themed dropdown-item dashboardColorPickerBorder"
                                                data-panel-setstyle="bg-widget-statusGreen-gradient"
                                                ng-click="widget.color='widget-statusGreen'"
                                                select-color="true" color="#356E35"
                                                style="margin:1px;"></button>
                                        <button type="button"
                                                class="btn d-inline-block bg-widget-statusYellow-gradient width-2 height-2 p-0 rounded-0 js-panel-color hover-effect-dot waves-effect waves-themed dropdown-item dashboardColorPickerBorder"
                                                data-panel-setstyle="bg-widget-statusYellow-gradient"
                                                ng-click="widget.color='widget-statusYellow'"
                                                style="margin:1px;"></button>
                                        <button type="button"
                                                class="btn d-inline-block bg-widget-statusRed-gradient width-2 height-2 p-0 rounded-0 js-panel-color hover-effect-dot waves-effect waves-themed dropdown-item dashboardColorPickerBorder"
                                                data-panel-setstyle="bg-widget-statusRed-gradient"
                                                ng-click="widget.color='widget-statusRed'"
                                                style="margin:1px;"></button>
                                        <button type="button"
                                                class="btn d-inline-block bg-widget-statusGrey-gradient width-2 height-2 p-0 rounded-0 js-panel-color hover-effect-dot waves-effect waves-themed dropdown-item dashboardColorPickerBorder"
                                                data-panel-setstyle="bg-widget-statusGrey-gradient"
                                                ng-click="widget.color='widget-statusGrey'"
                                                style="margin:1px;"></button>
                                        <button type="button"
                                                class="btn d-inline-block bg-widget-default width-2 height-2 p-0 rounded-0 js-panel-color hover-effect-dot waves-effect waves-themed dropdown-item dashboardColorPickerBorder"
                                                data-panel-setstyle="bg-widget-default"
                                                ng-click="widget.color='widget-default'"
                                                style="margin:1px;"></button>
                                        <button type="button"
                                                class="btn d-inline-block bg-widget-white width-2 height-2 p-0 rounded-0 js-panel-color hover-effect-dot waves-effect waves-themed dropdown-item dashboardColorPickerBorder"
                                                data-panel-setstyle="bg-widget-white"
                                                ng-click="widget.color='widget-white'"
                                                style="margin:1px;"></button>
                                        <button type="button"
                                                class="btn d-inline-block bg-widget-black-gradient width-2 height-2 p-0 rounded-0 js-panel-color hover-effect-dot waves-effect waves-themed dropdown-item dashboardColorPickerBorder"
                                                data-panel-setstyle="bg-widget-black-gradient"
                                                ng-click="widget.color='widget-black'"
                                                style="margin:1px;"></button>
                                        <button type="button"
                                                class="btn d-inline-block bg-widget-anthracite-gradient width-2 height-2 p-0 rounded-0 js-panel-color hover-effect-dot waves-effect waves-themed dropdown-item dashboardColorPickerBorder"
                                                data-panel-setstyle="bg-widget-anthracite-gradient"
                                                ng-click="widget.color='widget-anthracite'"
                                                style="margin:1px;"></button>
                                        <button type="button"
                                                class="btn d-inline-block bg-widget-colorbomb-gradient width-2 height-2 p-0 rounded-0 js-panel-color hover-effect-dot waves-effect waves-themed dropdown-item dashboardColorPickerBorder"
                                                data-panel-setstyle="bg-widget-colorbomb-gradient"
                                                ng-click="widget.color='widget-colorbomb'"
                                                style="margin:1px;"></button>
                                        <button type="button"
                                                class="btn d-inline-block bg-widget-colorbomb2-gradient width-2 height-2 p-0 rounded-0 js-panel-color hover-effect-dot waves-effect waves-themed dropdown-item dashboardColorPickerBorder"
                                                data-panel-setstyle="bg-widget-colorbomb2-gradient"
                                                ng-click="widget.color='widget-colorbomb2'"
                                                style="margin:1px;"></button>
                                    </div>
                                </div>

                                <div class="dropdown">
                                    <button class="btn btn-xs btn-default dropdown-toggle" type="button"
                                            id="statelessTextFontSize" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                        <i class="fa fa-font"></i>
                                        <?php echo __('Font size'); ?>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="statelessTextFontSize">
                                        <a class="dropdown-item" href="javascript:void(0);" select-fsize="true"
                                           fsize="xx-small"><?php echo __('Smallest'); ?></a>
                                        <a class="dropdown-item" href="javascript:void(0);" select-fsize="true"
                                           fsize="x-small"><?php echo __('Smaller'); ?></a>
                                        <a class="dropdown-item" href="javascript:void(0);" select-fsize="true"
                                           fsize="small"><?php echo __('Small'); ?></a>
                                        <a class="dropdown-item" href="javascript:void(0);" select-fsize="true"
                                           fsize="large"><?php echo __('Big'); ?></a>
                                        <a class="dropdown-item" href="javascript:void(0);" select-fsize="true"
                                           fsize="x-large"><?php echo __('Bigger'); ?></a>
                                        <a class="dropdown-item" href="javascript:void(0);" select-fsize="true"
                                           fsize="xx-large"><?php echo __('Biggest'); ?></a>
                                    </div>
                                </div>

                                <span class="padding-left-10"></span>
                                <button class="btn btn-default btn-xs" wysiwyg="true"
                                        task="bold"><i class="fa fa-bold"></i></button>
                                <button class="btn btn-default btn-xs" wysiwyg="true"
                                        task="italic"><i class="fa fa-italic"></i></button>
                                <button class="btn btn-default btn-xs" wysiwyg="true"
                                        task="underline"><i class="fa fa-underline"></i></button>
                                <span class="padding-left-10"></span>
                                <button class="btn btn-default btn-xs" wysiwyg="true"
                                        task="left"><i class="fa fa-align-left"></i></button>
                                <button class="btn btn-default btn-xs" wysiwyg="true"
                                        task="center"><i class="fa fa-align-center"></i></button>
                                <button class="btn btn-default btn-xs" wysiwyg="true"
                                        task="right"><i class="fa fa-align-right"></i></button>
                                <button class="btn btn-default btn-xs" wysiwyg="true"
                                        task="justify"><i class="fa fa-align-justify"></i></button>
                                <span class="padding-left-10"></span>
                                <button class="btn btn-default btn-xs mr-auto" ng-click="addLink = true"
                                        id="insert-link"><i class="fa fa-link"></i></button>
                            </div>
                            <div class="card-body" ng-class="{'has-error': errors.text}">
                                <textarea class="form-control"
                                          style="width: 100%; height: 200px;"
                                          id="docuText"></textarea>
                                <div ng-repeat="error in errors.text">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row margin-top-10">
                    <div class="col-lg-6">
                        <label class="control-label hintmark_red" for="addEditStatelessPosX">
                            <?php echo __('Position X'); ?>
                        </label>
                        <div class="input-group" ng-class="{'has-error': errors.x}">
                            <div class="input-group-prepend">
                                    <span class="input-group-text"><i
                                            class="icon-prepend fas fa-map-marked-alt"></i></span>
                            </div>
                            <input type="number"
                                   class="form-control"
                                   min="0"
                                   id="addEditStatelessPosX"
                                   placeholder="<?php echo __('0'); ?>"
                                   ng-model="currentItem.x">
                        </div>
                        <div ng-repeat="error in errors.x">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <label class="control-label hintmark_red" for="addEditStatelessPosY">
                            <?php echo __('Position Y'); ?>
                        </label>
                        <div class="input-group" ng-class="{'has-error': errors.y}">
                            <div class="input-group-prepend">
                                    <span class="input-group-text"><i
                                            class="icon-prepend fas fa-map-marked-alt"></i></span>
                            </div>
                            <input type="number"
                                   class="form-control"
                                   min="0"
                                   id="addEditStatelessPosY"
                                   placeholder="<?php echo __('0'); ?>"
                                   ng-model="currentItem.y">
                        </div>
                        <div ng-repeat="error in errors.y">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-lg-10 margin-top-10" ng-class="{'has-error': errors.z_index}">
                        <label class="control-label">
                            <?php echo __('Select layer'); ?>
                        </label>
                        <select
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
                    <div class="col-lg-2">
                        <button class="btn btn-block btn-default margin-top-30" ng-click="addNewLayer()">
                            <?php echo __('Add new layer'); ?>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" ng-click="deleteText()">
                    <?php echo __('Delete'); ?>
                </button>
                <button type="button" class="btn btn-success" ng-click="saveText()">
                    <?php echo __('Save'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Add/Edit stateless icon modal -->
<div id="AddEditStatelessIconModal" class="modal" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-object-ungroup"></i>
                    <?php echo __('Add or edit stateless icon'); ?>
                </h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-times"></i></span>
                    </button>
            </div>
            <div class="modal-body">

                    <div class="row">
                        <div class="col-lg-6">
                            <label class="control-label" for="addEditStatelessIconPosX">
                                <?php echo __('Position X'); ?>
                            </label>
                            <div class="input-group" ng-class="{'has-error': errors.x}">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i
                                            class="icon-prepend fas fa-map-marked-alt"></i></span>
                                </div>
                                <input type="text"
                                       class="form-control"
                                       id="addEditStatelessIconPosX"
                                       placeholder="<?php echo __('0'); ?>"
                                       ng-model="currentItem.x">
                            </div>
                            <div ng-repeat="error in errors.x">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="control-label" for="addEditStatelessIconPosY">
                                <?php echo __('Position Y'); ?>
                            </label>
                            <div class="input-group" ng-class="{'has-error': errors.y}">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i
                                            class="icon-prepend fas fa-map-marked-alt"></i></span>
                                </div>
                                <input type="text"
                                       class="form-control"
                                       id="addEditStatelessIconPosY"
                                       placeholder="<?php echo __('0'); ?>"
                                       ng-model="currentItem.y">
                            </div>
                            <div ng-repeat="error in errors.y">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-10 margin-top-10" ng-class="{'has-error': errors.z_index}">
                            <label class="control-label">
                                <?php echo __('Select layer'); ?>
                            </label>
                            <select
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
                        <div class="col-lg-2">
                            <button class="btn btn-block btn-default margin-top-30" ng-click="addNewLayer()">
                                <?php echo __('Add new layer'); ?>
                            </button>
                        </div>
                    </div>
<div class="row">
                    <div class="col-lg-12 no-padding padding-top-10">
                        <div class="col-lg-12">
                            <?php echo __('Upload new icon'); ?>
                        </div>
                        <div class="col-lg-12 text-info">
                            <i class="fa fa-info-circle"></i>
                            <?php echo __('Max allowed file size: '); ?>
                            {{ maxUploadLimit.string }}
                        </div>
                        <div class="col-lg-12">
                            <div class="profileImg-dropzone dropzone dropzoneStyle icon-dropzone"
                                 action="/map_module/backgroundUploads/icon/.json">
                                <div class="dz-message">
                                    <i class="fas fa-cloud-upload-alt fa-5x text-muted mb-3"></i> <br>
                                    <span class="text-uppercase">Drop files here or click to upload.</span>
                                </div>
                            </div>
                        </div>
                    </div>
</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger mr-auto" ng-click="deleteIcon()">
                    <?php echo __('Delete'); ?>
                </button>
                <button type="button" class="btn btn-success" ng-click="saveIcon()">
                    <?php echo __('Save'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit map item modal -->
<div id="addEditSummaryItemModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-circle"></i>
                    <?php echo __('Add or edit summary state item'); ?>
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
                                id="AddEditSummaryObjectSelect"
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
                    <div class="col-xs-12 col-lg-6 smart-form">
                        <div class="form-group smart-form" ng-class="{'has-error': errors.size_x}">
                            <label class="label hintmark_red"><?php echo __('Radius'); ?></label>
                            <label class="input"> <b class="icon-prepend">
                                    <i class="fa fa-expand"></i>
                                </b>
                                <input type="number" min="0" class="input-sm"
                                       placeholder="<?php echo __('0'); ?>"
                                       ng-model="currentItem.size_x">
                            </label>
                            <div class="help-block">
                                <?php echo __('Keep blank for default radius'); ?>
                            </div>
                            <div ng-repeat="error in errors.size_x">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
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
                                       ng-model="currentItem.show_label">
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

                <button type="button" class="btn btn-danger pull-left" ng-click="deleteSummaryItem()">
                    <?php echo __('Delete'); ?>
                </button>

                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>

                <button type="button" class="btn btn-primary" ng-click="saveSummaryItem()">
                    <?php echo __('Save'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

