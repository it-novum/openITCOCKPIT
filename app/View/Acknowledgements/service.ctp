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

use itnovum\openITCOCKPIT\Core\Views\Service;
use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Servicestatus;
use itnovum\openITCOCKPIT\Core\Views\AcknowledgementService;
use itnovum\openITCOCKPIT\Core\Views\ServicestatusIcon;
use itnovum\openITCOCKPIT\Core\Views\ListSettingsRenderer;

$ListSettingsRenderer = new ListSettingsRenderer($AcknowledgementListsettings);
$ListSettingsRenderer->setPaginator($this->Paginator);

$Service = new Service($service);
$Host = new Host($service);
if (!isset($servicestatus['Servicestatus'])):
    $servicestatus['Servicestatus'] = [];
endif;
$Servicestatus = new Servicestatus($servicestatus['Servicestatus']);

$this->Paginator->options(['url' => Hash::merge($this->params['named'], $this->params['pass'], ['Listsettings' => $AcknowledgementListsettings])]); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
        <h1 class="status_headline <?php echo $Servicestatus->ServiceStatusColor(); ?>">
            <?php echo $Servicestatus->getServiceFlappingIconColored(); ?>
            <i class="fa fa-cog fa-fw"></i>
            <?php echo h($Service->getServicename()); ?>
            <span>
                &nbsp;<?php echo __('on'); ?>
                <?php if ($this->Acl->hasPermission('browser', 'Hosts')): ?>
                    <a href="<?php echo Router::url([
                        'controller' => 'hosts',
                        'action' => 'browser',
                        $Service->getHostId()
                    ]); ?>">
                    <?php printf('%s (%s)', h($Host->getHostname()), h($Host->getAddress())); ?>
                </a>
                <?php else: ?>
                    <?php printf('%s (%s)', h($Host->getHostname()), h($Host->getAddress())); ?>
                <?php endif; ?>
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
        <h5>
            <div class="pull-right">
                <a href="/services/browser/<?php echo $service['Service']['id']; ?>" class="btn btn-primary btn-sm"><i
                            class="fa fa-arrow-circle-left"></i> <?php echo $this->Html->underline('b', __('Back to Service')); ?>
                </a>
                <?php echo $this->element('service_browser_menu'); ?>
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
                            echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']);
                        endif;
                        ?>
                    </div>
                    <div class="widget-toolbar" role="menu">
                        <a href="javascript:void(0);" class="dropdown-toggle selector" data-toggle="dropdown">
                            <i class="fa fa-lg fa-table"></i>
                        </a>
                        <ul class="dropdown-menu arrow-box-up-right pull-right stayOpenOnClick">
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left" my-column="0">
                                    <input type="checkbox" class="pull-left"/> &nbsp; <?php echo __('State'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left" my-column="1">
                                    <input type="checkbox" class="pull-left"/>&nbsp;<?php echo __('Date'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left" my-column="2">
                                    <input type="checkbox" class="pull-left"/>&nbsp;<?php echo __('Author'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left" my-column="3">
                                    <input type="checkbox" class="pull-left"/>&nbsp;<?php echo __('Comment'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left" my-column="4">
                                    <input type="checkbox" class="pull-left"/>&nbsp;<?php echo __('Sticky'); ?>
                                </a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div id="switch-1" class="widget-toolbar" role="menu">
                        <?php
                        echo $this->Form->create('acknowledgements', [
                            'class' => 'form-horizontal clear',
                            'url' => 'service/' . $Service->getId() //reset the URL on submit
                        ]);
                        echo $ListSettingsRenderer->getFromInput();
                        echo $ListSettingsRenderer->getToInput();
                        echo $ListSettingsRenderer->getLimitSelect();

                        $state_types = [
                            'ok' => __('Ok'),
                            'warning' => __('Warning'),
                            'critical' => __('Critical'),
                            'unknown' => __('Unknown'),
                        ];
                        ?>
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-default">
                                <?php echo __('State types'); ?> <i class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right stayOpenOnClick">
                                <?php
                                foreach ($state_types as $state_type => $name):
                                    $checked = '';
                                    if (isset($AcknowledgementListsettings['state_types'][$state_type]) && $AcknowledgementListsettings['state_types'][$state_type] == 1):
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
                    <span class="widget-icon"> <i class="fa fa-history"></i> </span>
                    <h2><?php echo __('Acknowledgement history'); ?> </h2>

                </header>
                <div>
                    <div class="widget-body no-padding">
                        <?php echo $this->ListFilter->renderFilterbox($filters, ['formActionParams' => ['url' => Router::url(Hash::merge($this->params['named'], $this->params['pass'], ['Listsettings' => $AcknowledgementListsettings])), 'merge' => false]], '<i class="fa fa-filter"></i> ' . __('Filter'), false, false); ?>

                        <table id="acknowledgements_list"
                               class="table table-striped table-hover table-bordered smart-form"
                               style="">
                            <thead>
                            <tr>
                                <?php $order = $this->Paginator->param('order'); ?>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'AcknowledgedService.state');
                                    echo $this->Paginator->sort('AcknowledgedService.state', __('State')); ?>
                                </th>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'AcknowledgedService.entry_time');
                                    echo $this->Paginator->sort('AcknowledgedService.entry_time', __('Date')); ?>
                                </th>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'AcknowledgedService.author_name');
                                    echo $this->Paginator->sort('AcknowledgedService.author_name', __('Author')); ?>
                                </th>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'AcknowledgedService.comment_data');
                                    echo $this->Paginator->sort('AcknowledgedService.comment_data', __('Comment')); ?>
                                </th>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'AcknowledgedService.is_sticky');
                                    echo $this->Paginator->sort('AcknowledgedService.is_sticky', __('Sticky')); ?>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php //debug($all_notification); ?>
                            <?php foreach ($all_acknowledgements as $acknowledgement):
                                $AcknowledgementService = new AcknowledgementService($acknowledgement['AcknowledgedService']);
                                $StatusIcon = new ServicestatusIcon($AcknowledgementService->getState());
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo $StatusIcon->getHtmlIcon(); ?>
                                    </td>
                                    <td>
                                        <?php echo $this->Time->format(
                                            $AcknowledgementService->getEntryTime(),
                                            $this->Auth->user('dateformat'),
                                            false,
                                            $this->Auth->user('timezone')
                                        ); ?>
                                    </td>
                                    <td><?php echo h($AcknowledgementService->getAuthorName()); ?></td>
                                    <td><?php echo h($AcknowledgementService->getCommentData()); ?></td>
                                    <td>
                                        <?php
                                        if ($AcknowledgementService->isSticky()):
                                            echo __('True');
                                        else:
                                            echo __('False');
                                        endif;
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if (empty($all_acknowledgements)): ?>
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
        </article>
    </div>
</section>
