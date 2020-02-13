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

/**
 * @var \App\View\AppView $this
 * @var \App\View\Helper\AclHelper $Acl
 */

?>

<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-code-fork fa-fw "></i>
            <?php echo __('Change log') ?>
            <span>>
                <?php echo __('Overview'); ?>
            </span>
        </h1>
    </div>
</div>


<div class="jarviswidget jarviswidget-color-blueDark">
    <header>
        <span class="widget-icon"> <i class="fa fa-code-fork"></i> </span>
        <h2><?php echo __('Change log overview'); ?></h2>

        <div class="widget-toolbar" role="menu">
            <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                <i class="fa fa-refresh"></i>
                <?php echo __('Refresh'); ?>
            </button>

            <button type="button" class="btn btn-xs btn-primary" ng-click="triggerFilter()">
                <i class="fa fa-filter"></i>
                <?php echo __('Filter'); ?>
            </button>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <!-- Filter start -->
            <div class="list-filter well" ng-show="showFilter">
                <h3><i class="fa fa-filter"></i> <?php echo __('Filter'); ?></h3>
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group smart-form">
                            <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                                <input type="text" class="input-sm"
                                       placeholder="<?php echo __('Filter by name'); ?>"
                                       ng-model="filter.Changelogs.name"
                                       ng-model-options="{debounce: 500}">
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group smart-form">
                            <label class="input"> <i class="icon-prepend"
                                                     style="padding-right:14px;"><?php echo __('From'); ?></i>
                                <input type="text" class="input-sm" style="padding-left:50px;"
                                       placeholder="<?php echo __('From date'); ?>"
                                       ng-model="filter.from"
                                       ng-model-options="{debounce: 500}">
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-offset-6 col-md-6">
                        <div class="form-group smart-form">
                            <label class="input"> <i class="icon-prepend"
                                                     style="padding-right:14px;"><?php echo __('To'); ?></i>
                                <input type="text" class="input-sm" style="padding-left:50px;"
                                       placeholder="<?php echo __('From to'); ?>"
                                       ng-model="filter.to"
                                       ng-model-options="{debounce: 500}">
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-xs-12 col-md-3">
                        <fieldset>
                            <legend><?php echo __('Object type'); ?></legend>
                            <div class="form-group smart-form">
                                <?php
                                $models = [
                                    'Commands',
                                    'Contacts',
                                    'Contactgroups',
                                    'Hosts',
                                    'Hostgroups',
                                    'Hosttemplates',
                                    'Services',
                                    'Servicegroups',
                                    'Servicetemplates',
                                    'Timeperiods'
                                ];
                                ?>

                                <?php foreach ($models as $model): ?>
                                    <label class="checkbox small-checkbox-label">
                                        <input type="checkbox" name="checkbox" checked="checked"
                                               ng-model="filter.Models.<?= $model ?>"
                                               ng-false-value="0"
                                               ng-true-value="1"
                                               ng-model-options="{debounce: 500}">
                                        <i class="checkbox-primary"></i>
                                        <?php echo __($model); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </fieldset>
                    </div>


                    <div class="col-xs-12 col-md-3">
                        <fieldset>
                            <legend><?php echo __('Actions'); ?></legend>
                            <div class="form-group smart-form">
                                <label class="checkbox small-checkbox-label">
                                    <input type="checkbox" name="checkbox" checked="checked"
                                           ng-model="filter.Actions.add"
                                           ng-false-value="0"
                                           ng-true-value="1"
                                           ng-model-options="{debounce: 500}">
                                    <i class="checkbox-success ok"></i>
                                    <?php echo __('Add'); ?>
                                </label>

                                <label class="checkbox small-checkbox-label">
                                    <input type="checkbox" name="checkbox" checked="checked"
                                           ng-model="filter.Actions.edit"
                                           ng-false-value="0"
                                           ng-true-value="1"
                                           ng-model-options="{debounce: 500}">
                                    <i class="checkbox-warning warning"></i>
                                    <?php echo __('Edit'); ?>
                                </label>

                                <label class="checkbox small-checkbox-label">
                                    <input type="checkbox" name="checkbox" checked="checked"
                                           ng-model="filter.Actions.copy"
                                           ng-false-value="0"
                                           ng-true-value="1"
                                           ng-model-options="{debounce: 500}">
                                    <i class="checkbox-primary"></i>
                                    <?php echo __('Copy'); ?>
                                </label>

                                <label class="checkbox small-checkbox-label">
                                    <input type="checkbox" name="checkbox" checked="checked"
                                           ng-model="filter.Actions.delete"
                                           ng-false-value="0"
                                           ng-true-value="1"
                                           ng-model-options="{debounce: 500}">
                                    <i class="checkbox-critical critical"></i>
                                    <?php echo __('Delete'); ?>
                                </label>

                                <label class="checkbox small-checkbox-label">
                                    <input type="checkbox" name="checkbox" checked="checked"
                                           ng-model="filter.Actions.activate"
                                           ng-false-value="0"
                                           ng-true-value="1"
                                           ng-model-options="{debounce: 500}">
                                    <i class="checkbox-primary"></i>
                                    <?php echo __('Activate'); ?>
                                </label>

                                <label class="checkbox small-checkbox-label">
                                    <input type="checkbox" name="checkbox" checked="checked"
                                           ng-model="filter.Actions.deactivate"
                                           ng-false-value="0"
                                           ng-true-value="1"
                                           ng-model-options="{debounce: 500}">
                                    <i class="checkbox-primary"></i>
                                    <?php echo __('Deactivate'); ?>
                                </label>
                            </div>
                        </fieldset>
                    </div>

                </div>
                <!-- Filter end -->

                <div class="row">
                    <div class="col-xs-12">
                        <div class="pull-right margin-top-10">
                            <button type="button" ng-click="resetFilter()"
                                    class="btn btn-xs btn-danger">
                                <?php echo __('Reset Filter'); ?>
                            </button>
                        </div>
                    </div>
                </div>

            </div>


            <div class="row">
                <div class="col-xs-12">
                    <ul class="cbp_tmtimeline">
                        <li ng-repeat="change in changes">
                            <time class="cbp_tmtime" datetime="{{change.time}}">
                                <span>{{change.time}}</span>
                                <span>{{change.timeAgoInWords}}</span>
                            </time>
                            <div class="cbp_tmicon txt-color-white {{change.color}}" title="{{change.action}}">
                                <i class="{{change.icon}}"></i>
                            </div>
                            <div class="cbp_tmlabel">
                                <h2 class="font-md">
                                    {{change.model}}:
                                    <strong>
                                        <a ui-sref="{{change.ngState}}({id: change.object_id})" ng-if="change.ngState">
                                            {{change.name}}
                                        </a>
                                        <span ng-if="!change.ngState"
                                              ng-class="{'changelog_delete': change.action ==='delete'}">
                                            {{change.name}}
                                        </span>

                                    </strong>
                                    <span class="font-xs" ng-if="change.includeUser && change.user.id > 0">
                                        <?= __('by') ?>
                                        <?php if ($this->Acl->hasPermission('edit', 'users')): ?>
                                            <a ui-sref="UsersEdit({id: change.user.id})">
                                                    {{change.user.firstname}}
                                                    {{change.user.lastname}}
                                                </a>
                                        <?php else: ?>
                                            {{change.user.firstname}}
                                            {{change.user.lastname}}
                                        <?php endif; ?>
                                    </span>
                                    <span class="font-xs" ng-if="change.includeUser && change.user === null">
                                        <?= __('by Cronjob') ?>
                                    </span>
                                </h2>

                                <!-- Add and copy changes -->
                                <blockquote
                                    ng-class="{'changelog-blockquote-success': change.action ==='add', 'changelog-blockquote-primary': change.action ==='copy'}"
                                    ng-if="change.action === 'add' || change.action === 'copy'">
                                    <div class="blockquote"
                                       ng-repeat="(tableName, tableChanges) in change.data_unserialized">
                                        {{tableName}}

                                        <span ng-repeat="(fieldName, fieldValue) in tableChanges.data"
                                              ng-if="!tableChanges.isArray">
                                            <small class="padding-left-10">
                                                {{fieldName}}: <span class="text-primary">{{fieldValue}}</span>
                                            </small>
                                        </span>

                                        <span ng-repeat="(fieldName, fieldValue) in tableChanges.data"
                                              ng-if="tableChanges.isArray" class="padding-top-5">
                                            <small ng-repeat="(subFieldName, subFieldValue) in fieldValue"
                                                   class="padding-left-10">
                                                {{subFieldName}}: <span class="text-primary">{{subFieldValue}}</span>
                                            </small>
                                            <div class="padding-top-5"></div>
                                        </span>
                                    </div>
                                </blockquote>

                                <!-- Edit changes -->
                                <blockquote class="changelog-blockquote-warning"
                                            ng-if="change.action === 'edit'">
                                    <div class="blockquote"
                                       ng-repeat="(tableName, tableChanges) in change.data_unserialized">
                                        {{tableName}}

                                        <span ng-repeat="(fieldName, fieldValueChanges) in tableChanges.data"
                                              ng-if="!tableChanges.isArray">
                                            <small class="padding-left-10">
                                                {{fieldName}}:
                                                <span class="down">{{fieldValueChanges.old}}</span>
                                                <i class="fa fa-caret-right"></i>
                                                <span class="up">{{fieldValueChanges.new}}</span>
                                            </small>
                                        </span>

                                        <span ng-repeat="(fieldIndex, fieldValueChanges) in tableChanges.data"
                                              ng-if="tableChanges.isArray" class="padding-top-5">
                                            <small ng-repeat="(newFieldName, newFieldValue) in fieldValueChanges.new"
                                                   ng-if="fieldValueChanges.old === null">
                                                {{newFieldName}}:
                                                <span class="up">{{newFieldValue}}</span>
                                            </small>

                                            <small ng-repeat="(oldFieldName, oldFieldValue) in fieldValueChanges.old"
                                                   ng-if="fieldValueChanges.new === null">
                                                {{oldFieldName}}:
                                                <span class="down changelog_delete">{{oldFieldValue}}</span>
                                            </small>

                                            <small ng-repeat="(newFieldName, newFieldValue) in fieldValueChanges.new"
                                                   ng-if="fieldValueChanges.old !== null && fieldValueChanges.new !== null">
                                                {{newFieldName}}:
                                                <span
                                                    ng-class="{'text-primary': fieldValueChanges.old[newFieldName] === newFieldValue, 'down': fieldValueChanges.old[newFieldName] !== newFieldValue}">
                                                    {{fieldValueChanges.old[newFieldName]}}
                                                </span>
                                                <i class="fa fa-caret-right"></i>
                                                <span
                                                    ng-class="{'text-primary': fieldValueChanges.old[newFieldName] === newFieldValue, 'up': fieldValueChanges.old[newFieldName] !== newFieldValue}">
                                                    {{newFieldValue}}
                                                </span>
                                            </small>

                                            <div class="padding-top-5"></div>
                                        </span>
                                    </div>
                                </blockquote>
                            </div>
                        </li>
                    </ul>

                </div>
                <div class="col-xs-12">
                    <scroll scroll="scroll" click-action="changepage" ng-if="scroll"></scroll>
                    <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                    <?php echo $this->element('paginator_or_scroll'); ?>
                </div>
            </div>

        </div>
    </div>
</div>


