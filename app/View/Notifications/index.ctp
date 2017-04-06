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
<?php $this->Paginator->options(['url' => Hash::merge($this->params['named'], $ListsettingsUrlParams)]); ?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-envelope fa-fw "></i>
            <?php echo __('Notifications'); ?>
            <span>>
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
                        <?php echo $this->Html->link(__('Filter'), 'javascript:', ['class' => 'oitc-list-filter btn btn-xs btn-primary toggle', 'hide-on-render' => 'true', 'icon' => 'fa fa-filter']); ?>
                        <?php
                        if ($isFilter):
                            echo $this->ListFilter->resetLink(null, ['class' => 'btn-danger btn-xs', 'icon' => 'fa fa-times']);
                        endif;
                        ?>
                    </div>
                    <div class="widget-toolbar" role="menu">
                        <a href="javascript:void(0);" class="dropdown-toggle selector" data-toggle="dropdown"><i
                                    class="fa fa-lg fa-table"></i></a>
                        <ul class="dropdown-menu arrow-box-up-right pull-right">
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="0"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('State'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="1"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Host'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="2"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Service'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="3"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Date'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="4"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('User'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="5"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Notification type'); ?></a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        my-column="6"><input type="checkbox" class="pull-left"/>
                                    &nbsp; <?php echo __('Output'); ?></a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div id="switch-1" class="widget-toolbar" role="menu">
                        <?php
                        echo $this->Form->create('notifications', [
                            'class' => 'form-horizontal clear',
                            'url'   => 'index' // reset the URL on submit
                        ]);

                        ?>

                        <div class="widget-toolbar pull-left hidden-mobile" role="menu">
                            <span style="line-height: 32px;" class="pull-left"><?php echo __('From:'); ?></span>
                            <input class="form-control text-center pull-left margin-left-10" style="width: 78%;"
                                   type="text" maxlength="255"
                                   value="<?php if (isset($NotificationListsettings['from'])): echo $NotificationListsettings['from'];
                                   else: echo date('d.m.Y H:i', strtotime('3 days ago')); endif; ?>"
                                   name="data[Listsettings][from]">
                        </div>

                        <div class="widget-toolbar pull-left hidden-mobile" role="menu">
                            <span style="line-height: 32px;" class="pull-left"><?php echo __('To:'); ?></span>
                            <input class="form-control text-center pull-left margin-left-10" style="width: 85%;"
                                   type="text" maxlength="255"
                                   value="<?php if (isset($NotificationListsettings['to'])): echo $NotificationListsettings['to'];
                                   else: echo date('d.m.Y H:i', time()); endif; ?>" name="data[Listsettings][to]">
                        </div>

                        <?php
                        $listoptions = [
                            //'all' => [
                            //	'submit_target' => '#listoptions_hidden_view',
                            //	'value' => 'all',
                            //	'human' => __('All'),
                            //	'selector' => '#listoptions_view'
                            //],
                            'hostOnly'    => [
                                'submit_target' => '#listoptions_hidden_view',
                                'value'         => 'hostOnly',
                                'human'         => __('Host notifications'),
                                'selector'      => '#listoptions_view',
                            ],
                            'serviceOnly' => [
                                'submit_target' => '#listoptions_hidden_view',
                                'value'         => 'serviceOnly',
                                'human'         => __('Service notifications'),
                                'selector'      => '#listoptions_view',
                            ],
                        ];

                        $selected = __('Host notifications');
                        if (isset($NotificationListsettings['view']) && isset($listoptions[$NotificationListsettings['view']]['human'])) {
                            $selected = $listoptions[$NotificationListsettings['view']]['human'];
                        }
                        ?>

                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-default">
                                <span id="listoptions_view"><?php echo $selected; ?></span> <i
                                        class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <?php foreach ($listoptions as $listoption): ?>
                                    <li>

                                        <a href="javascript:void(0);" class="listoptions_action"
                                           selector="<?php echo $listoption['selector']; ?>"
                                           submit_target="<?php echo $listoption['submit_target']; ?>"
                                           value="<?php echo $listoption['value']; ?>"><?php echo $listoption['human']; ?></a>
                                    </li>
                                <?php endforeach; ?>

                            </ul>
                            <input type="hidden"
                                   value="<?php if (isset($NotificationListsettings['view'])): echo $NotificationListsettings['view']; endif; ?>"
                                   id="listoptions_hidden_view" name="data[Listsettings][view]"/>
                        </div>

                        <div class="btn-group">
                            <?php
                            $listoptions = [
                                '5'   => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 5,
                                    'human'         => 5,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '10'  => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 10,
                                    'human'         => 10,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '25'  => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 25,
                                    'human'         => 25,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '50'  => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 50,
                                    'human'         => 50,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '100' => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 100,
                                    'human'         => 100,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '150' => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 150,
                                    'human'         => 150,
                                    'selector'      => '#listoptions_limit',
                                ],
                                '300' => [
                                    'submit_target' => '#listoptions_hidden_limit',
                                    'value'         => 300,
                                    'human'         => 300,
                                    'selector'      => '#listoptions_limit',
                                ],
                            ];

                            $selected = $paginatorLimit;
                            if (isset($NotificationListsettings['limit']) && isset($listoptions[$NotificationListsettings['limit']]['human'])) {
                                $selected = $listoptions[$NotificationListsettings['limit']]['human'];
                            }
                            ?>
                            <button data-toggle="dropdown" class="btn dropdown-toggle btn-xs btn-default hidden-mobile">
                                <span id="listoptions_limit"><?php echo $selected; ?></span> <i
                                        class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                <?php foreach ($listoptions as $listoption): ?>
                                    <li>
                                        <a href="javascript:void(0);" class="listoptions_action"
                                           selector="<?php echo $listoption['selector']; ?>"
                                           submit_target="<?php echo $listoption['submit_target']; ?>"
                                           value="<?php echo $listoption['value']; ?>"><?php echo $listoption['human']; ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <input type="hidden"
                                   value="<?php if (isset($NotificationListsettings['limit'])): echo $NotificationListsettings['limit']; endif; ?>"
                                   id="listoptions_hidden_limit" name="data[Listsettings][limit]"/>
                        </div>

                        <button class="btn btn-xs btn-success toggle hidden-mobile"><i
                                    class="fa fa-check"></i> <?php echo __('Apply'); ?></button>

                        <?php
                        echo $this->Form->end();
                        ?>
                    </div>

                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-envelope"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Notifications'); ?> </h2>

                </header>

                <!-- widget div-->
                <div>

                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php echo $this->ListFilter->renderFilterbox($filters, ['formActionParams' => ['url' => Router::url(Hash::merge($this->params['named'], $ListsettingsUrlParams)), 'merge' => false]], '<i class="fa fa-filter"></i> '.__('Filter'), false, false); ?>
                        <div class="mobile_table">
                            <table id="notification_list" class="table table-striped table-bordered smart-form" style="">
                                <thead>
                                <tr>
                                    <?php $order = $this->Paginator->param('order'); ?>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'state');
                                        echo $this->Paginator->sort('state', __('State')); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Host.name');
                                        echo $this->Paginator->sort('Host.name', __('Host')); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Service.name');
                                        echo $this->Paginator->sort('Service.name', __('Service')); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Notification.start_time');
                                        echo $this->Paginator->sort('Notification.start_time', __('Date')); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Contact.name');
                                        echo $this->Paginator->sort('Contact.name', __('Contact')); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Command.name');
                                        echo $this->Paginator->sort('Command.name', __('Notification method')); ?></th>
                                    <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'output');
                                        echo $this->Paginator->sort('output', __('Output')); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php //debug($all_notification); ?>
                                <?php foreach ($all_notification as $notification): ?>
                                    <tr>
                                        <td>
                                            <center><?php echo $this->Monitoring->NotificationStatusIcon($notification['Notification']['state'], $notification['Notification']['notification_type']); ?></center>
                                        </td>
                                        <td>
                                            <?php if (isset($notification['Host']['name']) && $notification['Host']['name'] != null): ?>
                                                <a href="/hosts/browser/<?php echo $notification['Host']['id']; ?>">
                                                    <?php echo $notification['Host']['name']; ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ((isset($notification['Service']['name']) && $notification['Service']['name'] != null) || isset($notification['Servicetemplate']['name']) && $notification['Servicetemplate']['name'] !== null): ?>
                                                <a href="/services/browser/<?php echo $notification['Service']['id']; ?>">
                                                    <?php
                                                    if ($notification['Service']['name'] != null):
                                                        echo $notification['Service']['name'];
                                                    else:
                                                        echo $notification['Servicetemplate']['name'];
                                                    endif;
                                                    ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $this->Time->format($notification['Contactnotification']['start_time'], $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?></td>
                                        <td>
                                            <?php
                                            //Checking if the contact exists or was deleted
                                            if (isset($notification['Contact']['id']) && $notification['Contact']['id'] != null): ?>
                                                <a href="/contacts/edit/<?php echo $notification['Contact']['id']; ?>"><?php echo $notification['Contact']['name']; ?></a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            //Checking if the command exists or was deleted
                                            if (isset($notification['Command']['id']) && $notification['Command']['id'] != null): ?>
                                                <a href="/commands/edit/<?php echo $notification['Command']['id']; ?>"><?php echo $notification['Command']['name']; ?></a>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $notification['Notification']['output']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (empty($all_notification)): ?>
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
                    <!-- end widget content -->

                </div>
                <!-- end widget div -->

            </div>
            <!-- end widget -->


    </div>

    <!-- end row -->

</section>
<!-- end widget grid -->