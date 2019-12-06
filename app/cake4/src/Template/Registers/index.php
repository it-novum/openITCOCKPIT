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
            <?php echo __('openITCOCKPIT'); ?>
            <span>>
                <?php echo __('Registration'); ?>
            </span>
        </h1>
    </div>
</div>


<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-check-square-o"></i> </span>
        <h2>
            <?php echo __('Register this openITCOCKPIT instance'); ?>
        </h2>

        <div class="widget-toolbar" role="menu">
            <button type="button" class="btn btn-default btn-xs" ng-click="toggleFullscreenMode();">
                <i class="fa fa-heart"></i>
                <?php echo __('Credits'); ?>
            </button>
        </div>

    </header>
    <div class="widget-body">
        <form ng-submit="submit();" class="form-horizontal">
            <div class="row">
                <div class="col-xs-12 col-md-12 col-lg-8">
                    <div class="form-group required" ng-class="{'has-error': errors.license}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('License key'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                <?php if ($disableAutocomplete): ?>
                                    autocomplete="off"
                                <?php endif; ?>
                                    ng-model="post.Registers.license">
                            <div ng-repeat="error in errors.license">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block">
                                <a href="https://openitcockpit.io/#Subscription" target="_blank">
                                    <i class="fa fa-external-link-square"></i>
                                    <?php echo __('Get your openITCOCKPIT Enterprise Subscription today'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" ng-show="valid">
                <div class="col-xs-12 col-md-12 col-lg-12">

                    <div class="row" ng-show="hasLicense">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6" style="background-image: url('/img/certs/{{certImage}}'); min-height: 667px; background-repeat: no-repeat; background-size: 100%;">

                            <dl class="dl-horizontal" style="padding-top: 30%;">
                                <dt><?php echo __('First name'); ?>:</dt>
                                <dd class="code-font text-info">{{license.firstname}}</dd>

                                <dt><?php echo __('Last name'); ?>:</dt>
                                <dd class="code-font text-info">{{license.lastname}}</dd>

                                <dt><?php echo __('Email'); ?>:</dt>
                                <dd class="code-font text-info">{{license.email}}</dd>

                                <dt><?php echo __('Company'); ?>:</dt>
                                <dd class="code-font text-info">{{license.company}}</dd>

                                <dt><?php echo __('Expires'); ?>:</dt>
                                <dd class="code-font text-info">{{license.expire}}</dd>

                                <dt><?php echo __('License key'); ?>:</dt>
                                <dd class="code-font text-info">{{license.licence}}</dd>
                            </dl>

                        </div>
                    </div>

                </div>
            </div>

            <div class="row">

                <div class="col-xs-12 margin-top-10">
                    <div class="well formactions ">
                        <div class="pull-right">
                            <input class="btn btn-primary" type="submit" value="<?php echo __('Register'); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="credits-container" id="credits-container" style="display:none;">
    <div id="credits">
        <h1 class="credits-h1">openITCOCKPIT</h1>
        <br/>

        <h2 class="credits-h2">Developers</h2>
        <dl>
            <dd>Irina Bering</dd>
            <dd>Daniel Ziegler</dd>
            <dd>Maximilian Pappert</dd>
            <dd>Timo Triebensky</dd>
        </dl>


        <h2 class="credits-h2">Special Thanks</h2>
        <p>Stephan Kraus, Oliver Müller, Laziz Karimov, Robert Braun,<br/>
            René Kleffel, Michael Ziegler, Jonas Rottmann,<br/>
            Johannes Drummer, Jens Michelsons, Jeremy Eder and more...</p>

        <h2 class="credits-h2">Special Thanks</h2>
        <p>Thanks to all the developers for the beautiful libraries.</p>

        <h2 class="credits-h2">Powered by</h2>
        <p>
            <img src="/img/logos/3rd/php.png">
        </p>

        <p>
            <img src="/img/logos/3rd/We-bake-with-CakePHP.png" style="width: 300px;">
        </p>

        <p>
            <img src="/img/logos/3rd/jquery.png">
        </p>

        <p>
            <img src="/img/logos/3rd/jquery_ui.png" >
        </p>

        <p>
            <img src="/img/logos/3rd/AngularJS-medium.png">
        </p>

        <p class="padding-top-80">
            <img src="/img/logos/3rd/Statusengine_dark.png">
        </p>

        <div class="credits-fineprint">All trademarks are the property of the trademark owners.</div>

        <br/>
        <br/>
        <h2 class="credits-h2">Sponsored by it-novum</h2>
        <p>
            <img src="/img/logos/it-novum.png">
        </p>

        <br/>
        <br/>
        <br/>
        <p id="credits-oitc-logo">
            <img src="/img/logos/openITCOCKPIT_dark.png" style="width: 600px;">
        </p>

        <br />
        <br />
        <div class="credits-fineprint">Press ESC to exit</div>

    </div>
</div>
