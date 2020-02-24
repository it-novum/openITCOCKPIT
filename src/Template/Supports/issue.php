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
        <a ui-sref="SupportsIssue">
            <i class="fa fa-bug"></i> <?php echo __('Support'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fas fa-clipboard-list"></i> <?php echo __('Issue'); ?>
    </li>
</ol>


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Support'); ?>
                    <span class="fw-300"><i><?php echo __('Report an issue'); ?></i></span>
                </h2>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="card-deck">
                        <?php if ($hasLicense): ?>
                            <div class="card">
                                <h3 class="card-header reportIssueCardCommercialSupportHeader">
                                    Commercial support
                                </h3>
                                <h3 class="card-header reportIssueCardSubheader">
                                    it-novum GmbH
                                </h3>
                                <div class="card-body text-align-center">
                                    <ul class="list-unstyled text-left">
                                        <li><i class="fa fa-minus text-muted"></i> General questions about openITCOCKPIT
                                        </li>
                                        <li><i class="fa fa-minus text-muted"></i> System issues</li>
                                        <li><i class="fa fa-minus text-muted"></i> System crashes</li>
                                        <li><i class="fa fa-minus text-muted"></i> Unwanted behavior or bugs</li>
                                        <li><i class="fa fa-minus text-muted"></i> Errors with packages for your
                                            distribution
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-footer text-align-center">
                                    <a href="mailto:support@itsm.it-novum.com" class="btn btn-primary btn-block"
                                       role="button">
                                        <i class="far fa-envelope"></i> Create a Ticket
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="card">
                            <h3 class="card-header reportIssueCardCommunityHeader">
                                GitHub
                            </h3>
                            <h3 class="card-header reportIssueCardSubheader">
                                Community
                            </h3>
                            <div class="card-body text-align-center">
                                <ul class="list-unstyled text-left">
                                    <li><i class="fa fa-minus text-muted"></i> System issues</li>
                                    <li><i class="fa fa-minus text-muted"></i> System crashes</li>
                                    <li><i class="fa fa-minus text-muted"></i> Unwanted behavior or bugs</li>
                                    <li><i class="fa fa-minus text-muted"></i> Errors with packages for your
                                        distribution
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer text-align-center">
                                <a href="https://github.com/it-novum/openITCOCKPIT/issues" target="_blank"
                                   class="btn btn-default btn-block" role="button">
                                    <i class="fab fa-github"></i> Create an issue
                                </a>
                            </div>
                        </div>
                        <div class="card">
                            <h3 class="card-header reportIssueCardChatHeader">
                                Want to chat?
                            </h3>
                            <div class="card-body text-align-center">
                                <a href="http://webchat.freenode.net/?channels=openitcockpit" target="_blank">
                                    Join #openITCOCKPIT on freenode.
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
