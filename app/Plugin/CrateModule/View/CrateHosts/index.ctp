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

/**
 * @property \itnovum\openITCOCKPIT\Monitoring\QueryHandler $QueryHandler
 */

use itnovum\openITCOCKPIT\Core\HostSharingPermissions;

$this->Paginator->options(['url' => $this->params['named']]);
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-desktop fa-fw "></i>
            <?php echo __('Hosts') ?>
            <span>>
                <?php echo __('List'); ?>
            </span>
        </h1>
    </div>
</div>

<?php echo $this->Flash->render('positive'); ?>



<div class="alert alert-success alert-block" id="flashSuccess" style="display:none;">
    <a href="#" data-dismiss="alert" class="close">×</a>
    <h4 class="alert-heading"><i class="fa fa-check-circle-o"></i> <?php echo __('Command sent successfully'); ?></h4>
    <?php echo __('Page refresh in'); ?> <span id="autoRefreshCounter"></span> <?php echo __('seconds...'); ?>
</div>

<?php if (!$QueryHandler->exists()): ?>
    <div class="alert alert-danger alert-block">
        <a href="#" data-dismiss="alert" class="close">×</a>
        <h4 class="alert-heading"><i class="fa fa-warning"></i> <?php echo __('Monitoring Engine is not running!'); ?>
        </h4>
        <?php echo __('File %s does not exists', $QueryHandler->getPath()); ?>
    </div>
<?php endif; ?>

<section id="widget-grid" class="">

    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <?php
                        if ($this->Acl->hasPermission('add', 'hosts', '')):
                            echo $this->Html->link(__('New'), '/' . $this->params['controller'] . '/add', ['class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus']);
                            echo " "; //Need a space for nice buttons
                        endif;
                        echo $this->Html->link(__('Filter'), 'javascript:', ['class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-filter']);

                        if ($isFilter):
                            echo " "; //Need a space for html
                            echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']);
                        endif;
                        echo $this->AdditionalLinks->renderAsLinks($additionalLinksTop);
                        ?>
                    </div>
                    <div class="widget-toolbar hidden-mobile" role="menu">
                        <a href="javascript:void(0);" class="dropdown-toggle selector" data-toggle="dropdown"><i
                                    class="fa fa-lg fa-table"></i></a>
                        <ul class="dropdown-menu arrow-box-up-right pull-right">
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="2"><input
                                            type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Acknowledgement'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="3"><input
                                            type="checkbox" class="pull-left"/> &nbsp; <?php echo __('In downtime'); ?>
                                </a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="4"><input
                                            type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Graph'); ?></a>
                            </li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="5"><input
                                            type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Shared'); ?></a>
                            </li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="6"><input
                                            type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Passive'); ?></a>
                            </li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="7"><input
                                            type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Host Name'); ?>
                                </a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="8"><input
                                            type="checkbox" class="pull-left"/> &nbsp; <?php echo __('IP-Address'); ?>
                                </a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="9"><input
                                            type="checkbox" class="pull-left"/> &nbsp; <?php echo __('State since'); ?>
                                </a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="10"><input
                                            type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Last check'); ?>
                                </a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="11"><input
                                            type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Output'); ?></a>
                            </li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="12"><input
                                            type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Instance'); ?></a>
                            </li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="13"><input
                                            type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Edit'); ?></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="jarviswidget-ctrls" role="menu">

                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-desktop"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Hosts'); ?></h2>
                    <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                        <li class="active">
                            <a href="<?php echo Router::url(array_merge(['controller' => 'hosts', 'action' => 'index'], $this->params['named'])); ?>"><i
                                        class="fa fa-stethoscope"></i> <span
                                        class="hidden-mobile hidden-tablet"> <?php echo __('Monitored'); ?> </span> </a>
                        </li>
                        <?php if ($this->Acl->hasPermission('notMonitored', 'hosts', '')): ?>
                            <li class="">
                                <a href="<?php echo Router::url(array_merge(['controller' => 'hosts', 'action' => 'notMonitored'], $this->params['named'])); ?>"><i
                                            class="fa fa-user-md"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Not monitored'); ?> </span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->Acl->hasPermission('disabled', 'hosts', '')): ?>
                            <li>
                                <a href="<?php echo Router::url(array_merge(['controller' => 'hosts', 'action' => 'disabled'], $this->params['named'])); ?>"><i
                                            class="fa fa-power-off"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Disabled'); ?> </span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->Acl->hasPermission('index', 'DeletedHosts', '')): ?>
                            <li>
                                <a href="<?php echo Router::url(array_merge(['controller' => 'deleted_hosts', 'action' => 'index'], $this->params['named'])); ?>"><i
                                            class="fa fa-trash-o"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Deleted'); ?> </span></a>
                            </li>
                        <?php endif; ?>
                    </ul>

                </header>
                <div>


                    <div class="widget-body no-padding">
                        <?php
                        $options = ['avoid_cut' => true];
                        echo $this->ListFilter->renderFilterbox($filters, $options, '<i class="fa fa-filter"></i> ' . __('Filter'), false, false);
                        ?>
                        <div class="mobile_table">
                            <table id="host_list" class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <th class="no-sort text-center"><i class="fa fa-check-square-o fa-lg"></i></th>
                                    <th class="select_datatable no-sort"><?php echo $this->Utils->getDirection($order, 'Host.current_state');
                                        echo $this->Paginator->sort('Host.hoststatus', 'Hoststatus'); ?></th>
                                    <th class="no-sort text-center"><i class="fa fa-user fa-lg"
                                                                       title="<?php echo __('Acknowledgedment'); ?>"></i>
                                    </th>
                                    <th class="no-sort text-center"><i class="fa fa-power-off fa-lg"
                                                                       title="<?php echo __('in Downtime'); ?>"></i>
                                    </th>
                                    <th class="no-sort text-center"><i class="fa fa-area-chart fa-lg"
                                                                       title="<?php echo __('Grapher'); ?>"></i></th>
                                    <th class="no-sort text-center"><i title="<?php echo __('Shared'); ?>"
                                                                       class="fa fa-sitemap fa-lg"></i></th>
                                    <th class="no-sort text-center"><strong
                                                title="<?php echo __('Passively transferred host'); ?>">P</strong></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'name');
                                        echo $this->Paginator->sort('name', 'Hostname'); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'address');
                                        echo $this->Paginator->sort('address', __('IP address')); ?></th>
                                    <th class="no-sort tableStatewidth"><?php echo $this->Utils->getDirection($order, 'last_hard_state_change');
                                        echo $this->Paginator->sort('Host.last_hard_state_change', __('State since')); ?></th>
                                    <th class="no-sort tableStatewidth"><?php echo $this->Utils->getDirection($order, 'last_check');
                                        echo $this->Paginator->sort('Host.last_check', __('Last check')); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'output');
                                        echo $this->Paginator->sort('Host.output', __('Output')); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'satellite_id');
                                        echo $this->Paginator->sort('Host.satellite_id', __('Instance')); ?></th>
                                    <th class="no-sort text-center editItemWidth"><i class="fa fa-gear fa-lg"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($all_hosts as $host):
                                    //Better performance, than run all the Hash::extracts if not necessary
                                    $hasEditPermission = false;
                                    $hostSharingPermissions = new HostSharingPermissions($host['Host']['container_id'], $hasRootPrivileges, $host['Host']['container_ids'], $userRights);
                                    $allowSharing = $hostSharingPermissions->allowSharing();
                                    if ($hasRootPrivileges === true):
                                        $hasEditPermission = true;
                                    else:
                                        if ($this->Acl->isWritableContainer($host['Host']['container_ids'])):
                                            $hasEditPermission = true;
                                        endif;
                                    endif;
                                    ?>
                                    <tr>
                                        <td class="text-center width-5">
                                            <?php if ($hasEditPermission): ?>
                                                <input type="checkbox" class="massChange"
                                                       hostname="<?php echo h($host['Host']['name']); ?>"
                                                       value="<?php echo $host['Host']['id']; ?>"
                                                       uuid="<?php echo $host['Host']['uuid']; ?>">
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center width-75">
                                            <?php
                                            if ($host['Hoststatus']['is_flapping'] == 1):
                                                echo $this->Monitoring->hostFlappingIconColored($host['Hoststatus']['is_flapping'], '', $host['Hoststatus']['current_state']);
                                            else:
                                                $href = 'javascript:void(0);';
                                                if ($this->Acl->hasPermission('browser', 'hosts', '')):
                                                    $href = '/hosts/browser/' . $host['Host']['id'];
                                                endif;
                                                echo $this->Status->humanHostStatus($host['Host']['uuid'], $href, [$host['Host']['uuid'] => ['Hoststatus' => ['current_state' => $host['Hoststatus']['current_state']]]])['html_icon'];
                                            endif;
                                            ?>
                                            <?php //echo $this->Status->humanHostStatus($host['Host']['uuid'], '/hosts/browser/'.$host['Host']['id'])['html_icon'];
                                            ?>
                                        </td>

                                        <td class="text-center"><?php //debug($host['Hoststatus']);
                                            ?>
                                            <?php if ($host['Hoststatus']['problem_has_been_acknowledged'] > 0): ?>
                                                <?php if ($host['Hoststatus']['acknowledgement_type'] == 1): ?>
                                                    <i class="fa fa-user fa-lg "
                                                       title="<?php echo __('Acknowledgedment'); ?>"></i>
                                                <?php else: ?>
                                                    <i class="fa fa-user-o fa-lg"
                                                       title="<?php echo __('Sticky Acknowledgedment'); ?>"></i>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <?php if ($host['Hoststatus']['scheduled_downtime_depth'] > 0): ?>
                                                <i class="fa fa-power-off fa-lg "
                                                   title="<?php echo __('in Downtime'); ?>"></i>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <?php if ($this->Monitoring->checkForHostGraph($host['Host']['uuid'])): ?>
                                                <?php
                                                $graphHref = 'javascript:void(0);';
                                                if ($this->Acl->hasPermission('serviceList', 'services', '')):
                                                    $graphHref = '/services/serviceList/' . $host['Host']['id'];
                                                endif;
                                                ?>
                                                <a class="txt-color-blueDark" href="<?php echo $graphHref; ?>"><i
                                                            class="fa fa-area-chart fa-lg "
                                                            title="<?php echo __('Grapher'); ?>"></i></a>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if (count($host['Host']['container_ids']) > 1):
                                                if ($allowSharing):?>
                                                    <a class="txt-color-blueDark" title="<?php echo __('Shared'); ?>"
                                                       href="/<?php echo $this->params['controller']; ?>/sharing/<?php echo $host['Host']['id']; ?>"><i
                                                                class="fa fa-sitemap fa-lg "></i></a>
                                                <?php
                                                else:?>
                                                    <i class="fa fa-low-vision fa-lg txt-color-blueLight"
                                                       title="<?php echo __('Restricted view'); ?>"></i>
                                                <?php
                                                endif;
                                            endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            if ($host['Host']['active_checks_enabled'] !== null && $host['Host']['active_checks_enabled'] !== '' || $host['Host']['satellite_id'] > 0):
                                                if ($host['Host']['active_checks_enabled'] == 0 || $host['Host']['satellite_id'] > 0): ?>
                                                    <strong title="<?php echo __('Passively transferred host'); ?>">P</strong>
                                                <?php endif;
                                            elseif ($host['Hoststatus']['active_checks_enabled'] == 0): ?>
                                                <strong title="<?php echo __('Passively transferred host'); ?>">P</strong>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($this->Acl->hasPermission('browser', 'hosts', '')): ?>
                                                <a href="/hosts/browser/<?php echo $host['Host']['id']; ?>"><?php echo h($host['Host']['name']); ?></a>
                                            <?php else: ?>
                                                <?php echo h($host['Host']['name']); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo h($host['Host']['address']); ?></td>
                                        <td data-original-title="<?php echo h($this->Time->format($host['Hoststatus']['last_hard_state_change'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?>"
                                            data-placement="bottom" rel="tooltip" data-container="body">
                                            <?php echo h($this->Utils->secondsInHumanShort(time() - strtotime($host['Hoststatus']['last_hard_state_change']))); ?>
                                        </td>
                                        <td><?php echo h($this->Time->format($host['Hoststatus']['last_check'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?></td>
                                        <td><?php echo h($host['Hoststatus']['output']); ?></td>
                                        <td>
                                            <?php
                                            if ($host['Host']['satellite_id'] == 0):
                                                echo $masterInstance;
                                            else:
                                                echo $SatelliteNames[$host['Host']['satellite_id']];
                                            endif;
                                            ?>
                                        </td>
                                        <td class="width-50">
                                            <div class="btn-group">
                                                <?php if ($this->Acl->hasPermission('edit', 'Hosts', '') && $hasEditPermission): ?>
                                                    <a href="<?php echo Router::url([
                                                        'controller' => 'Hosts',
                                                        'action'     => 'edit',
                                                        'plugin'     => '',
                                                        $host['Host']['id']
                                                    ]); ?>"
                                                       class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
                                                <?php else: ?>
                                                    <a href="javascript:void(0);" class="btn btn-default">&nbsp;<i
                                                                class="fa fa-cog"></i>&nbsp;</a>
                                                <?php endif; ?>
                                                <a href="javascript:void(0);" data-toggle="dropdown"
                                                   class="btn btn-default dropdown-toggle"><span
                                                            class="caret"></span></a>
                                                <ul class="dropdown-menu pull-right">
                                                    <?php if ($this->Acl->hasPermission('edit', 'Hosts', '') && $hasEditPermission): ?>
                                                        <li>
                                                            <a href="<?php echo Router::url([
                                                                'controller' => 'Hosts',
                                                                'action'     => 'edit',
                                                                'plugin'     => '',
                                                                $host['Host']['id'],
                                                            ]); ?>">
                                                                <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('sharing', 'Hosts', '') && $hasEditPermission): ?>
                                                        <li>
                                                            <a href="<?php echo Router::url([
                                                                'controller' => 'Hosts',
                                                                'action'     => 'sharing',
                                                                'plugin'     => '',
                                                                $host['Host']['id']
                                                            ]); ?>">
                                                                <i class="fa fa-sitemap fa-rotate-270"></i> <?php echo __('Sharing'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('deactivate', 'Hosts', '') && $hasEditPermission): ?>
                                                        <li>
                                                            <a href="<?php echo Router::url([
                                                                'controller' => 'Hosts',
                                                                'action'     => 'deactivate',
                                                                'plugin'     => '',
                                                                $host['Host']['id']
                                                            ]); ?>
                                                            ">
                                                                <i class="fa fa-plug"></i> <?php echo __('Disable'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('serviceList', 'services', '')): ?>
                                                        <li>
                                                            <a href="<?php echo Router::url([
                                                                'controller' => 'services',
                                                                'action'     => 'serviceList',
                                                                'plugin'     => '',
                                                                $host['Host']['id']
                                                            ]); ?>
                                                            ">
                                                                <i class="fa fa-list"></i> <?php echo __('Service List'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('allocateToHost', 'servicetemplategroups', '')): ?>
                                                        <li>
                                                            <a href="<?php echo Router::url([
                                                                'controller' => 'Hosts',
                                                                'action'     => 'allocateServiceTemplateGroup',
                                                                'plugin'     => '',
                                                                $host['Host']['id']
                                                            ]); ?>"
                                                            >
                                                                <i class="fa fa-external-link"></i> <?php echo __('Allocate Service Template Group'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>

                                                    <?php
                                                    if ($this->Acl->hasPermission('edit', 'Hosts', '') && $hasEditPermission):
                                                        echo $this->AdditionalLinks->renderAsListItems($additionalLinksList, $host['Host']['id']);
                                                    endif;
                                                    ?>
                                                    <?php if ($this->Acl->hasPermission('delete', 'Hosts', '') && $hasEditPermission): ?>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> ' . __('Delete'), ['controller' => 'hosts', 'action' => 'delete', 'plugin' => '', $host['Host']['id']], ['class' => 'txt-color-red', 'escape' => false], __('Are you sure you want to delete this host?')); ?>
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
                        <?php if (empty($all_hosts)): ?>
                            <div class="noMatch">
                                <center>
                                    <span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
                                </center>
                            </div>
                        <?php endif; ?>

                        <?php echo $this->element('host_mass_changes'); ?>

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
                    <?php echo $this->Form->input('rescheduleHost', ['options' => ['hostOnly' => __('only Hosts'), 'hostAndServices' => __('Hosts and Services')], 'label' => __('Host check for') . ':']); ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="submitRescheduleHost" data-dismiss="modal">
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
                    <div class="txt-color-red padding-bottom-20" id="validationErrorHostDowntime" style="display:none;">
                        <i class="fa fa-exclamation-circle"></i> <?php echo __('Please enter a valide date'); ?></div>
                    <?php
                    echo $this->Form->create('CommitHostDowntime', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <?php
                    $hostdowntimetyps = [
                        0 => __('Individual hosts'),
                        1 => __('Hosts including services'),
                        2 => __('Hosts and dependent Hosts (triggered)'),
                        3 => __('Hosts and dependent Hosts (non-triggered)'),
                    ];
                    ?>
                    <?php echo $this->Form->input('type', ['options' => $hostdowntimetyps, 'label' => __('Maintenance period for') . ':']) ?>
                    <?php echo $this->Form->input('comment', ['value' => __('In progress'), 'label' => __('Comment') . ':']); ?>

                    <!-- from -->
                    <div class="form-group">
                        <label class="col col-md-2 control-label"
                               for="CommitHostDowntimeFromDate"><?php echo __('From'); ?>:</label>
                        <div class="col col-xs-5" style="padding-right: 0px;">
                            <input type="text" id="CommitHostDowntimeFromDate" value="<?php echo date('d.m.Y'); ?>"
                                   class="form-control" name="data[CommitHostDowntime][from_date]">
                        </div>
                        <div class="col col-xs-5" style="padding-left: 0px;">
                            <input type="text" id="CommitHostDowntimeFromTime" value="<?php echo date('h:m'); ?>"
                                   class="form-control" name="data[CommitHostDowntime][from_time]">
                        </div>
                    </div>

                    <!-- to -->
                    <div class="form-group">
                        <label class="col col-md-2 control-label" for="CommitHostDowntimeToDate"><?php echo __('To'); ?>
                            :</label>
                        <div class="col col-xs-5" style="padding-right: 0px;">
                            <input type="text" id="CommitHostDowntimeToDate"
                                   value="<?php echo date('d.m.Y', strtotime('+3 days')); ?>" class="form-control"
                                   name="data[CommitHostDowntime][to_date]">
                        </div>
                        <div class="col col-xs-5" style="padding-left: 0px;">
                            <input type="text" id="CommitHostDowntimeToTime" value="<?php echo date('h:m'); ?>"
                                   class="form-control" name="data[CommitHostDowntime][to_time]">
                        </div>
                    </div>

                    <?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="submitCommitHostDowntime">
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
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Acknowledge host status'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <?php
                    echo $this->Form->create('CommitHostAck', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <?php echo $this->Form->input('type', ['options' => ['hostOnly' => __('Only hosts'), 'hostAndServices' => __('Hosts including services')], 'label' => 'Acknowledge for']); ?>
                    <?php echo $this->Form->input('comment', ['value' => __('In progress'), 'label' => __('Comment') . ':']); ?>
                    <?php echo $this->Form->input('sticky', ['type' => 'checkbox', 'label' => __('Sticky'), 'wrapInput' => 'col-md-offset-2 col-md-10']); ?>
                    <?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submitHostAck">
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
                    <?php echo $this->Form->input('type', ['options' => ['hostOnly' => __('Only hosts'), 'hostAndServices' => __('Hosts including services')], 'label' => 'Notifications']); ?>
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
                    <?php echo $this->Form->input('type', ['options' => ['hostOnly' => __('Only hosts'), 'hostAndServices' => __('Hosts including services')], 'label' => 'Notifications']); ?>
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
