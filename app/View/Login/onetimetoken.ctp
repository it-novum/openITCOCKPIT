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


<div class="login-screen"></div>
<div class="login-center">
    <div class="container min-height" style="padding-top: 20px;">
        <div class="row">
            <div class="col-xs-12">
                <?php echo $this->Flash->render(); ?>
                <?php echo $this->Flash->render('auth'); ?>
            </div>
        </div>

        <div class="row" style="padding-top: 20px;">
            <div class="col-xs-12 text-center">
                <h1><?php echo __('A One-time password was sent to your email address'); ?></h1>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-offset-4">

                <div class="login" id="card">
                    <div class="front signin_form">
                        <?php echo $this->Form->create('Onetimetoken', [
                            'class'         => 'login-form',
                            'inputDefaults' => [
                                'wrapInput' => false,
                                'label'     => false,
                                'div'       => false,
                            ],
                        ]); ?>

                        <?php echo $this->Form->hidden('id', ['value' => $user_id]); ?>

                        <div class="form-group">
                            <div class="input-group">
                                <?php echo $this->Form->input('onetimetoken', [
                                    'class'         => 'form-control',
                                    'placeholder'   => __('Type your One-time password'),
                                    'type'          => 'text',
                                    'inputDefaults' => [
                                        'wrapInput' => false,
                                        'label'     => false,
                                        'div'       => false,
                                    ]
                                ]); ?>
                                <span class="input-group-addon">
                                <i class="fa fa-lg fa-key"></i>
                            </span>
                            </div>
                        </div>
                        <div class="form-group sign-btn">
                            <button type="submit" class="btn btn-primary pull-right">
                                <?php echo __('Continue'); ?>
                            </button>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-9">
                <a href="https://openitcockpit.io/" target="_blank" class="btn btn-default">
                    <i class="fa fa-lg fa-globe"></i>
                </a>
                <a href="https://github.com/it-novum/openITCOCKPIT" target="_blank" class="btn btn-default">
                    <i class="fa fa-lg fa-github"></i>
                </a>
                <a href="https://twitter.com/openITCOCKPIT" target="_blank" class="btn btn-default">
                    <i class="fa fa-lg fa-twitter"></i>
                </a>
            </div>
            <div class="col-xs-12 col-md-3 text-right">
                Photo by
                <a class="credit"
                   href="https://unsplash.com/photos/GDdRP7U5ct0?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText">SpaceX</a>
                on
                <a class="credit"
                   href="https://unsplash.com/photos/GDdRP7U5ct0?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText">Unsplash</a>
            </div>
        </div>
    </div>
</div>

