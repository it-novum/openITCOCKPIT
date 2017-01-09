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
<?php

$this->Paginator->options(['url' => $this->params['named']]);
$filter = "/";
foreach ($this->params->named as $key => $value) {
    $filter .= $key.":".$value."/";
}

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

<!-- widget grid -->
<section id="widget-grid" class="">

    <!-- row -->
    <div class="row">

        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <!-- widget options:
                usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

                data-widget-colorbutton="false"
                data-widget-editbutton="false"
                data-widget-togglebutton="false"
                data-widget-deletebutton="false"
                data-widget-fullscreenbutton="false"
                data-widget-custombutton="false"
                data-widget-collapsed="true"
                data-widget-sortable="false"

                -->
                <header>
                    <div class="widget-toolbar" role="menu">
                        <?php echo $this->Html->link(__('Search'), 'javascript:', ['class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search']); ?>
                        <?php
                        if ($isFilter):
                            echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']);
                        endif;
                        ?>
                        <?php echo $this->AdditionalLinks->renderAsLinks($additionalLinksTop); ?>
                    </div>

                    <div class="jarviswidget-ctrls" role="menu">

                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-desktop"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Hosts'); ?> </h2>
                    <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                        <?php if ($this->Acl->hasPermission('index', 'hosts')): ?>
                            <li class="">
                                <a href="/hosts/index<?php echo $filter; ?>"><i class="fa fa-stethoscope"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Monitored'); ?> </span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->Acl->hasPermission('notMonitored', 'hosts')): ?>
                            <li class="">
                                <a href="/hosts/notMonitored<?php echo $filter; ?>"><i class="fa fa-user-md"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Not monitored'); ?> </span></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($this->Acl->hasPermission('disabled', 'hosts')): ?>
                            <li>
                                <a href="/hosts/disabled<?php echo $filter; ?>"><i class="fa fa-power-off"></i> <span
                                            class="hidden-mobile hidden-tablet"> <?php echo __('Disabled'); ?> </span></a>
                            </li>
                        <?php endif; ?>
                        <li class="active">
                            <a href="/deleted_hosts/index<?php echo $filter; ?>"><i class="fa fa-trash-o"></i> <span
                                        class="hidden-mobile hidden-tablet"> <?php echo __('Deleted'); ?> </span></a>
                        </li>
                    </ul>

                </header>

                <!-- widget div-->
                <div>

                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->

                    </div>
                    <!-- end widget edit box -->

                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php
                        $options = ['avoid_cut' => true];
                        echo $this->ListFilter->renderFilterbox($filters, $options, '<i class="fa fa-search"></i> '.__('search'), false, false);
                        ?>
                        <div class="mobile_table">
                            <table id="host_list" class="table table-striped table-bordered smart-form" style="">
                                <thead>
                                <tr>
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <th><?php echo $this->Utils->getDirection($order, 'DeletedHost.name');
                                        echo $this->Paginator->sort('DeletedHost.name', 'Hostname'); ?></th>
                                    <th><?php echo $this->Utils->getDirection($order, 'DeletedHost.uuid');
                                        echo $this->Paginator->sort('DeletedHost.uuid', __('UUID')); ?></th>
                                    <th><?php echo $this->Utils->getDirection($order, 'DeletedHost.created');
                                        echo $this->Paginator->sort('DeletedHost.created', __('Date')); ?></th>
                                    <th><?php echo $this->Utils->getDirection($order, 'DeletedHost.deleted_perfdata');
                                        echo $this->Paginator->sort('DeletedHost.deleted_perfdata', __('Performance data deleted')); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($deletedHosts as $host): ?>
                                    <tr>
                                        <td><?php echo $host['DeletedHost']['name']; ?></td>
                                        <td><?php echo $host['DeletedHost']['uuid']; ?></td>
                                        <td><?php echo $this->Time->format($host['DeletedHost']['created'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></td>
                                        <td class="text-center">
                                            <?php if ($host['DeletedHost']['deleted_perfdata'] == 1): ?>
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
                        <?php if (empty($deletedHosts)): ?>
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
                    <!-- end widget content -->

                </div>
                <!-- end widget div -->

            </div>
            <!-- end widget -->


    </div>

    <!-- end row -->

</section>
<!-- end widget grid -->
