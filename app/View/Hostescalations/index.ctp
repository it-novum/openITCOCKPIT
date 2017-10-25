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
                <i class="fa fa-bomb fa-fw"></i>
                <?php echo __('Monitoring'); ?>
                <span>>
                    <?php echo __('Host Escalations'); ?>
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
                                //echo " "; //Fix HTML if search is implemented
                            endif;
                            // TODO: search functionallity
                            //echo $this->Html->link(__('Filter'), 'javascript:', array('class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-filter'));

                            if ($isFilter):
                                echo " "; //Fix HTML
                                echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']);
                            endif;
                            ?>
                        </div>
                        <div class="widget-toolbar" role="menu">
                            <a href="javascript:void(0);" class="dropdown-toggle selector" data-toggle="dropdown"><i
                                        class="fa fa-lg fa-table"></i></a>
                            <ul class="dropdown-menu arrow-box-up-right pull-right stayOpenOnClick">
                                <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                            class="select_datatable text-left" my-column="0"><input
                                                type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Hosts'); ?>
                                    </a></li>
                                <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                            class="select_datatable text-left" my-column="1"><input
                                                type="checkbox" class="pull-left"/>
                                        &nbsp; <?php echo __('Ext. hosts'); ?></a></li>
                                <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                            class="select_datatable text-left" my-column="2"><input
                                                type="checkbox" class="pull-left"/>
                                        &nbsp; <?php echo __('Host groups'); ?></a></li>
                                <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                            class="select_datatable text-left" my-column="3"><input
                                                type="checkbox" class="pull-left"/>
                                        &nbsp; <?php echo __('Ext. hosts groups'); ?></a></li>
                                <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                            class="select_datatable text-left" my-column="4"><input
                                                type="checkbox" class="pull-left"/> &nbsp; <?php echo __('First'); ?>
                                    </a></li>
                                <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                            class="select_datatable text-left" my-column="5"><input
                                                type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Last'); ?></a>
                                </li>
                                <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                            class="select_datatable text-left" my-column="6"><input
                                                type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Interval'); ?>
                                    </a></li>
                                <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                            class="select_datatable text-left" my-column="7"><input
                                                type="checkbox" class="pull-left"/>
                                        &nbsp; <?php echo __('Timeperiod'); ?></a></li>
                                <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                            class="select_datatable text-left" my-column="8"><input
                                                type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Contacts'); ?>
                                    </a></li>
                                <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                            class="select_datatable text-left" my-column="9"><input
                                                type="checkbox" class="pull-left"/>
                                        &nbsp; <?php echo __('Contact groups'); ?></a></li>
                                <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                            class="select_datatable text-left" my-column="10"><input
                                                type="checkbox" class="pull-left"/> &nbsp; <?php echo __('Options'); ?>
                                    </a></li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="jarviswidget-ctrls" role="menu">
                        </div>
                        <span class="widget-icon hidden-mobile"> <i class="fa fa-bomb"></i> </span>
                        <h2 class="hidden-mobile"><?php echo __('Host Escalations'); ?> </h2>

                    </header>
                    <div>

                        <!-- widget content -->
                        <div class="widget-body no-padding">
                            <div class="mobile_table">
                                <table id="hostescalation_list" class="table table-striped table-hover table-bordered smart-form"
                                       style="">
                                    <thead>
                                    <tr>
                                        <th><?php echo __('Hosts'); ?></th>
                                        <th><?php echo __('Ext. hosts'); ?></th>
                                        <th><?php echo __('Host groups'); ?></th>
                                        <th><?php echo __('Ext. hosts groups'); ?></th>
                                        <th><?php echo __('First'); ?></th>
                                        <th><?php echo __('Last'); ?></th>
                                        <th><?php echo __('Interval'); ?></th>
                                        <th><?php echo __('Timeperiod'); ?></th>
                                        <th><?php echo __('Contacts'); ?></th>
                                        <th><?php echo __('Contact groups'); ?></th>
                                        <th class="no-sort"><?php echo __('Options'); ?></th>
                                        <th class="no-sort text-center"><i class="fa fa-gear fa-lg"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($all_hostescalations as $hostescalation):
                                        $allowEdit = $this->Acl->isWritableContainer($hostescalation['Hostescalation']['container_id']);
                                        ?>
                                        <tr>
                                            <td>
                                                <ul class="list-unstyled">
                                                    <?php
                                                    foreach (Hash::extract($hostescalation, 'HostescalationHostMembership.{n}[excluded=0]') as $host):
                                                        echo '<li>';
                                                        if ($this->Acl->hasPermission('edit', 'hosts')):
                                                            echo $this->Html->link(
                                                                $host['Host']['name'],
                                                                [
                                                                    'controller' => 'hosts',
                                                                    'action'     => 'edit',
                                                                    $host['Host']['id'],
                                                                ],
                                                                [
                                                                    'class'  => 'txt-color-green',
                                                                    'escape' => false,
                                                                ]
                                                            );
                                                        else:
                                                            echo h($host['Host']['name']);
                                                        endif;
                                                        echo ($host['Host']['disabled'])?
                                                            ' <i class="fa fa-power-off text-danger" title="disabled" aria-hidden="true"></i>':'';
                                                        echo '</li>';
                                                    endforeach;
                                                    ?>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul class="list-unstyled">
                                                    <?php
                                                    foreach (Hash::extract($hostescalation, 'HostescalationHostMembership.{n}[excluded=1]') as $host_excluded):
                                                        echo '<li>';
                                                        if ($this->Acl->hasPermission('edit', 'hosts')):
                                                            echo $this->Html->link(
                                                                $host_excluded['Host']['name'],
                                                                [
                                                                    'controller' => 'hosts',
                                                                    'action'     => 'edit',
                                                                    $host_excluded['Host']['id'],
                                                                ],
                                                                [
                                                                    'class'  => 'txt-color-red',
                                                                    'escape' => true,
                                                                ]
                                                            );
                                                        else:
                                                            echo h($host_excluded['Host']['name']);
                                                        endif;
                                                        echo ($host_excluded['Host']['disabled'])?
                                                            ' <i class="fa fa-power-off text-danger" title="disabled" aria-hidden="true"></i>':'';
                                                        echo '</li>';
                                                    endforeach;
                                                    ?>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul class="list-unstyled">
                                                    <?php
                                                    foreach (Hash::extract($hostescalation, 'HostescalationHostgroupMembership.{n}[excluded=0].Hostgroup') as $hostgroup):
                                                        if (!empty($hostgroup)):
                                                            echo '<li>';
                                                            if ($this->Acl->hasPermission('edit', 'hostgroups')):
                                                                echo $this->Html->link(
                                                                    $hostgroup['Container']['name'],
                                                                    [
                                                                        'controller' => 'hostgroups',
                                                                        'action'     => 'edit',
                                                                        $hostgroup['id'],
                                                                    ],
                                                                    [
                                                                        'class'  => 'txt-color-green',
                                                                        'escape' => true,
                                                                    ]
                                                                );
                                                            else:
                                                                echo h($hostgroup['Container']['name']);
                                                            endif;
                                                            echo '</li>';
                                                        endif;
                                                    endforeach;
                                                    ?>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul class="list-unstyled">
                                                    <?php
                                                    foreach (Hash::extract($hostescalation, 'HostescalationHostgroupMembership.{n}[excluded=1].Hostgroup') as $hostgroup):
                                                        if (!empty($hostgroup)):
                                                            echo '<li>';
                                                            if ($this->Acl->hasPermission('edit', 'hostgroups')):
                                                                echo $this->Html->link(
                                                                    $hostgroup['Container']['name'],
                                                                    [
                                                                        'controller' => 'hostgroups',
                                                                        'action'     => 'edit',
                                                                        $hostgroup['id'],
                                                                    ],
                                                                    [
                                                                        'class'  => 'txt-color-red',
                                                                        'escape' => true,
                                                                    ]
                                                                );
                                                            else:
                                                                echo h($hostgroup['Container']['name']);
                                                            endif;
                                                            echo '</li>';
                                                        endif;
                                                    endforeach;
                                                    ?>
                                                </ul>
                                            </td>
                                            <td><?php echo $hostescalation['Hostescalation']['first_notification']; ?></td>
                                            <td><?php echo $hostescalation['Hostescalation']['last_notification']; ?></td>
                                            <td><?php echo $hostescalation['Hostescalation']['notification_interval']; ?></td>
                                            <td>
                                                <?php
                                                if ($this->Acl->hasPermission('edit', 'timeperiods')):
                                                    echo $this->Html->link(
                                                        $hostescalation['Timeperiod']['name'],
                                                        [
                                                            'controller' => 'timeperiods',
                                                            'action'     => 'edit',
                                                            $hostescalation['Timeperiod']['id'],
                                                        ]
                                                    );
                                                else:
                                                    echo h($hostescalation['Timeperiod']['name']);
                                                endif;
                                                ?>
                                            </td>
                                            <td>
                                                <ul class="list-unstyled">
                                                    <?php
                                                    foreach ($hostescalation['Contact'] as $contact):
                                                        echo '<li>';
                                                        if ($this->Acl->hasPermission('edit', 'contacts')):
                                                            echo $this->Html->link(
                                                                $contact['name'],
                                                                [
                                                                    'controller' => 'contacts',
                                                                    'action'     => 'edit',
                                                                    $contact['id'],
                                                                ]
                                                            );
                                                        else:
                                                            echo h($contact['name']);
                                                        endif;
                                                        echo '</li>';
                                                    endforeach;
                                                    ?>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul class="list-unstyled">
                                                    <?php
                                                    foreach ($hostescalation['Contactgroup'] as $contactgroup):
                                                        echo '<li>';
                                                        if ($this->Acl->hasPermission('edit', 'contactgroups')):
                                                            echo $this->Html->link(
                                                                $contactgroup['Container']['name'],
                                                                [
                                                                    'controller' => 'contacts',
                                                                    'action'     => 'edit',
                                                                    $contactgroup['id'],
                                                                ]
                                                            );
                                                        else:
                                                            echo h($contactgroup['Container']['name']);
                                                        endif;
                                                        echo '</li>';
                                                    endforeach;
                                                    ?>
                                                </ul>
                                            </td>
                                            <td><?php echo __viewHostescalationOptions($hostescalation); ?></td>
                                            <td class="text-center">
                                                <?php if ($this->Acl->hasPermission('edit') && $allowEdit): ?>
                                                    <a href="/<?php echo $this->params['controller']; ?>/edit/<?php echo $hostescalation['Hostescalation']['id']; ?>"
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
                            <?php if (empty($all_hostescalations)): ?>
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
<?php
/**
 * This is a view function and ONLY CALLED IN THIS VIEW!
 *
 * @param array $hostescalation from find('first')
 *
 * @return string `<i />` HTML object with icons for each options
 * @author Daniel Ziegler <daniel.ziegler@it-novum.com>
 * @since  3.0
 */
function __viewHostescalationOptions($hostescalation = [])
{
    $options = ['escalate_on_recovery' => 'txt-color-greenLight', 'escalate_on_down' => 'txt-color-redLight', 'escalate_on_unreachable' => 'txt-color-blueDark'];
    $class = 'fa fa-square ';
    $html = '';
    foreach ($options as $option => $color) {
        if (isset($hostescalation['Hostescalation'][$option]) && $hostescalation['Hostescalation'][$option] == 1) {
            $html .= '<i class="'.$class.$color.'"></i>&nbsp';
        }
    }

    return $html;
}