<?php
// Copyright (C) <2022>  <it-novum GmbH>
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

/**
 * @var \App\View\AppView $this
 *
 */
?>

<div class="list-filter card margin-bottom-10">
    <div class="card-header">
        <i class="fa fa-bookmark"></i> <?php echo __('Filter Bookmarks'); ?>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xs-12 col-md-6 margin-bottom-10">
                <div class="form-group">
                    <input type="text" class="form-control form-control-sm"
                           ng-model="bookmark.name"
                           placeholder="<?php echo __('Filterbookmark name'); ?>"
                    >
                    <span class="txt-color-red">{{bookmarkError}}</span>
                </div>
            </div>
            <div class="col-xs-12 col-md-6 margin-bottom-10">
                <div class="form-group margin-left-10">
                    <div class="form-check form-check-inline">
                        <input type="checkbox"
                               id="defaultBookmark"
                               class="form-check-input"
                               name="checkbox"
                               checked="checked"
                               ng-model="bookmark.default">
                        <label class="form-check-label"
                               for="defaultBookmark">
                            <?php echo __('Set as default'); ?>
                        </label>
                    </div>
                    <button type="button" id="saveBookmark" class="btn btn-xs btn-primary float-right" ng-click="saveBookmark()">
                        <?php echo __('Save filter as bookmark'); ?>
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12 margin-bottom-10">
                <div class="form-group">
                    <button type="button" id="deleteBookmark" class="btn btn-xs btn-danger float-right" ng-click="" data-toggle="modal" data-target="#deleteBookmarkModal">
                        <?php echo __('Delete bookmark'); ?>
                    </button>
                    <button type="button" id="showBookmarkUrl" class="btn btn-xs btn-primary  margin-right-10 float-right" ng-click="showBookmarkFilterUrl()">
                        <?php echo __('Show bookmark URL'); ?>
                    </button>
                </div>
            </div>
        </div>
        <div class="row" ng-show="showFilterUrl">
            <div class="col-xs-12 col-md-12 margin-bottom-10">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" id="filterUrl"
                               ng-model="filterUrl"
                        >
                        <div class="input-group-prepend">
                            <button class="btn btn-secondary" ng-click="copy2Clipboard()">
                                <?php echo __('Copy to clipboard'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12 margin-bottom-10">
                <label><?php echo __('Saved bookmarks'); ?></label>
                <select class="form-control" ng-change="itemChanged()"
                        ng-options="bookmark.id as bookmark.name for bookmark in bookmarks"
                        ng-model="select">
                    <!--<option ng-selected="select.id == item.id" ng-repeat="item in bookmarks" ng-value="item.id">{{item.name}}</option>-->

                </select>
            </div>
        </div>
    </div>
</div>
<!-- End Filter -->
<div class="modal fade" id="deleteBookmarkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-color-danger txt-color-white">
                <h5 class="modal-title">
                    <?php echo __('Attention!'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <?php echo __('Do you really want delete the selected object?'); ?>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo __('Close'); ?></button>
                <button type="button" class="btn btn-danger" ng-click="deleteBookmark()"><?php echo __('Delete'); ?></button>
            </div>
        </div>
    </div>
</div>


