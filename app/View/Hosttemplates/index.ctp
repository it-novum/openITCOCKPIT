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
            <i class="fa fa-pencil-square-o fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Host Templates'); ?>
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
                            echo " "; // Fix HTML
                        endif;
                        echo $this->Html->link(__('Filter'), 'javascript:', ['class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-filter']);
                        if ($isFilter):
                            echo " "; //Fix HTML
                            echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']);
                        endif;
                        ?>
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-pencil-square-o"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Hoststemplates'); ?> </h2>

                </header>
                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body no-padding">
                        <?php echo $this->ListFilter->renderFilterbox($filters, [], '<i class="fa fa-filter"></i> ' . __('Filter'), false, false); ?>
                        <div class="mobile_table">
                            <table id="host_list" class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <th class="no-sort text-center"><i class="fa fa-check-square-o fa-lg"></i></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'name');
                                        echo $this->Paginator->sort('name', 'Name'); ?></th>
                                    <th class="no-sort text-center editItemWidth" style="width:52px;"><i
                                                class="fa fa-gear fa-lg"></i></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($all_hosttemplates as $hosttemplate): ?>
                                    <?php $allowEdit = $this->Acl->isWritableContainer($hosttemplate['Hosttemplate']['container_id']); ?>
                                    <tr>
                                        <td class="text-center width-5">
                                            <?php if ($allowEdit): ?>
                                                <input type="checkbox" class="massChange"
                                                       hostname="<?php echo h($hosttemplate['Hosttemplate']['name']); ?>"
                                                       value="<?php echo $hosttemplate['Hosttemplate']['id']; ?>"
                                                       uuid="<?php echo $hosttemplate['Hosttemplate']['uuid']; ?>">
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $hosttemplate['Hosttemplate']['name']; ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <?php if ($this->Acl->hasPermission('edit') && $allowEdit): ?>
                                                    <a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $hosttemplate['Hosttemplate']['id']; ?>"
                                                       class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
                                                <?php else: ?>
                                                    <a href="javascript:void(0);" class="btn btn-default">&nbsp;<i
                                                                class="fa fa-cog"></i>&nbsp;</a>
                                                <?php endif; ?>
                                                <a href="javascript:void(0);" data-toggle="dropdown"
                                                   class="btn btn-default dropdown-toggle"><span
                                                            class="caret"></span></a>
                                                <ul class="dropdown-menu pull-right">
                                                    <?php if ($this->Acl->hasPermission('edit') && $allowEdit): ?>
                                                        <li>
                                                            <a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $hosttemplate['Hosttemplate']['id']; ?>"><i
                                                                        class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('usedBy')): ?>
                                                        <li>
                                                            <a href="/<?php echo $this->params['controller']; ?>/usedBy/<?php echo $hosttemplate['Hosttemplate']['id']; ?>"><i
                                                                        class="fa fa-reply-all fa-flip-horizontal"></i> <?php echo __('Used by'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('view', 'documentations')): ?>
                                                        <li>
                                                            <a href="<?php echo Router::url(['controller' => 'documentations', 'action' => 'view', $hosttemplate['Hosttemplate']['uuid'], 'hosttemplate']); ?>"><i
                                                                        class="fa fa-book"></i> <?php echo __('Documentation'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php echo $this->AdditionalLinks->renderAsListItems($additionalLinksList, $hosttemplate['Hosttemplate']['id']); ?>
                                                    <?php if ($this->Acl->hasPermission('delete') && $allowEdit): ?>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> ' . __('Delete'), ['controller' => 'hosttemplates', 'action' => 'delete', $hosttemplate['Hosttemplate']['id']], ['class' => 'txt-color-red', 'escape' => false]); ?>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (empty($all_hosttemplates)): ?>
                            <div class="noMatch">
                                <center>
                                    <span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
                                </center>
                            </div>
                        <?php endif; ?>

                        <?php echo $this->element('hosttemplate_mass_changes'); ?>

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
