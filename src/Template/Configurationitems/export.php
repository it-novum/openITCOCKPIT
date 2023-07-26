<?php
// Copyright (C) <2018>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

?>
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?= __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="WizardsIndex">
            <i class="fa-solid fa-wand-magic-sparkles"></i> <?= __('Export / Import'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa-solid fa-file-export"></i> <?= __('Export'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?= __('Export configuration items for /root Container'); ?>
                </h2>
                <div class="panel-toolbar">
                    <ul class="nav nav-tabs border-bottom-0 nav-tabs-clean" role="tablist">
                        <?php if ($this->Acl->hasPermission('export', 'configurationitems')): ?>
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" ui-sref="ConfigurationitemsExport"
                                   role="tab">
                                    <i class="fa-solid fa-file-export pr-1"></i><?= __('Export'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->Acl->hasPermission('import', 'configurationitems')): ?>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" ui-sref="ConfigurationitemsImport" role="tab">
                                    <i class="fa-solid fa-file-import pr-1"></i><?= __('Import'); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content fuelux">
                    <form class="form-horizontal">
                        <fieldset class="padding-bottom-20">
                            <legend class="fs-md fieldset-legend-border-bottom"
                                    ng-class="{'fieldset-legend-border-bottom-danger': (errors | json) !== '{}'}">
                                <h4>
                                    <?= __('Configuration items'); ?>
                                    <span class="text-danger font-xs pl-1 fw-300"
                                          ng-show="(errors | json) !== '{}'">
                                        <?= __('You must select at least one configuration item for export.'); ?>
                                    </span>
                                </h4>
                            </legend>
                            <div class="col-12 py-2">
                                <div class="form-group">
                                    <label class="control-label" for="Commands">
                                        <?= __('Commands'); ?>
                                    </label>
                                    <select
                                        id="Commands"
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        multiple
                                        chosen="commands"
                                        ng-options="command.key as command.value for command in commands"
                                        ng-model="post.Configurationitems.commands._ids">
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="Timeperiods">
                                        <?= __('Time periods'); ?>
                                    </label>
                                    <select
                                        id="Timeperiods"
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        multiple
                                        chosen="timeperiods"
                                        ng-options="timeperiod.key as timeperiod.value for timeperiod in timeperiods"
                                        ng-model="post.Configurationitems.timeperiods._ids">
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="Contacts">
                                        <?= __('Contacts'); ?>
                                    </label>
                                    <select
                                        id="Contacts"
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        multiple
                                        chosen="contacts"
                                        ng-options="contact.key as contact.value for contact in contacts"
                                        ng-model="post.Configurationitems.contacts._ids">
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="Contactgroups">
                                        <?= __('Contact groups'); ?>
                                    </label>
                                    <select
                                        id="Contactgroups"
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        multiple
                                        chosen="contactgroups"
                                        ng-options="contactgroup.key as contactgroup.value for contactgroup in contactgroups"
                                        ng-model="post.Configurationitems.contactgroups._ids">
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="Servicetemplates">
                                        <?= __('Service templates'); ?>
                                    </label>
                                    <select
                                        id="Servicetemplates"
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        multiple
                                        chosen="servicetemplates"
                                        ng-options="servicetemplate.key as servicetemplate.value for servicetemplate in servicetemplates"
                                        ng-model="post.Configurationitems.servicetemplates._ids">
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="Servicetemplategroups">
                                        <?= __('Service template groups'); ?>
                                    </label>
                                    <select
                                        id="Servicetemplategroups"
                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                        class="form-control"
                                        multiple
                                        chosen="servicetemplategroups"
                                        ng-options="servicetemplategroup.key as servicetemplategroup.value for servicetemplategroup in servicetemplategroups"
                                        ng-model="post.Configurationitems.servicetemplategroups._ids">
                                    </select>
                                </div>
                            </div>
                            <div class="card margin-top-10">
                                <div class="card-body">
                                    <div class="float-right">
                                        <button class="btn btn-primary"
                                                ng-disabled="isGenerating"
                                                type="button"
                                                ng-click="submit()">
                                            <i class="fas fa-spinner fa-spin" ng-show="isGenerating"></i>
                                            <?= __('Export configuration items'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
