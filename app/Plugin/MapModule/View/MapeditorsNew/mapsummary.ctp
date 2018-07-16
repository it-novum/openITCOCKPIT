<?php
// Copyright (C) <2015>  <it-novum GmbH>
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
?>
<div class="map-summary-state-popover col-xs-12">

    <div class="row" style="background:white;">
        <div class="col-md-12">
            <div class="col-md-4">
                <?php echo __('Hostname'); ?>
            </div>
            <div class="col-md-8">
                {{summaryState.Host.hostname}}
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-4">
                <?php echo __('Description'); ?>
            </div>
            <div class="col-md-8">
                {{summaryState.Host.description}}
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-4">
                <?php echo __('State (State Type)'); ?>
            </div>
            <div class="col-md-8 bg-up">
                {{summaryState.Hoststatus.currentState}}
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-4">
                <?php echo __('Output'); ?>
            </div>
            <div class="col-md-8">
                {{summaryState.Hoststatus.output}}
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-4">
                <?php echo __('Perfdata'); ?>
            </div>
            <div class="col-md-8">
                {{summaryState.Hoststatus.perfdata}}
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-4">
                <?php echo __('Current attempt'); ?>
            </div>
            <div class="col-md-8">
                {{summaryState.Hoststatus.current_check_attempt}}/{{summaryState.Hoststatus.max_check_attempts}}
            </div>
        </div>
    </div>
</div>

