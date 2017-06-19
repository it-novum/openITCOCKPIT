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

use itnovum\openITCOCKPIT\Core\Views\Command;
use itnovum\openITCOCKPIT\Core\Views\Contact;
use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Hoststatus;
use itnovum\openITCOCKPIT\Core\Views\HoststatusIcon;
use itnovum\openITCOCKPIT\Core\Views\NotificationHost;
use itnovum\openITCOCKPIT\Core\Views\ListSettingsRenderer;

$Host = new Host($host);
$Hoststatus = new Hoststatus($hoststatus['Hoststatus']);
$ListSettingsRenderer = new ListSettingsRenderer($NotificationListsettings);
$ListSettingsRenderer->setPaginator($this->Paginator);

$this->Paginator->options(['url' => Hash::merge($this->params['named'], $this->params['pass'], ['Listsettings' => $NotificationListsettings])]); ?>
<div id="error_msg"></div>
<div class="alert auto-hide alert-success" id="flashSuccess"
     style="display:none"><?php echo __('Command sent successfully'); ?></div>
<div class="alert auto-hide alert-danger" id="flashFailed"
     style="display:none"><?php echo __('Error while sending command'); ?></div>
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
                    $Host->getId()]); ?>" class="btn btn-primary btn-sm">
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
                            echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']);
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
                                    &nbsp; <?php echo __('Host'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="2"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Date'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="3"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('User'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="4"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Notification type'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="5"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Output'); ?></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div id="switch-1" class="widget-toolbar" role="menu">
                        <?php
                        echo $this->Form->create('notifications', [
                            'class' => 'form-horizontal clear',
                            'url' => 'hostNotification/' . $Host->getId() //reset the URL on submit
                        ]);
                        echo $ListSettingsRenderer->getFromInput();
                        echo $ListSettingsRenderer->getToInput();
                        echo $ListSettingsRenderer->getLimitSelect();
                        echo $ListSettingsRenderer->getApply();
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
                        <?php
                        echo $this->ListFilter->renderFilterbox($filters, [
                            'formActionParams' => [
                                'url' => Router::url(
                                    Hash::merge(
                                        $this->params['named'],
                                        $this->params['pass'],
                                        ['Listsettings' => $NotificationListsettings]
                                    )
                                ),
                                'merge' => false
                            ]
                        ], '<i class="fa fa-filter"></i> ' . __('Filter'), false, false);
                        ?>

                        <table id="host_list" class="table table-striped table-hover table-bordered smart-form"
                               style="">
                            <thead>
                            <tr>
                                <?php $order = $this->Paginator->param('order'); ?>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'NotificationHost.state');
                                    echo $this->Paginator->sort('NotificationHost.state', __('State')); ?>
                                </th>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'Host.name');
                                    echo $this->Paginator->sort('Host.name', __('Host')); ?>
                                </th>
                                <th class="no-sort">
                                    <?php echo $this->Utils->getDirection($order, 'NotificationHost.start_time');
                                    echo $this->Paginator->sort('NotificationHost.start_time', __('Date')); ?>
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
                                    <?php echo $this->Utils->getDirection($order, 'NotificationHost.output');
                                    echo $this->Paginator->sort('NotificationHost.output', __('Output')); ?>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($all_notification as $notification):
                                $Host = new Host($notification);
                                $NotificationHost = new NotificationHost($notification);
                                $StatusIcon = new HoststatusIcon($NotificationHost->getState());
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
                                        <?php echo h($this->Time->format(
                                            $NotificationHost->getStartTime(),
                                            $this->Auth->user('dateformat'),
                                            false,
                                            $this->Auth->user('timezone')
                                        )); ?>
                                    </td>
                                    <td>
                                        <?php
                                        //Checking if the contact exists or was deleted
                                        if ($Contact->getId()): ?>
                                            <a href="<?php echo Router::url([
                                                'controller' => 'contacts',
                                                'action' => 'edit',
                                                $Contact->getId()]); ?>">
                                                <?php echo h($Contact->getName()); ?>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        //Checking if the command exists or was deleted
                                        if ($Command->getId()): ?>
                                            <a href="<?php echo Router::url([
                                                'controller' => 'commands',
                                                'action' => 'edit',
                                                $Command->getId()]); ?>">
                                                <?php echo h($Command->getName()); ?>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo h($NotificationHost->getOutput()); ?></td>
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
