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
<div class="btn-group mr-2" role="group" aria-label="<?= __('Display of server and client times'); ?>">

    <button class="btn btn-secondary" data-original-title="<?= __('local time of client'); ?>"
            data-placement="bottom" rel="tooltip" data-container="body" ng-if="showClientTime">
        {{ currentClientTime }}
    </button>
    <button class="btn btn-default " data-original-title="<?= __('display time information'); ?>"
            data-placement="bottom"
            rel="tooltip" data-container="body"><i class="fas fa-clock"></i></button>
    <button class="btn btn-primary" data-original-title="<?= __('local time of server'); ?>" data-placement="bottom"
            rel="tooltip" data-container="body">
        {{ currentServerTime }}
    </button>
</div>
