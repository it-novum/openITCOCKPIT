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
                <i class="fa fa-sitemap fa-fw"></i>
                <?php echo __('Monitoring'); ?>
                <span>>
                    <?php echo __('Hostdependencies'); ?>
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
                                echo " "; //Fix HTML if search is implemented
                            endif;
                            // TODO Implement Search
                            //echo $this->Html->link(__('Filter'), 'javascript:', array('class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-filter'));
                            if ($isFilter):
                                echo " "; //Fix HTML
                                echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']);
                            endif;
                            ?>
                        </div>

                        <div class="jarviswidget-ctrls" role="menu">
                        </div>
                        <span class="widget-icon hidden-mobile"> <i class="fa fa-sitemap"></i> </span>
                        <h2 class="hidden-mobile"><?php echo __('Hostdependencies'); ?> </h2>

                    </header>
                    <div>

                        <!-- widget content -->
                        <div class="widget-body no-padding">
                            <div class="mobile_table">
                                <table id="hostdependency_list"
                                       class="table table-striped table-hover table-bordered smart-form"
                                       style="">
                                    <thead>
                                    <tr>
                                        <th class="no-sort"><?php echo __('Hosts'); ?></th>
                                        <th class="no-sort"><?php echo __('Dependent hosts'); ?></th>
                                        <th class="no-sort"><?php echo __('Hostgroups'); ?></th>
                                        <th class="no-sort"><?php echo __('Dependent hostgroups'); ?></th>
                                        <th class="no-sort"><?php echo __('Dependency period'); ?></th>
                                        <th class="no-sort"><?php echo __('Inherits parent'); ?></th>
                                        <th class="no-sort"><?php echo __('Execution failure criteria'); ?></th>
                                        <th class="no-sort"><?php echo __('Notification failure criteria'); ?></th>
                                        <th class="no-sort text-center"><i class="fa fa-gear fa-lg"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($all_hostdependencies as $hostdependency): ?>
                                        <?php $allowEdit = $this->Acl->isWritableContainer($hostdependency['Hostdependency']['container_id']); ?>
                                        <tr>
                                            <td>
                                                <ul class="list-unstyled">
                                                    <?php
                                                    foreach (Hash::extract($hostdependency, 'HostdependencyHostMembership.{n}[dependent=0]') as $host):
                                                        echo '<li>';
                                                        if ($this->Acl->hasPermission('edit', 'hosts')):
                                                            echo $this->Html->link(
                                                                $host['Host']['name'],
                                                                [
                                                                    'controller' => 'hosts',
                                                                    'action'     => 'edit',
                                                                    $host['host_id'],
                                                                ],
                                                                ['escape' => true]
                                                            );
                                                        else:
                                                            echo h($host['Host']['name']);
                                                        endif;
                                                        echo ($host['Host']['disabled']) ?
                                                            ' <i class="fa fa-power-off text-danger" title="disabled" aria-hidden="true"></i>' : '';
                                                        echo '</li>';
                                                    endforeach;
                                                    ?>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul class="list-unstyled">
                                                    <?php
                                                    foreach (Hash::extract($hostdependency, 'HostdependencyHostMembership.{n}[dependent=1]') as $host_dependent):
                                                        echo '<li>';
                                                        if ($this->Acl->hasPermission('edit', 'hosts')):
                                                            echo $this->Html->link(
                                                                $host_dependent['Host']['name'],
                                                                [
                                                                    'controller' => 'hosts',
                                                                    'action'     => 'edit',
                                                                    $host_dependent['host_id'],
                                                                ],
                                                                ['escape' => true]
                                                            );
                                                        else:
                                                            echo h($host_dependent['Host']['name']);
                                                        endif;
                                                        echo ($host_dependent['Host']['disabled']) ?
                                                            ' <i class="fa fa-power-off text-danger" title="disabled" aria-hidden="true"></i>' : '';
                                                        echo '</li>';
                                                    endforeach;
                                                    ?>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul class="list-unstyled">
                                                    <?php
                                                    foreach (Hash::extract($hostdependency, 'HostdependencyHostgroupMembership.{n}[dependent=0]') as $hostgroup):
                                                        echo '<li>';
                                                        if ($this->Acl->hasPermission('edit', 'hostgroups')):
                                                            echo $this->Html->link(
                                                                $hostgroup['Hostgroup']['Container']['name'],
                                                                [
                                                                    'controller' => 'hostgroups',
                                                                    'action'     => 'edit',
                                                                    $hostgroup['hostgroup_id'],
                                                                ],
                                                                ['escape' => true]
                                                            );
                                                        else:
                                                            echo h($hostgroup['Hostgroup']['Container']['name']);
                                                        endif;
                                                        echo '</li>';
                                                    endforeach;
                                                    ?>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul class="list-unstyled">
                                                    <?php
                                                    foreach (Hash::extract($hostdependency, 'HostdependencyHostgroupMembership.{n}[dependent=1]') as $hostgroup_dependent):
                                                        echo '<li>';
                                                        if ($this->Acl->hasPermission('edit', 'hostgroups')):
                                                            echo $this->Html->link(
                                                                $hostgroup_dependent['Hostgroup']['Container']['name'],
                                                                [
                                                                    'controller' => 'hostgroups',
                                                                    'action'     => 'edit',
                                                                    $hostgroup_dependent['hostgroup_id'],
                                                                ],
                                                                ['escape' => true]
                                                            );
                                                        else:
                                                            echo h($hostgroup_dependent['Hostgroup']['Container']['name']);
                                                        endif;
                                                        echo '</li>';
                                                    endforeach;
                                                    ?>
                                                </ul>
                                            </td>
                                            <td><?php
                                                if ($this->Acl->hasPermission('edit', 'timeperiods')):
                                                    echo $this->Html->link($hostdependency['Timeperiod']['name'], [
                                                        'controller' => 'timeperiods',
                                                        'action'     => 'edit',
                                                        $hostdependency['Hostdependency']['timeperiod_id'],
                                                    ]);
                                                else:
                                                    echo h($hostdependency['Timeperiod']['name']);
                                                endif;
                                                ?>
                                            </td>
                                            <td><?php
                                                echo $this->Form->fancyCheckbox('', [
                                                    'caption'   => '',
                                                    'checked'   => $hostdependency['Hostdependency']['inherits_parent'],
                                                    'showLabel' => false,
                                                    'disabled'  => true,
                                                ]);
                                                ?></td>

                                            <td><?php echo __viewDependencyOptions($hostdependency, 'execution'); ?></td>
                                            <td><?php echo __viewDependencyOptions($hostdependency, 'notification'); ?></td>
                                            <td class="text-center">
                                                <?php if ($this->Acl->hasPermission('edit') && $allowEdit): ?>
                                                    <a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $hostdependency['Hostdependency']['id']; ?>"
                                                       data-original-title="<?php echo __('edit'); ?>"
                                                       data-placement="left" rel="tooltip" data-container="body"><i
                                                                id="list_edit"
                                                                class="fa fa-gear fa-lg txt-color-teal"></i></a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php if (empty($all_hostdependencies)): ?>
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
 * @param array $hostdependency from find('first')
 * @param       string          [options_mode] [Dependency options mode execution|notification]
 *
 * @return string `<i />` HTML object with icons for each options
 * @since 3.0
 */
function __viewDependencyOptions($hostdependency = [], $options_mode) {
    $options = [
        $options_mode . '_fail_on_up'          => [
            'color' => 'txt-color-greenLight',
            'class' => 'fa fa-square',
        ],
        $options_mode . '_fail_on_down'        => [
            'color' => 'txt-color-redLight',
            'class' => 'fa fa-square',
        ],
        $options_mode . '_fail_on_unreachable' => [
            'color' => 'txt-color-blueDark',
            'class' => 'fa fa-square',
        ],
        $options_mode . '_fail_on_pending'     => [
            'color' => '',
            'class' => 'fa fa-square-o',
        ],
        $options_mode . '_none'                => [
            'color' => '',
            'class' => 'fa fa-minus-square-o',
        ],
    ];
    $html = '';
    foreach ($options as $option => $layout_sett) { //$layout_sett => color + icons for options
        if (isset($hostdependency['Hostdependency'][$option]) && $hostdependency['Hostdependency'][$option] == 1) {
            $html .= '<i class="' . $layout_sett['class'] . ' ' . $layout_sett['color'] . '" title="' . preg_replace('/(' . $options_mode . '_|fail_on_)/', '', $option) . '"></i>&nbsp';
        }
    }

    return $html;
}
