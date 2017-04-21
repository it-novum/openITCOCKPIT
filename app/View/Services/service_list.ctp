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
<?php $this->Paginator->url($this->params['url']); ?>
<?php //$this->Paginator->options(array('url' => $this->params['named'])); ?>
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
                        'options'          => $hosts,
                        'selected'         => $host_id,
                        'data-placeholder' => __('Please select...'),
                        'class'            => 'chosen',
                        'label'            => false,
                        'wrapInput'        => 'col col-xs-12',
                        'style'            => 'width: 100%',
                    ]);
                    ?>
                </div>
                <div class="col col-xs-4" style="padding-left:0;">
                    <div class="btn-group pull-left" style="padding-top: 2px;">
                        <?php if ($this->Acl->hasPermission('edit', 'hosts') && $allowEdit): ?>
                            <a href="/hosts/edit/<?php echo $host['Host']['id']; ?>" class="btn btn-default btn-xs">
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
                                    <a href="/hosts/browser/<?php echo $host['Host']['id']; ?>"><i
                                                class="fa fa-desktop"></i> <?php echo __('Browser'); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if ($this->Acl->hasPermission('edit', 'hosts') && $allowEdit): ?>
                                <li>
                                    <a href="/hosts/edit/<?php echo $host['Host']['id']; ?>"><i
                                                class="fa fa-cog"></i> <?php echo __('Edit'); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if ($this->Acl->hasPermission('deactivate', 'hosts') && $allowEdit): ?>
                                <li>
                                    <a href="/hosts/deactivate/<?php echo $host['Host']['id']; ?>"><i
                                                class="fa fa-plug"></i> <?php echo __('Disable'); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
                                <li>
                                    <a href="/services/serviceList/<?php echo $host['Host']['id']; ?>"><i
                                                class="fa fa-list"></i> <?php echo __('Service List'); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if ($this->Acl->hasPermission('allocateToHost', 'servicetemplategroups')): ?>
                                <li>
                                    <a href="/hosts/allocateServiceTemplateGroup/<?php echo $host['Host']['id']; ?>"><i
                                                class="fa fa-external-link"></i> <?php echo __('Allocate Service Template Group'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php
                            if ($this->Acl->hasPermission('edit') && $allowEdit):
                                echo $this->AdditionalLinks->renderAsListItems($additionalLinksList, $host['Host']['id']);
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
                            echo $this->Html->link(__('New'), '/'.$this->params['controller'].'/add/'.$host_id, ['class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus']);
                            echo " "; //Fix HTML
                        endif;
                        if ($this->Acl->hasPermission('browser', 'hosts')):
                            echo $this->Html->link(__('Open host in browser'), '/hosts/browser/'.$host_id, ['class' => 'btn btn-xs btn-primary hidden-mobile', 'icon' => 'fa fa-desktop']);
                        endif;
                        ?>
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-desktop"></i> </span>
                    <h2 class="hidden-mobile"><?php echo $host['Host']['name']; ?> </h2>
                    <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab"> <i class="fa fa-stethoscope"></i> <span
                                        class="hidden-mobile hidden-tablet"> <?php echo __('Active'); ?> </span> </a>
                        </li>
                        <li class="">
                            <a href="#tab2" data-toggle="tab"><i class="fa fa-plug"></i> <span
                                        class="hidden-mobile hidden-tablet"> <?php echo __('Disabled'); ?> </span></a>
                        </li>
                        <li class="">
                            <a href="#tab3" data-toggle="tab"> <i class="fa fa-trash-o"></i> <span
                                        class="hidden-mobile hidden-tablet"> <?php echo __('Deleted'); ?> </span></a>
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
                                    <table id="host_list" class="table table-striped table-hover table-bordered smart-form"
                                           style="">
                                        <thead>
                                        <tr>
                                            <?php $order = $this->Paginator->param('order'); ?>
                                            <th class="no-sort"></th>
                                            <th class="select_datatable no-sort"><?php echo $this->Utils->getDirection($order, 'Service.servicestatus');
                                                echo $this->Paginator->sort('Service.servicestatus', 'Service'); ?></th>
                                            <th class="no-sort text-center"><i class="fa fa-gear fa-lg"></i></th>
                                            <th class="no-sort text-center"><i class="fa fa-user fa-lg"
                                                                               title="<?php echo __('Acknowledgedment'); ?>"></i>
                                            </th>
                                            <th class="no-sort text-center"><i class="fa fa-power-off fa-lg"
                                                                               title="<?php echo __('in Downtime'); ?>"></i>
                                            </th>
                                            <th class="no-sort text-center"><i class="fa fa fa-area-chart fa-lg"
                                                                               title="<?php echo __('Grapher'); ?>"></i>
                                            </th>
                                            <th class="no-sort text-center"><strong
                                                        title="<?php echo __('Passively transferred service'); ?>">P</strong>
                                            </th>
                                            <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Service.servicename');
                                                echo $this->Paginator->sort('Service.servicename', __('Servicename')); ?></th>
                                            <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Service.last_hard_state_change');
                                                echo $this->Paginator->sort('Service.last_hard_state_change', __('Status since')); ?></th>
                                            <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Service.last_check');
                                                echo $this->Paginator->sort('Service.last_check', __('Last check')); ?></th>
                                            <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Service.next_check');
                                                echo $this->Paginator->sort('Service.next_check', __('Next check')); ?></th>
                                            <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Service.output');
                                                echo $this->Paginator->sort('Service.output', __('Service output')); ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($all_services as $service): ?>
                                            <?php
                                            if ($service['Service']['name'] !== null && $service['Service']['name'] !== ''):
                                                $serviceName = $service['Service']['name'];
                                            else:
                                                $serviceName = $service['Servicetemplate']['name'];
                                            endif;
                                            ?>
                                            <tr>
                                                <td class="text-center width-5">
                                                    <?php if ($allowEdit): ?>
                                                        <input type="checkbox" class="massChange"
                                                               servicename="<?php echo h($serviceName); ?>"
                                                               value="<?php echo $service['Service']['id']; ?>"
                                                               uuid="<?php echo $service['Service']['uuid']; ?>">
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center width-90">
                                                    <?php
                                                    if ($service['Servicestatus']['is_flapping'] == 1):
                                                        echo $this->Monitoring->serviceFlappingIconColored($service['Servicestatus']['is_flapping'], '', $service['Servicestatus']['current_state']);
                                                    else:
                                                        echo $this->Status->humanServiceStatus($service['Service']['uuid'], '/services/browser/'.$service['Service']['id'], [$service['Service']['uuid'] => ['Servicestatus' => ['current_state' => $service['Servicestatus']['current_state']]]])['html_icon'];
                                                    endif;
                                                    ?>
                                                </td>
                                                <td class="width-50">
                                                    <div class="btn-group">
                                                        <?php if ($this->Acl->hasPermission('edit', 'services') && $allowEdit): ?>
                                                            <a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $service['Service']['id']; ?>"
                                                               class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;
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
                                                                    <a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $service['Service']['id']; ?>"><i
                                                                                class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                                    </a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if ($this->Acl->hasPermission('deactivate', 'services') && $allowEdit): ?>
                                                                <li>
                                                                    <a href="/<?php echo $this->params['controller']; ?>/deactivate/<?php echo $service['Service']['id']; ?>"><i
                                                                                class="fa fa-plug"></i> <?php echo __('Disable'); ?>
                                                                    </a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if ($this->Acl->hasPermission('delete', 'services') && $allowEdit): ?>
                                                                <li class="divider"></li>
                                                                <li>
                                                                    <?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> '.__('Delete'), ['controller' => 'services', 'action' => 'delete', $service['Service']['id']], ['class' => 'txt-color-red', 'escape' => false]); ?>
                                                                </li>
                                                            <?php endif; ?>
                                                        </ul>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($this->Monitoring->checkForAck($service['Servicestatus']['problem_has_been_acknowledged'])): ?>
                                                        <?php if ($service['Servicestatus']['acknowledgement_type'] == 1): ?>
                                                            <i class="fa fa-user fa-lg "
                                                               title="<?php echo __('Acknowledgedment'); ?>"></i>
                                                        <?php else: ?>
                                                            <i class="fa fa-user-o fa-lg"
                                                               title="<?php echo __('Sticky Acknowledgedment'); ?>"></i>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($this->Monitoring->checkForDowntime($service['Servicestatus']['scheduled_downtime_depth'])): ?>
                                                        <i class="fa fa-power-off fa-lg "></i>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($this->Monitoring->checkForServiceGraph($service['Host']['uuid'], $service['Service']['uuid'])): ?>
                                                        <a class="txt-color-blueDark"
                                                           href="/services/grapherSwitch/<?php echo $service['Service']['id']; ?>"><i
                                                                    class="fa fa-area-chart fa-lg popupGraph"
                                                                    host-uuid="<?php echo $service['Host']['uuid']; ?>"
                                                                    service-uuid="<?php echo $service['Service']['uuid']; ?>"></i></a>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if (($service['Servicestatus']['active_checks_enabled'] == 0 && $service['Servicestatus']['active_checks_enabled'] !== null) || (isset($service['Host']['satellite_id'])) && $service['Host']['satellite_id'] > 0): ?>
                                                        <strong title="<?php echo __('Passively transferred service'); ?>">P</strong>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="/services/browser/<?php echo $service['Service']['id']; ?>">
                                                        <?php echo h($serviceName); ?>
                                                    </a></td>
                                                <td data-original-title="<?php echo h($this->Time->format($service['Servicestatus']['last_hard_state_change'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?>"
                                                    data-placement="bottom" rel="tooltip" data-container="body">
                                                    <?php echo h($this->Utils->secondsInHumanShort(time() - strtotime($service['Servicestatus']['last_hard_state_change']))); ?>
                                                </td>
                                                <td><?php echo h($this->Time->format($service['Servicestatus']['last_check'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?></td>
                                                <td>
                                                    <?php
                                                    if ($service['Service']['active_checks_enabled'] == 1 || $service['Service']['active_checks_enabled'] === null || $service['Service']['active_checks_enabled']):
                                                        if ($service['Servicetemplate']['active_checks_enabled'] == 1):
                                                            echo h($this->Time->format($service['Servicestatus']['last_check'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')));
                                                        else:
                                                            echo __('n/a');
                                                        endif;
                                                    else:
                                                        echo __('n/a');
                                                    endif;
                                                    ?>
                                                </td>
                                                <td><?php echo h($service['Servicestatus']['output']); ?></td>
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
                                        <?php foreach ($disabledServices as $disabledService): ?>
                                            <tr>
                                                <td>
                                                    <?php if ($disabledService['Service']['name'] === '' || $disabledService['Service']['name'] === null):
                                                        echo $disabledService['Servicetemplate']['name'];
                                                    else:
                                                        echo $disabledService['Service']['name'];
                                                    endif; ?>
                                                </td>
                                                <td><?php echo $disabledService['Servicetemplate']['name']; ?></td>
                                                <td><?php echo $disabledService['Service']['uuid']; ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <?php if ($this->Acl->hasPermission('edit', 'services') && $allowEdit): ?>
                                                            <a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $disabledService['Service']['id']; ?>"
                                                               class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;
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
                                                                    <a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $disabledService['Service']['id']; ?>"><i
                                                                                class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                                    </a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if ($this->Acl->hasPermission('enable', 'services') && $allowEdit): ?>
                                                                <li>
                                                                    <a href="/services/enable/<?php echo $disabledService['Service']['id']; ?>"><i
                                                                                class="fa fa-plug"></i> <?php echo __('Enable'); ?>
                                                                    </a>
                                                                </li>
                                                            <?php endif; ?>
                                                            <?php if ($this->Acl->hasPermission('delete', 'services') && $allowEdit): ?>
                                                                <li class="divider"></li>
                                                                <li>
                                                                    <?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> '.__('Delete'), ['controller' => 'services', 'action' => 'delete', $disabledService['Service']['id']], ['class' => 'txt-color-red', 'escape' => false]); ?>
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
                                                <td><?php echo $deletedService['DeletedService']['name']; ?></td>
                                                <td><?php echo $deletedService['DeletedService']['uuid']; ?></td>
                                                <td><?php echo $this->Time->format($deletedService['DeletedService']['created'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></td>
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
                                         id="datatable_fixed_column_info"><?php echo $this->Paginator->counter(__('Page').' {:page} '.__('of').' {:pages}, '.__('Total').' {:count} '.__('entries')); ?></div>
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
                    <?php echo $this->Form->input('comment', ['value' => __('In progress'), 'label' => __('Comment').':']); ?>

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
                    <?php echo $this->Form->input('comment', ['value' => __('In progress'), 'label' => __('Comment').':']); ?>
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

