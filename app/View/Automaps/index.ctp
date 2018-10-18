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
                <i class="fa fa-magic fa-fw"></i>
                <?php echo __('Monitoring'); ?>
                <span>>
                    <?php echo __('Automaps'); ?>
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
                            <?php if ($this->Acl->hasPermission('add')): ?>
                                <?php echo $this->Html->link(__('New'), '/' . $this->params['controller'] . '/add', ['class' => 'btn btn-xs btn-success', 'icon' => 'fa fa-plus']); ?>
                            <?php endif; ?>
                            <?php echo $this->Html->link(__('Filter'), 'javascript:', ['class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-filter']); ?>
                            <?php
                            if ($isFilter):
                                echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']);
                            endif;
                            ?>
                        </div>
                        <div class="widget-toolbar" role="menu">
                            <div class="clearfix"></div>
                        </div>
                        <div class="jarviswidget-ctrls" role="menu">
                        </div>
                        <span class="widget-icon hidden-mobile"> <i class="fa fa-magic"></i> </span>
                        <h2 class="hidden-mobile"><?php echo __('Automaps'); ?> </h2>

                    </header>
                    <div>

                        <!-- widget content -->
                        <div class="widget-body no-padding">
                            <?php
                            $options = ['avoid_cut' => true];
                            echo $this->ListFilter->renderFilterbox($filters, $options, '<i class="fa fa-filter"></i> ' . __('Filter'), false, false);
                            ?>
                            <div class="mobile_table">
                                <table id="automaps_list" class="table table-striped table-hover table-bordered smart-form"
                                       style="">
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <thead>
                                    <tr>
                                        <th class="no-sort">
                                            <?php
                                            echo $this->Utils->getDirection($order, 'Automap.name');
                                            echo $this->Paginator->sort('Automap.name', __('Name'));
                                            ?>
                                        </th>
                                        <th class="no-sort">
                                            <?php
                                            echo $this->Utils->getDirection($order, 'Automap.description');
                                            echo $this->Paginator->sort('Automap.description', __('Description'));
                                            ?>
                                        </th>
                                        <th class="no-sort"><?php echo __('Host RegEx'); ?></th>
                                        <th class="no-sort"><?php echo __('Service RegEx'); ?></th>
                                        <th class="no-sort"><?php echo __('Options'); ?></th>
                                        <th class="no-sort"><?php echo __('Recursive'); ?></th>
                                        <th class="no-sort text-center"><i class="fa fa-gear fa-lg"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($all_automaps as $automap): ?>
                                        <?php
                                        //Better performance, than run all the Hash::extracts if not necessary
                                        $hasEditPermission = false;
                                        if ($hasRootPrivileges === true):
                                            $hasEditPermission = true;
                                        else:
                                            if ($this->Acl->isWritableContainer($automap['Automap']['container_id'])):
                                                $hasEditPermission = true;
                                            endif;
                                        endif;
                                        ?>
                                        <tr>
                                            <td>
                                                <a href="/<?php echo $this->params['controller']; ?>/view/<?php echo $automap['Automap']['id']; ?>"><?php echo h($automap['Automap']['name']); ?></a>
                                            </td>
                                            <td><?php echo h($automap['Automap']['description']); ?></td>
                                            <td><?php echo h($automap['Automap']['host_regex']); ?></td>
                                            <td><?php echo h($automap['Automap']['service_regex']); ?></td>
                                            <td><?php echo __viewAutomapOptions($automap); ?></td>
                                            <td>
                                                <?php if ($automap['Automap']['recursive'] == 1): ?>
                                                    <span class="label label-success label-forced"><?php echo __('Yes'); ?></span>
                                                <?php else: ?>
                                                    <span class="label label-danger label-forced"><?php echo __('No'); ?></span>
                                                <?php endif; ?>
                                            </td>


                                            <td>
                                                <div class="btn-group">
                                                    <?php if ($this->Acl->hasPermission('edit') && $hasEditPermission): ?>
                                                        <a href="<?php echo Router::url([
                                                            'action' => 'edit',
                                                            $automap['Automap']['id']
                                                        ]); ?>"
                                                           class="btn btn-default btn-xs">
                                                            &nbsp;<i class="fa fa-cog"></i>&nbsp;
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="javascript:void(0);" class="btn btn-default btn-xs">&nbsp;
                                                            <i class="fa fa-cog"></i>&nbsp;
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="javascript:void(0);" data-toggle="dropdown"
                                                       class="btn btn-default btn-xs dropdown-toggle">
                                                        <span class="caret"></span>
                                                    </a>
                                                    <ul class="dropdown-menu pull-right">
                                                        <?php if ($this->Acl->hasPermission('edit') && $hasEditPermission): ?>
                                                            <li>
                                                                <a href="<?php echo Router::url([
                                                                    'action' => 'edit',
                                                                    $automap['Automap']['id']
                                                                ]); ?> ">
                                                                    <i class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <?php if ($this->Acl->hasPermission('view')): ?>
                                                            <li>
                                                                <a href="<?php echo Router::url([
                                                                    'action' => 'view',
                                                                    $automap['Automap']['id']
                                                                ]); ?>">
                                                                    <i class="fa fa-eye"></i>
                                                                    <?php echo __('View'); ?>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <?php if ($this->Acl->hasPermission('delete') && $hasEditPermission): ?>
                                                            <li class="divider"></li>
                                                            <li>
                                                                <?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> ' . __('Delete'), [
                                                                    'controller' => 'automaps',
                                                                    'action'     => 'delete',
                                                                    $automap['Automap']['id']
                                                                ], [
                                                                    'class'  => 'txt-color-red',
                                                                    'escape' => false
                                                                ],
                                                                    __('Are you sure you want to delete this automap?'));
                                                                ?>
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
                            <?php if (empty($all_automaps)): ?>
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
<?php
/**
 * This is a view function and ONLY CALLED IN THIS VIEW!
 *
 * @param array $automap from find('first')
 *
 * @return string `<i />` HTML object with icons for each options
 * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
 * @since  3.0.1
 */
function __viewAutomapOptions($automap = []) {
    $options = ['show_ok' => 'ok', 'show_warning' => 'warning', 'show_critical' => 'critical', 'show_unknown' => 'unknown'];
    $class = 'fa fa-square ';
    $html = [];
    foreach ($options as $option => $color) {
        if (isset($automap['Automap'][$option]) && $automap['Automap'][$option] == 1) {
            $html [] = '<i class="' . $class . $color . '"></i>';
        }
    }

    if ($automap['Automap']['show_acknowledged'] === true) {
        $html [] = '<i class="fa fa-user"></i>';
    }

    if ($automap['Automap']['show_downtime'] === true) {
        $html [] = '<i class="fa fa-power-off"></i>';
    }

    return implode('&nbsp;', $html);
}
