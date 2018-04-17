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
<?php $this->Paginator->options(['url' => $this->params['named']]); ?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-clock-o fa-fw "></i>
            <?php echo __('Administration') ?>
            <span>>
                <?php echo __('Cronjobs'); ?>
            </span>
        </h1>
    </div>
</div>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <?php
                        if ($this->Acl->hasPermission('add')):
                            echo $this->Html->link(__('New'), '/' . $this->params['controller'] . '/add', ['class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus']);
                        endif;
                        ?>
                    </div>

                    <span class="widget-icon hidden-mobile"> <i class="fa fa-clock-o"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Cronjobs'); ?> </h2>

                </header>

                <div>
                    <div class="widget-body no-padding">
                        <div class="mobile_table">
                            <table id="systemfailure_list"
                                   class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <th class="no-sort"><?php echo __('Task') ?></th>
                                    <th class="no-sort"><?php echo __('Plugin') ?></th>
                                    <th class="no-sort"><?php echo __('Interval'); ?></th>
                                    <th class="no-sort"><?php echo __('Last scheduled'); ?></th>
                                    <th class="no-sort"><?php echo __('Is currently running'); ?></th>
                                    <th class="no-sort"><?php echo __('Enabled'); ?></th>
                                    <th class="no-sort text-center"><i class="fa fa-gear fa-lg"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($cronjobs as $cronjob): ?>
                                    <tr>
                                        <td><?php echo h($cronjob['Cronjob']['task']); ?></td>
                                        <td><?php echo h($cronjob['Cronjob']['plugin']); ?></td>
                                        <td><?php echo h($cronjob['Cronjob']['interval']); ?></td>
                                        <td><?php echo h($this->Time->format($cronjob['Cronschedule']['start_time'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?></td>
                                        <td class="text-center">
                                            <?php
                                            if ($cronjob['Cronschedule']['is_running'] == 0):
                                                echo __('No');
                                            else:
                                                echo __('Yes');
                                            endif;
                                            ?>
                                        </td>
                                        <td class="text-align-center">
                                            <?php if ($cronjob['Cronjob']['enabled']): ?>
                                                <i class="fa fa-check text-success"></i>
                                                <?php else: ?>
                                                <i class="fa fa-times text-danger"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($this->Acl->hasPermission('edit')): ?>
                                                <a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $cronjob['Cronjob']['id']; ?>"
                                                   data-original-title="<?php echo __('Edit'); ?>" data-placement="left"
                                                   rel="tooltip" data-container="body"><i id="list_edit"
                                                                                          class="fa fa-gear fa-lg txt-color-teal"></i></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (empty($cronjobs)): ?>
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
