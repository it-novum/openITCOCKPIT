openITCOCKPIT.config(function($stateProvider){
    $stateProvider

        .state('MapsIndex', {
            url: '/map_module/maps/index',
            templateUrl: "/map_module/maps/index.html",
            controller: "MapsIndexController"
        })

        .state('MapsAdd', {
            url: '/map_module/maps/add',
            templateUrl: "/map_module/maps/add.html",
            controller: "MapsAddController"
        })

        .state('MapsEdit', {
            url: '/map_module/maps/edit/:id',
            templateUrl: "/map_module/maps/edit.html",
            controller: "MapsEditController"
        })

        .state('MapsCopy', {
            url: '/map_module/maps/copy/:id',
            templateUrl: "/map_module/maps/copy.html",
            controller: "MapsCopyController"
        })

        .state('MapeditorsEdit', {
            url: '/map_module/mapeditors/edit/:id',
            templateUrl: "/map_module/mapeditors/edit.html",
            controller: "MapeditorsEditController"
        })

        .state('MapeditorsView', {
            url: '/map_module/mapeditors/view/:id/:fullscreen/:rotation/{interval:int}',
            params: {
                fullscreen: {
                    value: "false",
                    squash: false
                },
                rotation: {
                    value: null,
                    squash: true
                },
                interval: {
                    value: 0,
                    squash: true
                }
            },
            templateUrl: "/map_module/mapeditors/view.html",
            controller: "MapeditorsViewController"
        })

        .state('RotationsIndex', {
            url: '/map_module/rotations/index',
            templateUrl: "/map_module/rotations/index.html",
            controller: "RotationsIndexController"
        })

        .state('RotationsAdd', {
            url: '/map_module/rotations/add',
            templateUrl: "/map_module/rotations/add.html",
            controller: "RotationsAddController"
        })

        .state('RotationsEdit', {
            url: '/map_module/rotations/edit/:id',
            templateUrl: "/map_module/rotations/edit.html",
            controller: "RotationsEditController"
        })

});
