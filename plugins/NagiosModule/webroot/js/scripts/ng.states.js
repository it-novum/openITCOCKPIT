openITCOCKPIT.config(function($stateProvider){
    $stateProvider

        .state('ExternalCommandsIndex', {
            url: '/nagios_module/cmd/index',
            templateUrl: "/nagios_module/cmd/index.html",
            controller: "ExternalCommandsIndexController"
        })

});
