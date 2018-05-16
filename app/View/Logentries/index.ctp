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
            <i class="fa fa-file-text-o fa-fw"></i>
            <?php echo __('Log Entries'); ?>
            <span>>
                <?php echo __('Overview'); ?>
            </span>
        </h1>
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
                        <button type="button" class="btn btn-xs btn-primary" ng-click="triggerFilter()">
                            <i class="fa fa-filter"></i>
                            <?php echo __('Filter'); ?>
                        </button>
                    </div>


                    <div class="jarviswidget-ctrls" role="menu"></div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-file-text-o"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Log Entries'); ?> </h2>

                </header>

                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">

                        <div class="list-filter well" ng-show="showFilter">
                            <h3><i class="fa fa-filter"></i> <?php echo __('Filter'); ?></h3>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by record'); ?>"
                                                   ng-model="filter.Logentry.logentry_data"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-lg-6">
                                    <fieldset>
                                        <legend><?php echo __('Filter hy host'); ?></legend>
                                        <div class="form-group smart-form">
                                            <select
                                                    id="HostnameFilterSelect"
                                                    data-placeholder="<?php echo __('Filter by host'); ?>"
                                                    class="form-control"
                                                    chosen="hosts"
                                                    multiple
                                                    ng-model="filter.Host.id"
                                                    callback="loadHosts"
                                                    ng-options="host.key as host.value for host in hosts"
                                                    ng-model-options="{debounce: 500}">
                                            </select>
                                        </div>
                                    </fieldset>
                                </div>

                                <div class="col-xs-12 col-lg-6">
                                    <fieldset>
                                        <legend><?php echo __('Filter by type'); ?></legend>
                                        <div class="form-group smart-form">
                                            <select
                                                    id="LogentryTypeFilter"
                                                    data-placeholder="<?php echo __('Filter by log entry type'); ?>"
                                                    class="form-control"
                                                    chosen="{}"
                                                    multiple
                                                    ng-model="filter.Logentry.logentry_type"
                                                    ng-model-options="{debounce: 500}">
                                                <?php
                                                foreach ($logentry_types as $typeId => $typeName):
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
                            <table id="logentries_list"
                                   class="table table-striped table-hover table-bordered smart-form">
                                <thead>
                                <tr>
                                    <th class="no-sort" ng-click="orderBy('Logentry.entry_time')">
                                        <i class="fa" ng-class="getSortClass('Logentry.entry_time')"></i>
                                        <?php echo __('Date'); ?>
                                    </th>

                                    <th class="no-sort" ng-click="orderBy('Logentry.logentry_type')">
                                        <i class="fa" ng-class="getSortClass('Logentry.logentry_type')"></i>
                                        <?php echo __('Type'); ?>
                                    </th>

                                    <th class="no-sort" ng-click="orderBy('Logentry.logentry_data')">
                                        <i class="fa" ng-class="getSortClass('Logentry.logentry_data')"></i>
                                        <?php echo __('Record'); ?>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="logentry in logentries">
                                    <td>
                                        {{ logentry.Logentry.entry_time }}
                                    </td>
                                    <td>
                                        {{ logentry.Logentry.logentry_type_string }}
                                    </td>
                                    <td>
                                        {{ logentry.Logentry.logentry_data }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row margin-top-10 margin-bottom-10">
                            <div class="row margin-top-10 margin-bottom-10" ng-show="logentries.length == 0">
                                <div class="col-xs-12 text-center txt-color-red italic">
                                    <?php echo __('No entries match the selection'); ?>
                                </div>
                            </div>
                        </div>

                        <scroll scroll="scroll" click-action="changepage" ng-if="scroll"></scroll>
                        <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                        <?php echo $this->element('paginator_or_scroll'); ?>
                    </div>
                </div>
        </article>
    </div>
</section>
