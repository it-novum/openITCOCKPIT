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

use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Hoststatus;

//Flapping Workaround while the status date is not loaded via Angular
echo $this->Html->script('lib/FlappingWorkaround.js');

$Host = new Host($host);
if (!isset($hoststatus['Hoststatus'])):
    $hoststatus['Hoststatus'] = [];
endif;
$Hoststatus = new Hoststatus($hoststatus['Hoststatus']);
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-6 col-lg-6">
        <h1 class="status_headline <?php echo $Hoststatus->HostStatusColor(); ?>">
            <?php echo $Hoststatus->getHostFlappingIconColored(); ?>
            <i class="fa fa-desktop fa-fw"></i>
            <?php echo h($Host->getHostname()) ?>
            <span>
                (<?php echo h($Host->getAddress()) ?>)
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-6 col-lg-6">
        <h5>
            <div class="pull-right">
                <a href="<?php echo Router::url([
                    'controller' => 'hosts',
                    'action' => 'browser',
                    $Host->getId()]); ?>"
                   class="btn btn-primary btn-sm">
                    <i class="fa fa-arrow-circle-left"></i> <?php echo $this->Html->underline('b', __('Back to Host')); ?>
                </a>
                <?php echo $this->element('host_browser_menu'); ?>
            </div>
        </h5>
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
                    <span class="widget-icon"> <i class="fa fa-history"></i> </span>
                    <h2><?php echo __('Host check history'); ?> </h2>

                </header>

                <div>
                    <div class="widget-body no-padding">

                        <div class="list-filter well" ng-show="showFilter">
                            <h3><i class="fa fa-filter"></i> <?php echo __('Filter'); ?></h3>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend" style="padding-right:14px;"><?php echo __('From'); ?></i>
                                            <input type="text" class="input-sm" style="padding-left:50px;"
                                                   placeholder="<?php echo __('From Date'); ?>"
                                                   ng-model="filter.from"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo __('Filter by output'); ?>"
                                                   ng-model="filter.Hostcheck.output"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend" style="padding-right:14px;"><?php echo __('To'); ?></i>
                                            <input type="text" class="input-sm" style="padding-left:50px;"
                                                   placeholder="<?php echo __('To Date'); ?>"
                                                   ng-model="filter.to"
                                                   ng-model-options="{debounce: 500}">
                                        </label>
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-xs-12 col-md-3">
                                    <fieldset>
                                        <legend><?php echo __('States'); ?></legend>
                                        <div class="form-group smart-form">
                                            <label class="checkbox small-checkbox-label">
                                                <input type="checkbox" name="checkbox" checked="checked"
                                                       ng-model="filter.Hostcheck.state.recovery"
                                                       ng-model-options="{debounce: 500}">
                                                <i class="checkbox-success"></i>
                                                <?php echo __('Up'); ?>
                                            </label>

                                            <label class="checkbox small-checkbox-label">
                                                <input type="checkbox" name="checkbox" checked="checked"
                                                       ng-model="filter.Hostcheck.state.down"
                                                       ng-model-options="{debounce: 500}">
                                                <i class="checkbox-danger"></i>
                                                <?php echo __('Down'); ?>
                                            </label>

                                            <label class="checkbox small-checkbox-label">
                                                <input type="checkbox" name="checkbox" checked="checked"
                                                       ng-model="filter.Hostcheck.state.unreachable"
                                                       ng-model-options="{debounce: 500}">
                                                <i class="checkbox-default"></i>
                                                <?php echo __('Unreachable'); ?>
                                            </label>
                                        </div>
                                    </fieldset>
                                </div>

                                <div class="col-xs-12 col-md-3">
                                    <fieldset>
                                        <legend><?php echo __('State Types'); ?></legend>
                                        <div class="form-group smart-form">
                                            <label class="checkbox small-checkbox-label">
                                                <input type="checkbox" name="checkbox" checked="checked"
                                                       ng-model="filter.Hostcheck.state_types.soft"
                                                       ng-model-options="{debounce: 500}">
                                                <i class="checkbox-primary"></i>
                                                <?php echo __('Soft'); ?>
                                            </label>

                                            <label class="checkbox small-checkbox-label">
                                                <input type="checkbox" name="checkbox" checked="checked"
                                                       ng-model="filter.Hostcheck.state_types.hard"
                                                       ng-model-options="{debounce: 500}">
                                                <i class="checkbox-primary"></i>
                                                <?php echo __('Hard'); ?>
                                            </label>

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

                        <table id="hostchecks_list"
                               class="table table-striped table-hover table-bordered smart-form"
                               style="">
                            <thead>
                            <tr>
                                <th class="no-sort" ng-click="orderBy('Hostcheck.state')">
                                    <i class="fa" ng-class="getSortClass('Hostcheck.state')"></i>
                                    <?php echo __('State'); ?>
                                </th>
                                <th class="no-sort" ng-click="orderBy('Hostcheck.start_time')">
                                    <i class="fa" ng-class="getSortClass('Hostcheck.start_time')"></i>
                                    <?php echo __('Date'); ?>
                                </th>
                                <th class="no-sort" ng-click="orderBy('Hostcheck.current_check_attempt')">
                                    <i class="fa" ng-class="getSortClass('Hostcheck.current_check_attempt')"></i>
                                    <?php echo __('Check attempt'); ?>
                                </th>
                                <th class="no-sort" ng-click="orderBy('Hostcheck.state_type')">
                                    <i class="fa" ng-class="getSortClass('Hostcheck.state_type')"></i>
                                    <?php echo __('State type'); ?>
                                </th>
                                <th class="no-sort" ng-click="orderBy('Hostcheck.output')">
                                    <i class="fa" ng-class="getSortClass('Hostcheck.output')"></i>
                                    <?php echo __('Host output'); ?>
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr ng-repeat="Hostcheck in hostchecks">

                                <td class="text-center">
                                    <hoststatusicon state="Hostcheck.Hostcheck.state"></hoststatusicon>
                                </td>
                                <td>
                                    {{ Hostcheck.Hostcheck.start_time }}
                                </td>
                                <td class="text-center">
                                    {{ Hostcheck.Hostcheck.current_check_attempt }}/{{ Hostcheck.Hostcheck.max_check_attempts }}
                                </td>
                                <td class="text-center">
                                        <span ng-show="Hostcheck.Hostcheck.is_hardstate">
                                            <?php echo __('Hard'); ?>
                                        </span>

                                    <span ng-show="!Hostcheck.Hostcheck.is_hardstate">
                                            <?php echo __('Soft'); ?>
                                        </span>

                                </td>
                                <td>
                                    {{ Hostcheck.Hostcheck.output }}
                                </td>
                            </tr>

                            </tbody>
                        </table>


                        <div class="row margin-top-10 margin-bottom-10">
                            <div class="row margin-top-10 margin-bottom-10" ng-show="hostchecks.length == 0">
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
            </div>
        </div>
</section>