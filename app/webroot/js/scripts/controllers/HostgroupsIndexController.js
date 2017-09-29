angular.module('openITCOCKPIT')
    .controller('HostgroupsIndexController', function($scope, $http, $state){

        //Refactor me with Angular-UI-Router!
        if(!$state.is('HostgroupsIndex')){
            $state.go('HostgroupsIndex');
        }

        $scope.init = true;
        $scope.load = function(){
            console.log('yolo');

        };



        $scope.load();

    });