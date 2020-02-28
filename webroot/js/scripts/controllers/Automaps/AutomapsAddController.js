angular.module('openITCOCKPIT')
    .controller('AutomapsAddController', function($scope, $http, SudoService, $state, NotyService, RedirectService){

        var fontSizes = {
            1: 'xx-small',
            2: 'x-small',
            3: 'small',
            4: 'medium',
            5: 'large',
            6: 'x-large',
            7: 'xx-large'
        };

        $scope.data = {
            createAnother: false,
            hostCount: 0,
            serviceCount: 0
        };

        var clearForm = function(){
            $scope.post = {
                Automap: {
                    name: '',
                    description: '',
                    container_id: 0,
                    recursive: 0,

                    host_regex: '',
                    service_regex: '',

                    show_ok: 1,
                    show_warning: 1,
                    show_critical: 1,
                    show_unknown: 1,
                    show_downtime: 1,
                    show_acknowledged: 1,

                    show_label: 1,
                    group_by_host: 1,

                    use_paginator: 1,

                    font_size: "4"
                }
            };
        };
        clearForm();

        $scope.init = true;

        $scope.loadContainers = function(){
            var params = {
                'angular': true
            };

            $http.get("/automaps/loadContainers.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.submit = function(){
            $http.post("/automaps/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('AutomapsEdit', {id: result.data.automap.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });


                if($scope.data.createAnother === false){
                    RedirectService.redirectWithFallback('AutomapsIndex');
                }else{
                    clearForm();
                    $scope.data.hostCount = 0;
                    $scope.data.serviceCount = 0;
                    $scope.errors = {};
                    NotyService.scrollTop();
                }

                console.log('Data saved successfully');
            }, function errorCallback(result){

                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.getFontsize = function(){
            return {
                'font-size': fontSizes[$scope.post.Automap.font_size]
            };
        };

        $scope.getMatchingHostAndServices = function(){
            $http.post("/automaps/getMatchingHostAndServices.json?angular=true",
                $scope.post
            ).then(function(result){
                $scope.data.hostCount = result.data.hostCount;
                $scope.data.serviceCount = result.data.serviceCount;
            });
        };

        $scope.$watch('post.Automap.host_regex', function(){
            if($scope.init){
                return;
            }

            if($scope.post.Automap.host_regex != '' && $scope.post.Automap.container_id > 0){
                $scope.getMatchingHostAndServices();
            }

        });

        $scope.$watch('post.Automap.service_regex', function(){
            if($scope.init){
                return;
            }

            if($scope.post.Automap.service_regex != '' && $scope.post.Automap.container_id > 0){
                $scope.getMatchingHostAndServices();
            }
        });

        $scope.$watch('post.Automap.container_id', function(){
            if($scope.init){
                return;
            }

            if($scope.post.Automap.host_regex != '' && $scope.post.Automap.service_regex != '' && $scope.post.Automap.container_id > 0){
                $scope.getMatchingHostAndServices();
            }
        });

        $scope.$watch('post.Automap.recursive', function(){
            if($scope.init){
                return;
            }

            if($scope.post.Automap.host_regex != '' && $scope.post.Automap.service_regex != '' && $scope.post.Automap.container_id > 0){
                $scope.getMatchingHostAndServices();
            }
        });

        // Fire on page
        $scope.loadContainers();

    });
