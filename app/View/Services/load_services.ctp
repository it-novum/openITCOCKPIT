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

/*
 *         _                    _               
 *   __ _ (_) __ ___  __ __   _(_) _____      __
 *  / _` || |/ _` \ \/ / \ \ / / |/ _ \ \ /\ / /
 * | (_| || | (_| |>  <   \ V /| |  __/\ V  V / 
 *  \__,_|/ |\__,_/_/\_\   \_/ |_|\___| \_/\_/  
 *      |__/                                    
*/
if (!empty($services)):
    //debug($services);
    ?>
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
                            <?php echo $this->Html->link(__('New'), '/'.$this->params['controller'].'/add', ['class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus']); ?>
                            <?php echo $this->Html->link(__('Search'), 'javascript:', ['class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search']); ?>
                            <?php
                            /*if($isFilter):
                                echo $this->ListFilter->resetLink(null, array('class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times'));
                            endif;
                            */
                            ?>
                            <div class="widget-toolbar" role="menu">
                                <a href="javascript:void(0);" class="dropdown-toggle selector" data-toggle="dropdown"><i
                                            class="fa fa-lg fa-table"></i></a>
                                <ul class="dropdown-menu arrow-box-up-right pull-right">
                                    <li style="width: 100%;"><a href="javascript:void(0)"
                                                                class="select_datatable text-left"
                                                                class="select_datatable text-left" my-column="1"><input
                                                    type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Edit'); ?>
                                        </a></li>
                                    <li style="width: 100%;"><a href="javascript:void(0)"
                                                                class="select_datatable text-left"
                                                                class="select_datatable text-left" my-column="2"><input
                                                    type="checkbox" class="pull-left"/>
                                            &nbsp; <?php echo __('Graph'); ?></a></li>
                                    <li style="width: 100%;"><a href="javascript:void(0)"
                                                                class="select_datatable text-left"
                                                                class="select_datatable text-left" my-column="3"><input
                                                    type="checkbox" class="pull-left"/>
                                            &nbsp; <?php echo __('Passive'); ?></a></li>
                                    <li style="width: 100%;"><a href="javascript:void(0)"
                                                                class="select_datatable text-left"
                                                                class="select_datatable text-left" my-column="4"><input
                                                    type="checkbox" class="pull-left"/>
                                            &nbsp; <?php echo __('Hostname'); ?></a></li>
                                    <li style="width: 100%;"><a href="javascript:void(0)"
                                                                class="select_datatable text-left"
                                                                class="select_datatable text-left" my-column="5"><input
                                                    type="checkbox" class="pull-left"/>
                                            &nbsp; <?php echo __('IP-Address'); ?></a></li>
                                    <li style="width: 100%;"><a href="javascript:void(0)"
                                                                class="select_datatable text-left"
                                                                class="select_datatable text-left" my-column="6"><input
                                                    type="checkbox" class="pull-left"/> &nbsp; <?php echo __('UUID'); ?>
                                        </a></li>
                                    <li style="width: 100%;"><a href="javascript:void(0)"
                                                                class="select_datatable text-left"
                                                                class="select_datatable text-left" my-column="7"><input
                                                    type="checkbox" class="pull-left"/>
                                            &nbsp; <?php echo __('host_object_id'); ?></a></li>
                                    <li style="width: 100%;"><a href="javascript:void(0)"
                                                                class="select_datatable text-left"
                                                                class="select_datatable text-left" my-column="8"><input
                                                    type="checkbox" class="pull-left"/>
                                            &nbsp; <?php echo __('Output'); ?></a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="jarviswidget-ctrls" role="menu">
                            <!--		<a data-placement="bottom" title="" rel="tooltip" class="button-icon jarviswidget-fullscreen-btn" href="javascript:void(0);" data-original-title="Fullscreen"><i class="fa fa-resize-full"></i></a> -->
                        </div>
                        <span class="widget-icon"> <i class="fa fa-gear"></i> </span>
                        <h2>Services </h2>

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
                            <?php echo $this->ListFilter->renderFilterbox($filters, [], '<i class="fa fa-search"></i> '.__('search'), false, false); ?>
                            <table id="service_list" class="table table-striped table-bordered smart-form" style="">
                                <thead>
                                <tr>
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <th class="select_datatable no-sort"><?php echo $this->Utils->getDirection($order, 'ServiceStatus.current_state');
                                        echo $this->Paginator->sort('ServiceStatus.current_state', 'Servicestatus'); ?></th>
                                    <th class="no-sort text-center"><i class="fa fa-area-chart fa-lg"></i></th>
                                    <th class="no-sort text-center"><i class="fa fa-arrow-down fa-lg"></i></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'name');
                                        echo $this->Paginator->sort('name', 'Servicename'); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Service.address');
                                        echo $this->Paginator->sort('address', 'IP-Address'); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Service.display_name');
                                        echo $this->Paginator->sort('display_name', 'UUID'); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Service.service_object_id');
                                        echo $this->Paginator->sort('service_object_id', 'service_object_id'); ?></th>
                                    <th class="no-sort"><?php echo __('Output'); ?></th>
                                    <th class="no-sort text-center"><i class="fa fa-gear fa-lg"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($services as $service): ?>
                                    <?php
                                    //debug($service['Service']);
                                    //debug($service); ?>
                                    <tr>
                                        <td class="text-center"><?php echo $this->Status->humanServiceStatus($service['Service']['uuid'], '/service/browser/'.$service['Service']['id'])['html_icon']; ?></td>
                                        <td class="text-center"><i class="fa fa-area-chart fa-lg "></i></td>
                                        <td class="text-center"><i class="fa fa-arrow-down  fa-lg"></i></td>
                                        <td>
                                            <a href="/services/browser/<?php echo $service['Service']['id']; ?>"><?php echo $service['Service']['name']; ?></a>
                                        </td>
                                        <td><?php echo $service['Service']['address']; ?></td>
                                        <td><?php echo $service['Service']['uuid']; ?></td>
                                        <td><?php echo $service['Service']['id']; ?></td>
                                        <td><?php echo $this->Status->serviceOutput($service['Service']['uuid']); ?></td>
                                        <td class="text-center"><a
                                                    href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $service['Service']['id']; ?>"
                                                    data-original-title="<?php echo __('edit'); ?>"
                                                    data-placement="left" rel="tooltip" data-container="body"><i
                                                        id="list_edit" class="fa fa-gear fa-lg txt-color-teal"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php if (empty($services)): ?>
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
    <?php
else:

endif;