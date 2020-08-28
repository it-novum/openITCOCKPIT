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
        <i class="fas fa-magic"></i> <?php echo __('Wizards'); ?>
    </li>
</ol>


<div class="row">
    <div class="col-xl-12">
        <div id="widget-container" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Configuration Wizards'); ?>
                </h2>
                <div class="panel-toolbar">
                    <div class="btn-group btn-group-xs" data-toggle="buttons">
                        <label class="btn btn-default">
                            <input class="invisible" type="checkbox" name="season_revenue_1"
                                   id="season_revenue_1" value="linux">
                            <i class="fab fa-linux"></i> <?= ('Linux'); ?>
                        </label>
                        <label class="btn btn-default">
                            <input class="invisible" type="checkbox" name="season_revenue_2" id="season_revenue_2"
                                   value="windows">
                            <i class="fab fa-windows"></i> <?= ('Windows'); ?>
                        </label>
                        <label class="btn btn-default">
                            <input class="invisible" type="checkbox" name="season_revenue_3" id="season_revenue_3"
                                   value="database">
                            <i class="fas fa-database"></i> <?= ('Database'); ?>
                        </label>
                        <label class="btn btn-default">
                            <input class="invisible" type="checkbox" name="season_revenue_4" id="season_revenue_4"
                                   value="mail">
                            <i class="fas fa-mail-bulk"></i> <?= ('Email'); ?>
                        </label>
                        <label class="btn btn-default">
                            <input class="invisible" type="checkbox" name="season_revenue_5" id="season_revenue_5"
                                   value="docker">
                            <i class="fab fa-docker"></i> <?= ('Docker'); ?>
                        </label>
                        <label class="btn btn-danger">
                            <i class="fas fa-undo"></i> <?= ('Reset'); ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="row">
                        <div class="col-3">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <a href="javascript:void(0);" class="d-flex flex-row align-items-center">
                                        <div class="icon-stack display-3 flex-shrink-0">
                                            <i class="far fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                            <i class="fas fa-graduation-cap icon-stack-1x opacity-100 color-primary-500"></i>
                                        </div>
                                        <div class="ml-3">
                                            <strong>
                                                Add Qualifications
                                            </strong>
                                            <br>
                                            Adding qualifications will help gain more clients
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <a href="javascript:void(0);" class="d-flex flex-row align-items-center">
                                        <div class="icon-stack display-3 flex-shrink-0">
                                            <i class="far fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                            <i class="fas fa-graduation-cap icon-stack-1x opacity-100 color-primary-500"></i>
                                        </div>
                                        <div class="ml-3">
                                            <strong>
                                                Add Qualifications
                                            </strong>
                                            <br>
                                            Adding qualifications will help gain more clients
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <a href="javascript:void(0);" class="d-flex flex-row align-items-center">
                                        <div class="icon-stack display-3 flex-shrink-0">
                                            <i class="far fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                            <i class="fas fa-graduation-cap icon-stack-1x opacity-100 color-primary-500"></i>
                                        </div>
                                        <div class="ml-3">
                                            <strong>
                                                Add Qualifications
                                            </strong>
                                            <br>
                                            Adding qualifications will help gain more clients
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <a href="javascript:void(0);" class="d-flex flex-row align-items-center">
                                        <div class="icon-stack display-3 flex-shrink-0">
                                            <i class="far fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                            <i class="fas fa-graduation-cap icon-stack-1x opacity-100 color-primary-500"></i>
                                        </div>
                                        <div class="ml-3">
                                            <strong>
                                                Add Qualifications
                                            </strong>
                                            <br>
                                            Adding qualifications will help gain more clients
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <a href="javascript:void(0);" class="d-flex flex-row align-items-center">
                                        <div class="icon-stack display-3 flex-shrink-0">
                                            <i class="far fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                            <i class="fas fa-graduation-cap icon-stack-1x opacity-100 color-primary-500"></i>
                                        </div>
                                        <div class="ml-3">
                                            <strong>
                                                Add Qualifications
                                            </strong>
                                            <br>
                                            Adding qualifications will help gain more clients
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <a href="javascript:void(0);" class="d-flex flex-row align-items-center">
                                        <div class="icon-stack display-3 flex-shrink-0">
                                            <i class="far fa-circle icon-stack-3x opacity-100 color-primary-400"></i>
                                            <i class="fas fa-graduation-cap icon-stack-1x opacity-100 color-primary-500"></i>
                                        </div>
                                        <div class="ml-3">
                                            <strong>
                                                Add Qualifications
                                            </strong>
                                            <br>
                                            Adding qualifications will help gain more clients
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>












