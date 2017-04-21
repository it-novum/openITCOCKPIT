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
            <i class="fa fa-retweet fa-fw "></i>
            <?php echo __('Map'); ?>
            <span>>
                <?php echo __('Rotations'); ?>
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
                        <?php echo $this->Html->link(__('New'), '/'.$this->params['plugin'].'/'.$this->params['controller'].'/add', ['class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus']); ?>
                        <?php echo $this->Html->link(__('Search'), 'javascript:', ['class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search']); ?>
                        <?php
                        if ($isFilter):
                            echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times'], true);
                        endif;
                        ?>
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-retweet"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Map rotations'); ?></h2>
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
                        <?php echo $this->ListFilter->renderFilterbox($filters, [], '<i class="fa fa-search"></i> '.__('search'), false, false, true); ?>
                        <!-- <form action="/nagios_module/commands/edit/" id="multiEditForm" method="post"> -->
                        <div class="mobile_table">
                            <table id="datatable_fixed_column" class="table table-striped table-hover table-bordered smart-form">
                                <thead>
                                <tr>
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <th><?php echo $this->Utils->getDirection($order, 'Rotation.name');
                                        echo $this->Paginator->sort('Rotation.name', __('Name')); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Rotation.interval');
                                        echo $this->Paginator->sort('Rotation.interval', __('Interval')); ?></th>
                                    <th class="no-sort text-center" style="width:52px;"><i class="fa fa-gear fa-lg"></i>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($all_rotations as $rotation):
                                    ?>
                                    <tr>
                                        <td><?php echo h($rotation['Rotation']['name']); ?></td>
                                        <td><?php echo h($rotation['Rotation']['interval']); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="/<?php echo $this->params['plugin'].'/'.$this->params['controller']; ?>/edit/<?php echo $rotation['Rotation']['id']; ?>"
                                                   class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
                                                <a href="javascript:void(0);" data-toggle="dropdown"
                                                   class="btn btn-default dropdown-toggle"><span
                                                            class="caret"></span></a>
                                                <ul class="dropdown-menu pull-right">
                                                    <li>
                                                        <a href="/<?php echo $this->params['plugin']; ?>/<?php echo $this->params['controller']; ?>/edit/<?php echo $rotation['Rotation']['id']; ?>"><i
                                                                    class="fa fa-cog"></i> <?php echo __('Edit'); ?></a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo Router::url([
                                                            'controller' => 'mapeditors',
                                                            'action'     => 'view',
                                                            'plugin'     => 'map_module',
                                                            'rotate'     => Hash::extract($rotation['Map'], '{n}.id'),
                                                            'interval'   => $rotation['Rotation']['interval'],
                                                        ]); ?>">
                                                            <i class="fa fa-eye"></i> <?php echo __('View'); ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo Router::url([
                                                            'controller' => 'mapeditors',
                                                            'action'     => 'view',
                                                            'plugin'     => 'map_module',
                                                            'rotate'     => Hash::extract($rotation['Map'], '{n}.id'),
                                                            'interval'   => $rotation['Rotation']['interval'],
                                                            'fullscreen' => 1,
                                                        ]); ?>">
                                                            <i class="glyphicon glyphicon-resize-full"></i> <?php echo __('View in fullscreen'); ?>
                                                        </a>
                                                    </li>
                                                    <li class="divider"></li>
                                                    <li>
                                                        <?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> '.__('Delete'), ['controller' => 'rotations', 'action' => 'delete', $rotation['Rotation']['id']], ['class' => 'txt-color-red', 'escape' => false]); ?>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (empty($all_rotations)): ?>
                            <div class="noMatch">
                                <center>
                                    <span class="txt-color-red italic"><?php echo __('search.noVal'); ?></span>
                                </center>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div style="padding: 5px 10px;">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="dataTables_info" style="line-height: 32px;"
                                     id="datatable_fixed_column_info"><?php echo $this->Paginator->counter(__('paginator.showing').' {:page} '.__('of').' {:pages}, '.__('paginator.overall').' {:count} '.__('entries')); ?></div>
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