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
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-calendar-o"></i>
            <?php echo __('Calendar'); ?>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="jarviswidget" id="wid-id-0">
            <header>
                <span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
                <h2><?php echo __('Add') . ' ' . __('Calendar'); ?></h2>
                <div class="widget-toolbar" role="menu">
                    <?php echo $this->Utils->backButton(); ?>
                </div>
            </header>
            <div>
                <div class="widget-body">
                    <?php
                    echo $this->Form->create('Calendar', ['class' => 'form-horizontal clear']);
                    echo $this->Form->input('Calendar.name', ['label' => h(__('Name'))]);
                    echo $this->Form->input('Calendar.description', ['label' => h(__('Description'))]);
                    echo $this->Form->input('Calendar.container_id', [
                        'options'  => $tenants,
                        'multiple' => false,
                        'class'    => 'chosen',
                        'style'    => 'width:100%;',
                        'label'    => __('Tenant'),
                    ]); ?>
                    <br/>
                    <div class="widget-body calendarsize padding-top-20">
                        <!-- content goes here -->
                        <div id="calendar-buttons" class="hidden">
                            <div class="btn-group padding-right-10">
                                <button class="dropdown-toggle fc-button fc-state-default fc-corner-left fc-corner-right"
                                        data-toggle="dropdown">
                                    <?php echo __('Holidays'); ?> <i class="fa fa-caret-down"></i>
                                </button>
                                <ul class="dropdown-menu js-status-update pull-right">
                                    <li>
                                        <a href="#" id="de"><?php echo __('Germany'); ?></a>
                                    </li>
                                    <li>
                                        <a href="#" id="at"><?php echo __('Austria'); ?></a>
                                    </li>
                                    <li>
                                        <a href="#"
                                           id="removeDefaultHolidays"><?php echo __('Remove all Holidays'); ?></a>
                                    </li>
                                </ul>
                            </div>
                            <div class="btn-group padding-right-10">
                                <button id="btn-delete-month-events"
                                        class="fc-button fc-state-default fc-corner-left fc-corner-right" type="button"
                                        title="<?php echo __('Delete all events of the current month.'); ?>"><i
                                            class="fa fa-calendar-o"></i> <?php echo __('Delete month'); ?></button>
                            </div>
                            <div class="btn-group padding-right-10">
                                <button id="btn-delete-all-events"
                                        class="fc-button fc-state-default fc-corner-left fc-corner-right" type="button"
                                        title="<?php echo __('Delete all events of in this calendar.'); ?>"><i
                                            class="fa fa-calendar-o"></i> <?php echo __('Delete all events'); ?>
                                </button>
                            </div>
                        </div>
                        <div id="calendar"></div>
                    </div>
                    <!-- end content -->
                </div>
                <?php echo $this->Form->formActions(); ?>
            </div>
        </div>
    </div>
</div>
