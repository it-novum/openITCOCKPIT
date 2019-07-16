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
            <i class="fa fa-pencil-square-o fa-fw "></i>
            <?php echo __('Administration'); ?>
            <span>>
                <?php echo __('System failure'); ?>
            </span>
            <div class="third_level"> <?php echo ucfirst($this->params['action']); ?></div>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('Add System failure'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(__('Back'), $back_url); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php
            echo $this->Form->create('Systemfailure', [
                'class' => 'form-horizontal clear',
            ]); ?>
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <?php
                    echo $this->Form->input('comment', [
                        'label'     => ['text' => __('Comment'), 'class' => 'col-xs-1 col-md-1 col-lg-1'],
                        'wrapInput' => 'col col-xs-10 col-md-10 col-lg-10',
                    ]);
                    ?>
                    <!-- from -->
                    <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('from_date'); ?>">
                        <label class="col col-md-1 control-label" for="SystemdowntimeFromDate"><?php echo __('From'); ?>
                            :</label>
                        <div class="col col-xs-3 col-md-3" style="padding-right: 0px;">
                            <input type="text" id="SystemdowntimeFromDate"
                                   value="<?php echo $this->CustomValidationErrors->refill('from_date', date('d.m.Y')); ?>"
                                   class="form-control" name="data[Systemfailure][from_date]">
                            <div>
                                <?php echo $this->CustomValidationErrors->errorHTML('from_date'); ?>
                            </div>
                        </div>
                        <div class="col col-xs-4 col-md-2 <?php echo $this->CustomValidationErrors->errorClass('from_time'); ?>"
                             style="padding-left: 0px;">
                            <input type="text" id="SystemdowntimeFromTime" value="<?php echo date('H:m'); ?>"
                                   class="form-control" name="data[Systemfailure][from_time]">
                            <div>
                                <?php echo $this->CustomValidationErrors->errorHTML('from_time'); ?>
                            </div>
                        </div>
                    </div>


                    <!-- to -->
                    <div class="form-group required <?php echo $this->CustomValidationErrors->errorClass('to_date'); ?>">
                        <label class="col col-md-1 control-label" for="SystemdowntimeToDate"><?php echo __('To'); ?>
                            :</label>
                        <div class="col col-xs-3 col-md-3" style="padding-right: 0px;">
                            <input type="text" id="SystemdowntimeToDate"
                                   value="<?php echo date('d.m.Y', strtotime('+3 days')); ?>" class="form-control"
                                   name="data[Systemfailure][to_date]">
                            <div>
                                <?php echo $this->CustomValidationErrors->errorHTML('to_date'); ?>
                            </div>
                        </div>
                        <div class="col col-xs-4 col-md-2 <?php echo $this->CustomValidationErrors->errorClass('to_time'); ?>"
                             style="padding-left: 0px;">
                            <input type="text" id="SystemdowntimeToTime" value="<?php echo date('H:m'); ?>"
                                   class="form-control" name="data[Systemfailure][to_time]">
                            <div>
                                <?php echo $this->CustomValidationErrors->errorHTML('to_time'); ?>
                            </div>
                        </div>
                    </div>
                    <?php //echo $this->Form->input('start_time', ['label' => __('From'), 'class' => 'chosen', 'style' => 'min-width: 65px;']); ?>
                    <?php //echo $this->Form->input('end_time', ['label' => __('To'), 'class' => 'chosen', 'style' => 'min-width: 65px;']); ?>

                </div>

            </div> <!-- close col -->
        </div> <!-- close row-->
        <br/>
        <?php echo $this->Form->formActions(); ?>
    </div> <!-- close widget body -->
</div>
</div> <!-- end jarviswidget -->
