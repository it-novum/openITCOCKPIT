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

echo $this->Html->css('vendor/vis-4.21.0/dist/vis.min.css', ['inline' => false]);
echo $this->Html->css('vendor/css3-percentage-loader/circle.css', ['inline' => false]);


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
<div class="row padding-bottom-10">
    <div class="col col-xs-12 no-padding">
        <select
                id="Instance"
                data-placeholder="<?php echo __('Filter by instance'); ?>"
                class="form-control"
                chosen="{}"
                ng-model="filter.Host.satellite_id"
                ng-model-options="{debounce: 500}">
            <?php
            foreach ($satellites as $satelliteId => $satelliteName):
                printf('<option value="%s">%s</option>', h($satelliteId), h($satelliteName));
            endforeach;
            ?>
        </select>
    </div>
</div>
<div class="jarviswidget" id="wid-id-0">
    <header>
        <h2>
            <span class="no-padding">
                <i class="fa fa-check-circle no-padding up"></i>
                <em class="padding-right-10">
                    <?php
                    echo __('Up');
                    ?>
                </em>
                <i class="fa fa-exclamation-circle no-padding down"></i>
                <em class="padding-right-10">
                    <?php
                    echo __('Down');
                    ?>
                </em>
                <i class="fa fa-question-circle no-padding unreachable"></i>
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
            <div class="pull-right padding-left-10">
                <div class="form-group smart-form no-padding">
                    <label class="checkbox small-checkbox-label">
                        <input type="checkbox" name="checkbox" checked="checked"
                               ng-model="filter.showAll"
                               ng-model-options="{debounce: 500}"
                               ng-true-value="false"
                               ng-false-value="true">
                        <i class="checkbox-primary"></i>
                        <?php
                        echo __('Consider parent child relations');
                        ?>
                    </label>
                </div>
            </div>
            <div class="pull-right padding-left-10">
                <div class="form-group smart-form no-padding">
                    <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                        <input type="text" class="input-sm"
                               placeholder="<?php echo __('Filter by IP address'); ?>"
                               ng-model="filter.Host.address"
                               ng-model-options="{debounce: 500}">
                    </label>
                </div>
            </div>
            <div class="pull-right padding-left-10">
                <div class="form-group smart-form no-padding">
                    <label class="input"> <i class="icon-prepend fa fa-desktop"></i>
                        <input type="text" class="input-sm"
                               placeholder="<?php echo __('Filter by host name'); ?>"
                               ng-model="filter.Host.name"
                               ng-model-options="{debounce: 500}">
                    </label>
                </div>
            </div>
        </h2>
        <div class="widget-toolbar" role="menu">
            <?php echo $this->Utils->backButton(); ?>
        </div>
    </header>
    <div ng-if="isEmpty">
        <center>
            <span class="txt-color-red italic"><?php echo __('No entries match the selection'); ?></span>
        </center>
    </div>
    <div class="widget-body" id="statusmap">
    </div>
</div>
<div id="statusmap-progress-icon">
    <center>
        <div>
            <?php echo __('Loading visualization for '); ?>
        </div>
        <div>
            {{nodesCount}} <?php echo __(' nodes'); ?>
            <span class="statusmap-progress-dots"></span>
        </div>
    </center>
    <div class="progress" data-progress="0">
        <div class="progress_mask isFull">
            <div class="progress_fill"></div>
        </div>
        <div class="progress_mask">
            <div class="progress_fill"></div>
        </div>
    </div>
</div>