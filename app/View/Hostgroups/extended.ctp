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
 * @property \itnovum\openITCOCKPIT\HostgroupsController\CumulatedServicestatusCollection $ServicestatusCollection
 */

use itnovum\openITCOCKPIT\Core\Hoststatus;
use itnovum\openITCOCKPIT\Core\HumanTime;

?>
<div id="error_msg"></div>
<div class="alert alert-success alert-block" id="flashSuccess" style="display:none;">
    <a href="#" data-dismiss="alert" class="close">×</a>
    <h4 class="alert-heading"><i class="fa fa-check-circle-o"></i> <?php echo __('Command sent successfully'); ?></h4>
    <?php echo __('Page refresh in'); ?> <span id="autoRefreshCounter"></span> <?php echo __('seconds...'); ?>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-sitemap fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Host Groups'); ?>
            </span>
        </h1>
    </div>
</div>
<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <?php
            if(!empty($hostgroup)):
            //Wee only need the form for the nice markup -.-
            echo $this->Form->create('extended', [
                'class' => 'form-horizontal clear',
            ]);
            ?>
            <div class="row">
                <div class="col col-xs-8">
                    <?php
                    echo $this->Form->input('host_id', [
                        'options'          => $hostgroups,
                        'selected'         => $hostgroup['Hostgroup']['id'],
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
                        <?php if ($this->Acl->hasPermission('edit')): ?>
                            <a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $hostgroup['Hostgroup']['id']; ?>"
                               class="btn btn-default btn-xs">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
                        <?php else: ?>
                            <a href="javascript:void(0);" class="btn btn-default btn-xs">&nbsp;<i class="fa fa-cog"></i>&nbsp;
                            </a>
                        <?php endif; ?>
                        <a href="javascript:void(0);" data-toggle="dropdown"
                           class="btn btn-default btn-xs dropdown-toggle"><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php if ($this->Acl->hasPermission('edit')): ?>
                                <li>
                                    <a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $hostgroup['Hostgroup']['id']; ?>"><i
                                                class="fa fa-cog"></i> <?php echo __('Edit'); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if ($this->Acl->hasPermission('externalcommands', 'hosts')): ?>
                                <li class="divider"></li>
                                <li>
                                    <a href="javascript:void(0);" data-toggle="modal"
                                       data-target="#nag_command_reschedule"><i
                                                class="fa fa-refresh"></i> <?php echo __('Reset check time'); ?></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" data-toggle="modal"
                                       data-target="#nag_command_schedule_downtime"><i
                                                class="fa fa-clock-o"></i> <?php echo __('Set planned maintenance times'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" data-toggle="modal"
                                       data-target="#nag_command_ack_state"><i
                                                class="fa fa-user"></i> <?php echo __('Acknowledge host status'); ?></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" data-toggle="modal"
                                       data-target="#nag_command_disable_notifications"><i
                                                class="fa fa-envelope-o"></i> <?php echo __('Disable notification'); ?>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" data-toggle="modal"
                                       data-target="#nag_command_enable_notifications"><i
                                                class="fa fa-envelope"></i> <?php echo __('Enable notifications'); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php echo $this->AdditionalLinks->renderAsListItems($additionalLinksList, $hostgroup['Hostgroup']['id']); ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php echo $this->Form->end(); ?>

            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <?php if ($this->Acl->hasPermission('add')): ?>
                        <div class="widget-toolbar" role="menu">
                            <?php echo $this->Html->link(__('New'), '/'.$this->params['controller'].'/add', ['class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus']); ?>
                        </div>
                    <?php endif; ?>
                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-sitemap"></i> </span>
                    <h2 class="hidden-mobile"><?php echo h($hostgroup['Container']['name']); ?></h2>
                    <?php if ($this->Acl->hasPermission('index')): ?>
                        <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                            <li>
                                <a href="/hostgroups/index"><i class="fa fa-minus-square"></i>
                                    <span class="hidden-mobile hidden-tablet"><?php echo __('Default overview'); ?></span></a>
                            </li>
                        </ul>
                    <?php endif; ?>
                    <div class="widget-toolbar cursor-default hidden-xs hidden-sm hidden-md">
                        <?php echo __('UUID: %s', h($hostgroup['Hostgroup']['uuid'])); ?>
                    </div>
                </header>
                <div>
                    <div class="widget-body no-padding">
                        <table id="hostgroup_list" class="table table-striped table-hover table-bordered smart-form" style="">
                            <tr>
                                <td colspan="10" class="no-padding text-right txt-color-white">
                                    <?php
                                    $host_status_objects = Hash::extract($hosts, '{n}.Hoststatus.current_state');
                                    $state_values = array_count_values($host_status_objects);
                                    ?>
                                    <div class="col-md-12 pull-right">
                                        <div class="col-md-6 txt-color-blueDark">
                                            <div class="padding-5">
                                                <strong>
                                                    <?php echo __('Host status total').': '.sizeof($hosts); ?>
                                                </strong>
                                                <?php
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-md-2 btn-success">
                                            <div class="padding-5">
                                                <label for="<?php echo $hostgroup['Hostgroup']['uuid'].'[0]'; ?>"
                                                       class="no-padding pointer">
                                                    <input type="checkbox"
                                                           name="<?php echo $hostgroup['Hostgroup']['uuid'].'[0]'; ?>"
                                                           id="<?php echo $hostgroup['Hostgroup']['uuid'].'[0]'; ?>"
                                                           class="no-padding pointer state_filter" state="0"
                                                           checked="checked"
                                                           uuid="<?php echo $hostgroup['Hostgroup']['uuid']; ?>"/>
                                                    <strong>
                                                        <?php
                                                        echo __(' %s up', (isset($state_values[0]) ? $state_values[0] : 0)); ?>
                                                    </strong>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2 btn-danger">
                                            <div class="padding-5">
                                                <label for="<?php echo $hostgroup['Hostgroup']['uuid'].'[1]'; ?>"
                                                       class="no-padding pointer">
                                                    <input type="checkbox"
                                                           name="<?php echo $hostgroup['Hostgroup']['uuid'].'[1]'; ?>"
                                                           id="<?php echo $hostgroup['Hostgroup']['uuid'].'[1]'; ?>"
                                                           class="no-padding pointer state_filter" state="1"
                                                           checked="checked"
                                                           uuid="<?php echo $hostgroup['Hostgroup']['uuid']; ?>"/>
                                                    <strong>
                                                        <?php
                                                        echo __(' %s down', (isset($state_values[1]) ? $state_values[1] : 0)); ?>
                                                    </strong>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2 bg-color-blueLight">
                                            <div class="padding-5">
                                                <label for="<?php echo $hostgroup['Hostgroup']['uuid'].'[2]'; ?>"
                                                       class="no-padding pointer">
                                                    <input type="checkbox"
                                                           name="<?php echo $hostgroup['Hostgroup']['uuid'].'[2]'; ?>"
                                                           id="<?php echo $hostgroup['Hostgroup']['uuid'].'[2]'; ?>"
                                                           class="no-padding pointer state_filter" state="2"
                                                           checked="checked"
                                                           uuid="<?php echo $hostgroup['Hostgroup']['uuid']; ?>"/>
                                                    <strong>
                                                        <?php
                                                        echo __(' %s unreachable', (isset($state_values[2]) ? $state_values[2] : 0)); ?>
                                                    </strong>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6"></td>
                                <td>
                                    <?php
                                    echo $this->Form->input('hostname', [
                                        'label'       => false,
                                        'placeholder' => __('Host'),
                                        'class'       => 'padding-5',
                                        'search_id'   => $hostgroup['Hostgroup']['uuid'],
                                        'filter'      => 'true',
                                        'needle'      => 'hostname',
                                    ]);
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $this->Form->input('status_since', [
                                        'label'       => false,
                                        'placeholder' => __('Status since'),
                                        'class'       => 'padding-5',
                                        'search_id'   => $hostgroup['Hostgroup']['uuid'],
                                        'filter'      => 'true',
                                        'needle'      => 'status_since',
                                    ]);
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $this->Form->input('last_check', [
                                        'label'       => false,
                                        'placeholder' => __('Last check'),
                                        'class'       => 'padding-5',
                                        'search_id'   => $hostgroup['Hostgroup']['uuid'],
                                        'filter'      => 'true',
                                        'needle'      => 'last_check',
                                    ]);
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $this->Form->input('next_check', [
                                        'label'       => false,
                                        'placeholder' => __('Next check'),
                                        'class'       => 'padding-5',
                                        'search_id'   => $hostgroup['Hostgroup']['uuid'],
                                        'filter'      => 'true',
                                        'needle'      => 'next_check',
                                    ]);
                                    ?>
                                </td>
                            </tr>
                            <?php foreach ($hosts as $host): ?>
                                <?php
                                $hoststatus = new Hoststatus($host['Hoststatus']); ?>

                                <tr class="<?php echo $host['Hostgroup']['uuid']; ?> state_<?php echo $hoststatus->currentState(); ?>"
                                    id="append_<?php echo $hostgroup['Hostgroup']['uuid'].$host['Host']['uuid']; ?>"
                                    content="false" host-id="<?php echo $host['Host']['id']; ?>">
                                    <td class="width-15">
                                        <i class="showhide fa fa-plus-square-o pointer font-md"
                                           showhide_uuid="<?php echo $host['Hostgroup']['uuid'].$host['Host']['uuid']; ?>"></i>
                                    </td>
                                    <td class="text-center width-40">
                                        <?php
                                        $hasHoststatus = !empty($host['Hoststatus']);
                                        $hostHref = 'javascript:void(0);';
                                        if ($this->Acl->hasPermission('browser', 'hosts')):
                                            $hostHref = '/hosts/browser/'.$host['Host']['id'];
                                        endif;

                                        if ($hasHoststatus):
                                            if ($hoststatus->isFlapping() === true):
                                                echo $hoststatus->getHostFlappingIconColored();
                                            else:
                                                echo $hoststatus->getHumanHoststatus($hostHref)['html_icon'];
                                            endif;
                                        else:
                                            echo $hoststatus->getHumanHoststatus($hostHref)['html_icon'];
                                        endif;
                                        ?>
                                    </td>

                                    <td class="text-center width-15">
                                        <?php $accumulatedServiceState = $ServicestatusCollection->getByHostIdEvenIfNotExists($host['Host']['id']); ?>
                                        <?php if ($accumulatedServiceState >= 0): ?>
                                            <i class="fa fa-gears
                                            <?php
                                            echo $this->Status->ServiceStatusTextColor(
                                                $accumulatedServiceState
                                            );
                                            ?>
                                            "></i>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center width-15">
                                        <?php
                                        if ($hoststatus->currentState() > 0):
                                            if ($hoststatus->isAcknowledged()): ?>
                                                <i class="fa fa-user txt-color-blue"></i>
                                            <?php endif;
                                        endif;
                                        ?>
                                    </td>

                                    <td class="text-center width-15">
                                        <?php
                                        if ($hoststatus->isInDowntime()):?>
                                            <i class="fa fa-power-off"></i>
                                            <?php
                                        endif;
                                        ?>
                                    </td>

                                    <td class="text-center width-15">
                                        <?php if ($this->Acl->hasPermission('view', 'documentations')): ?>
                                            <a href="/documentations/view/<?php echo $host['Host']['uuid']; ?>"
                                               data-original-title="<?php echo __('Documentation'); ?>"
                                               data-placement="bottom" rel="tooltip"><i
                                                        class="fa fa-book txt-color-blueDark"></i></a>
                                        <?php endif; ?>
                                    </td>

                                    <td class="<?php echo h($host['Host']['name']) ?>" search="hostname">
                                        <strong>
                                            <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                                <a href="/hosts/browser/<?php echo $host['Host']['id']; ?>"><?php echo h($host['Host']['name']); ?></a>
                                            <?php else: ?>
                                                <?php echo h($host['Host']['name']); ?>
                                            <?php endif; ?>
                                        </strong>
                                    </td>

                                    <td search="status_since">
                                        <?php
                                        if ($hoststatus->getLastHardStateChange()):
                                            echo h(HumanTime::secondsInHumanShort(time() - $hoststatus->getLastHardStateChange()));
                                        else:
                                            echo __('N/A');
                                        endif;
                                        ?>
                                    </td>

                                    <td search="last_check">
                                        <?php
                                        if ($hoststatus->getLastCheck()):
                                            echo h($this->Time->format($hoststatus->getLastCheck(), $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')));
                                        else:
                                            echo __('N/A');
                                        endif;
                                        ?>
                                    </td>

                                    <td search="next_check">
                                        <?php
                                        if ($hoststatus->isActiveChecksEnabled()):
                                            echo h($this->Time->format($hoststatus->getNextCheck(), $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')));
                                        else:
                                            echo __('N/A');
                                        endif;
                                        ?>
                                    </td>
                                </tr>

                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
            <?php else: ?>

                <div class="alert alert-info alert-block">
                    <h4 class="alert-heading"><i class="fa fa-exclamation-circle"></i> <?php echo __('No host groups available'); ?></h4>
                </div>

            <?php endif; ?>
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
                    <?php echo $this->Form->input('rescheduleHostgroup', ['options' => ['hostOnly' => __('only Hosts'), 'hostAndServices' => __('Hosts and Services')], 'label' => __('Host check for').':']); ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="submitRescheduleHostgroup" data-dismiss="modal">
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
                    echo $this->Form->create('CommitHostgroupDowntime', [
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
                    <?php echo $this->Form->input('type', ['options' => $hostdowntimetyps, 'label' => __('Maintenance period for').':']) ?>
                    <?php echo $this->Form->input('comment', ['value' => __('In progress'), 'label' => __('Comment').':']); ?>

                    <!-- from -->
                    <div class="form-group">
                        <label class="col col-md-2 control-label"
                               for="CommitHostgroupDowntimeFromDate"><?php echo __('From'); ?>:</label>
                        <div class="col col-xs-5" style="padding-right: 0px;">
                            <input type="text" id="CommitHostgroupDowntimeFromDate" value="<?php echo date('d.m.Y'); ?>"
                                   class="form-control" name="data[CommitHostgroupDowntime][from_date]">
                        </div>
                        <div class="col col-xs-5" style="padding-left: 0px;">
                            <input type="text" id="CommitHostgroupDowntimeFromTime" value="<?php echo date('h:m'); ?>"
                                   class="form-control" name="data[CommitHostgroupDowntime][from_time]">
                        </div>
                    </div>

                    <!-- to -->
                    <div class="form-group">
                        <label class="col col-md-2 control-label"
                               for="CommitHostgroupDowntimeToDate"><?php echo __('To'); ?>:</label>
                        <div class="col col-xs-5" style="padding-right: 0px;">
                            <input type="text" id="CommitHostgroupDowntimeToDate"
                                   value="<?php echo date('d.m.Y', strtotime('+3 days')); ?>" class="form-control"
                                   name="data[CommitHostgroupDowntime][to_date]">
                        </div>
                        <div class="col col-xs-5" style="padding-left: 0px;">
                            <input type="text" id="CommitHostgroupDowntimeToTime" value="<?php echo date('h:m'); ?>"
                                   class="form-control" name="data[CommitHostgroupDowntime][to_time]">
                        </div>
                    </div>

                    <?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
                </div>

            </div>
            <div class="modal-footer">
                <a href="<?php echo Router::url(['controller' => 'systemdowntimes', 'action' => 'addHostgroupdowntime', 'hostgroup_id' => $hostgroup['Hostgroup']['id']]); ?>"
                   class="btn btn-primary pull-left"><i class="fa fa-cogs"></i> <?php echo __('More options'); ?></a>
                <button type="button" class="btn btn-success" id="submitCommitHostgroupDowntime">
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
                    echo $this->Form->create('CommitHostgroupAck', [
                        'class' => 'form-horizontal clear',
                    ]); ?>
                    <?php echo $this->Form->input('type', ['options' => ['hostOnly' => __('Only hosts'), 'hostAndServices' => __('Hosts including services')], 'label' => 'Acknowledge for']); ?>
                    <?php echo $this->Form->input('comment', ['value' => __('In progress'), 'label' => __('Comment').':']); ?>
                    <?php echo $this->Form->input('sticky', ['type' => 'checkbox', 'label' => __('Sticky')]); ?>
                    <?php echo $this->Form->input('author', ['type' => 'hidden', 'value' => $username]) ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submitHostgroupAck">
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
