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
            <i class="fa fa-terminal fa-fw "></i>
            Nagios
            <span>> 
				Commands
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
                        <?php echo $this->Html->link(__('New'), '/'.$this->params['plugin'].'/'.$this->params['controller'].'/add', ['class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus']); ?>
                        <?php echo $this->Html->link(__('Search'), 'javascript:', ['class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-search']); ?>
                        <?php
                        if ($isFilter):
                            echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']);
                        endif;
                        ?>
                    </div>
                    <span class="widget-icon"> <i class="fa fa-terminal"></i> </span>
                    <h2><?php echo __('commands'); ?></h2>
                    <ul class="nav nav-tabs pull-left padding-left-20" id="widget-tab-1">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab"> <i class="fa fa-lg fa-code"></i> <span
                                        class="hidden-mobile hidden-tablet"> <?php echo __('Commands'); ?></span> </a>
                        </li>
                        <li class="">
                            <a href="#tab2" data-toggle="tab"> <i class="fa fa-lg fa-envelope-o"></i> <span
                                        class="hidden-mobile hidden-tablet"> <?php echo __('Notifications'); ?> </span></a>
                        </li>
                        <li class="">
                            <a href="#tab3" data-toggle="tab"> <i class="fa fa-lg fa-code-fork"></i> <span
                                        class="hidden-mobile hidden-tablet"> <?php echo __('Event handler'); ?> </span></a>
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
                        <?php echo $this->ListFilter->renderFilterbox($filters, [], '<i class="fa fa-search"></i> '.__('search'), false, false); ?>
                        <div class="tab-content">
                            <!-- <form action="/nagios_module/commands/edit/" id="multiEditForm" method="post"> -->
                            <div id="tab1" class="tab-pane fade active in">
                                <table id="datatable_fixed_column"
                                       class="table table-striped table-hover table-bordered smart-form">
                                    <thead>
                                    <tr>
                                        <?php $order = $this->Paginator->param('order'); ?>
                                        <th colspan="3"><?php echo $this->Utils->getDirection($order, 'Objects.name1');
                                            echo $this->Paginator->sort('Objects.name1', 'Commandname'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($all_commands['check'] as $command):
                                        ?>
                                        <tr>
                                            <td class="text-center"><input class="multiselect" type="checkbox"
                                                                           name="command[<?php echo $command['Command']['object_id']; ?>]"/>
                                            </td>
                                            <td><?php echo $command['Objects']['name1']; ?></td>
                                            <td class="text-center"><a
                                                        href="/<?php echo $this->params['plugin']; ?>/<?php echo $this->params['controller']; ?>/edit/<?php echo $command['Command']['object_id']; ?>"
                                                        data-original-title="<?php echo __('edit'); ?>"
                                                        data-placement="left" rel="tooltip" data-container="body"><i
                                                            id="list_edit" class="fa fa-gear fa-lg txt-color-teal"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php if (empty($all_commands['check'])): ?>
                                    <div class="noMatch">
                                        <center>
                                            <span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
                                        </center>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div id="tab2" class="tab-pane fade">
                                <table id="datatable_fixed_column"
                                       class="table table-striped table-hover table-bordered smart-form">
                                    <thead>
                                    <tr>
                                        <?php $order = $this->Paginator->param('order'); ?>
                                        <th colspan="2"><?php echo $this->Utils->getDirection($order, 'Objects.name1');
                                            echo $this->Paginator->sort('Objects.name1', 'Commandname'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($all_commands['notify'] as $command):
                                        ?>
                                        <tr>
                                            <td><?php echo $command['Objects']['name1']; ?></td>
                                            <td class="text-center"><a
                                                        href="/<?php echo $this->params['plugin']; ?>/<?php echo $this->params['controller']; ?>/edit/<?php echo $command['Command']['object_id']; ?>"
                                                        data-original-title="<?php echo __('edit'); ?>"
                                                        data-placement="left" rel="tooltip" data-container="body"><i
                                                            id="list_edit" class="fa fa-gear fa-lg txt-color-teal"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php if (empty($all_commands['notify'])): ?>
                                    <div class="noMatch">
                                        <center>
                                            <span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
                                        </center>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div id="tab3" class="tab-pane fade">
                                <table id="datatable_fixed_column"
                                       class="table table-striped table-hover table-bordered smart-form">
                                    <thead>
                                    <tr>
                                        <?php $order = $this->Paginator->param('order'); ?>
                                        <th colspan="2"><?php echo $this->Utils->getDirection($order, 'Objects.name1');
                                            echo $this->Paginator->sort('Objects.name1', 'Commandname'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($all_commands['handle'] as $command):
                                        ?>
                                        <tr>
                                            <td><?php echo $command['Objects']['name1']; ?></td>
                                            <td class="text-center"><a
                                                        href="/<?php echo $this->params['plugin']; ?>/<?php echo $this->params['controller']; ?>/edit/<?php echo $command['Command']['object_id']; ?>"
                                                        data-original-title="<?php echo __('edit'); ?>"
                                                        data-placement="left" rel="tooltip" data-container="body"><i
                                                            id="list_edit" class="fa fa-gear fa-lg txt-color-teal"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <?php if (empty($all_commands['handle'])): ?>
                                    <div class="noMatch">
                                        <center>
                                            <span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
                                        </center>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!-- </form> -->
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
                    <!-- end widget content -->

                </div>
                <!-- end widget div -->

            </div>
            <!-- end widget -->
    </div>

    <!-- end row -->

</section>
<!-- end widget grid -->


<div class="well listTools">
    <div class="row">
        <div class="pointer col-sm-12 col-lg-1 txt-color-blueDark text-center"><span
                    data-original-title="Selected elements" data-placement="top" rel="tooltip" class="selectedElements">(0)</span>
        </div>
        <div class="pointer col-sm-12 col-lg-2 selectAll"><i class="fa fa-lg fa-list-ul"></i> Select all</div>
        <div class="pointer col-sm-12 col-lg-2 undoSelection"><i class="fa fa-lg  fa-rotate-left"></i> Undo selection
        </div>
        <!-- <div class="pointer col-sm-12 col-lg-2 txt-color-green multiEdit"><i class="fa fa-lg fa-wrench"></i> Edit</div> -->
        <div class="pointer col-sm-12 col-lg-2 "><span class="txt-color-red"><i class="fa fa-lg fa-trash-o"></i> Delete</span>
        </div>
    </div>
</div>