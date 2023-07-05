openITCOCKPIT.config(function($stateProvider){
    $stateProvider

        .state('ChangecalendarsIndex', {
            url: '/changecalendar_module/changecalendars/index',
            templateUrl: "/changecalendar_module/changecalendars/index.html",
            controller: "ChangecalendarsIndexController"
        })
        .state('ChangecalendarsAdd', {
            url: '/changecalendar_module/changecalendars/add',
            templateUrl: "/changecalendar_module/changecalendars/add.html",
            controller: "ChangecalendarsAddController"
        })
        .state('ChangecalendarsEdit', {
            url: '/changecalendar_module/changecalendars/edit/:id',
            templateUrl: "/changecalendar_module/changecalendars/edit.html",
            controller: "ChangecalendarsEditController"
        })
});
