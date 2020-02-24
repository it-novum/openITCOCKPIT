openITCOCKPIT.config(function($stateProvider){
    $stateProvider

        .state('MattermostSettingsIndex', {
            url: '/mattermost_module/MattermostSettings/index',
            templateUrl: "/mattermost_module/MattermostSettings/index.html",
            controller: "MattermostSettingsIndexController"
        })

});
