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
            <i class="fa fa-cogs fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Service Groups'); ?>
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
                            echo " "; //Fiox HTML
                        endif;
                        echo $this->Html->link(__('Filter'), 'javascript:', ['class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-filter']);
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
                                                        my-column="1"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Service Group Name'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="2"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Description'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="3"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Services'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="4"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Service Templates'); ?></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-cogs"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Service Groups'); ?></h2>
                </header>
                <div>
                    <div class="widget-body no-padding">
                        <?php echo $this->ListFilter->renderFilterbox($filters, [], '<i class="fa fa-filter"></i> '.__('Filter'), false, false); ?>
                        <div class="mobile_table">
                            <table id="servicegroup_list" class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <th class="no-sort" style="width: 15px;"><i class="fa fa-check-square-o fa-lg"></i>
                                    </th>
                                    <th class="select_datatable no-sort"><?php echo $this->Utils->getDirection($order, 'Container.name');
                                        echo $this->Paginator->sort('Container.name', __('Servicegroup name')); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Servicegroup.description');
                                        echo $this->Paginator->sort('Servicegroup.description', __('Description')); ?></th>
                                    <th class="no-sort"><?php echo __('Services'); ?></th>
                                    <th class="no-sort"><?php echo __('Service Templates'); ?></th>
                                    <th class="no-sort"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($all_servicegroups as $servicegroup): ?>
                                    <?php $allowEdit = $this->Acl->isWritableContainer($servicegroup['Container']['parent_id']); ?>
                                    <tr>
                                        <td class="text-center">
                                            <?php if ($this->Acl->hasPermission('edit') && $allowEdit): ?>
                                                <input class="massChange" type="checkbox"
                                                       name="servicegroup[<?php echo $servicegroup['Servicegroup']['id']; ?>]"
                                                       servicegroupname="<?php echo h($servicegroup['Container']['name']); ?>"
                                                       value="<?php echo $servicegroup['Servicegroup']['id']; ?>"/>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $servicegroup['Container']['name']; ?></td>
                                        <td><?php echo $servicegroup['Servicegroup']['description']; ?></td>
                                        <td>
                                            <ul class="list-unstyled">
                                                <?php
                                                foreach ($servicegroup['Service'] as $service):
                                                    echo '<li>';
                                                    $serviceName = $service['name'];
                                                    if ($serviceName === null || $serviceName === ''):
                                                        $serviceName = $service['Servicetemplate']['name'];
                                                    endif;
                                                    if ($this->Acl->hasPermission('edit', 'hosts')): ?>
                                                        <a href="<?php echo Router::url(['controller' => 'hosts', 'action' => 'edit', $service['Host']['id']]); ?>"><?php echo h($service['Host']['name']); ?></a>
                                                        <?php
                                                    else:
                                                        echo h($service['Host']['name']);
                                                    endif;
                                                    echo "/";
                                                    if ($this->Acl->hasPermission('edit', 'services')): ?>
                                                        <a href="<?php echo Router::url(['controller' => 'services', 'action' => 'edit', $service['id']]); ?>"><?php echo h($serviceName); ?></a>
                                                        <?php
                                                    else:
                                                        echo h($serviceName);
                                                    endif;
                                                    echo '</li>';
                                                endforeach;
                                                ?>
                                            </ul>
                                        </td>
                                        <td>
                                            <ul class="list-unstyled">
                                                <?php
                                                foreach ($servicegroup['Servicetemplate'] as $servicetemplate):
                                                    echo '<li>';
                                                    if ($this->Acl->hasPermission('edit', 'servicetemplates')): ?>
                                                        <a href="<?php echo Router::url(['controller' => 'servicetemplates', 'action' => 'edit', $servicetemplate['id']]); ?>"><?php echo h($servicetemplate['template_name'].' ('.$servicetemplate['name'].')'); ?></a>
                                                        <?php
                                                    else:
                                                        echo h($servicetemplate['name']);
                                                    endif;
                                                    echo '</li>';
                                                endforeach;
                                                ?>
                                            </ul>
                                        </td>
                                        <td>
                                            <center>
                                                <?php if ($this->Acl->hasPermission('edit') && $allowEdit): ?>
                                                    <a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $servicegroup['Servicegroup']['id']; ?>"
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

                        <?php echo $this->element('servicegroup_mass_changes'); ?>

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
    </div>
</section>
