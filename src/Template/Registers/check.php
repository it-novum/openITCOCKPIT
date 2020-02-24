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
            <i class="fa fa-check-square-o fa-fw "></i>
            <?php echo __('System'); ?>
            <span>>
                <?php echo __('Registration'); ?>
			</span>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-check-square-o"></i> </span>
        <h2><?php echo __('Try to connect to openITCOCKPIT license service...'); ?></h2>
        <div class="widget-toolbar" role="menu">
            <?php
            if (!$isValide):
                echo $this->Utils->backButton(__('Back to license agreement'), ['action' => 'index']);
            endif; ?>
            <a class="btn btn-default btn-xs" href="javascript:void(0);" name="creditos"><i
                        class="fa fa-users"></i> <?php echo __('Credits'); ?></a>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <?php if ($isValide === true): ?>
                <div class="alert alert-success alert-block">
                    <a href="#" data-dismiss="alert" class="close">×</a>
                    <h1 class="alert-heading" style="color:#356635"><?php echo __('Success'); ?></h1>
                    <h4><?php echo __('The entered license key is valid'); ?></h4>
                </div>
                <div class="paddint-top-20">
                    <h2><?php echo __('Your license is registered to:'); ?></h2>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-1">
                        <strong><?php echo __('First name'); ?>:</strong>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?php echo $licence->firstname; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-1">
                        <strong><?php echo __('Last name'); ?>:</strong>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?php echo $licence->lastname; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-1">
                        <strong><?php echo __('Email'); ?>:</strong>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?php echo $licence->email; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-1">
                        <strong><?php echo __('Company'); ?>:</strong>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?php echo $licence->company; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-1">
                        <strong><?php echo __('Expires'); ?>:</strong>
                    </div>
                    <div class="col-xs-12 col-md-3 text-primary">
                        <?php echo $this->Time->format($licence->expire, $this->Auth->user('dateformat'), false, $this->Auth->user('timezone')); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-1">
                        <strong><?php echo __('Your license key'); ?>:</strong>
                    </div>
                    <div class="col-xs-12 col-md-3 text-primary">
                        <?php echo $licence->licence; ?>
                    </div>
                </div>
            <?php else: ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-block">
                        <a href="#" data-dismiss="alert" class="close">×</a>
                        <h4 class="alert-heading"><?php echo __('Error No.:') . $error['errno']; ?></h4>
                        <?php echo $error['error']; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger alert-block">
                        <a href="#" data-dismiss="alert" class="close">×</a>
                        <h4 class="alert-heading"><?php echo __('Error'); ?></h4>
                        <?php echo __('The entered license key is not valid'); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>