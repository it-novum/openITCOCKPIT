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

namespace GrafanaModule\Lib;


use App\Lib\PluginAclDependencies;

class AclDependencies extends PluginAclDependencies {

    public function __construct() {
        parent::__construct();

        // Add actions that should always be allowed.
        $this
            ->allow('GrafanaConfiguration', 'grafanaWidget')
            ->allow('GrafanaConfiguration', 'getGrafanaDashboards');

        $this
            ->allow('GrafanaUserdashboards', 'grafanaRow')
            ->allow('GrafanaUserdashboards', 'grafanaPanel')
            ->allow('GrafanaUserdashboards', 'getPerformanceDataMetrics')
            ->allow('GrafanaUserdashboards', 'grafanaWidget');

        ///////////////////////////////
        //    Add dependencies       //
        //////////////////////////////

        $this
            ->dependency('GrafanaConfiguration', 'index', 'GrafanaConfiguration', 'loadHostgroups');

        $this
            ->dependency('GrafanaUserdashboards', 'add', 'GrafanaUserdashboards', 'loadContainers')
            ->dependency('GrafanaUserdashboards', 'edit', 'GrafanaUserdashboards', 'loadContainers')
            ->dependency('GrafanaUserdashboards', 'view', 'GrafanaUserdashboards', 'getViewIframeUrl')
            ->dependency('GrafanaUserdashboards', 'editor', 'GrafanaUserdashboards', 'addMetricToPanel')
            ->dependency('GrafanaUserdashboards', 'editor', 'GrafanaUserdashboards', 'editMetricToPanel')
            ->dependency('GrafanaUserdashboards', 'editor', 'GrafanaUserdashboards', 'removeMetricFromPanel')
            ->dependency('GrafanaUserdashboards', 'editor', 'GrafanaUserdashboards', 'addPanel')
            ->dependency('GrafanaUserdashboards', 'editor', 'GrafanaUserdashboards', 'removePanel')
            ->dependency('GrafanaUserdashboards', 'editor', 'GrafanaUserdashboards', 'addRow')
            ->dependency('GrafanaUserdashboards', 'editor', 'GrafanaUserdashboards', 'removeRow')
            ->dependency('GrafanaUserdashboards', 'editor', 'GrafanaUserdashboards', 'savePanelUnit')
            ->dependency('GrafanaUserdashboards', 'editor', 'GrafanaUserdashboards', 'synchronizeWithGrafana');
    }
}
