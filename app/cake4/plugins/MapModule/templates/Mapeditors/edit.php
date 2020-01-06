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
            <a class="btn btn-xs btn-default" ui-sref="MapsIndex">
                <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i>
                <?php echo __('Back to list'); ?>
            </a>
            <?php if ($this->Acl->hasPermission('view', 'mapeditors', 'mapmodule')): ?>
                <a class="btn btn-xs btn-default" ui-sref="MapeditorsView({id: map.Map.id})">
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


            <div ng-repeat="item in map.Mapitems" class="draggable" ng-dblclick="editItem(item)"
                 style="position:absolute; top: {{item.y}}px; left: {{item.x}}px;  z-index: {{item.z_index}}; cursor: move;"
                 data-id="{{item.id}}" data-type="item" ng-show="item.display">
                <map-item item="item" refresh-interval="0"></map-item>
            </div>

            <div ng-repeat="textItem in map.Maptexts" class="draggable"
                 style="position:absolute; top: {{textItem.y}}px; left: {{textItem.x}}px;  z-index: {{textItem.z_index}}; cursor: move;"
                 data-id="{{textItem.id}}" data-type="text" ng-dblclick="editText(textItem)" ng-show="textItem.display">
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
                 data-id="{{iconItem.id}}" data-type="icon" ng-dblclick="editIcon(iconItem)" ng-show="iconItem.display">
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
                                  ng-if="gadgetItem.gadget === 'Temperature'" refresh-interval="0"></temperature-item>
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
                 data-id="{{summaryItem.id}}" data-type="summaryItem" ng-dblclick="editSummaryItem(summaryItem)"
                 ng-show="summaryItem.display">
                <map-summary-item item="summaryItem" refresh-interval="0"></map-summary-item>
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

            <div class="mapToolbarTool" title="<?php echo __('Add summary status item'); ?>"
                 ng-click="addSummaryItem()">
                <i class="fa fa-lg fa-circle"></i>
            </div>

            <div class="mapToolbarLine"></div>

            <div class="mapToolbarTool" title="<?php echo __('Add gadget'); ?>" ng-click="addGadget()">
                <i class="fa fa-lg fa-dashboard"></i>
            </div>

            <div class="mapToolbarLine"></div>

            <div class="mapToolbarTool" title="<?php echo __('Change background image'); ?>"
                 ng-click="openChangeMapBackgroundModal()">
                <i class="fa fa-lg fa-picture-o"></i>
            </div>

            <div class="mapToolbarLine"></div>

            <div class="mapToolbarTool" title="<?php echo __('Add stateless text'); ?>" ng-click="addText()">
                <i class="fa fa-lg fa-font"></i>
            </div>

            <div class="mapToolbarTool" title="<?php echo __('Add stateless icon'); ?>" ng-click="addIcon()">
                <i class="fa fa-lg fa-object-ungroup"></i>
            </div>
        </div>

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

                    <button class="btn btn-default pull-right margin-right-10" ng-click="uploadIconSet = true">
                        <i class="fa fa-upload"></i>
                        <?php echo __('Upload iconset'); ?>
                    </button>
                </h4>
            </div>
            <div class="modal-body">

                <div ng-hide="uploadIconSet">
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

                <div ng-show="uploadIconSet">
                    <!-- Iconset Upload dropzone -->
                    <div class="row">
                        <div class="col-xs-12">
                            <?php echo __('Upload new iconset (as .zip package)'); ?>
                        </div>
                        <div class="col-xs-12 text-info">
                            <i class="fa fa-info-circle"></i>
                            <?php echo __('Max allowed file size: '); ?>
                            {{ maxUploadLimit.string }}
                        </div>
                        <div class="col-xs-12">
                            <div class="iconset-dropzone dropzone"
                                 action="/map_module/backgroundUploads/iconset/.json">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 padding-top-10 text-info">
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
                    </div>

                    <div class="row">
                        <div class="col-xs-12 padding-top-10">
                            <button class="btn btn-primary pull-right" ng-click="uploadIconSet = false">
                                <i class="fa fa-arrow-left"></i>
                                <?php echo __('Go back to settings'); ?>
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
                    <i class="fa fa-pencil"></i>
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
                                id="AddEditLineObjectSelect"
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
                <br/>
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

                <div class="row" ng-show="currentItem.type !== 'stateless'">
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

<!-- Add/Edit map gadget modal -->
<div id="addEditMapGadgetModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-dashboard"></i>
                    <?php echo __('Add or edit gadget'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form hintmark_red">
                            <?php echo __('Select service'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group" ng-class="{'has-error': errors.object_id}">
                            <select
                                id="AddEditGadgetObjectSelect"
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
                            <?php echo __('Select gadget type'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12" ng-if="iconsets">
                        <div class="row" style="max-height: 200px; overflow: auto;"
                             ng-class="{'has-error-border': errors.iconset}">
                            <?php foreach ($gadgetPreviews as $gadgetName => $gadgetPreview): ?>
                                <div class="col-xs-12 col-md-6 col-lg-3">
                                    <div class="thumbnail"
                                         style="height: 175px; width: 175px;display: flex; align-items: center; overflow: hidden;"
                                         ng-click="currentItem.gadget = '<?php echo $gadgetName; ?>'; currentItem.size_x = null; currentItem.size_y = null;"
                                         ng-class="{ 'selectedMapItem': currentItem.gadget === '<?php echo $gadgetName; ?>' }">
                                        <img class="image_picker_selector"
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
                <br/>

                <div class="row"
                     ng-show="currentItem.gadget !== 'TrafficLight' && currentItem.gadget !== 'ServiceOutput'">
                    <div class="col-xs-12">
                        <div class="form-group smart-form hintmark_red">
                            <?php echo __('Select metric'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group" ng-class="{'has-error': errors.metric}">
                            <select
                                id="AddEditGadgetObjectGaugeSelect"
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
                </div>

                <div class="row" ng-show="currentItem.gadget == 'ServiceOutput'">
                    <div class="col-xs-12">
                        <div class="form-group smart-form hintmark_red">
                            <?php echo __('Select output type'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group" ng-class="{'has-error': errors.output_type}">
                            <select
                                id="AddEditGadgetObjectGaugeSelect"
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
                </div>

                <div class="row" ng-show="currentItem.gadget == 'ServiceOutput'">
                    <div class="col-xs-12">
                        <div class="form-group smart-form" ng-class="{'has-error': errors.font_size}">
                            <label class="label hintmark_red"><?php echo __('Font size'); ?></label>
                            <label class="input"> <b class="icon-prepend">X</b>
                                <input type="number" min="1" class="input-sm"
                                       placeholder="<?php echo __('13'); ?>"
                                       ng-model="currentItem.font_size">
                            </label>
                            <div ng-repeat="error in errors.font_size">
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
                            <label class="label hintmark_red"><?php echo __('Width'); ?></label>
                            <label class="input"> <b class="icon-prepend">
                                    <i class="fa fa-arrows-h"></i>
                                </b>
                                <input type="number" min="0" class="input-sm"
                                       placeholder="<?php echo __('0'); ?>"
                                       ng-model="currentItem.size_x">
                            </label>
                            <div class="help-block">
                                <?php echo __('Keep blank for default width'); ?>
                            </div>
                            <div ng-repeat="error in errors.size_x">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-lg-6 smart-form" ng-class="{'has-error': errors.size_y}">
                        <div class="form-group smart-form">
                            <label class="label hintmark_red"><?php echo __('Height'); ?></label>
                            <label class="input"> <b class="icon-prepend">
                                    <i class="fa fa-arrows-v"></i>
                                </b>
                                <input type="number" min="0" class="input-sm"
                                       placeholder="<?php echo __('0'); ?>"
                                       ng-model="currentItem.size_y">
                            </label>
                        </div>
                        <div class="help-block">
                            <?php echo __('Keep blank for default height'); ?>
                        </div>
                        <div ng-repeat="error in errors.size_y">
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
                                       ng-model="currentItem.show_label">
                                <i class="checkbox-primary"></i>
                                <?php echo __('Show label'); ?>
                            </label>
                        </div>
                    </div>

                </div>


            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-danger pull-left" ng-click="deleteGadget()">
                    <?php echo __('Delete'); ?>
                </button>

                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>

                <button type="button" class="btn btn-primary" ng-click="saveGadget()">
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

                    <div class="col-xs-12 padding-top-10">
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

<!-- Add/Edit stateless text modal -->
<div id="AddEditStatelessTextModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-font"></i>
                    <?php echo __('Add or edit stateless text'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div ng-show="addLink">
                    <div class="row">
                        <div class="col-xs-12 smart-form">
                            <div class="form-group smart-form">
                                <label class="label hintmark_red"><?php echo __('URL'); ?></label>
                                <label class="input"> <b class="icon-prepend">
                                        <i class="fa fa-external-link-square"></i>
                                    </b>
                                    <input type="text" class="input-sm"
                                           placeholder="https://openitcockpit.io"
                                           id="modalLinkUrl">
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-12 smart-form">
                            <div class="form-group smart-form">
                                <label class="label hintmark_red"><?php echo __('URL'); ?></label>
                                <label class="input"> <b class="icon-prepend">
                                        <i class="fa fa-tag"></i>
                                    </b>
                                    <input type="text" class="input-sm"
                                           placeholder="<?php echo __('Official page for openITCOCKPIT'); ?>"
                                           id="modalLinkDescription">
                                </label>
                            </div>
                        </div>
                    </div>
                    <br/>

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
                    <div class="col-xs-12">
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
                                                                <span
                                                                    data-original-title="<?php echo __('Green Grass'); ?>"
                                                                    data-placement="left" rel="tooltip"
                                                                    data-widget-setstyle="jarviswidget-color-green"
                                                                    select-color="true" color="#356E35"
                                                                    class="bg-color-green"></span></li>
                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span
                                                                    data-original-title="<?php echo __('Dark Green'); ?>"
                                                                    data-placement="top" rel="tooltip"
                                                                    data-widget-setstyle="jarviswidget-color-greenDark"
                                                                    select-color="true" color="#496949"
                                                                    class="bg-color-greenDark"></span></li>
                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span
                                                                    data-original-title="<?php echo __('Light Green'); ?>"
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
                                                                <span
                                                                    data-original-title="<?php echo __('Fade Pink'); ?>"
                                                                    data-placement="left" rel="tooltip"
                                                                    data-widget-setstyle="jarviswidget-color-pinkDark"
                                                                    select-color="true" color="#A8829F"
                                                                    class="bg-color-pinkDark"></span></li>
                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span
                                                                    data-original-title="<?php echo __('Light Blue'); ?>"
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
                                                                <span
                                                                    data-original-title="<?php echo __('Ocean Blue'); ?>"
                                                                    data-placement="top" rel="tooltip"
                                                                    data-widget-setstyle="jarviswidget-color-blue"
                                                                    select-color="true" color="#57889C"
                                                                    class="bg-color-blue"></span></li>
                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span
                                                                    data-original-title="<?php echo __('Night Sky'); ?>"
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
                                                                <span
                                                                    data-original-title="<?php echo __('Day Light'); ?>"
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
                                                                <span
                                                                    data-original-title="<?php echo __('Dark Orange'); ?>"
                                                                    data-placement="bottom" rel="tooltip"
                                                                    data-widget-setstyle="jarviswidget-color-orangeDark"
                                                                    select-color="true" color="#A57225"
                                                                    class="bg-color-orangeDark"></span></li>
                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span
                                                                    data-original-title="<?php echo __('Red Rose'); ?>"
                                                                    data-placement="bottom" rel="tooltip"
                                                                    data-widget-setstyle="jarviswidget-color-red"
                                                                    select-color="true" color="#A90329"
                                                                    class="bg-color-red"></span></li>
                                            <li style="display: inline-block; margin:0; float: none;">
                                                                <span
                                                                    data-original-title="<?php echo __('Light Red'); ?>"
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
                                    <a href="javascript:void(0);" class="btn btn-default" ng-click="addLink = true"
                                       id="insert-link"><i class="fa fa-link"></i></a>
                                </div>
                                <div class="widget-toolbar pull-right" role="menu"></div>
                            </header>
                            <div>
                                <div class="widget-body" ng-class="{'has-error': errors.text}">
                                        <textarea class="form-control"
                                                  style="width: 100%; height: 200px;"
                                                  id="docuText"></textarea>
                                </div>
                                <div ng-repeat="error in errors.text">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

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

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-danger pull-left" ng-click="deleteText()">
                        <?php echo __('Delete'); ?>
                    </button>

                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?php echo __('Close'); ?>
                    </button>

                    <button type="button" class="btn btn-primary" ng-click="saveText()">
                        <?php echo __('Save'); ?>
                    </button>
                </div>


            </div>
        </div>
    </div>
</div>


<!-- Add/Edit stateless icon modal -->
<div id="AddEditStatelessIconModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-object-ungroup"></i>
                    <?php echo __('Add or edit stateless icon'); ?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form hintmark_red">
                            <?php echo __('Select icon'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12" ng-if="icons">
                        <div class="row" style="max-height: 200px; overflow: auto;"
                             ng-class="{'has-error-border': errors.icon}">
                            <div class="col-xs-12 col-md-6 col-lg-3" ng-repeat="icon in icons">
                                <div class="thumbnail"
                                     style="height: 155px; width: 175px;display: flex; align-items: center; overflow: hidden;"
                                     ng-click="currentItem.icon = icon"
                                     ng-class="{ 'selectedMapItem': currentItem.icon === icon}">
                                    <button class="btn btn-xs btn-danger"
                                            style="position: absolute; top: 11px; left: 158px;"
                                            ng-click="deleteIconImage(icon)">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                    <img class="image_picker_selector"
                                         ng-src="/map_module/img/icons/{{icon}}">
                                </div>
                            </div>
                        </div>
                        <div ng-repeat="error in errors.icon" class="row">
                            <div class="col-xs-12">
                                <div class="help-block text-danger" style="color: #a94442;">{{ error }}</div>
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

                    <div class="col-xs-12 padding-top-10">
                        <?php echo __('Upload new icon'); ?>
                    </div>
                    <div class="col-xs-12 text-info">
                        <i class="fa fa-info-circle"></i>
                        <?php echo __('Max allowed file size: '); ?>
                        {{ maxUploadLimit.string }}
                    </div>
                    <div class="col-xs-12">
                        <div class="icon-dropzone dropzone"
                             action="/map_module/backgroundUploads/icon/.json">
                        </div>
                    </div>
                </div>


            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left" ng-click="deleteIcon()">
                    <?php echo __('Delete'); ?>
                </button>

                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>

                <button type="button" class="btn btn-primary" ng-click="saveIcon()">
                    <?php echo __('Save'); ?>
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

