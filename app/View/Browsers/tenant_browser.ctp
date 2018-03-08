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

use itnovum\openITCOCKPIT\Core\Views\Host;
use itnovum\openITCOCKPIT\Core\Hoststatus;

?>
<ol class="breadcrumb">
    <li></li> <!-- leading / -->
    <?php
    if ($currentContainer['Container']['parent_id'] != null):
        foreach ($parents as $parent):
            if ($parent['Container']['containertype_id'] == CT_GLOBAL):
                echo '<li>' . $this->Html->link($parent['Container']['name'], 'index/' . $parent['Container']['id']) . '</li>';
            else:
                if (in_array($parent['Container']['id'], $MY_RIGHTS_WITH_TENANT)):
                    echo '<li>' . $this->Html->link($parent['Container']['name'], ['action' => 'tenantBrowser', $parent['Container']['id']]) . '</li>';
                else:
                    echo '<li class="active">' . h($currentContainer['Container']['name']) . '</li>';
                endif;
            endif;
        endforeach;
    endif;
    ?>
    <li class="active"><?php echo h($currentContainer['Container']['name']); ?></li>
</ol>

<div class="row">
    <article class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="jarviswidget node-list" role="widget">
            <header>
                <span class="widget-icon"> <i class="fa fa-list-ul"></i></span>
                <h2> <?php echo __('Nodes'); ?> </h2>
            </header>
            <div class="no-padding height-100" style="overflow-y:auto; overflow-x: hidden;">
                <input type="text" id="node-list-search" placeholder="<?php echo __('Search...'); ?>"/>
                <div class="padding-10">
                    <div class="widget-body">
                        <?php foreach ($browser as $b): ?>
                            <div class="ellipsis searchContainer">
                                <?php
                                $faClass = $this->BrowserMisc->containertypeIcon($b['containertype_id']);
                                ?>
                                <i class="fa <?php echo $faClass; ?>"></i>
                                <?php echo $this->Html->link($b['name'], [
                                    'action' => 'tenantBrowser',
                                    $b['id']
                                ], [
                                    'class' => 'searchMe',
                                    'title' => $b['name']
                                ]); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
    </article>
    <article class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="jarviswidget">
            <header>
                <span class="widget-icon"> <i class="fa fa-pie-chart"></i></span>
                <h2><?php echo __('Hoststatus overview'); ?></h2>
            </header>
            <!-- widget div-->
            <div class="widget-body node-list">
                <!-- end widget edit box -->
                <div class="text-center">
                    <?php
                    $state_total = array_sum($state_array_host);
                    if ($state_total > 0):
                        $overview_chart = $this->PieChart->createPieChart($state_array_host);
                        echo $this->Html->image(
                            '/img/charts/' . $overview_chart
                        );
                        $state_colors = [
                            'ok',
                            'critical',
                            'unknown',
                        ]; ?>
                        <div class="col-md-12 text-center padding-bottom-10 font-xs">
                            <?php
                            foreach ($state_array_host as $state => $state_count):?>
                                <div class="col-md-4 no-padding">
                                    <a href="/hosts/index<?php echo Router::queryString([
                                        'filter'             => [
                                            'Hoststatus.current_state' => [$state => 1]
                                        ],
                                        'sort'               => 'Hoststatus.last_state_change',
                                        'direction'          => 'desc',
                                        'BrowserContainerId' => $currentContainer['Container']['id']
                                    ]); ?>">
                                        <i class="fa fa-square <?php echo $state_colors[$state] ?>"></i>
                                        <?php echo $state_count . ' (' . round($state_count / $state_total * 100, 2) . ' %)'; ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-muted padding-top-80"><?php echo __('No hosts associated with this node'); ?></div>
                    <?php endif; ?>
                </div>

            </div>
    </article>
    <article class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
        <div class="jarviswidget">
            <header>
                <span class="widget-icon"> <i class="fa fa-pie-chart"></i></i></span>
                <h2><?php echo __('Servicestatus overview'); ?></h2>
            </header>
            <!-- widget div-->
            <div class="widget-body node-list">
                <!-- end widget edit box -->
                <div class="text-center">
                    <?php
                    $state_total = array_sum($state_array_service);
                    if ($state_total > 0):
                        $overview_chart = $this->PieChart->createPieChart($state_array_service);

                        echo $this->Html->image(
                            '/img/charts/' . $overview_chart
                        );
                        $state_colors = [
                            'ok',
                            'warning',
                            'critical',
                            'unknown',
                        ]; ?>
                        <div class="col-md-12 text-center padding-bottom-10 font-xs">
                            <?php

                            foreach ($state_array_service as $state => $state_count):?>
                                <div class="col-md-3 no-padding">
                                    <a href="/services/index<?php echo Router::queryString([
                                        'filter'             => [
                                            'Servicestatus.current_state' => [$state => 1]
                                        ],
                                        'sort'               => 'Servicestatus.last_state_change',
                                        'direction'          => 'desc',
                                        'BrowserContainerId' => $currentContainer['Container']['id']
                                    ]); ?>">
                                        <i class="fa fa-square <?php echo $state_colors[$state] ?>"></i>
                                        <?php
                                        //Fix for a system without host or services
                                        if ($state_total == 0):
                                            $state_total = 1;
                                            if ($state == 3):
                                                $state_count = 1;
                                            endif;
                                        endif;
                                        ?>
                                        <?php echo $state_count . ' (' . round($state_count / $state_total * 100, 2) . ' %)'; ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-muted padding-top-80"><?php echo __('No services associated with this node'); ?></div>
                    <?php endif; ?>
                </div>
            </div>
    </article>
</div>

<div class="row">
    <article class="col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget ">
            <header>
                <span class="widget-icon hidden-mobile"> <i class="fa fa-desktop"></i> </span>
                <h2 class="hidden-mobile"><?php echo __('Hosts'); ?></h2>
            </header>
            <div>
                <div class="widget-body no-padding">
                    <div class="mobile_table">
                        <?php if (!empty($hosts)): ?>
                            <table id="host-list-datatables"
                                   class="table table-striped table-hover table-bordered smart-form">
                                <thead>
                                <tr>
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <th class="select_datatable no-sort"><?php echo __('Hoststatus'); ?></th>
                                    <th class="no-sort text-center"><i class="fa fa-gear fa-lg"></i></th>
                                    <th class="no-sort"><?php echo __('Host Name'); ?></th>
                                    <th class="no-sort"><?php echo __('IP address'); ?></th>
                                    <th class="no-sort"><?php echo __('State since'); ?></th>
                                    <th class="no-sort"><?php echo __('Last check'); ?></th>
                                    <th class="no-sort"><?php echo __('Output'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($hosts as $host): ?>
                                    <?php
                                    $Host = new Host($host);
                                    $Hoststatus = new Hoststatus($host['Hoststatus']);

                                    //Better performance, than run all the Hash::extracts if not necessary
                                    $hasEditPermission = false;
                                    if ($hasRootPrivileges === true):
                                        $hasEditPermission = true;
                                    else:
                                        if ($this->Acl->isWritableContainer(Hash::extract($host, 'Container.{n}.HostsToContainer.container_id'))):
                                            $hasEditPermission = true;
                                        endif;
                                    endif;
                                    ?>
                                    <tr>
                                        <td class="text-center width-75"
                                            data-sort="<?php echo $Hoststatus->currentState(); ?>">
                                            <?php
                                            if ($Hoststatus->isFlapping() == 1):
                                                echo $Hoststatus->getHostFlappingIconColored();
                                            else:
                                                $href = 'javascript:void(0);';
                                                if ($this->Acl->hasPermission('browser', 'hosts')):
                                                    $href = '/hosts/browser/' . $Host->getId();
                                                endif;
                                                echo $Hoststatus->getHumanHoststatus($href)['html_icon'];
                                            endif;
                                            ?>
                                        </td>
                                        <td class="width-50">
                                            <div class="btn-group">
                                                <?php if ($this->Acl->hasPermission('edit', 'hosts') && $hasEditPermission): ?>
                                                    <a href="/hosts/edit/<?php echo $Host->getId(); ?>"
                                                       class="btn btn-default">&nbsp;<i class="fa fa-cog"></i>&nbsp;</a>
                                                <?php else: ?>
                                                    <a href="javascript:void(0);" class="btn btn-default">&nbsp;<i
                                                                class="fa fa-cog"></i>&nbsp;</a>
                                                <?php endif; ?>
                                                <a href="javascript:void(0);" data-toggle="dropdown"
                                                   class="btn btn-default dropdown-toggle"><span
                                                            class="caret"></span></a>
                                                <ul class="dropdown-menu">
                                                    <?php if ($this->Acl->hasPermission('edit', 'hosts') && $hasEditPermission): ?>
                                                        <li>
                                                            <a href="/hosts/edit/<?php echo $Host->getId(); ?>"><i
                                                                        class="fa fa-cog"></i> <?php echo __('Edit'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <?php if ($this->Acl->hasPermission('serviceList', 'services')): ?>
                                                        <li>
                                                            <a href="/services/serviceList/<?php echo $Host->getId(); ?>"><i
                                                                        class="fa fa-list"></i> <?php echo __('Service list'); ?>
                                                            </a>
                                                        </li>
                                                    <?php endif; ?>

                                                    <?php
                                                    if ($this->Acl->hasPermission('edit', 'hosts') && $hasEditPermission):
                                                        echo $this->AdditionalLinks->renderAsListItems(
                                                            $additionalLinksList,
                                                            $Host->getId()
                                                        );
                                                    endif;
                                                    ?>
                                                    <?php if ($this->Acl->hasPermission('delete', 'hosts') && $hasEditPermission): ?>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <?php echo $this->Form->postLink('<i class="fa fa-trash-o"></i> ' . __('Delete'), ['controller' => 'hosts', 'action' => 'delete', $Host->getId()], ['class' => 'txt-color-red', 'escape' => false]); ?>
                                                        </li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </td>

                                        <td>
                                            <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                                                <a href="/hosts/browser/<?php echo $Host->getId(); ?>">
                                                    <?php echo h($Host->getHostname()); ?>
                                                </a>
                                            <?php else: ?>
                                                <?php echo h($Host->getHostname()); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo h($Host->getAddress()); ?></td>
                                        <td data-original-title="<?php echo h($this->Time->format(
                                            $Hoststatus->getLastHardStateChange(),
                                            $this->Auth->user('dateformat'),
                                            false,
                                            $this->Auth->user('timezone')
                                        )); ?>"
                                            data-placement="bottom" rel="tooltip" data-container="body">
                                            <?php echo h($this->Utils->secondsInHumanShort(time() - strtotime($Hoststatus->getLastHardStateChange()))); ?>
                                        </td>
                                        <td>
                                            <?php echo h($this->Time->format(
                                                $Hoststatus->getLastCheck(),
                                                $this->Auth->user('dateformat'),
                                                false,
                                                $this->Auth->user('timezone')
                                            )); ?>
                                        </td>
                                        <td><?php echo h($Hoststatus->getOutput()); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="noMatch">
                                <center>
                                    <span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
                                </center>
                            </div>
                        <?php endif; ?>

                        <div style="padding: 5px 10px;">
                            <div class="row">
                                <div class="col-sm-6">
                                    <?php if (!empty($hosts)): ?>
                                        <div class="dataTables_info" style="line-height: 32px;"
                                             id="datatable_fixed_column_info"><?php echo $this->Paginator->counter(__('Page') . ' {:page} ' . __('of') . ' {:pages}, ' . __('Total') . ' {:count} ' . __('entries')); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <div class="dataTables_paginate paging_bootstrap">
                                        <?php
                                        if (!empty($hosts)):
                                            echo $this->Paginator->pagination([
                                                'ul' => 'pagination',
                                            ]);
                                        endif;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>
</div>
