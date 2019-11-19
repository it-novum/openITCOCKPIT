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

<div ng-show="parentOutages.length == 0 && filter.Host.name == ''" class="text-center text-success">
    <h5 class="padding-top-50">
        <i class="fa fa-check"></i>
        <?php echo __('Currently are no network segment issues'); ?>
    </h5>
</div>

<div class="row" ng-show="parentOutages.length > 0 || filter.Host.name">
    <div class="col-xs-12 padding-0">
        <div class="form-group smart-form">
            <label class="input"> <i class="icon-prepend fa fa-filter"></i>
                <input class="input-sm"
                       placeholder="<?php echo __('Filter by host name'); ?>"
                       ng-model="filter.Host.name"
                       ng-model-options="{debounce: 500}"
                       type="text">
            </label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 padding-0">
        <table class="table table-striped table-hover table-bordered">
            <tbody>
            <tr ng-repeat="outage in parentOutages">
                <td class="padding-5">
                    <?php if ($this->Acl->hasPermission('browser', 'hosts')): ?>
                        <a href="/ng/#!/hosts/browser/{{ outage.Host.id }}">
                            {{ outage.Host.name }}
                        </a>
                    <?php else: ?>
                        {{ outage.Host.name }}
                    <?php endif; ?>
                </td>
            </tr>
            </tbody>
        </table>


        <div class="col-xs-12 text-center txt-color-red italic"
             ng-show="parentOutages.length == 0 && filter.Host.name != ''">
            <?php echo __('No entries match the selection'); ?>
        </div>

    </div>
</div>


