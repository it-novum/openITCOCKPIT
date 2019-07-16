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
            <i class="fa fa-exclamation-circle fa-fw "></i>
            <?php echo __('Administration') ?>
            <span>>
                <?php echo __('System Failure'); ?>
            </span>
        </h1>
    </div>
</div>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <?php
                        if ($this->Acl->hasPermission('add')):
                            echo $this->Html->link(__('New'), '/' . $this->params['controller'] . '/add', ['class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus']);
                            echo " "; //Fix HTML
                        endif;
                        echo $this->Html->link(__('Filter'), 'javascript:', ['class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-filter']);

                        if ($isFilter):
                            echo " "; //Fix HTML
                            echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']);
                        endif;
                        ?>
                        <?php echo $this->AdditionalLinks->renderAsLinks($additionalLinksTop); ?>
                    </div>
                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-exclamation-circle"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('System failure'); ?> </h2>

                </header>
                <div>
                    <div class="widget-body no-padding">
                        <?php echo $this->ListFilter->renderFilterbox($filters, [], '<i class="fa fa-filter"></i> ' . __('Filter'), false, false); ?>
                        <div class="mobile_table">
                            <table id="systemfailure_list"
                                   class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Systemfailure.start_time');
                                        echo $this->Paginator->sort('Systemfailure.start_time', __('Start')); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Systemfailure.end_time');
                                        echo $this->Paginator->sort('Systemfailure.end_time', __('End')); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'User.full_name');
                                        echo $this->Paginator->sort('User.full_name', __('Username')); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Systemfailure.comment');
                                        echo $this->Paginator->sort('Systemfailure.comment', __('Comment')); ?></th>
                                    <th class="no-sort"><?php echo __('Delete'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($all_systemfailures as $systemfailure): ?>
                                    <tr>
                                        <td><?php echo $this->Time->format($systemfailure['Systemfailure']['start_time'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></td>
                                        <td><?php echo $this->Time->format($systemfailure['Systemfailure']['end_time'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></td>
                                        <td><?php echo $systemfailure['User']['full_name']; ?></td>
                                        <td><?php echo $systemfailure['Systemfailure']['comment']; ?></td>
                                        <td>
                                            <?php
                                            if ($this->Acl->hasPermission('delete')):
                                                echo $this->Utils->deleteButton(null, $systemfailure['Systemfailure']['id']);
                                            endif;
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (empty($all_systemfailures)): ?>
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
