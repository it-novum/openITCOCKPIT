<?php
// Copyright (C) <2018>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, version 3 of the License.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//  If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//  under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//  License agreement and license key will be shipped with the order
//  confirmation.

use itnovum\openITCOCKPIT\ConfigGenerator\ConfigInterface;

/** @var ConfigInterface $ConfigFileObject */

?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-file-text-o fa-fw "></i>
            <?php echo __('Edit configuration file'); ?>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="alert alert-info">
            <i class="fa-fw fa fa-info"></i>
            <?php echo __('To apply configuration changes, please "Refresh monitoring configuration".'); ?>
        </div>
    </div>
</div>
<div id="error_msg"></div>

<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-file-text-o"></i> </span>
        <h2>
            <?php echo __('Edit configuration file'); ?>
            <?php echo $ConfigFileObject->getOutfile(); ?>
        </h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div>
        <div class="widget-body">

            <?php
            //Load the AngularJs Directive for the given configuration file
            printf('<%s></%s>', $ConfigFileObject->getAngularDirective(), $ConfigFileObject->getAngularDirective());
            ?>

        </div>
    </div>
</div>

