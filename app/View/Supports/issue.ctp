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
            <i class="fa fa-bug fa-fw "></i>
            <?php echo __('Support'); ?>
            <span>>
                <?php echo __('Report an issue'); ?>
            </span>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-bug"></i> </span>
        <h2><?php echo __('Report an issue'); ?></h2>
    </header>
    <div>
        <div class="widget-body">
            <div class="row">

                <?php if ($hasLicense): ?>
                    <div class="col-xs-12 col-sm-6 col-md-3">
                        <div class="panel panel-darken pricing-big">

                            <div class="panel-heading">
                                <h3 class="panel-title text-transform-none">
                                    Commercial support
                                </h3>
                            </div>
                            <div class="panel-body no-padding text-align-center">
                                <div class="the-price">
                                    <span class="subscript">it-novum GmbH</span>
                                </div>
                                <div class="price-features">
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
                            </div>
                            <div class="panel-footer text-align-center">
                                <a href="mailto:openitcockpit@support.it-novum.com" class="btn btn-primary btn-block"
                                   role="button">
                                    Create a Ticket
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="panel panel-primary pricing-big">

                        <div class="panel-heading">
                            <h3 class="panel-title text-transform-none">
                                IRC #openITCOCKPIT
                            </h3>
                        </div>
                        <div class="panel-body no-padding text-align-center">
                            <div class="the-price">
                                <span class="subscript">Community</span>
                            </div>
                            <div class="price-features">
                                <ul class="list-unstyled text-left">
                                    <li><i class="fa fa-minus text-muted"></i> General questions about openITCOCKPIT
                                    </li>
                                    <li><i class="fa fa-minus text-muted"></i> System issues</li>
                                    <li><i class="fa fa-minus text-muted"></i> System crashes</li>
                                    <li><i class="fa fa-minus text-muted"></i> Unwanted behavior or bugs</li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-footer text-align-center">
                            <a href="http://webchat.freenode.net/?channels=openitcockpit"
                               class="btn btn-primary btn-block" target="_blank"
                               role="button">
                                Join channel
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="panel panel-purple pricing-big">

                        <div class="panel-heading">
                            <h3 class="panel-title text-transform-none">
                                JIRA or GitHub
                            </h3>
                        </div>
                        <div class="panel-body no-padding text-align-center">
                            <div class="the-price">
                                <span class="subscript">Developers</span>
                            </div>
                            <div class="price-features">
                                <ul class="list-unstyled text-left">
                                    <li><i class="fa fa-minus text-muted"></i> Unwanted behavior or bugs</li>
                                    <li><i class="fa fa-minus text-muted"></i> Errors with packages for your
                                        distribution
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-footer text-align-center">
                            <div class="row no-padding">
                                <div class="col-xs-12 col-md-6">
                                    <button
                                            class="btn btn-primary btn-block" id="JIRAIssue" role="button"
                                            type="button">
                                        Create a Ticket
                                    </button>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <a href="https://github.com/it-novum/openITCOCKPIT/issues" target="_blank"
                                       class="btn btn-default btn-block" role="button">
                                        <i class="fa fa-github"></i> Create an issue
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
