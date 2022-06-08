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

<div class="card-header">
    <div class="row align-items-center">
        <div class="col-1">
            <i class="fa fa-filter"></i> <?php echo __('Filter'); ?>
        </div>
        <div class="col-11 form-inline">
            <div class="col-6 offset-3">
                <div class="form-group chosen-small">
                    <select class="form-control" chosen="bookmarks" ng-change="itemChanged(bookmark)"
                            ng-options="bookmark.id as (bookmark.default == 1)?bookmark.name+' ⭐':bookmark.name for bookmark in bookmarks"
                            ng-model="select">
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="col-3 no-padding">
                <div class="btn-group pull-left">
                    <button type="button"
                            ng-show="bookmark.uuid"
                            class="btn btn-default btn-xs waves-effect waves-themed"
                            ng-click="computeBookmarkUrl()" data-toggle="modal" data-target="#showBookmarkModal"
                            title="<?= __('Edit bookmark'); ?>">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button"
                            ng-show="bookmark.uuid"
                            class="btn btn-primary btn-xs waves-effect waves-themed"
                            ng-click="computeBookmarkUrl()" data-toggle="modal" data-target="#showBookmarkModal"
                            title="<?= __('Share filter'); ?>">
                        <i class="far fa-bookmark"></i>
                    </button>
                    <button type="button"
                            ng-show="bookmark.id && bookmark.user_id"
                            id="deleteBookmark"
                            class="btn btn-danger btn-xs waves-effect waves-themed"
                            data-toggle="modal" data-target="#deleteBookmarkModal"
                            title="<?= __('Delete current filter'); ?>">
                        <i class="fa fa-trash"></i>
                    </button>
                    <button ng-click="saveBookmark(0)"
                            ng-show="bookmark.uuid"
                            class="btn btn-success btn-xs waves-effect waves-themed">
                        <?= __('Update filter'); ?>
                    </button>
                </div>
                <div class="btn-group pull-right">
                    <button ng-click="saveBookmark(0)"
                            class="btn btn-success btn-xs waves-effect waves-themed">
                        <i class="fas fa-plus"></i>
                        <?= __('Save as new filter'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="row align-items-top" ng-if="errors.name">
        <div class="col-4 offset-2 padding-left-0">
            <div ng-repeat="error in errors.name">
                <div class="help-block text-danger">{{ error }}</div>
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
                <button type="button" class="btn btn-danger"
                        ng-click="deleteBookmark()"><?php echo __('Delete'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Cancel'); ?></button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="showBookmarkModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info txt-color-white">
                <h5 class="modal-title">
                    <?php echo __('Bookmark URL'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <input type="text"
                           class="form-control"
                           id="filterUrl"
                           readonly="readonly"
                           ng-model="filterUrl">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary"
                                type="button"
                                ng-click="copy2Clipboard()"
                                title="<?= __('Copy to clipboard'); ?>">
                            <i class="fa fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close'); ?></button>
            </div>
        </div>
    </div>
</div>
