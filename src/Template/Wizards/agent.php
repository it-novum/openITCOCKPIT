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
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="WizardsIndex">
            <i class="fas fa-magic"></i> <?php echo __('Wizards'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fas fa-magic"></i> <?php echo __('Monitor Linux Server with Agent'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Configuration Wizard: Linux Server'); ?>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content fuelux">

                    <form ng-submit="submit();" class="form-horizontal">

                        <div class="wizard">
                            <ul class="nav nav-tabs step-anchor">
                                <li class="active">
                                    <span class="badge badge-info">1</span><?php echo __('Linux Server Information'); ?>
                                    <span class="chevron"></span>
                                </li>
                                <li>
                                    <span class="badge">2</span><?php echo __('Basic agent configuration '); ?>
                                    <span class="chevron"></span>
                                </li>
                            </ul>
                            <div class="pull-right margin-right-5" style="margin-top: -39px;">
                                <div class="actions" style="position: relative; display: inline;">
                                    <button type="submit" class="btn btn-success btn-sm waves-effect waves-themed">
                                        <?php echo __('Next'); ?> <i class="fa fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>


                        <div class="step-content">
                            <wizard-host-configuration></wizard-host-configuration>






                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>














