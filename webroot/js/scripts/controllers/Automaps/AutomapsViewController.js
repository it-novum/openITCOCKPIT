angular.module('openITCOCKPIT')
    .controller('AutomapsViewController', function($scope, $http, $stateParams){

        $scope.id = $stateParams.id;
        $scope.init = true;

        $scope.currentPage = 1;
        $scope.useScroll = true;
        $scope.onlyButtons = false;

        $scope.load = function(){
            var params = {
                'angular': true,
                'scroll': $scope.useScroll,
                'page': $scope.currentPage
            };

            $http.get("/automaps/view/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.automap = result.data.automap;
                $scope.servicesByHost = result.data.servicesByHost;
                if($scope.automap.use_paginator){
                    $scope.paging = result.data.paging;
                    $scope.scroll = result.data.scroll;
                }
                $scope.init = false;
            });
        };

        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.changeMode = function(val){
            $scope.useScroll = val;
            $scope.load();
        };

        $scope.load();
    });
