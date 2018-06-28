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
<div class="row">
    <div class="col-xs-12 col-md-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-area-chart fa-fw"></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Graphgenerator'); ?>
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="alert alert-info">
            <i class="fa-fw fa fa-info"></i>
            <?php echo __('This feature is marked as deprecated and will be removed in a future version.'); ?>
        </div>
    </div>
</div>

<div class="overlay" style="display: none;">
    <div id="nag_longoutput_loader"
         style="position: absolute; top: 50%; left: 50%; margin-top: -29px; margin-left: -23px; z-index: 20; font-size: 40px; color: #fff;">
        <i class="fa fa-cog fa-lg fa-spin"></i>
    </div>
</div>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu"></div>
                    <div class="jarviswidget-ctrls" role="menu"></div>
                    <span class="widget-icon"><i class="fa fa-area-chart"></i></span>

                    <h2 class="hidden-mobile hidden-tablet"><?php echo __('Graphgenerator'); ?></h2>
                    <ul class="nav nav-tabs pull-right padding-left-20" id="widget-tab-1">
                        <li>
                            <a href="/graphgenerators/index">
                                <i class="fa fa-lg fa-plus"></i>
                                <span class="hidden-mobile hidden-tablet"> <?php echo __('New'); ?></span>
                            </a>
                        </li>
                        <li class="active">
                            <a>
                                <i class="fa fa-lg fa-save"></i>
                                <span class="hidden-mobile hidden-tablet"> <?php echo __('List'); ?></span>
                            </a>
                        </li>
                    </ul>
                </header>
                <div>
                    <div class="widget-body no-padding">
                        <!--						--><?php //echo $this->ListFilter->renderFilterbox($filters, [], '<i class="fa fa-filter"></i> ' . __('Filter'), false, false); ?>
                        <div class="mobile_table">
                            <table id="host_list" class="table table-striped table-hover table-bordered">
                                <!--							<table id="host_list" class="table table-striped table-bordered smart-form">-->
                                <thead>
                                <tr>
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <th class="no-sort"></th>
                                    <th class="select_datatable no-sort">
                                        <?php
                                        //	echo $this->Utils->getDirection($order, 'Graphgen.current_state');
                                        //	echo $this->Paginator->sort('Host.hoststatus', 'Hoststatus');
                                        echo $this->Utils->getDirection($order, 'GraphgenTmpl.name');
                                        echo $this->Paginator->sort('GraphgenTmpl.name', 'Name');
                                        ?>
                                    </th>
                                    <th>
                                        <?php
                                        echo $this->Utils->getDirection($order, 'GraphgenTmpl.relative_time');
                                        echo $this->Paginator->sort('GraphgenTmpl.relative_time', 'Time');
                                        ?>
                                    </th>
                                    <th>Assigned Hosts and Services</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php foreach ($all_templates as $template): ?>
                                    <tr>
                                        <td class="text-center width-5">
                                            <input type="checkbox" class="massChange"
                                                   data-delete-display-text="<?php echo $template['GraphgenTmpl']['name'].' (ID:'.$template['GraphgenTmpl']['id'].')'; ?>"
                                                   value="<?php echo $template['GraphgenTmpl']['id']; ?>">
                                        </td>
                                        <td>
                                            <?php echo $this->Html->link($template['GraphgenTmpl']['name'], [
                                                'controller' => $this->params['controller'],
                                                'action'     => 'index',
                                                $template['GraphgenTmpl']['id'],
                                            ]); ?>
                                        </td>
                                        <!--										<td>-->
                                        <?php //echo $template['GraphgenTmpl']['relative_time']; ?><!--</td>-->
                                        <td><?php echo $this->Utils->secondsInHumanShort($template['GraphgenTmpl']['relative_time']); ?></td>
                                        <td>
                                            <?php if (count($template['HostAndServices'])): ?>
                                                <ul>
                                                    <?php foreach ($template['HostAndServices'] as $host_and_services): ?>
                                                        <li><?php echo $host_and_services['host_name']; ?>
                                                            <?php if (count($host_and_services['services']) > 0): ?>
                                                                <ul>
                                                                    <?php foreach ($host_and_services['services'] as $service_id => $service): ?>
                                                                        <li><?php echo $service['service_name']; ?></li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                            <?php endif; ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-md-2 text-muted">
                                <center><span id="selectionCount"></span></center>
                            </div>
                            <div class="col-xs-12 col-md-2 "><span id="selectAll" class="pointer"><i
                                            class="fa fa-lg fa-check-square-o"></i> <?php echo __('Select all'); ?></span>
                            </div>
                            <div class="col-xs-12 col-md-2"><span id="untickAll" class="pointer"><i
                                            class="fa fa-lg fa-square-o"></i> <?php echo __('Undo selection'); ?></span>
                            </div>
                            <div class="col-xs-12 col-md-2"><a href="javascript:void(0);" id="deleteAll"
                                                               class="txt-color-red" style="text-decoration: none;"> <i
                                            class="fa fa-lg fa-trash-o"></i> <?php echo __('Delete'); ?></a></div>
                            <!-- hidden fields for multi language -->
                            <input type="hidden" id="delete_message_h1" value="<?php echo __('Attention!'); ?>"/>
                            <input type="hidden" id="delete_message_h2"
                                   value="<?php echo __('Do you really want to delete the selected graph configurations?'); ?>"/>
                            <input type="hidden" id="disable_message_h1" value="<?php echo __('Notice!'); ?>"/>
                            <!--							<input type="hidden" id="disable_message_h2" value="-->
                            <?php //echo __('Do you really want disable the selected graph configurations?'); ?><!--" />-->
                            <input type="hidden" id="message_yes" value="<?php echo __('Yes'); ?>"/>
                            <input type="hidden" id="message_no" value="<?php echo __('No'); ?>"/>
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

