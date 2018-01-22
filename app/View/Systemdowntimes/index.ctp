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
<?php $this->Paginator->options(['url' => Hash::merge($this->params['named'], $this->params['pass'], ['Listsettings' => $DowntimeListsettings])]); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-power-off fa-fw "></i>
            <?php echo __('Recurring downtimes'); ?>
            <span>>
                <?php echo __('List'); ?>
			</span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="alert alert-info fade in">
            <button data-dismiss="alert" class="close">×</button>
            <i class="fa fa-info-circle"></i>
            <strong><?php echo __('Notice'); ?>
                :</strong> <?php echo __('Recurring downtimes with deleted objects will be deleted automatically by the cronjob'); ?>
        </div>
    </div>
</div>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-success">
                                <span><i class="fa fa-plus"></i> <?php echo __('Create downtime'); ?></span> <i
                                        class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <?php if ($this->Acl->hasPermission('addHostdowntime', 'systemdowntimes')): ?>
                                    <li>
                                        <a href="<?php echo Router::url(['controller' => 'systemdowntimes', 'action' => 'addHostdowntime']); ?>"><?php echo __('Create host downtime'); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->Acl->hasPermission('addHostdowntime', 'systemdowntimes')): ?>
                                    <li>
                                        <a href="<?php echo Router::url(['controller' => 'systemdowntimes', 'action' => 'addHostgroupdowntime']); ?>"><?php echo __('Create hostgroup downtime'); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->Acl->hasPermission('addServicedowntime', 'systemdowntimes')): ?>
                                    <li>
                                        <a href="<?php echo Router::url(['controller' => 'systemdowntimes', 'action' => 'addServicedowntime']); ?>"><?php echo __('Create service downtime'); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->Acl->hasPermission('addHostdowntime', 'systemdowntimes')): ?>
                                    <li>
                                        <a href="<?php echo Router::url(['controller' => 'systemdowntimes', 'action' => 'addContainerdowntime']); ?>"><?php echo __('Create container downtime'); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
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
                        <ul class="dropdown-menu arrow-box-up-right pull-right stayOpenOnClick">
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="0"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Object type'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="1"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Object name'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="2"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('User'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="3"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Comment'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="4"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Wekkdays'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="5"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Days of month'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="6"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Start time'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="7"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('End time'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="8"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Delete'); ?></a></li>

                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div id="switch-1" class="widget-toolbar" role="menu">
                        <?php
                        echo $this->Form->create('systemdowntimes', [
                            'class' => 'form-horizontal clear',
                            'url'   => 'index' //reset the URL on submit
                        ]);

                        ?>
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-default">
                                <span id="listoptions_view"
                                      class="hidden-mobile"><?php echo __('Recurring downtimes'); ?></span> <i
                                        class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <?php if ($this->Acl->hasPermission('host', 'downtimes')): ?>
                                    <li>
                                        <a href="<?php echo Router::url(['controller' => 'downtimes', 'action' => 'host']); ?>"><?php echo __('Host downtimes'); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->Acl->hasPermission('service', 'downtimes')): ?>
                                    <li>
                                        <a href="<?php echo Router::url(['controller' => 'downtimes', 'action' => 'service']); ?>"><?php echo __('Service downtimes'); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($this->Acl->hasPermission('index', 'systemdowntimes')): ?>
                                    <li>
                                        <a href="<?php echo Router::url(['controller' => 'systemdowntimes', 'action' => 'index']); ?>"><?php echo __('Recurring downtimes'); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <div class="btn-group">
                            <?php
                            $listoptions = [
                                '5'   => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 5,
                                    'human'         => 5,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '10'  => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 10,
                                    'human'         => 10,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '25'  => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 25,
                                    'human'         => 25,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '50'  => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 50,
                                    'human'         => 50,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '100' => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 100,
                                    'human'         => 100,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '150' => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 150,
                                    'human'         => 150,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '300' => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 300,
                                    'human'         => 300,
                                    'selector'      => '#listoptions_limit',
                                ],
                            ];

                            $selected = $paginatorLimit;

                            if (isset($DowntimeListsettings['limit']) && isset($listoptions[$DowntimeListsettings['limit']]['human'])) {
                                $selected = $listoptions[$DowntimeListsettings['limit']]['human'];
                            }
                            ?>
                            <button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-default hidden-mobile">
                                <span id="listoptions_limit"><?php echo $selected; ?></span> <i
                                        class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right stayOpenOnClick">
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
                                   value="<?php if (isset($DowntimeListsettings['limit'])): echo $DowntimeListsettings['limit']; endif; ?>"
                                   id="listoptions_hidden_limit" name="data[Listsettings][limit]"/>
                        </div>

                        <button class="btn btn-xs btn-success toggle hidden-mobile"><i
                                    class="fa fa-check"></i> <?php echo __('Apply'); ?></button>

                        <?php
                        echo $this->Form->end();
                        ?>
                    </div>

                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-power-off"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Recurring downtimes'); ?> </h2>

                </header>

                <div>

                    <div class="widget-body no-padding">
                        <?php echo $this->ListFilter->renderFilterbox($filters, ['formActionParams' => ['url' => Router::url(Hash::merge($this->params['named'], $this->params['pass'], ['Listsettings' => $DowntimeListsettings])), 'merge' => false]], '<i class="fa fa-filter"></i> ' . __('Filter'), false, false); ?>
                        <div class="mobile_table">
                            <table id="recurringdowntimes_list"
                                   class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <th class="no-sort"><?php echo __('Object type'); ?></th>
                                    <th class="no-sort"><?php echo __('Object name'); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Systemdowntime.author');
                                        echo $this->Paginator->sort('Systemdowntime.author', __('User')); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Systemdowntime.comment');
                                        echo $this->Paginator->sort('Systemdowntime.comment', __('Comment')); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Systemdowntime.weekdays');
                                        echo $this->Paginator->sort('Systemdowntime.weekdays', __('Weekdays')); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Systemdowntime.day_of_month');
                                        echo $this->Paginator->sort('Systemdowntime.day_of_month', __('Days of month')); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Systemdowntime.from_time');
                                        echo $this->Paginator->sort('Systemdowntime.from_time', __('Start time')); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Systemdowntime.duration');
                                        echo $this->Paginator->sort('Systemdowntime.duration', __('Duration')); ?></th>
                                    <th class="no-sort"><?php echo __('Delete'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $objects = [
                                    OBJECT_HOST      => __('Host'),
                                    OBJECT_SERVICE   => __('Service'),
                                    OBJECT_HOSTGROUP => __('Host group'),
                                    OBJECT_NODE      => __('Container'),
                                ];
                                $weekdays = [
                                    1 => __('Monday'),
                                    2 => __('Tuesday'),
                                    3 => __('Wednesday'),
                                    4 => __('Thursday'),
                                    5 => __('Friday'),
                                    6 => __('Saturday'),
                                    7 => __('Sunday'),
                                ];
                                ?>
                                <?php foreach ($all_systemdowntimes as $systemdowntime): ?>
                                    <tr>
                                        <td><?php echo $objects[$systemdowntime['Systemdowntime']['objecttype_id']]; ?></td>
                                        <td>
                                            <?php
                                            switch ($systemdowntime['Systemdowntime']['objecttype_id']):
                                                case OBJECT_HOST:
                                                    if (isset($systemdowntime['Host']['id']) && $systemdowntime['Host']['id'] !== null): ?>
                                                        <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                                            <a href="/hosts/browser/<?php echo $systemdowntime['Host']['id']; ?>"><?php echo $systemdowntime['Host']['name']; ?></a>
                                                        <?php else: ?>
                                                            <?php echo h($systemdowntime['Host']['name']); ?>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="muted italic"><?php echo __('Host deleted'); ?></span>
                                                        <?php
                                                    endif;
                                                    break;

                                                case OBJECT_SERVICE:
                                                    if (isset($systemdowntime['Service']['id']) && $systemdowntime['Service']['id'] !== null):
                                                        $serviceName = $systemdowntime['Service']['name'];
                                                        if ($serviceName === null || $serviceName === ''):
                                                            $serviceName = $systemdowntime['Servicetemplate']['name'];
                                                        endif;

                                                        if ($this->Acl->hasPermission('browser', 'services')):
                                                            ?>
                                                            <a href="/services/browser/<?php echo $systemdowntime['Service']['id']; ?>"><?php echo h($serviceName); ?></a>
                                                        <?php else: ?>
                                                            <?php echo h($serviceName); ?>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="muted italic"><?php echo __('Service deleted'); ?></span>
                                                        <?php
                                                    endif;
                                                    break;

                                                case OBJECT_HOSTGROUP:
                                                    if (isset($systemdowntime['Hostgroup']['id']) && $systemdowntime['Hostgroup']['id'] !== null): ?>
                                                        <?php if ($this->Acl->hasPermission('edit', 'hostgroups')): ?>
                                                            <a href="/hostgroups/edit/<?php echo $systemdowntime['Hostgroup']['id']; ?>"><?php echo h($systemdowntime['Container']['name']); ?></a>
                                                        <?php else: ?>
                                                            <?php echo h($systemdowntime['Container']['name']); ?>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="muted"><?php echo __('Hostgroup deleted'); ?></span>
                                                        <?php
                                                    endif;
                                                    break;

                                                case OBJECT_NODE:
                                                    //debug($systemdowntime);
                                                    if (isset($systemdowntime['Container']['id']) && $systemdowntime['Container']['id'] !== null): ?>
                                                        <?php echo h($systemdowntime['Container']['name']); ?>
                                                    <?php else: ?>
                                                        <span class="muted"><?php echo __('Container deleted'); ?></span>
                                                    <?php
                                                    endif;
                                                    break;

                                            endswitch;
                                            ?>
                                        </td>
                                        <td><?php echo $systemdowntime['Systemdowntime']['author']; ?></td>
                                        <td>
                                            <span class="text-muted">
                                                AUTO[<?php echo $systemdowntime['Systemdowntime']['id']; ?>]:
                                            </span>
                                            <?php echo $systemdowntime['Systemdowntime']['comment']; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $days = explode(',', $systemdowntime['Systemdowntime']['weekdays']);
                                            $days = Hash::filter($days);
                                            $_days = [];
                                            foreach ($days as $day):
                                                $_days[] = $weekdays[$day];
                                            endforeach;
                                            echo implode(', ', $_days);
                                            ?>

                                        </td>
                                        <td>
                                            <?php
                                            if ($systemdowntime['Systemdowntime']['day_of_month'] == ''): ?>
                                                <span class="text-muted"><?php echo __('every'); ?></span>
                                                <?php
                                            else:
                                                echo $systemdowntime['Systemdowntime']['day_of_month'];
                                            endif;
                                            ?>
                                        </td>
                                        <td><?php echo $systemdowntime['Systemdowntime']['from_time']; ?></td>
                                        <td><?php echo $systemdowntime['Systemdowntime']['duration']; ?></td>
                                        <td class="text-center">
                                            <?php
                                            if ($this->Acl->hasPermission('delete', 'systemdowntimes') && $systemdowntime['canDelete']):
                                                echo $this->Utils->deleteButton(null, $systemdowntime['Systemdowntime']['id']);
                                            endif;
                                            ?>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (empty($all_systemdowntimes)): ?>
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


