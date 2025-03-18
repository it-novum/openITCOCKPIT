<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//     This program is free software: you can redistribute it and/or modify
//     it under the terms of the GNU General Public License as published by
//     the Free Software Foundation, version 3 of the License.
//
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//     If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//     under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//     License agreement and license key will be shipped with the order
//     confirmation.

namespace itnovum\openITCOCKPIT\Core\Dashboards;


interface ModuleWidgetsInterface {

    /**
     * ModuleWidgetsInterface constructor.
     * @param array $ACL_PERMISSIONS
     */
    public function __construct($ACL_PERMISSIONS);

    /**
     *
     *  If you add a new Widget, please also add it to the widgets.enum.ts in the Angular Frontend.
     *  Use a CamelCase name for the directive and the type_id as value like
     *  WelcomeWidget = 1
     *
     * @return array
     */
    public function getAvailableWidgets();

}
