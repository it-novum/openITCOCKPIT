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
            <i class="fa fa-usd fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('User defined macros'); ?>
            </span>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="alert alert-block alert-warning">
            <a class="close" data-dismiss="alert" href="#">×</a>
            <h4 class="alert-heading">
                <i class="fa fa-exclamation-triangle"></i>
                <?php echo __('Security notice'); ?>
            </h4>
            <?php echo __('User defined macros can also be used inside of check commands. This could lead to unwanted code execution.'); ?>
            <br />
            <?php echo __('It is recommended to only provide access for a certain group of users to edit commands and user defined macros.'); ?>
        </div>
    </div>
</div>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">

                        <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                            <i class="fa fa-refresh"></i>
                            <?php echo __('Refresh'); ?>
                        </button>

                        <?php if ($this->Acl->hasPermission('add', 'macros')): ?>
                            <button type="button" class="btn btn-xs btn-success" ng-click="triggerAddModal()">
                                <i class="fa fa-plus"></i> <?php echo __('New'); ?>
                            </button>
                        <?php endif; ?>

                    </div>
                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-usd"></i> </span>
                    <h2 class="hidden-mobile hidden-tablet"><?php echo __('User defined macros'); ?> </h2>
                </header>
                <div>

                    <div class="widget-body no-padding">

                        <div class="mobile_table">
                            <table id="macrosTable" class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <th class="no-sort">
                                        <?php echo __('Name'); ?>
                                    </th>
                                    <th class="no-sort">
                                        <?php echo __('Value'); ?>
                                    </th>
                                    <th class="no-sort">
                                        <?php echo __('Description'); ?>
                                    </th>
                                    <th class="no-sort">
                                        <?php echo __('Actions'); ?>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="macro in macros">
                                    <td class="text-primary bold">
                                        {{macro.name}}
                                    </td>
                                    <td>
                                        <code ng-class="{'macroPassword': macro.password}">
                                            {{macro.value}}
                                        </code>
                                    </td>
                                    <td>
                                        {{macro.description}}
                                    </td>
                                    <td>
                                        <button class="btn btn-default txt-color-red btn-sm"
                                                title="<?php echo __('Hide value'); ?>"
                                                ng-click="macro.password = 1"
                                                ng-hide="macro.password">
                                            <i class="fa fa-eye-slash fa-lg"></i>
                                        </button>

                                        <button class="btn btn-default txt-color-blue btn-sm"
                                                title="<?php echo __('Show value'); ?>"
                                                ng-click="macro.password = 0"
                                                ng-show="macro.password">
                                            <i class="fa fa-eye fa-lg"></i>
                                        </button>

                                        <button class="btn btn-default btn-sm" ng-click="triggerEditModal(macro);"
                                                title="<?php echo __('Edit macro'); ?>">
                                            <i class="fa fa-cog fa-lg"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row margin-top-10 margin-bottom-10">
                            <div class="row margin-top-10 margin-bottom-10" ng-show="macros.length == 0">
                                <div class="col-xs-12 text-center txt-color-red italic">
                                    <?php echo __('No entries match the selection'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    

                    <div class="col-xs-12 padding-bottom-10 text-info">
                        <i class="fa fa-info-circle"></i>
                        <?php echo __('Nagios supports up to 256 $USERx$ macros ($USER1$ through $USER256$)'); ?>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>


<!-- Add macro modal -->
<div id="addMacroModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-usd"></i>
                    <?php echo __('Add user defined macro'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form hintmark_red">
                            <?php echo __('Select macro name'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group" ng-class="{'has-error': errors.name}">
                            <select
                                    id="AddMacroNameSelect"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="availableMacros"
                                    ng-options="value as value for (key , value) in availableMacros"
                                    ng-model="post.Macro.name">
                            </select>
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-lg-6 smart-form">
                        <div class="form-group smart-form" ng-class="{'has-error': errors.value}">
                            <label class="label hintmark_red"><?php echo __('Value'); ?></label>
                            <label class="input"> <b class="icon-prepend">
                                    <i class="fa fa-usd"></i>
                                </b>
                                <input type="text" class="input-sm" ng-class="{'macroPassword': post.Macro.password}"
                                       ng-model="post.Macro.value">
                            </label>
                            <div ng-repeat="error in errors.value">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-lg-6 smart-form" ng-class="{'has-error': errors.description}">
                        <div class="form-group smart-form">
                            <label class="label"><?php echo __('Description'); ?></label>
                            <label class="input"> <b class="icon-prepend">
                                    <i class="fa fa-pencil"></i>
                                </b>
                                <input type="text" class="input-sm"
                                       ng-model="post.Macro.description">
                            </label>
                        </div>
                        <div ng-repeat="error in errors.description">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>

                <div class="row padding-top-10">
                    <div class="col-xs-12 smart-form" ng-class="{'has-error': errors.password}">

                        <label class="checkbox small-checkbox-label">
                            <input type="checkbox" name="checkbox"
                                   ng-true-value="1"
                                   ng-false-value="0"
                                   ng-model="post.Macro.password">
                            <i class="checkbox-primary"></i>
                            <?php echo __('Hide value'); ?>
                            <div ng-repeat="error in errors.password">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block">
                                <?php echo __('Blur macro value to prevent accidentally leak values if your PC is connected to a projector or television.'); ?>
                                <?php echo __('Security notice: The value will be still written to the HTML document in plaintext!'); ?>
                            </div>
                        </label>

                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>

                <button type="button" class="btn btn-primary" ng-click="saveMacro()">
                    <?php echo __('Save'); ?>
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Edit macro modal -->
<div id="editMacroModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-usd"></i>
                    <?php echo __('Edit user defined macro'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form hintmark_red">
                            <?php echo __('Select macro name'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group" ng-class="{'has-error': errors.name}">
                            <select
                                    id="EditMacroNameSelect"
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="availableMacros"
                                    ng-options="value as value for (key , value) in availableMacros"
                                    ng-model="editPost.Macro.name">
                            </select>
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>


                            <div class="help-block txt-color-yellow">
                                <i class="fa fa-exclamation-triangle"></i>
                                <?php echo __('If you change the macro name, you manually need to edit all occurrences of the macro!'); ?>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-lg-6 smart-form">
                        <div class="form-group smart-form" ng-class="{'has-error': errors.value}">
                            <label class="label hintmark_red"><?php echo __('Value'); ?></label>
                            <label class="input"> <b class="icon-prepend">
                                    <i class="fa fa-usd"></i>
                                </b>
                                <input type="text" class="input-sm"
                                       ng-class="{'macroPassword': editPost.Macro.password}"
                                       ng-model="editPost.Macro.value">
                            </label>
                            <div ng-repeat="error in errors.value">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-lg-6 smart-form" ng-class="{'has-error': errors.description}">
                        <div class="form-group smart-form">
                            <label class="label"><?php echo __('Description'); ?></label>
                            <label class="input"> <b class="icon-prepend">
                                    <i class="fa fa-pencil"></i>
                                </b>
                                <input type="text" class="input-sm"
                                       ng-model="editPost.Macro.description">
                            </label>
                        </div>
                        <div ng-repeat="error in errors.description">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </div>
                </div>

                <div class="row padding-top-10">
                    <div class="col-xs-12 smart-form" ng-class="{'has-error': errors.password}">

                        <label class="checkbox small-checkbox-label">
                            <input type="checkbox" name="checkbox"
                                   ng-true-value="1"
                                   ng-false-value="0"
                                   ng-model="editPost.Macro.password">
                            <i class="checkbox-primary"></i>
                            <?php echo __('Hide value'); ?>
                            <div ng-repeat="error in errors.password">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block">
                                <?php echo __('Blur macro value to prevent accidentally leak values if your PC is connected to a projector or television.'); ?>
                                <?php echo __('Security notice: The value will be still written to the HTML document in plaintext!'); ?>
                            </div>
                        </label>

                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-danger pull-left" ng-click="deleteMacro()">
                    <?php echo __('Delete'); ?>
                </button>

                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>

                <button type="button" class="btn btn-primary" ng-click="editMacro()">
                    <?php echo __('Save'); ?>
                </button>
            </div>
        </div>
    </div>
</div>