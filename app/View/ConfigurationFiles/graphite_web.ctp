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


use itnovum\openITCOCKPIT\ConfigGenerator\GraphiteWeb;

/** @var GraphiteWeb $GraphiteWeb */
?>


<form ng-submit="submit();" class="form-horizontal">

    <div class="row">
        <?php foreach ($GraphiteWeb->getDefaults()['string'] as $key => $defaultValue): ?>

            <div class="form-group required" ng-class="{'has-error': errors.Configfile.<?php echo $key; ?>}">
                <label class="col col-md-2 control-label">
                    <?php echo h($key); ?>
                </label>
                <div class="col col-xs-10">
                    <input
                            class="form-control"
                            type="text"
                            ng-model="post.string.<?php echo $key; ?>">
                    <div ng-repeat="error in errors.Configfile.<?php echo $key; ?>">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($GraphiteWeb->getHelpText($key)); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row">
        <?php foreach ($GraphiteWeb->getDefaults()['int'] as $key => $defaultValue): ?>
            <div class="form-group required" ng-class="{'has-error': errors.Configfile.<?php echo $key; ?>}">
                <label class="col col-md-2 control-label">
                    <?php echo h($key); ?>
                </label>
                <div class="col col-xs-10">
                    <input
                            class="form-control"
                            type="number"
                            min="0"
                            ng-model="post.int.<?php echo $key; ?>">
                    <div ng-repeat="error in errors.Configfile.<?php echo $key; ?>">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($GraphiteWeb->getHelpText($key)); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row">
        <?php foreach ($GraphiteWeb->getDefaults()['bool'] as $key => $defaultValue): ?>
            <div class="form-group required" ng-class="{'has-error': errors.Configfile.<?php echo $key; ?>}">
                <label class="col col-md-2 control-label" for="<?php echo $key; ?>">
                    <?php echo h($key); ?>
                </label>
                <div class="col col-md-10 padding-top-7">
                    <input
                            type="checkbox"
                            id="<?php echo $key; ?>"
                            ng-false-value="0"
                            ng-true-value="1"
                            ng-model="post.bool.<?php echo $key; ?>">
                    <div ng-repeat="error in errors.Configfile.<?php echo $key; ?>">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>
                <div class="helpText text-muted col-md-offset-2 col-md-6">
                    <?php echo h($GraphiteWeb->getHelpText($key)); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>


    <div class="row">
        <div class="col-xs-12 margin-top-10">
            <div class="well formactions ">
                <div class="pull-right">
                    <input class="btn btn-primary" type="submit" value="<?php echo __('Save'); ?>">&nbsp;
                    <a ui-sref="ConfigurationFilesIndex" class="btn btn-default">
                        <?php echo __('Cancel'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
