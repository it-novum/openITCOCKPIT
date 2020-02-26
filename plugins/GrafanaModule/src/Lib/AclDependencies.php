<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is licensed under the terms of the openITCOCKPIT Enterprise Edition license agreement.
// The license agreement and license key were sent with the order confirmation.

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
            ->allow('GrafanaUserdashboards', 'grafanaWidget')
            ->allow('GrafanaUserdashboards', 'grafanaTimepicker');

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
            ->dependency('GrafanaUserdashboards', 'editor', 'GrafanaUserdashboards', 'removeMetricFromPanel')
            ->dependency('GrafanaUserdashboards', 'editor', 'GrafanaUserdashboards', 'addPanel')
            ->dependency('GrafanaUserdashboards', 'editor', 'GrafanaUserdashboards', 'removePanel')
            ->dependency('GrafanaUserdashboards', 'editor', 'GrafanaUserdashboards', 'addRow')
            ->dependency('GrafanaUserdashboards', 'editor', 'GrafanaUserdashboards', 'removeRow')
            ->dependency('GrafanaUserdashboards', 'editor', 'GrafanaUserdashboards', 'savePanelUnit')
            ->dependency('GrafanaUserdashboards', 'editor', 'GrafanaUserdashboards', 'synchronizeWithGrafana');
    }
}
