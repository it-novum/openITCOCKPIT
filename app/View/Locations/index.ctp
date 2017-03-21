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
                <i class="fa fa-location-arrow fa-fw "></i>
                <?php echo __('Monitoring'); ?>
                <span>>
                    <?php echo __('Locations'); ?>
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

                        <div class="jarviswidget-ctrls" role="menu">
                        </div>
                        <span class="widget-icon hidden-mobile"> <i class="fa fa-location-arrow"></i> </span>
                        <h2 class="hidden-mobile"><?php echo __('Locations'); ?> </h2>

                    </header>

                    <div>

                        <div class="widget-body no-padding">
                            <?php echo $this->ListFilter->renderFilterbox($filters, [], '<i class="fa fa-search"></i> '.__('search'), false, false); ?>
                            <div class="mobile_table">
                                <table id="location_list" class="table table-striped table-bordered smart-form"
                                       style="">
                                    <thead>
                                    <tr>
                                        <?php $order = $this->Paginator->param('order'); ?>
                                        <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Container.name');
                                            echo $this->Paginator->sort('name', __('Name')); ?></th>
                                        <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Location.description');
                                            echo $this->Paginator->sort('description', __('Description')); ?></th>
                                        <th class="no-sort"><?php echo __('Container'); ?></th>
                                        <th class="no-sort text-center" style="width:52px;"><i
                                                    class="fa fa-gear fa-lg"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($all_locations as $location): ?>
                                        <?php $allowEdit = $this->Acl->isWritableContainer($location['Location']['container_id']); ?>
                                        <tr>
                                            <td><?php echo $location['Container']['name']; ?></td>
                                            <td><?php echo $location['Location']['description']; ?></td>
                                            <td><?php if ($location['Container']['parent_id'] == 1): echo __('/root/');
                                                else: ?> <?php echo $container[$location['Container']['parent_id']]; ?><?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <?php if ($this->Acl->hasPermission('edit') && $allowEdit): ?>
                                                        <a href="<?php echo Router::url(['action' => 'edit', $location['Location']['id']]); ?>"
                                                           class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                        </a>
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
                                                                <a href="<?php echo Router::url(['action' => 'edit', $location['Location']['id']]); ?>"><i
                                                                            class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <?php if ($this->Acl->hasPermission('delete') && $allowEdit): ?>
                                                            <li class="divider"></li>
                                                            <li>
                                                                <a href="#" data-toggle="modal"
                                                                   data-target="#delete_location_<?php echo $location['Location']['id']; ?>"
                                                                   class="txt-color-red"><i
                                                                            class="fa fa-trash-o"></i> <?php echo __('Delete'); ?>
                                                                </a>
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
                            <?php if (empty($all_locations)): ?>
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
                    </div>
                </div>
        </div>
    </section>

<?php foreach ($all_locations as $location): ?>
    <div class="modal fade" id="delete_location_<?php echo $location['Location']['id']; ?>" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title"
                        id="myModalLabel"><?php echo __('Do you really want to delete this location and all related objects'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        Make sure that you really do not use this location</br>
                        and any related objects like: </br>
                        <ul>
                            <li>Hosts</li>
                            <li>Services</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php echo $this->Form->postLink(__('Delete'), ['controller' => 'locations', 'action' => 'delete', $location['Location']['id']], ['class' => 'btn btn-danger', 'data-dismiss' => "modal"]); ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?php echo __('Cancel'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>