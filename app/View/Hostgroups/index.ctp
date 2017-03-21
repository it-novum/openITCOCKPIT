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
            <i class="fa fa-sitemap fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Hostgroups'); ?>
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
                            echo $this->Html->link(__('New'), '/'.$this->params['controller'].'/add', ['class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus']);
                            echo " "; //Fix HTML
                        endif;
                        echo $this->Html->link(__('Search'), 'javascript:', ['class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search']);
                        if ($isFilter):
                            echo " "; //Fix HTML
                            echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']);
                        endif;
                        ?>
                    </div>
                    <div class="widget-toolbar" role="menu">
                        <a href="javascript:void(0);" class="dropdown-toggle selector" data-toggle="dropdown"><i
                                    class="fa fa-lg fa-table"></i></a>
                        <ul class="dropdown-menu arrow-box-up-right pull-right">
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="1"><input
                                            type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Hostgroup name'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="2"><input
                                            type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Description'); ?>
                                </a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="3"><input
                                            type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Hosts'); ?></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-sitemap"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Hostgroups'); ?></h2>
                    <?php if (!empty($all_hostgroups) && $this->Acl->hasPermission('extended')): ?>
                        <ul class="nav nav-tabs pull-right" id="widget-tab-1">
                            <li>
                                <a href="/hostgroups/extended"><i class="fa fa-plus-square"></i>
                                    <span class="hidden-mobile hidden-tablet"><?php echo __('Extended overview'); ?></span></a>
                            </li>
                        </ul>
                    <?php endif; ?>
                </header>
                <div>
                    <div class="widget-body no-padding">
                        <?php echo $this->ListFilter->renderFilterbox($filters, [], '<i class="fa fa-search"></i> '.__('search'), false, false); ?>
                        <div class="mobile_table">
                            <table id="hostgroup_list" class="table table-striped table-bordered smart-form" style="">
                                <thead>
                                <tr>
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <th class="no-sort" style="width: 15px;"><i class="fa fa-check-square-o fa-lg"></i>
                                    </th>
                                    <th class="select_datatable no-sort"><?php echo $this->Utils->getDirection($order, 'Container.name');
                                        echo $this->Paginator->sort('Container.name', 'Hostgroup name'); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Hostgroup.description');
                                        echo $this->Paginator->sort('Hostgroup.description', 'Description'); ?></th>
                                    <th class="no-sort"><?php echo __('Hosts'); ?></th>
                                    <th class="no-sort"><?php echo __('Host templates'); ?></th>
                                    <th class="no-sort"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($all_hostgroups as $hostgroup): ?>
                                    <?php $allowEdit = $this->Acl->isWritableContainer($hostgroup['Container']['parent_id']); ?>
                                    <tr>
                                        <td class="text-center" style="width: 15px;">
                                            <?php if ($this->Acl->hasPermission('edit') && $allowEdit): ?>
                                                <input class="massChange" type="checkbox"
                                                       name="hostgroup[<?php echo $hostgroup['Hostgroup']['id']; ?>]"
                                                       hostgroupname="<?php echo h($hostgroup['Container']['name']); ?>"
                                                       value="<?php echo $hostgroup['Hostgroup']['id']; ?>"/>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $hostgroup['Container']['name']; ?></td>
                                        <td><?php echo $hostgroup['Hostgroup']['description']; ?></td>
                                        <td>
                                            <ul class="list-unstyled">
                                                <?php
                                                foreach ($hostgroup['Host'] as $host):
                                                    if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                                                        <li>
                                                            <a href="<?php echo Router::url(['controller' => 'hosts', 'action' => 'edit', $host['id']]); ?>"><?php echo h($host['name']); ?></a>
                                                        </li>
                                                    <?php else: ?>
                                                        <li><?php echo h($host['name']); ?></li>
                                                    <?php endif;
                                                endforeach;
                                                ?>
                                            </ul>
                                        </td>
                                        <td>
                                            <ul class="list-unstyled">
                                                <?php
                                                foreach ($hostgroup['Hosttemplate'] as $hosttemplate):
                                                    if ($this->Acl->hasPermission('edit', 'hosttemplates')): ?>
                                                        <li>
                                                            <a href="<?php echo Router::url(['controller' => 'hosttemplates', 'action' => 'edit', $hosttemplate['id']]); ?>"><?php echo h($hosttemplate['name']); ?></a>
                                                        </li>
                                                    <?php else: ?>
                                                        <li><?php echo h($hosttemplate['name']); ?></li>
                                                    <?php endif;
                                                endforeach;
                                                ?>
                                            </ul>
                                        </td>
                                        <td>
                                            <center>
                                                <?php if ($this->Acl->hasPermission('edit') && $allowEdit): ?>
                                                    <a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $hostgroup['Hostgroup']['id']; ?>"
                                                       data-original-title="<?php echo __('edit'); ?>"><i id="list_edit"
                                                                                                          class="fa fa-gear fa-lg txt-color-teal"></i></a>
                                                <?php endif; ?>
                                            </center>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php echo $this->element('hostgroup_mass_changes'); ?>

                        <div style="padding: 5px 10px;">
                            <?php /*
                            <div class="row">
                                <div class="col-xs-12 col-md-3">
                                    <a href="<?php echo Router::url(['controller' => 'hostgroups', 'action' => 'listToPdf/.pdf']); ?>" id="listAsPDF" class="pointer" style="text-decoration: none; color:#333;"><i class="fa fa-lg fa-file-pdf-o"></i> <?php echo __('List as PDF') ?></a>
                                </div>
                            </div>
                            */ ?>
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
    </div>
</section>
