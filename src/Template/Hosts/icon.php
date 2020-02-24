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
<a href="javascript:void(0);" class="btn btn-{{btnColor}} status-circle" ng-if="!isFlapping"></a>
<span class="flapping_airport stateClass" ng-if="isFlapping">
    <i class="{{flappingColor}} fa" ng-class="flappingState === 1 ? 'fa-circle' : 'fa-circle-o'"></i>
    <i class="{{flappingColor}} fa" ng-class="flappingState === 0 ? 'fa-circle' : 'fa-circle-o'"></i>
</span>
