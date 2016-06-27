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
<?php $this->Paginator->options(array('url' => $this->params['named'])); ?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-map-marker fa-fw "></i>
            <?php echo __('Nmap'); ?>
            <span>>
                <?php echo __('Hostscan'); ?>
			</span>
        </h1>
    </div>
</div>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false" >
                <header>
                    <div class="widget-toolbar" role="menu">

                    </div>
                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon"> <i class="fa fa-search"></i> </span>
                    <h2>Nmap Host Scan on <?php echo $result[0]->getHostname()." (".$result[0]->getAddress().")";?></h2>
                </header>
                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body no-padding">
                        <table id="contactgroup_list" class="table table-striped table-bordered smart-form" style="">
                            <thead>
                            <tr>
                                <?php $order = $this->Paginator->param('order'); ?>
                                <th class="no-sort"></th>
                                <th class="select_datatable no-sort"><?php echo __('Name'); ?></th>
                                <th class="select_datatable no-sort"><?php echo __('Port'); ?></th>
                                <th class="select_datatable no-sort"><?php echo __('Protocol'); ?></th>
                                <th class="no-sort"><?php echo __('Information'); ?></th>
                                <th class="select_datatable no-sort"><?php echo __('Version'); ?></th>
                                <th class="select_datatable no-sort"><?php echo __('Additional Info'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($result[0]->getServices() as $res): ?>
                                <tr>
                                    <td></td>
                                    <td><?php echo $res->name;?></td>
                                    <td><?php echo $res->port;?></td>
                                    <td><?php echo $res->protocol;?></td>
                                    <td><?php echo $res->product;?></td>
                                    <td><?php echo $res->version;?></td>
                                    <td><?php echo $res->extrainfo;?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>



                        <div style="padding: 5px 10px;">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="dataTables_info" style="line-height: 32px;" id="datatable_fixed_column_info"></div>
                                </div>
                                <div class="col-sm-6 text-right">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end widget div -->
            </div>
        </article>
    </div>
</section>