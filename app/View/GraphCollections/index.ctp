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
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-area-chart fa-fw"></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Graph Collections'); ?>
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
                        if ($this->Acl->hasPermission('add', 'graphcollections')):
                            echo $this->Html->link(__('New'), Router::url(['action' => 'add']), ['class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus']);
                            echo " "; //Need a space for nice buttons
                        endif;
                        ?>
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-area-chart"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Graph Collections'); ?></h2>
                </header>
                <div>
                    <div class="widget-body no-padding">
                        <div class="mobile_table">
                            <table id="graphcolections_list" class="table table-striped table-hover table-bordered smart-form"
                                   style="">
                                <thead>
                                <tr>
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <th class="no-sort"></th>
                                    <th class="select_datatable no-sort">
                                        <?php
                                        echo $this->Utils->getDirection($order, 'GraphCollection.name');
                                        echo $this->Paginator->sort('GraphCollection.name', __('Name'));
                                        ?>
                                    </th>
                                    <th>
                                        <?php
                                        echo $this->Utils->getDirection($order, 'GraphCollection.relative_time');
                                        echo $this->Paginator->sort('GraphCollection.description', __('Description'));
                                        ?>
                                    </th>
                                    <th class="no-sort text-center" style="width:52px;"><i class="fa fa-gear fa-lg"></i>
                                    </th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php foreach ($all_collections as $collection): ?>
                                    <tr>
                                        <td class="text-center width-5">
                                            <input type="checkbox" class="massChange"
                                                   data-delete-display-text="<?php echo $collection['GraphCollection']['name'].' (ID:'.$collection['GraphCollection']['id'].')'; ?>"
                                                   value="<?php echo $collection['GraphCollection']['id']; ?>">
                                        </td>
                                        <td>
                                            <?php
                                            if ($this->Acl->hasPermission('display', 'graphcollections')):
                                                echo $this->Html->link($collection['GraphCollection']['name'], [
                                                    'action' => 'display',
                                                    $collection['GraphCollection']['id'],
                                                ]);
                                            else:
                                                echo h($collection['GraphCollection']['name']);
                                            endif;
                                            ?>
                                        </td>
                                        <!--										<td>-->
                                        <?php //echo $template['GraphCollection']['relative_time']; ?><!--</td>-->
                                        <td>
                                            <?php echo h($collection['GraphCollection']['description']); ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <?php if ($this->Acl->hasPermission('edit', 'graphcollections')): ?>
                                                    <a href="<?php echo Router::url(['action' => 'edit', $collection['GraphCollection']['id']]); ?>"
                                                       class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
                                                <?php else: ?>
                                                    <a href="javascript:void(0);" class="btn btn-default">&nbsp;<i
                                                                class="fa fa-cog"></i>&nbsp;</a>
                                                <?php endif; ?>
                                                <a href="javascript:void(0);" data-toggle="dropdown"
                                                   class="btn btn-default dropdown-toggle"><span
                                                            class="caret"></span></a>
                                                <ul class="dropdown-menu">
                                                    <?php if ($this->Acl->hasPermission('edit', 'graphcollections')): ?>
                                                        <li>
                                                            <a href="<?php echo Router::url(['action' => 'edit', $collection['GraphCollection']['id']]); ?>"><i
                                                                        class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('display', 'graphcollections')): ?>
                                                        <li>
                                                            <a href="<?php echo Router::url(['action' => 'display', $collection['GraphCollection']['id']]); ?>"><i
                                                                        class="fa fa-eye"></i> <?php echo __('View'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('mass_delete', 'graphcollections')): ?>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> '.__('Delete'), ['controller' => 'GraphCollections', 'action' => 'mass_delete', $collection['GraphCollection']['id']], ['class' => 'txt-color-red', 'escape' => false]); ?>
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
                            <!--<div class="col-xs-12 col-md-2"><a href="javascript:void(0);" id="copyAll" style="text-decoration: none; color:#333;"><i class="fa fa-lg fa-files-o"></i> <?php echo __('Copy'); ?></a></div>-->
                            <div class="col-xs-12 col-md-2">
                                <?php if ($this->Acl->hasPermission('mass_delete', 'graphcollections')): ?>
                                    <a href="javascript:void(0);" id="deleteAll" class="txt-color-red"
                                       style="text-decoration: none;"> <i
                                                class="fa fa-lg fa-trash-o"></i> <?php echo __('Delete'); ?></a>
                                <?php endif; ?>
                            </div>
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
