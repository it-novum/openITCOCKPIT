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
<?php $this->Paginator->options(['url' => $this->params['named']]); ?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-file-text-o fa-fw "></i>
            <?php echo __('Reporting'); ?>
            <span>>
                <?php echo __('Instant Report');
                ?>
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
                        <?php
                        if ($this->Acl->hasPermission('add')):
                            echo $this->Html->link(
                                __('New'),
                                '/' . $this->params['controller'] . '/add', [
                                    'class' => 'btn btn-xs btn-success',
                                    'icon' => 'fa fa-plus'
                                ]
                            );
                            echo " "; //Fix HTML
                        endif;
                        ?>
                        <button type="button" class="btn btn-xs btn-primary" ng-click="triggerFilter()">
                            <i class="fa fa-filter"></i>
                            <?php echo __('Filter'); ?>
                        </button>
                    </div>
                </header>
                <div>
                    <div class="widget-body no-padding">
                        <div class="list-filter well" ng-show="showFilter">
                            <h3><i class="fa fa-filter"></i> <?php echo __('Filter'); ?></h3>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-file-text-o"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by instant report name'); ?>"
                                                   ng-model="filter.instantreport.name"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>
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
                            <table id="intantreport_list" class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <th class="no-sort" ng-click="orderBy('Instantreport.name')">
                                        <i class="fa" ng-class="getSortClass('Instantreport.name')"></i>
                                        <?php echo __('Name'); ?>
                                    </th>
                                    <th class="no-sort">
                                        <?php echo __('Evaluation'); ?>
                                    </th>
                                    <th class="no-sort">
                                        <?php echo __('Type'); ?>
                                    </th>
                                    <th class="no-sort">
                                        <?php echo __('Time period'); ?>
                                    </th>
                                    <th class="no-sort">
                                        <?php echo __('Summary display'); ?>
                                    </th>
                                    <th class="no-sort">
                                        <?php echo __('Consider downtimes'); ?>
                                    </th>
                                    <th class="no-sort">
                                        <?php echo __('Send interval'); ?>
                                    </th>
                                    <th class="no-sort">
                                        <?php echo __('Send to'); ?>
                                    </th>
                                    <th class="no-sort text-center">
                                        <i class="fa fa-cog fa-lg"></i>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="instantreport in instantreports">
                                    <td>
                                        {{ instantreport.Instantreport.name }}
                                    </td>
                                    <td>
                                        <i class="fa fa-{{ instantreport.Instantreport.evaluation.icon }}"></i>
                                        {{ instantreport.Instantreport.evaluation.label }}
                                    </td>
                                    <td>
                                        {{ instantreport.Instantreport.type }}
                                    </td>
                                    <td>
                                        {{ instantreport.Timeperiod.name }}
                                    </td>
                                    <td class="text-center">
                                        <i class="fa
                                        {{instantreport.Instantreport.summary === '1'
                                            ? ' fa-check fa-lg text-success'
                                            : ' fa-times fa-lg text-danger'
                                        }}
                                        "></i>
                                    </td>
                                    <td class="text-center">
                                        <i class="fa
                                        {{instantreport.Instantreport.downtimes === '1'
                                            ? ' fa-check fa-lg text-success'
                                            : ' fa-times fa-lg text-danger'
                                        }}
                                        "></i>
                                    </td>
                                    <td>
                                        {{ instantreport.Instantreport.send_interval }}
                                    </td>

                                    <td>
                                        <ul class="list-unstyled">
                                            <ul class="list-unstyled">
                                                <li ng-repeat="user in instantreport.User">
                                                    <a href="users/edit/{{user.InstantreportsToUser.user_id}}"
                                                       ng-if="user.allowEdit">
                                                        {{ user.firstname }} {{ user.lastname }}
                                                    </a>

                                                    <span ng-if="!user.allowEdit">
                                                        {{ user.firstname }} {{ user.lastname }}
                                                    </span>
                                                </li>
                                            </ul>
                                        </ul>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row margin-top-10 margin-bottom-10">
                            <div class="row margin-top-10 margin-bottom-10" ng-show="instantreports.length == 0">
                                <div class="col-xs-12 text-center txt-color-red italic">
                                    <?php echo __('No entries match the selection'); ?>
                                </div>
                            </div>
                        </div>
                        <paginator paging="paging" click-action="changepage" ng-if="paging"></paginator>
                    </div>
                </div>
            </div>
    </div>
</section>
