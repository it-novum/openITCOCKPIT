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
            <i class="fa fa-globe fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Status Map'); ?>
            </span>
        </h1>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-sitemap"></i> </span>
        <h2>
            <?php echo __('Host status: '); ?>
            <span class="padding-left-20">
                <i class="fa fa-check-circle no-padding text-success"></i>
                <em class="padding-right-10">
                    <?php
                    echo __('Up');
                    ?>
                </em>
                <i class="fa fa-exclamation-circle no-padding text-danger"></i>
                <em class="padding-right-10">
                    <?php
                    echo __('Down');
                    ?>
                </em>
                <i class="fa fa-question-circle no-padding text-muted"></i>
                <em class="padding-right-10">
                    <?php
                    echo __('Unreachable');
                    ?>
                </em>
                <i class="fa fa-eye-slash no-padding text-primary"></i>
                <em class="padding-right-10">
                    <?php
                    echo __('Not monitored');
                    ?>
                </em>
                <i class="fa fa-plug no-padding text-primary"></i>
                <em class="padding-right-10">
                    <?php
                    echo __('Disabled');
                    ?>
                </em>
                |
                 <i class="fa fa-power-off no-padding txt-color-blueDark"></i>
                <em class="padding-right-10">
                    <?php
                    echo __('In downtime');
                    ?>
                </em>
                 <i class="fa fa-user no-padding txt-color-blueDark"></i>
                <em class="padding-right-10">
                    <?php
                    echo __('Acknowledged');
                    ?>
                </em>
                <i class="fa fa-user-md no-padding txt-color-blueDark"></i>
                <em class="padding-right-10">
                    <?php
                    echo __('Acknowledged and in downtime');
                    ?>
                </em>
            </span>
        </h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <div class="row">
            </div>
    </div>
    </div></div>