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
            <i class="fa fa-pencil-square-o fa-fw"></i>
            <?php echo __('Service templates'); ?>
            <span>>
            <?php echo __('Overview'); ?>
        </span>
        </h1>
    </div>
</div>

<massdelete></massdelete>
<?php if ($this->Acl->hasPermission('add', 'servicetemplates')): ?>
    <add-servicetemplates-to-servicetemplategroup></add-servicetemplates-to-servicetemplategroup>
<?php endif; ?>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <button type="button" class="btn btn-xs btn-default" ng-click="load()">
                            <i class="fa fa-refresh"></i>
                            <?php echo __('Refresh'); ?>
                        </button>

                        <?php if ($this->Acl->hasPermission('add', 'servicetemplates')): ?>
                            <a class="btn btn-xs btn-success" ui-sref="ServicetemplatesAdd">
                                <i class="fa fa-plus"></i>
                                <?php echo __('New'); ?>
                            </a>
                        <?php endif; ?>

                        <button type="button" class="btn btn-xs btn-primary" ng-click="triggerFilter()">
                            <i class="fa fa-filter"></i>
                            <?php echo __('Filter'); ?>
                        </button>
                    </div>

                    <span class="widget-icon hidden-mobile"> <i class="fa fa-pencil-square-o"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Service templates overview'); ?></h2>

                </header>
                <div>
                    <div class="widget-body no-padding">
                        <div class="list-filter well" ng-show="showFilter">
                            <h3><i class="fa fa-filter"></i> <?php echo __('Filter'); ?></h3>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by service template name'); ?>"
                                                   ng-model="filter.Servicetemplates.template_name"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by service template description'); ?>"
                                                   ng-model="filter.Servicetemplates.description"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-cog"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by service name'); ?>"
                                                   ng-model="filter.Servicetemplates.name"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                    <fieldset>
                                        <legend><?php echo __('Service template types'); ?></legend>
                                        <div class="form-group smart-form">
                                            <select
                                                id="Instance"
                                                data-placeholder="<?php echo __('Filter by type'); ?>"
                                                class="form-control"
                                                chosen="{}"
                                                multiple
                                                ng-model="filter.Servicetemplates.servicetemplatetype_id"
                                                ng-model-options="{debounce: 500}">
                                                <?php
                                                foreach ($types as $typeId => $typeName):
                                                    printf('<option value="%s">%s</option>', h($typeId), h($typeName));
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>
                                    </fieldset>
                                </div>

                            </div>


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

                        <div class="mobile_table">
                            <table class="table table-striped table-hover table-bordered smart-form">
                                <thead>
                                <tr>
                                    <th class="no-sort sorting_disabled width-15">
                                        <i class="fa fa-check-square-o fa-lg"></i>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Servicetemplates.template_name')">
                                        <i class="fa" ng-class="getSortClass('Servicetemplates.template_name')"></i>
                                        <?php echo __('Service template name'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Servicetemplates.name')">
                                        <i class="fa" ng-class="getSortClass('Servicetemplates.name')"></i>
                                        <?php echo __('Service name'); ?>
                                    </th>
                                    <th class="no-sort" ng-click="orderBy('Servicetemplates.description')">
                                        <i class="fa" ng-class="getSortClass('Servicetemplates.description')"></i>
                                        <?php echo __('Description'); ?>
                                    </th>
                                    <th class="no-sort text-center">
                                    <i class="fa fa-cog"></i>
                                    </th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr ng-repeat="servicetemplate in servicetemplates">
                                    <td class="text-center" class="width-15">
                                        <?php if ($this->Acl->hasPermission('delete', 'servicetemplates')): ?>
                                            <input type="checkbox"
                                                   ng-model="massChange[servicetemplate.Servicetemplate.id]"
                                                   ng-show="servicetemplate.Servicetemplate.allow_edit">
                                        <?php endif; ?>
                                    </td>
                                    <td>{{servicetemplate.Servicetemplate.template_name}}</td>
                                    <td>{{servicetemplate.Servicetemplate.name}}</td>
                                    <td>{{servicetemplate.Servicetemplate.description}}</td>
                                    <td class="width-50">
                                        <div class="btn-group">
                                            <?php if ($this->Acl->hasPermission('edit', 'servicetemplates')): ?>
                                                <a ui-sref="ServicetemplatesEdit({id: servicetemplate.Servicetemplate.id})"
                                                   ng-if="servicetemplate.Servicetemplate.allow_edit"
                                                   class="btn btn-default">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                </a>
                                                <a href="javascript:void(0);"
                                                   ng-if="!servicetemplate.Servicetemplate.allow_edit"
                                                   class="btn btn-default disabled">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                </a>
                                            <?php else: ?>
                                                <a href="javascript:void(0);" class="btn btn-default disabled">
                                                    &nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
                                            <?php endif; ?>
                                            <a href="javascript:void(0);" data-toggle="dropdown"
                                               class="btn btn-default dropdown-toggle"><span
                                                        class="caret"></span></a>
                                            <ul class="dropdown-menu pull-right"
                                                id="menuHack-{{servicetemplate.Servicetemplate.id}}">
                                                <?php if ($this->Acl->hasPermission('edit', 'servicetemplates')): ?>
                                                    <li ng-if="servicetemplate.Servicetemplate.allow_edit">
                                                        <a ui-sref="ServicetemplatesEdit({id:servicetemplate.Servicetemplate.id})">
                                                            <i class="fa fa-cog"></i>
                                                            <?php echo __('Edit'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>


                                                <?php if ($this->Acl->hasPermission('usedBy', 'servicetemplates')): ?>
                                                    <li>
                                                        <a ui-sref="ServicetemplatesUsedBy({id:servicetemplate.Servicetemplate.id})">
                                                            <i class="fa fa-reply-all fa-flip-horizontal"></i>
                                                            <?php echo __('Used by'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php if ($this->Acl->hasPermission('view', 'documentations')): ?>
                                                    <li>
                                                        <a ui-sref="DocumentationsView({uuid:servicetemplate.Servicetemplate.uuid, type:'servicetemplate'})">
                                                            <i class="fa fa-book"></i>
                                                            <?php echo __('Documentation'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>

                                                <?php if ($this->Acl->hasPermission('delete', 'servicetemplates')): ?>
                                                    <li class="divider"
                                                        ng-if="servicetemplate.Servicetemplate.allow_edit"></li>
                                                    <li ng-if="servicetemplate.Servicetemplate.allow_edit">
                                                        <a href="javascript:void(0);"
                                                           class="txt-color-red"
                                                           ng-click="confirmDelete(getObjectForDelete(servicetemplate))">
                                                            <i class="fa fa-trash-o"></i> <?php echo __('Delete'); ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row margin-top-10 margin-bottom-10">
                            <div class="row margin-top-10 margin-bottom-10" ng-show="servicetemplates.length == 0">
                                <div class="col-xs-12 text-center txt-color-red italic">
                                    <?php echo __('No entries match the selection'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row margin-top-10 margin-bottom-10">
                            <div class="col-xs-12 col-md-2 text-muted text-center">
                                <span ng-show="selectedElements > 0">({{selectedElements}})</span>
                            </div>
                            <div class="col-xs-12 col-md-2">
                            <span ng-click="selectAll()" class="pointer">
                                <i class="fa fa-lg fa-check-square-o"></i>
                                <?php echo __('Select all'); ?>
                            </span>
                            </div>
                            <div class="col-xs-12 col-md-2">
                            <span ng-click="undoSelection()" class="pointer">
                                <i class="fa fa-lg fa-square-o"></i>
                                <?php echo __('Undo selection'); ?>
                            </span>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <a ui-sref="ServicetemplatesCopy({ids: linkForCopy()})" class="a-clean">
                                    <i class="fa fa-lg fa-files-o"></i>
                                    <?php echo __('Copy'); ?>
                                </a>
                            </div>
                            <div class="col-xs-12 col-md-2 txt-color-red">
                            <span ng-click="confirmDelete(getObjectsForDelete())" class="pointer">
                                <i class="fa fa-lg fa-trash-o"></i>
                                <?php echo __('Delete all'); ?>
                            </span>
                            </div>
                            <div class="xol-xs-12 col-md-2">
                                <div class="btn-group">
                                    <a href="javascript:void(0);" class="btn btn-default"><?php echo __('More'); ?></a>
                                    <a href="javascript:void(0);" data-toggle="dropdown"
                                       class="btn btn-default dropdown-toggle">
                                        <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <?php if ($this->Acl->hasPermission('add', 'servicetemplates')): ?>
                                            <li>
                                                <a ng-click="confirmAddServicetemplatessToServicetemplategroup(getObjectsForDelete())"
                                                   class="a-clean pointer">
                                                    <i class="fa fa-plus-circle"></i> <?php echo __('Add to service template group'); ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <scroll scroll="scroll" click-action="changepage" ng-if="scroll"></scroll>
                        <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                        <?php echo $this->element('paginator_or_scroll'); ?>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>



