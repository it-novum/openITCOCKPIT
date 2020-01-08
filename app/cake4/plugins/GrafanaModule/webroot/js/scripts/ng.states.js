openITCOCKPIT.config(function($stateProvider){
    $stateProvider

        .state('GrafanaUserdashboardsIndex', {
            url: '/grafana_module/grafana_userdashboards/index',
            templateUrl: "/grafana_module/grafana_userdashboards/index.html",
            controller: "Grafana_userdashboardsIndexController"
        })

        .state('GrafanaUserdashboardsAdd', {
            url: '/grafana_module/grafana_userdashboards/add',
            templateUrl: "/grafana_module/grafana_userdashboards/add.html",
            controller: "Grafana_userdashboardsAddController"
        })

        .state('GrafanaUserdashboardsEditor', {
            url: '/grafana_module/grafana_userdashboards/editor/:id',
            templateUrl: "/grafana_module/grafana_userdashboards/editor.html",
            controller: "Grafana_userdashboardsEditorController"
        })

        .state('GrafanaUserdashboardsEdit', {
            url: '/grafana_module/grafana_userdashboards/edit/:id',
            templateUrl: "/grafana_module/grafana_userdashboards/edit.html",
            controller: "Grafana_userdashboardsEditController"
        })

        .state('GrafanaUserdashboardsView', {
            url: '/grafana_module/grafana_userdashboards/view/:id',
            templateUrl: "/grafana_module/grafana_userdashboards/view.html",
            controller: "Grafana_userdashboardsViewController"
        })

        .state('GrafanaConfigurationIndex', {
            url: '/grafana_module/grafana_configuration/index',
            templateUrl: "/grafana_module/grafana_configuration/index.html",
            controller: "Grafana_configurationIndexController"
        })
});
