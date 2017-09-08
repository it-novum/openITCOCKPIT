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

$this->Paginator->options(['url' => Hash::merge($this->params['named'], $this->params['pass'], ['Listsettings' => $LogentiresListsettings])]);
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-file-text-o fa-fw"></i>
            <?php echo __('Log Entries'); ?>
            <span>
                <?php echo __('Overview'); ?>
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
                <header>
                    <div class="widget-toolbar" role="menu">
                        <?php
                        echo $this->Html->link(__('Filter'), 'javascript:', ['class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-filter']);
                        if ($isFilter):
                            echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']);
                        endif;
                        ?>
                    </div>
                    <div class="widget-toolbar" role="menu">
                        <a href="javascript:void(0);" class="dropdown-toggle selector" data-toggle="dropdown">
                            <i class="fa fa-lg fa-table"></i>
                        </a>
                        <ul class="dropdown-menu arrow-box-up-right pull-right stayOpenOnClick">
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left" my-column="0">
                                    <input type="checkbox" class="pull-left"/>&nbsp; <?php echo __('Date'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left" my-column="1">
                                    <input type="checkbox" class="pull-left"/>&nbsp; <?php echo __('Type'); ?>
                                </a>
                            </li>
                            <li style="width: 100%;">
                                <a href="javascript:void(0)" class="select_datatable text-left" my-column="2">
                                    <input type="checkbox" class="pull-left"/>&nbsp; <?php echo __('Log Entry'); ?>
                                </a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div id="switch-1" class="widget-toolbar" role="menu">
                        <?php
                        echo $this->Form->create('logentries', ['class' => 'form-horizontal clear', 'url' => 'index']);
                        ?>

                        <div class="btn-group">
                            <?php
                            $listoptions = [
                                '5'    => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 5,
                                    'human'         => 5,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '10'   => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 10,
                                    'human'         => 10,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '25'   => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 25,
                                    'human'         => 25,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '50'   => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 50,
                                    'human'         => 50,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '100'  => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 100,
                                    'human'         => 100,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '150'  => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 150,
                                    'human'         => 150,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '500'  => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 500,
                                    'human'         => 500,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '1000' => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 1000,
                                    'human'         => 1000,
                                    'selector'      => '#listoptions_limit',
                                ],
                            ];

                            $selected = $paginatorLimit;


                            if (isset($LogentiresListsettings['limit']) && isset($listoptions[$LogentiresListsettings['limit']]['human'])) {
                                $selected = $listoptions[$LogentiresListsettings['limit']]['human'];
                            }
                            ?>
                            <button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-default">
                                <span id="listoptions_limit"><?php echo $selected; ?></span> <i
                                        class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right stayOpenOnClick">
                                <?php foreach ($listoptions as $listoption): ?>
                                    <li>
                                        <a href="javascript:void(0);" class="listoptions_action"
                                           selector="<?php echo $listoption['selector']; ?>"
                                           submit_target="<?php echo $listoption['submit_target']; ?>"
                                           value="<?php echo $listoption['value']; ?>"><?php echo $listoption['human']; ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <input type="hidden" value="<?php if (isset($LogentiresListsettings['limit'])) {
                                echo $LogentiresListsettings['limit'];
                            } ?>" id="listoptions_hidden_limit" name="data[Listsettings][limit]"/>
                        </div>
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-default">
                                <?php echo __('Options'); ?> <i class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right stayOpenOnClick">
                                <?php
                                foreach ($logentry_types as $logentry_type => $logentry_name):
                                    $htmlChecked = 'checked="checked"';
                                    if (isset($LogentiresListsettings['logentry_type'])):
                                        $htmlChecked = '';
                                        if ($LogentiresListsettings['logentry_type'] & $logentry_type):
                                            $htmlChecked = 'checked="checked"';
                                        endif;
                                    endif;
                                    ?>
                                    <li>
                                        <input type="hidden" value="0"
                                               name="data[Listsettings][logentry_type][<?php echo $logentry_type; ?>]"/>
                                    </li>
                                    <li style="width: 100%;">
                                        <a href="javascript:void(0)" class="listoptions_checkbox text-left">
                                            <input type="checkbox"
                                                   name="data[Listsettings][logentry_type][<?php echo $logentry_type; ?>]"
                                                   value="<?php echo $logentry_type; ?>" <?php echo $htmlChecked; ?>/>&nbsp; <?php echo $logentry_name; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                                <li class="divider"></li>
                                <li style="width: 100%;">
                                    <a href="javascript:void(0)" class="tick_all text-left">
                                        <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;<?php echo __('Tick all'); ?>
                                    </a>
                                </li>
                                <li style="width: 100%;">
                                    <a href="javascript:void(0)" class="untick_all text-left">
                                        <i class="fa fa-square-o"></i>&nbsp;&nbsp;<?php echo __('Untick all'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <button class="btn btn-xs btn-success toggle">
                            <i class="fa fa-check"></i> <?php echo __('Apply'); ?>
                        </button>
                        <?php
                        echo $this->Form->end();
                        ?>
                    </div>

                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-file-text-o"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Log Entries'); ?> </h2>

                </header>

                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php echo $this->ListFilter->renderFilterbox($filters, ['formActionParams' => ['url' => Router::url(Hash::merge($this->params['named'], $this->params['pass'], ['Listsettings' => $LogentiresListsettings])), 'merge' => false]], '<i class="fa fa-filter"></i> '.__('Filter'), false, false); ?>
                        <div class="mobile_table">
                            <table id="logentries_list" class="table table-striped table-hover table-bordered smart-form" style="">
                                <thead>
                                <tr>
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <th class="no-sort">
                                        <?php echo $this->Utils->getDirection($order, 'logentry_time');
                                        echo $this->Paginator->sort('logentry_time', __('Date')); ?>
                                    </th>
                                    <th class="no-sort">
                                        <?php echo $this->Utils->getDirection($order, 'logentry_type');
                                        echo $this->Paginator->sort('logentry_type', __('Type')); ?>
                                    </th>
                                    <th class="no-sort">
                                        <?php echo $this->Utils->getDirection($order, 'logentry_data');
                                        echo $this->Paginator->sort('logentry_data', __('Log Entry')); ?>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($all_logentries as $logentry): ?>
                                    <tr>
                                        <td>
                                            <?php echo h($this->Time->format($logentry['Logentry']['logentry_time'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone'))); ?>
                                        </td>
                                        <td>
                                            <?php
                                            switch ($logentry['Logentry']['logentry_type']):
                                                case 514:
                                                    echo __('External command failed');
                                                    break;
                                                case 6:
                                                    echo __('Timeperiod transition');
                                                    break;
                                                default:
                                                    echo $logentry_types[$logentry['Logentry']['logentry_type']];
                                                    break;
                                            endswitch;
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $this->Uuid->replaceUuids($logentry['Logentry']['logentry_data']); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (empty($all_logentries)): ?>
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
                                         id="datatable_fixed_column_info">
                                        <?php echo $this->Paginator->counter(__('Page').' {:page} '.__('of').' {:pages}, '.__('Total').' {:count} '.__('entries')); ?>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <div class="dataTables_paginate paging_bootstrap">
                                        <?php
                                        echo $this->Paginator->pagination([
                                            'ul' => 'pagination',
                                        ]);
                                        ?>
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
        </article>
    </div>
    <!-- end row -->
</section>
<!-- end widget grid -->