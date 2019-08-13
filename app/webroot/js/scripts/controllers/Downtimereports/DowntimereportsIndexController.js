angular.module('openITCOCKPIT')
    .controller('DowntimereportsIndexController', function($rootScope, $scope, $http, $timeout, NotyService, QueryStringService, $httpParamSerializer){

        $scope.init = true;
        $scope.errors = null;
        $scope.hasEntries = null;

        $scope.post = {
            services: [],
            report_format: '2'

        };


    });
