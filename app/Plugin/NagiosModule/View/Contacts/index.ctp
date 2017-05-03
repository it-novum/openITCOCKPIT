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
<?php //debug($all_contacts); ?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-user fa-fw "></i>
            Nagios
            <span>>
				Contacts
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
                    <div class="widget-toolbar" role="menu">
                        <a href="javascript:void(0);" class="dropdown-toggle selector" data-toggle="dropdown"><i
                                    class="fa fa-lg fa-table"></i></a>
                        <ul class="dropdown-menu arrow-box-up-right pull-right">
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="1"><input
                                            type="checkbox" class="pull-left"/> &nbsp; Contact name</a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="2"><input
                                            type="checkbox" class="pull-left"/> &nbsp; Alias</a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="3"><input
                                            type="checkbox" class="pull-left"/> &nbsp; Email</a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="4"><input
                                            type="checkbox" class="pull-left"/> &nbsp; Pager</a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="5"><input
                                            type="checkbox" class="pull-left"/> &nbsp; Notifications (Host)</a></li>
                            <li style="width: 100%;"><a href="javascript:void(0)" class="select_datatable text-left"
                                                        class="select_datatable text-left" my-column="6"><input
                                            type="checkbox" class="pull-left"/> &nbsp; Notifications (Service)</a></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon"> <i class="fa fa-user"></i> </span>
                    <h2>Contacts </h2>

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
                        <table id="contact_list" class="table table-striped table-hover table-bordered smart-form" style="">
                            <thead>
                            <tr>
                                <?php $order = $this->Paginator->param('order'); ?>
                                <th class="no-sort"></th>
                                <th class="select_datatable no-sort"><?php echo $this->Utils->getDirection($order, 'Objects.name1');
                                    echo $this->Paginator->sort('Objects.name1', 'Contact name'); ?></th>
                                <th class="no-sort"><?php echo $this->Utils->getDirection($order, 'Contacts.alias');
                                    echo $this->Paginator->sort('Contact.alias', 'Alias'); ?></th>
                                <th class="no-sort">Email</th>
                                <th class="no-sort">Pager</th>
                                <th class="no-sort">Notifications (Host)</th>
                                <th class="no-sort">Notifications (Service)</th>
                                <th class="text-center no-sort"><i class="fa fa-envelope fa-lg"></i></th>
                                <th class="no-sort"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $notification_settings = [
                                'host'    => [
                                    'notify_host_recovery', 'notify_host_down', 'notify_host_unreachable', 'notify_host_flapping', 'notify_host_downtime',
                                ],
                                'service' => ['notify_service_recovery', 'notify_service_warning', 'notify_service_unknown', 'notify_service_critical', 'notify_service_flapping', 'notify_service_downtime'],
                            ];
                            ?>
                            <?php foreach ($all_contacts as $contact): ?>
                                <tr>
                                    <td class="text-center"><input class="multiselect" type="checkbox"
                                                                   name="contact[<?php echo $contact['Contact']['contact_object_id']; ?>]"/>
                                    </td>
                                    <td><?php echo $contact['Objects']['name1']; ?></td>
                                    <td><?php echo $contact['Contact']['alias']; ?></td>
                                    <td><?php echo $contact['Contact']['email_address']; ?></td>
                                    <td><?php echo $contact['Contact']['pager_address']; ?></td>
                                    <?php foreach ($notification_settings as $key => $notification_settings_arr): ?>
                                        <?php
                                        $notification_status = 'success';
                                        $notification_status_message = 'On';
                                        if (!$contact['Contact'][$key.'_notifications_enabled']):
                                            $notification_status = 'danger';
                                            $notification_status_message = 'Off';
                                        endif;
                                        ?>
                                        <td>
                                            <div>
                                                <i class="fa fa-envelope-o"></i> Notifications enabled:
                                                <span class="onoffswitch">
														<input type="checkbox"
                                                               id="<?php echo $contact['Objects']['object_id'].$key; ?>NotificationsEnabled" <?php echo ($contact['Contact'][$key.'_notifications_enabled']) ? ' checked="checked" ' : ''; ?>
                                                               class="onoffswitch-checkbox" name="onoffswitch">
														<label for="<?php echo $contact['Objects']['object_id'].$key; ?>NotificationsEnabled"
                                                               class="onoffswitch-label">
															<span data-swchoff-text="Off" data-swchon-text="On"
                                                                  class="onoffswitch-inner"></span>
															<span class="onoffswitch-switch"></span>
														</label>
													</span>
                                            </div>
                                            <div>
                                                <i class="fa fa-clock-o"></i> Timeperiod:
                                                <a href="#"><?php echo '24x7'; ?></a>
                                            </div>
                                            <div>
                                                <i class="fa fa-terminal"></i> Command:
                                                <a href="#"><?php echo 'notify-by-mail'; ?></a>
                                            </div>
                                            <div style="margin-top:10px;">
                                                <?php foreach ($notification_settings_arr as $notification_setting): ?>
                                                    <?php echo (($contact['Contact'][$notification_setting]) ? '<i class="fa fa-check txt-color-green"></i>' : '<i class="fa fa-times txt-color-red"></i>').' '.$notification_setting; ?>
                                                    <br/>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                    <?php endforeach; ?>
                                    <td class="text-center">
                                        <a href="#"
                                           data-original-title="<?php echo __('Contact notifications (Host)'); ?>"
                                           data-placement="bottom" rel="tooltip" data-container="body"><i
                                                    class="fa fa-desktop fa-lg"></i></a>
                                        <a href="#"
                                           data-original-title="<?php echo __('Contact notifications (Services)'); ?>"
                                           data-placement="bottom" rel="tooltip" data-container="body"><i
                                                    class="fa fa-gears fa-lg"></i></a>
                                    </td>
                                    <td>
                                        <center>
                                            <a href="/<?php echo $this->params['plugin']; ?>/<?php echo $this->params['controller']; ?>/edit/<?php echo $contact['Contact']['contact_object_id']; ?>"><i
                                                        id="list_edit" class="fa fa-gear fa-lg txt-color-teal"></i></a>
                                        </center>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php if (empty($all_contacts)): ?>
                            <div class="noMatch">
                                <center>
                                    <span class="txt-color-red italic"><?php echo __('search.noVal'); ?></span>
                                </center>
                            </div>
                        <?php endif; ?>

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