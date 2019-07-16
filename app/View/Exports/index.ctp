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
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-retweet fa-fw "></i>
            <?php echo __('Administration') ?>
            <span>>
                <?php echo __('refresh monitoring configuration'); ?>
            </span>
        </h1>
    </div>
</div>
<?php if (!$gearmanReachable): ?>
    <div id="error_msg">
        <div class="alert alert-danger alert-block">
            <a href="#" data-dismiss="alert" class="close">×</a><h5 class="alert-heading"><i
                        class="fa fa-warning"></i> <?php echo __('Error'); ?>
            </h5><?php echo __('Could not connect to Gearman Job Server'); ?>
        </div>
    </div>
<?php endif; ?>
<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">
                        <?php echo $this->AdditionalLinks->renderAsLinks($additionalLinksTop); ?>
                    </div>
                    <div class="jarviswidget-ctrls" role="menu">
                    </div>
                    <span class="widget-icon"> <i class="fa fa-retweet"></i> </span>
                    <h2><?php echo __('Refresh monitoring configuration'); ?> </h2>
                </header>

                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body">
                        <div class="form-group ">
                            <label class="col col-md-3 control-label text-left" for="CreateBackup"><i
                                        class="fa fa-hdd-o"></i> <?php echo __('Create backup of current configuration?'); ?>
                            </label>
                            <div class="col col-md-9">
                                <div class="">
                                    <span class="onoffswitch">
                                            <input type="hidden" value="0" id="CreateBackup_"
                                                   name="data[Export][create_backup]">
                                                <input type="checkbox" id="CreateBackup" showlabel="1" value="1"
                                                       checked="checked"
                                                       class="onoffswitch-checkbox notification_control"
                                                       name="data[Export][create_backup]">
                                                <label class="onoffswitch-label" for="CreateBackup">
                                                    <span class="onoffswitch-inner"
                                                          data-swchon-text="<?php echo __('Yes'); ?>"
                                                          data-swchoff-text="<?php echo __('No'); ?>"></span>
                                                    <span class="onoffswitch-switch"></span>
                                                </label>
                                        </span>
                                </div>
                            </div>
                        </div>


                        <?php echo $this->AdditionalLinks->renderElements($additionalElementsForm); ?>

                        <div class="row">
                            <?php $style = 'display:none;'; ?>
                            <?php if ($exportRunning == true): ?>
                                <?php $style = ''; ?>
                            <?php endif; ?>
                            <div id="exportRunning" class="col-xs-12 padding-top-20" style="<?php echo $style; ?>">
                                <div class="alert alert-info alert-block">
                                    <h4 class="alert-heading"><i
                                                class="fa fa-info-circle"></i> <?php echo __('Refresh in progress'); ?>
                                    </h4>
                                    <?php echo __('You need to wait before the currently running refresh of the monitoring configuration is finished.'); ?>
                                </div>
                            </div>
                            <div id="exportSuccessfully" class="col-xs-12 padding-top-20" style="display:none;">
                                <div class="alert alert-success alert-block">
                                    <h4 class="alert-heading"><i class="fa fa-check"></i> <?php echo __('Success'); ?>
                                    </h4>
                                    <?php echo __('Refresh of monitoring configuration successfully done.'); ?>
                                </div>
                            </div>
                            <div id="exportError" class="col-xs-12 padding-top-20" style="display:none;">
                                <div class="alert alert-danger alert-block">
                                    <h4 class="alert-heading"><i class="fa fa-times"></i> <?php echo __('Error'); ?>
                                    </h4>
                                    <?php echo __('Error while refreshing your monitoring configuration'); ?>
                                </div>
                            </div>
                            <div id="verifyError" class="col-xs-12 padding-top-20" style="display:none;">
                                <div class="alert alert-danger alert-block">
                                    <h4 class="alert-heading"><i
                                                class="fa fa-times"></i> <?php echo __('Error - new configuration is not valid'); ?>
                                    </h4>
                                    &nbsp;
                                    <div class="well" id="verifyOutput"></div>
                                </div>
                            </div>
                            <div id="exportInfo" style="display:none;">
                                <div class="col-xs-12">
                                    <div>
                                        <h4><?php echo __('Additional information'); ?>:</h4>
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="well" id="exportLog"></div>
                                </div>
                            </div>


                            <?php if ($exportRunning === false): ?>
                                <div class="col-xs-12 padding-top-20">
                                    <div class="well formactions ">
                                        <div class="pull-right">
                                            <?php if ($gearmanReachable): ?>
                                                <a href="javascript:void(0);" id="launchExport"
                                                   class="btn btn-success"><?php echo __('Refresh configuration'); ?></a>
                                            <?php else: ?>
                                                <a href="javascript:void(0);"
                                                   class="btn btn-danger"><?php echo __('Connection error'); ?></a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
