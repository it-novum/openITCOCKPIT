openITCOCKPIT.config(function($stateProvider){
    $stateProvider
        .state('TelegramSettingsIndex', {
            url: '/telegram_module/TelegramSettings/index',
            templateUrl: "/telegram_module/TelegramSettings/index.html",
            controller: "TelegramSettingsIndexController"
        })
});
