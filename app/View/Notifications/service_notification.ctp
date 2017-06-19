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
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.+
//	License agreement and license key will be shipped with the order
//	confirmation.

use itnovum\openITCOCKPIT\Core\Views\Command;
use itnovum\openITCOCKPIT\Core\Views\Contact;
use itnovum\openITCOCKPIT\Core\Views\NotificationService;
use itnovum\openITCOCKPIT\Core\Views\Service;
use itnovum\openITCOCKPIT\Core\Views\ServicestatusIcon;
use itnovum\openITCOCKPIT\Core\Servicestatus;
use itnovum\openITCOCKPIT\Core\Views\Host;

$Service = new Service($service);
$Host = new Host($service);
$Servicestatus = new Servicestatus($servicestatus['Servicestatus']);

$this->Paginator->options(['url' => Hash::merge($this->params['named'], $this->params['pass'], ['Listsettings' => $NotificationListsettings])]); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
        <h1 class="page-title <?php echo $Servicestatus->ServiceStatusColor(); ?>">
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
                <a href="<?php echo Router::url([
                    'controller' => 'services',
                    'action' => 'browser',
                    $Service->getId()
                ]); ?>" class="btn btn-primary btn-sm">
                    <i class="fa fa-arrow-circle-left"></i> <?php echo $this->Html->underline('b', __('Back to Service')); ?>
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
                        <ul class="dropdown-menu arrow-box-up-right pull-right">
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left" my-column="0">
                                    <input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('State'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left" my-column="1">
                                    <input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Host'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left" my-column="2">
                                    <input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Service'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left" my-column="3">
                                    <input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Date'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left" my-column="4">
                                    <input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Contact'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left" my-column="5">
                                    <input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Notification method'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left" my-column="6">
                                    <input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Output'); ?>
                                </a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div id="switch-1" class="widget-toolbar" role="menu">
                        <?php
                        echo $this->Form->create('notifications', [
                            'class' => 'form-horizontal clear',
                            'url' => 'serviceNotification/' . $service['Service']['id'] //reset the URL on submit
                        ]);

                        ?>

                        <div class="widget-toolbar pull-left" role="menu">
                            <span style="line-height: 32px;" class="pull-left"><?php echo __('From:'); ?></span>
                            <input class="form-control text-center pull-left margin-left-10" style="width: 78%;"
                                   type="text" maxlength="255"
                                   value="<?php if (isset($NotificationListsettings['from'])): echo $NotificationListsettings['from'];
                                   else: echo date('d.m.Y H:i', strtotime('3 days ago')); endif; ?>"
                                   name="data[Listsettings][from]">
                        </div>

                        <div class="widget-toolbar pull-left" role="menu">
                            <span style="line-height: 32px;" class="pull-left"><?php echo __('To:'); ?></span>
                            <input class="form-control text-center pull-left margin-left-10" style="width: 85%;"
                                   type="text" maxlength="255"
                                   value="<?php if (isset($NotificationListsettings['to'])): echo $NotificationListsettings['to'];
                                   else: echo date('d.m.Y H:i', time()); endif; ?>" name="data[Listsettings][to]">
                        </div>

                        <div class="btn-group">
                            <?php
                            $listoptions = [
                                '30' => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value' => 30,
                                    'human' => 30,
                                    'selector' => '#listoptions_limit',
                                ],
                                '50' => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value' => 50,
                                    'human' => 50,
                                    'selector' => '#listoptions_limit',
                                ],
                                '100' => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value' => 100,
                                    'human' => 100,
                                    'selector' => '#listoptions_limit',
                                ],
                                '300' => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value' => 300,
                                    'human' => 300,
                                    'selector' => '#listoptions_limit',
                                ],
                            ];

                            $selected = 30;
                            if (isset($NotificationListsettings['limit']) && isset($listoptions[$NotificationListsettings['limit']]['human'])) {
                                $selected = $listoptions[$NotificationListsettings['limit']]['human'];
                            }
                            ?>
                            <button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-default">
                                <span id="listoptions_limit"><?php echo $selected; ?></span> <i
                                        class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <?php foreach ($listoptions as $listoption): ?>
                                    <li>
                                        <a href="javascript:void(0);" class="listoptions_action"
                                           selector="<?php echo $listoption['selector']; ?>"
                                           submit_target="<?php echo $listoption['submit_target']; ?>"
                                           value="<?php echo $listoption['value']; ?>"><?php echo $listoption['human']; ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <input type="hidden"
                                   value="<?php if (isset($NotificationListsettings['limit'])): echo $NotificationListsettings['limit']; endif; ?>"
                                   id="listoptions_hidden_limit" name="data[Listsettings][limit]"/>
                        </div>

                        <button class="btn btn-xs btn-success toggle"><i
                                    class="fa fa-check"></i> <?php echo __('Apply'); ?></button>

                        <?php
                        echo $this->Form->end();
                        ?>
                    </div>

                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon"> <i class="fa fa-envelope"></i> </span>
                    <h2><?php echo __('Notifications'); ?> </h2>

                </header>
                <div>

                    <div class="widget-body no-padding">
                        <?php echo $this->ListFilter->renderFilterbox($filters, [
                            'formActionParams' => [
                                'url' => Router::url(
                                    Hash::merge(
                                        $this->params['named'],
                                        $this->params['pass'],
                                        ['Listsettings' => $NotificationListsettings]
                                    )
                                ), 'merge' => false
                            ]
                        ], '<i class="fa fa-filter"></i> ' . __('Filter'), false, false); ?>

                        <table id="host_list" class="table table-striped table-hover table-bordered smart-form"
                               style="">
                            <thead>
                            <tr>
                                <?php $order = $this->Paginator->param('order'); ?>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'state');
                                    echo $this->Paginator->sort('state', __('State')); ?>
                                </th>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'Host.name');
                                    echo $this->Paginator->sort('Host.name', __('Host')); ?>
                                </th>
                                <th class="no-sort">
                                    <?php
                                    if ($DbBackend->isNdoUtils()) :
                                        echo $this->Utils->getDirection($order, 'NotificationService.servicename');
                                        echo $this->Paginator->sort('NotificationService.servicename', __('Service'));
                                    endif;

                                    if ($DbBackend->isCrateDb()):
                                        echo $this->Utils->getDirection($order, 'Service.name');
                                        echo $this->Paginator->sort('Service.name', __('Service'));
                                    endif;
                                    ?>
                                </th>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'NotificationService.start_time');
                                    echo $this->Paginator->sort('NotificationService.start_time', __('Date')); ?>
                                </th>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'Contact.name');
                                    echo $this->Paginator->sort('Contact.name', __('Contact')); ?>
                                </th>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'Command.name');
                                    echo $this->Paginator->sort('Command.name', __('Notification method')); ?>
                                </th>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'NotificationService.output');
                                    echo $this->Paginator->sort('NotificationService.output', __('Output')); ?>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($all_notification as $notification):
                                $NotificationService = new NotificationService($notification);
                                $StatusIcon = new ServicestatusIcon($NotificationService->getState());
                                $Command = new Command($notification['Command']);
                                $Contact = new Contact($notification['Contact']);
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo $StatusIcon->getHtmlIcon(); ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($this->Acl->hasPermission('browser', 'Hosts')):
                                            if ($Host->getHostname()): ?>
                                                <a href="<?php echo Router::url([
                                                    'controller' => 'hosts',
                                                    'action' => 'browser',
                                                    $Host->getId()
                                                ]); ?>">
                                                    <?php echo h($Host->getHostname()); ?>
                                                </a>
                                            <?php endif;
                                        else:
                                            echo h($Host->getHostname());
                                        endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($this->Acl->hasPermission('browser', 'Services')):
                                            if ($Service->getServicename()): ?>
                                                <a href="<?php echo Router::url([
                                                    'controller' => 'services',
                                                    'action' => 'browser',
                                                    $Service->getId()
                                                ]); ?>">
                                                    <?php echo h($Service->getServicename()); ?>
                                                </a>
                                            <?php endif;
                                        else:
                                            echo h($Service->getServicename());
                                        endif; ?>
                                    </td>

                                    <td><?php echo h($this->Time->format(
                                            $NotificationService->getStartTime(),
                                            $this->Auth->user('dateformat'),
                                            false,
                                            $this->Auth->user('timezone')
                                        )); ?>
                                    </td>
                                    <td>
                                        <?php
                                        //Checking if the contact exists or was deleted
                                        if ($Contact->getName()):
                                            if ($this->Acl->hasPermission('edit', 'Contacts')): ?>
                                                <a href="<?php echo Router::url(['controller' => 'contacts', 'action' => 'edit', $Contact->getId()]); ?>">
                                                    <?php echo h($Contact->getName()); ?>
                                                </a>
                                            <?php else:
                                                echo h($Contact->getName());
                                            endif;
                                        endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        //Checking if the contact exists or was deleted
                                        if ($Command->getName()):
                                            if ($this->Acl->hasPermission('edit', 'Commands')): ?>
                                                <a href="<?php echo Router::url(['controller' => 'commands', 'action' => 'edit', $Command->getId()]); ?>">
                                                    <?php echo h($Command->getName()); ?>
                                                </a>
                                            <?php else:
                                                echo h($Command->getName());
                                            endif;
                                        endif; ?>
                                    </td>
                                    <td><?php echo h($NotificationService->getOutput()); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if (empty($all_notification)): ?>
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
