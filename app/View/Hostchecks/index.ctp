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
use itnovum\openITCOCKPIT\Core\Views\HoststatusIcon;
use \itnovum\openITCOCKPIT\Core\Views\Hostcheck;
use itnovum\openITCOCKPIT\Core\Views\ListSettingsRenderer;

$ListSettingsRenderer = new ListSettingsRenderer($HostcheckListsettings);
$ListSettingsRenderer->setPaginator($this->Paginator);

$Host = new Host($host);
if (!isset($hoststatus['Hoststatus'])):
    $hoststatus['Hoststatus'] = [];
endif;
$Hoststatus = new Hoststatus($hoststatus['Hoststatus']);

$this->Paginator->options(['url' => Hash::merge($this->params['named'], $this->params['pass'], ['Listsettings' => $HostcheckListsettings])]); ?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-6 col-lg-6">
        <h1 class="page-title <?php echo $Hoststatus->HostStatusColor(); ?>">
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
                        <?php echo $this->Html->link(__('Filter'), 'javascript:', ['class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-filter']); ?>
                        <?php
                        if ($isFilter):
                            echo $this->ListFilter->resetLink(null, [
                                'class' => 'btn-danger btn-xs',
                                'icon' => 'fa fa-times',
                                'url' => 'index/' . $host['Host']['id']
                            ]);
                        endif;
                        ?>
                    </div>
                    <div class="widget-toolbar" role="menu">
                        <a href="javascript:void(0);" class="dropdown-toggle selector" data-toggle="dropdown"><i
                                    class="fa fa-lg fa-table"></i></a>
                        <ul class="dropdown-menu arrow-box-up-right pull-right">
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="0"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('State'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="1"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Date'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="2"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Check attempt'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="3"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Sate type'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="4"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Host output'); ?></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div id="switch-1" class="widget-toolbar" role="menu">
                        <?php
                        echo $this->Form->create('hostchecks', [
                            'class' => 'form-horizontal clear',
                            'url' => 'index/' . $host['Host']['id'] //reset the URL on submit
                        ]);
                        echo $ListSettingsRenderer->getFromInput();
                        echo $ListSettingsRenderer->getToInput();
                        echo $ListSettingsRenderer->getLimitSelect();

                        $state_types = [
                            'recovery' => __('Recovery'),
                            'down' => __('Down'),
                            'unreachable' => __('Unreachable'),
                        ];
                        ?>

                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-default">
                                <?php echo __('State types'); ?> <i class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <?php
                                foreach ($state_types as $state_type => $name):
                                    $checked = '';
                                    if (isset($HostcheckListsettings['state_types'][$state_type]) && $HostcheckListsettings['state_types'][$state_type] == 1):
                                        $checked = 'checked="checked"';
                                    endif;
                                    ?>
                                    <li>
                                        <input type="hidden" value="0"
                                               name="data[Listsettings][state_types][<?php echo $state_type; ?>]"/>
                                    </li>
                                    <li style="width: 100%;"><a href="javascript:void(0)"
                                                                class="listoptions_checkbox text-left"><input
                                                    type="checkbox"
                                                    name="data[Listsettings][state_types][<?php echo $state_type; ?>]"
                                                    value="1" <?php echo $checked; ?>/> &nbsp; <?php echo $name; ?></a>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                        <?php

                        echo $ListSettingsRenderer->getApply();
                        echo $this->Form->end();
                        ?>
                    </div>

                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon"> <i class="fa fa-check-square-o"></i> </span>
                    <h2><?php echo __('Check history'); ?> </h2>

                </header>

                <!-- widget div-->
                <div>

                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php echo $this->ListFilter->renderFilterbox($filters, ['formActionParams' => ['url' => Router::url(Hash::merge($this->params['named'], $this->params['pass'], ['Listsettings' => $HostcheckListsettings])), 'merge' => false]], '<i class="fa fa-filter"></i> ' . __('Filter'), false, false); ?>

                        <table id="hostchecks_list" class="table table-striped table-hover table-bordered smart-form">
                            <thead>
                            <tr>
                                <?php $order = $this->Paginator->param('order'); ?>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'Hostcheck.state');
                                    echo $this->Paginator->sort('Hostcheck.state', __('State')); ?>
                                </th>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'Hostcheck.start_time');
                                    echo $this->Paginator->sort('Hostcheck.start_time', __('Date')); ?>
                                </th>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'current_check_attempt');
                                    echo $this->Paginator->sort('current_check_attempt', __('Check attempt')); ?>
                                </th>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'Hostcheck.state_type');
                                    echo $this->Paginator->sort('Hostcheck.state_type', __('State type')); ?>
                                </th>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'Hostcheck.output');
                                    echo $this->Paginator->sort('Hostcheck.output', __('Host output')); ?>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php //debug($all_notification); ?>
                            <?php
                            foreach ($all_hostchecks as $hostcheck):
                                $Hostcheck = new Hostcheck($hostcheck['Hostcheck']);
                                $StatusIcon = new HoststatusIcon($Hostcheck->getState());
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo $StatusIcon->getHtmlIcon(); ?>
                                    </td>
                                    <td>
                                        <?php echo h($this->Time->format(
                                            $Hostcheck->getStartTime(),
                                            $this->Auth->user('dateformat'),
                                            false,
                                            $this->Auth->user('timezone')
                                        )); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo h(sprintf('%s/%s',
                                            $Hostcheck->getCurrentCheckAttempt(),
                                            $Hostcheck->getMaxCheckAttempts()
                                        )); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo h($this->Status->humanServiceStateType(
                                            $Hostcheck->isHardstate()
                                        )); ?>
                                    </td>
                                    <td>
                                        <?php echo h($Hostcheck->getOutput()); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if (empty($all_hostchecks)): ?>
                            <div class="noMatch">
                                <center>
                                    <span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
                                </center>
                            </div>
                        <?php endif; ?>

                        <div style="padding: 5px 10px;">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="dataTables_info" style="line-height: 32px;"
                                         id="datatable_fixed_column_info"><?php echo $this->Paginator->counter(__('Page') . ' {:page} ' . __('of') . ' {:pages}, ' . __('Total') . ' {:count} ' . __('entries')); ?></div>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <div class="dataTables_paginate paging_bootstrap">
                                        <?php echo $this->Paginator->pagination([
                                            'ul' => 'pagination',
                                        ]); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</section>