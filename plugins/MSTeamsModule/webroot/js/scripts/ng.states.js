openITCOCKPIT.config(function($stateProvider){
    $stateProvider
        .state('MSTeamsSettingsIndex', {
            url: '/msteams_module/msteamssettings/index',
            templateUrl: "/msteams_module/MSTeamsSettings/index.html",
            controller: "MSTeamsSettingsIndexController"
        })
});
