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

use itnovum\openITCOCKPIT\Core\Servicestatus;
use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Views\Service;
use itnovum\openITCOCKPIT\Core\Views\ServicestatusIcon;

$Host = new Host($host);

$this->Paginator->url($this->params['url']); ?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-cogs fa-fw "></i>
            <?php echo __('Host'); ?>
            <span>>
                <?php echo __('Services'); ?>
            </span>
        </h1>
    </div>
</div>

<?php echo $this->Flash->render('positive'); ?>
<div id="error_msg"></div>
<div class="alert alert-success alert-block" id="flashSuccess" style="display:none;">
    <a href="#" data-dismiss="alert" class="close">×</a>
    <h4 class="alert-heading"><i class="fa fa-check-circle-o"></i> <?php echo __('Command sent successfully'); ?></h4>
    <?php echo __('Page refresh in'); ?> <span id="autoRefreshCounter"></span> <?php echo __('seconds...'); ?>
</div>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php
            //Wee only need the form for the nice markup -.-
            echo $this->Form->create('serviceList', [
                'class' => 'form-horizontal clear',
            ]);
            ?>
            <div class="row">
                <div class="col col-xs-8">
                    <?php
                    echo $this->Form->input('host_id', [
                        'options' => $hosts,
                        'selected' => $Host->getId(),
                        'data-placeholder' => __('Please select...'),
                        'class' => 'chosen',
                        'label' => false,
                        'wrapInput' => 'col col-xs-12',
                        'style' => 'width: 100%',
                    ]);
                    ?>
                </div>
                <div class="col col-xs-4" style="padding-left:0;">
                    <div class="btn-group pull-left" style="padding-top: 2px;">
                        <?php if ($this->Acl->hasPermission('edit', 'hosts') && $allowEdit): ?>
                            <a href="<?php echo Router::url([
                                'controller' => 'hosts',
                                'action' => 'edit',
                                $Host->getId()
                            ]); ?> " class="btn btn-default btn-xs">
                                &nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
                        <?php else: ?>
                            <a href="javascript:void(0);" class="btn btn-default btn-xs">&nbsp;<i class="fa fa-cog"></i>&nbsp;
                            </a>
                        <?php endif; ?>
                        <a href="javascript:void(0);" data-toggle="dropdown"
                           class="btn btn-default btn-xs dropdown-toggle"><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                <li>
                                    <a href="<?php echo Router::url([
                                        'controller' => 'hosts',
                                        'action' => 'browser',
                                        $Host->getId()
                                    ]); ?>">
                                        <i class="fa fa-desktop"></i> <?php echo __('Browser'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($this->Acl->hasPermission('edit', 'hosts') && $allowEdit): ?>
                                <li>
                                    <a href="<?php echo Router::url([
                                        'controller' => 'hosts',
                                        'action' => 'edit',
                                        $Host->getId()
                                    ]); ?>">
                                        <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($this->Acl->hasPermission('deactivate', 'hosts') && $allowEdit): ?>
                                <li>
                                    <a href="<?php echo Router::url([
                                        'controller' => 'hosts',
                                        'action' => 'deactivate',
                                        $Host->getId()
                                    ]); ?>">
                                        <i class="fa fa-plug"></i> <?php echo __('Disable'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
                                <li>
                                    <a href="<?php echo Router::url([
                                        'controller' => 'services',
                                        'action' => 'serviceList',
                                        $Host->getId()
                                    ]); ?>">
                                        <i class="fa fa-list"></i> <?php echo __('Service List'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($this->Acl->hasPermission('allocateToHost', 'servicetemplategroups')): ?>
                                <li>
                                    <a href="<?php echo Router::url([
                                        'controller' => 'hosts',
                                        'action' => 'allocateServiceTemplateGroup',
                                        $Host->getId()
                                    ]); ?>">
                                        <i class="fa fa-external-link"></i>
                                        <?php echo __('Allocate Service Template Group'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php
                            if ($this->Acl->hasPermission('edit') && $allowEdit):
                                echo $this->AdditionalLinks->renderAsListItems($additionalLinksList, $Host->getId());
                            endif;
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <?php
                        if ($this->Acl->hasPermission('add', 'services') && $allowEdit):
                            echo $this->Html->link(
                                __('New'),
                                ['controller' => 'services', 'action' => 'add', $Host->getId()],
                                ['class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus']
                            );
                            echo " "; //Fix HTML
                        endif;
                        if ($this->Acl->hasPermission('browser', 'hosts')):
                            echo $this->Html->link(
                                __('Open host in browser'),
                                ['controller' => 'hosts', 'action' => 'browser', $Host->getId()],
                                ['class' => 'btn btn-xs btn-primary hidden-mobile', 'icon' => 'fa fa-desktop']);
                        endif;
                        ?>
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-desktop"></i> </span>
                    <h2 class="hidden-mobile"><?php echo h($Host->getHostname()); ?> </h2>
                    <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab">
                                <i class="fa fa-stethoscope"></i>
                                <span class="hidden-mobile hidden-tablet">
                                    <?php echo __('Active'); ?>
                                </span>
                            </a>
                        </li>
                        <li class="">
                            <a href="#tab2" data-toggle="tab"><i class="fa fa-plug"></i>
                                <span class="hidden-mobile hidden-tablet">
                                    <?php echo __('Disabled'); ?>
                                </span>
                            </a>
                        </li>
                        <li class="">
                            <a href="#tab3" data-toggle="tab"> <i class="fa fa-trash-o"></i>
                                <span class="hidden-mobile hidden-tablet">
                                    <?php echo __('Deleted'); ?>
                                </span>
                            </a>
                        </li>
                    </ul>
                </header>
                <div>
                    <div class="jarviswidget-editbox"></div>
                    <div class="widget-body no-padding">
                        <div class="tab-content">
                            <!-- Tab index -->
                            <div id="tab1" class="tab-pane fade active in">
                                <div class="mobile_table">
                                    <table id="host_list"
                                           class="table table-striped table-hover table-bordered smart-form"
                                           style="">
                                        <thead>
                                        <tr>
                                            <?php $order = $this->Paginator->param('order'); ?>
                                            <th class="no-sort"></th>
                                            <th class="select_datatable no-sort">
                                                <?php echo $this->Utils->getDirection($order, 'Service.servicestatus');
                                                echo $this->Paginator->sort('Service.servicestatus', 'Servicestatus'); ?>
                                            </th>
                                            <th class="no-sort text-center"><i class="fa fa-gear fa-lg"></i></th>
                                            <th class="no-sort text-center">
                                                <i class="fa fa-user fa-lg"
                                                   title="<?php echo __('Acknowledgedment'); ?>"></i>
                                            </th>
                                            <th class="no-sort text-center">
                                                <i class="fa fa-power-off fa-lg"
                                                   title="<?php echo __('in Downtime'); ?>"></i>
                                            </th>
                                            <th class="no-sort text-center">
                                                <i class="fa fa fa-area-chart fa-lg"
                                                   title="<?php echo __('Grapher'); ?>"></i>
                                            </th>
                                            <th class="no-sort text-center">
                                                <strong title="<?php echo __('Passively transferred service'); ?>">
                                                    P
                                                </strong>
                                            </th>
                                            <th class="no-sort">
                                                <?php echo $this->Utils->getDirection($order, 'Service.servicename');
                                                echo $this->Paginator->sort('Service.servicename', __('Servicename')); ?>
                                            </th>
                                            <th class="no-sort">
                                                <?php echo $this->Utils->getDirection($order, 'Servicestatus.last_hard_state_change');
                                                echo $this->Paginator->sort('Servicestatus.last_hard_state_change', __('Status since')); ?>
                                            </th>
                                            <th class="no-sort">
                                                <?php echo $this->Utils->getDirection($order, 'Servicestatus.last_check');
                                                echo $this->Paginator->sort('Servicestatus.last_check', __('Last check')); ?>
                                            </th>
                                            <th class="no-sort">
                                                <?php echo $this->Utils->getDirection($order, 'Servicestatus.next_check');
                                                echo $this->Paginator->sort('Servicestatus.next_check', __('Next check')); ?>
                                            </th>
                                            <th class="no-sort">
                                                <?php echo $this->Utils->getDirection($order, 'Servicestatus.output');
                                                echo $this->Paginator->sort('Servicestatus.output', __('Service output')); ?>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($all_services as $service):
                                            $Service = new Service($service);
                                            $Servicestatus = new Servicestatus($service['Servicestatus']);
                                            $ServicestatusIcon = new ServicestatusIcon(
                                                $Servicestatus->currentState(),
                                                Router::url([
                                                    'controller' => 'services',
                                                    'action' => 'browser',
                                                    $Service->getId()
                                                ])
                                            );
                                            ?>
                                            <tr>
                                                <td class="text-center width-5">
                                                    <?php if ($allowEdit): ?>
                                                        <input type="checkbox" class="massChange"
                                                               servicename="<?php echo h($Service->getServicename()); ?>"
                                                               value="<?php echo $Service->getId(); ?>"
                                                               uuid="<?php echo $Service->getUuid(); ?>">
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center width-90">
                                                    <?php
                                                    if ($Servicestatus->isFlapping()):
                                                        echo $Servicestatus->getServiceFlappingIconColored();
                                                    else:
                                                        echo $ServicestatusIcon->getHtmlIcon();
                                                    endif;
                                                    ?>
                                                </td>
                                                <td class="width-50">
                                                    <div class="btn-group">
                                                        <?php if ($this->Acl->hasPermission('edit', 'services') && $allowEdit): ?>
                                                            <a href="<?php echo Router::url([
                                                                'controller' => 'services',
                                                                'action' => 'edit',
                                                                $Service->getId()
                                                            ]); ?>" class="btn btn-default">&nbsp;<i
                                                                        class="fa fa-cog"></i>&nbsp;
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="javascript:void(0);" class="btn btn-default">&nbsp;<i
                                                                        class="fa fa-cog"></i>&nbsp;</a>
                                                        <?php endif; ?>
                                                        <a href="javascript:void(0);" data-toggle="dropdown"
                                                           class="btn btn-default dropdown-toggle"><span
                                                                    class="caret"></span></a>
                                                        <ul class="dropdown-menu">
                                                            <?php if ($this->Acl->hasPermission('edit', 'services') && $allowEdit): ?>
                                                                <li>
                                                                    <a href="<?php echo Router::url([
                                                                        'controller' => 'services',
                                                                        'action' => 'edit',
                                                                        $Service->getId()
                                                                    ]); ?>">
                                                                        <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                                    </a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if ($this->Acl->hasPermission('deactivate', 'services') && $allowEdit): ?>
                                                                <li>
                                                                    <a href="<?php echo Router::url([
                                                                        'controller' => 'services',
                                                                        'action' => 'deactivate',
                                                                        $Service->getId()
                                                                    ]); ?>">
                                                                        <i class="fa fa-plug"></i> <?php echo __('Disable'); ?>
                                                                    </a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if ($this->Acl->hasPermission('delete', 'services') && $allowEdit): ?>
                                                                <li class="divider"></li>
                                                                <li>
                                                                    <?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> ' . __('Delete'), ['controller' => 'services', 'action' => 'delete', $Service->getId()], ['class' => 'txt-color-red', 'escape' => false]); ?>
                                                                </li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($Servicestatus->isAcknowledged()): ?>
                                                        <?php if ($Servicestatus->getAcknowledgementType() == 1): ?>
                                                            <i class="fa fa-user fa-lg "
                                                               title="<?php echo __('Acknowledgedment'); ?>"></i>
                                                        <?php else: ?>
                                                            <i class="fa fa-user-o fa-lg"
                                                               title="<?php echo __('Sticky Acknowledgedment'); ?>"></i>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($Servicestatus->isInDowntime()): ?>
                                                        <i class="fa fa-power-off fa-lg "></i>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($this->Monitoring->checkForServiceGraph($Host->getUuid(), $Service->getUuid())): ?>
                                                        <a class="txt-color-blueDark"
                                                           href="<?php echo Router::url([
                                                               'controller' => 'services',
                                                               'action' => 'grapherSwitch',
                                                               $Service->getId()

                                                           ]); ?>">
                                                            <i class="fa fa-area-chart fa-lg popupGraph"
                                                               host-uuid="<?php echo $Host->getUuid(); ?>"
                                                               service-uuid="<?php echo $Service->getUuid(); ?>"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if (!$Service->isActiveChecksEnabled() || $Host->isSatelliteHost()): ?>
                                                        <strong title="<?php echo __('Passively transferred service'); ?>">P</strong>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo Router::url([
                                                        'controller' => 'services',
                                                        'action' => 'browser',
                                                        $Service->getId()
                                                    ]); ?>">
                                                        <?php echo h($Service->getServicename()); ?>
                                                    </a></td>
                                                <td data-original-title="<?php echo h($this->Time->format($Servicestatus->getLastStateChange(), $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?>"
                                                    data-placement="bottom" rel="tooltip" data-container="body">
                                                    <?php echo h($this->Utils->secondsInHumanShort(
                                                        time() - strtotime($Servicestatus->getLastStateChange()))
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?php echo h($this->Time->format(
                                                        $Servicestatus->getLastCheck(),
                                                        $this->Auth->user('dateformat'),
                                                        false,
                                                        $this->Auth->user('timezone'))
                                                    ); ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    if ($Service->isActiveChecksEnabled() === true && $Host->isSatelliteHost() === false):
                                                        echo h($this->Time->format(
                                                            $Servicestatus->getNextCheck(),
                                                            $this->Auth->user('dateformat'),
                                                            false,
                                                            $this->Auth->user('timezone'))
                                                        );
                                                    else:
                                                        echo __('n/a');
                                                    endif;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php echo h($Servicestatus->getOutput()); ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php if (empty($all_services)): ?>
                                    <div class="noMatch">
                                        <center>
                                            <span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
                                        </center>
                                    </div>
                                <?php endif; ?>
                                <div class="padding-top-10"></div>
                                <?php echo $this->element('service_mass_changes'); ?>

                            </div>
                            <!-- Disabled services -->
                            <div id="tab2" class="tab-pane fade">
                                <div class="mobile_table">
                                    <table class="table table-striped table-hover table-bordered smart-form" style="">
                                        <thead>
                                        <tr>
                                            <?php $order = $this->Paginator->param('order'); ?>
                                            <th class="no-sort"><?php echo __('Servicename'); ?></th>
                                            <th class="no-sort"><?php echo __('Service template'); ?></th>
                                            <th class="no-sort"><?php echo __('UUID'); ?></th>
                                            <th class="no-sort"><?php echo __('Options'); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($disabledServices as $disabledService):
                                            $Service = new Service($disabledService);
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo h($Service->getServicename()); ?>
                                                </td>
                                                <td><?php echo h($disabledService['Servicetemplate']['name']); ?></td>
                                                <td><?php echo h($Service->getUuid()); ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <?php if ($this->Acl->hasPermission('edit', 'services') && $allowEdit): ?>
                                                            <a href="<?php echo Router::url([
                                                                'controller' => 'services',
                                                                'action' => 'edit',
                                                                $Service->getId()
                                                            ]); ?>" class="btn btn-default">
                                                                &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="javascript:void(0);"
                                                               class="btn btn-default btn-xs">&nbsp;<i
                                                                        class="fa fa-cog"></i>&nbsp;</a>
                                                        <?php endif; ?>
                                                        <a href="javascript:void(0);" data-toggle="dropdown"
                                                           class="btn btn-default dropdown-toggle"><span
                                                                    class="caret"></span></a>
                                                        <ul class="dropdown-menu">
                                                            <?php if ($this->Acl->hasPermission('edit', 'services') && $allowEdit): ?>
                                                                <li>
                                                                    <a href="<?php echo Router::url([
                                                                        'controller' => 'services',
                                                                        'action' => 'edit',
                                                                        $Service->getId()
                                                                    ]); ?>">
                                                                        <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                                    </a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if ($this->Acl->hasPermission('enable', 'services') && $allowEdit): ?>
                                                                <li>
                                                                    <a href="<?php echo Router::url([
                                                                        'controller' => 'services',
                                                                        'action' => 'enable',
                                                                        $Service->getId()
                                                                    ]); ?>">
                                                                        <i class="fa fa-plug"></i> <?php echo __('Enable'); ?>
                                                                    </a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if ($this->Acl->hasPermission('delete', 'services') && $allowEdit): ?>
                                                                <li class="divider"></li>
                                                                <li>
                                                                    <?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> ' . __('Delete'), ['controller' => 'services', 'action' => 'delete', $Service->getId()], ['class' => 'txt-color-red', 'escape' => false]); ?>
                                                                </li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Deleted services -->
                            <div id="tab3" class="tab-pane fade">
                                <div class="mobile_table">
                                    <table class="table table-striped table-hover table-bordered smart-form" style="">
                                        <thead>
                                        <tr>
                                            <?php $order = $this->Paginator->param('order'); ?>
                                            <th class="no-sort"><?php echo __('Servicename'); ?></th>
                                            <th class="no-sort"><?php echo __('UUID'); ?></th>
                                            <th class="no-sort"><?php echo __('Date'); ?></th>
                                            <th class="no-sort"><?php echo __('Performance data deleted'); ?></th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($deletedServices as $deletedService): ?>
                                            <tr>
                                                <td><?php echo h($deletedService['DeletedService']['name']); ?></td>
                                                <td><?php echo h($deletedService['DeletedService']['uuid']); ?></td>
                                                <td>
                                                    <?php echo $this->Time->format(
                                                        $deletedService['DeletedService']['created'],
                                                        $this->Auth->user('dateformat'),
                                                        false,
                                                        $this->Auth->user('timezone')
                                                    ); ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($deletedService['DeletedService']['deleted_perfdata'] == 1): ?>
                                                        <i class="fa fa-check text-success"></i>
                                                    <?php else: ?>
                                                        <i class="fa fa-times txt-color-red"></i>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

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


<div class="modal fade" id="nag_command_reschedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Reset check time '); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('nag_command', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <center>
                        <?php echo __('Reset check time now'); ?>
                    </center>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="submitRescheduleService" data-dismiss="modal">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="nag_command_schedule_downtime" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Set planned maintenance times'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="txt-color-red padding-bottom-20" id="validationErrorServiceDowntime"
                         style="display:none;"><i
                                class="fa fa-exclamation-circle"></i> <?php echo __('Please enter a valide date'); ?>
                    </div>
                    <?php
                    echo $this->Form->create('CommitServiceDowntime', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <?php echo $this->Form->input('comment', ['value' => __('In progress'), 'label' => __('Comment') . ':']); ?>

                    <!-- from -->
                    <div class="form-group">
                        <label class="col col-md-2 control-label"
                               for="CommitServiceDowntimeFromDate"><?php echo __('From'); ?>:</label>
                        <div class="col col-xs-5" style="padding-right: 0px;">
                            <input type="text" id="CommitServiceDowntimeFromDate" value="<?php echo date('d.m.Y'); ?>"
                                   class="form-control" name="data[CommitServiceDowntime][from_date]">
                        </div>
                        <div class="col col-xs-5" style="padding-left: 0px;">
                            <input type="text" id="CommitServiceDowntimeFromTime" value="<?php echo date('h:m'); ?>"
                                   class="form-control" name="data[CommitServiceDowntime][from_time]">
                        </div>
                    </div>

                    <!-- to -->
                    <div class="form-group">
                        <label class="col col-md-2 control-label"
                               for="CommitServiceDowntimeToDate"><?php echo __('To'); ?>:</label>
                        <div class="col col-xs-5" style="padding-right: 0px;">
                            <input type="text" id="CommitServiceDowntimeToDate"
                                   value="<?php echo date('d.m.Y', strtotime('+3 days')); ?>" class="form-control"
                                   name="data[CommitServiceDowntime][to_date]">
                        </div>
                        <div class="col col-xs-5" style="padding-left: 0px;">
                            <input type="text" id="CommitServiceDowntimeToTime" value="<?php echo date('h:m'); ?>"
                                   class="form-control" name="data[CommitServiceDowntime][to_time]">
                        </div>
                    </div>

                    <?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="submitCommitServiceDowntime">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="nag_command_ack_state" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Acknowledge Service status'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('CommitServiceAck', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <?php echo $this->Form->input('comment', ['value' => __('In progress'), 'label' => __('Comment') . ':']); ?>
                    <?php echo $this->Form->input('sticky', ['type' => 'checkbox', 'label' => __('Sticky'), 'wrapInput' => 'col-md-offset-2 col-md-10']); ?>
                    <?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submitServiceAck">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="nag_command_disable_notifications" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Disable notifications'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('disableNotifications', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <center>
                        <span class="hintmark">
                            <?php echo __('Yes, i want temporarily <strong>disable</strong> notifications.'); ?>
                        </span>
                    </center>

                    <div class="padding-left-10 padding-top-10">
                        <span class="note hintmark_before"><?php echo __('This option is only temporary. It does not affect your configuration. This is an external command and only saved in the memory of your monitoring engine'); ?></span>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submitDisableNotifications">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="nag_command_enable_notifications" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Disable notifications'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('enableNotifications', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <center>
                        <span class="hintmark">
                            <?php echo __('Yes, i want temporarily <strong>enable</strong> notifications.'); ?>
                        </span>
                    </center>

                    <div class="padding-left-10 padding-top-10">
                        <span class="note hintmark_before"><?php echo __('This option is only temporary. It does not affect your configuration. This is an external command and only saved in the memory of your monitoring engine'); ?></span>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submitEnableNotifications">
                    <?php echo __('Send'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>

