angular.module('openITCOCKPIT')
    .controller('ServicegroupsAddController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){

        $scope.data = {
            createAnother: false
        };

        // preSelectedIds is used for "Append hosts to host group from /hosts/index"
        var preSelectedIds = $stateParams.ids;
        if(preSelectedIds !== null){
            var idsAsString = preSelectedIds.split(',');
            preSelectedIds = [];
            //int ids are required for AngularJS
            for(var i in idsAsString){
                preSelectedIds.push(parseInt(idsAsString[i], 10));
            }
        }

        if(preSelectedIds === null){
            preSelectedIds = [];
        }


        var clearForm = function(){
            $scope.post = {
                Servicegroup: {
                    description: '',
                    servicegroup_url: '',
                    container: {
                        name: '',
                        parent_id: 0
                    },
                    hosts: {
                        _ids: preSelectedIds
                    },
                    hosttemplates: {
                        _ids: []
                    }
                }
            };
        };
        clearForm();

        $scope.init = true;
        $scope.load = function(){
            $http.get("/servicegroups/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.loadServices = function(searchString){
            $http.get("/servicegroups/loadServices.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Servicegroup.container.parent_id,
                    'filter[servicename]': searchString,
                    'selected[]': $scope.post.Servicegroup.services._ids
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
            });
        };

        if($scope.post.Container.parent_id){
            $http.get("/services/loadServicesByContainerId.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Container.parent_id,
                    'filter[Host.name]': searchString,
                    'filter[Service.servicename]': searchString,
                    'selected[]': $scope.post.Servicegroup.Service
                }
            }).then(function(result){
                $scope.services = result.data.services;
            });
        }






        $scope.loadHosttemplates = function(searchString){
            $http.get("/hostgroups/loadHosttemplates.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Servicegroup.container.parent_id,
                    'filter[Hosttemplates.name]': searchString,
                    'selected[]': $scope.post.Servicegroup.hosttemplates._ids
                }
            }).then(function(result){
                $scope.hosttemplates = result.data.hosttemplates;
            });
        };

        $scope.submit = function(){
            $http.post("/servicegroups/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('ServicegroupsEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                if($scope.data.createAnother === false){
                    RedirectService.redirectWithFallback('ServicegroupsIndex');
                }else{
                    clearForm();
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


        $scope.$watch('post.Servicegroup.container.parent_id', function(){
            if($scope.init){
                return;
            }

            if($scope.post.Servicegroup.container.parent_id == 0){
                //Create another
                return;
            }

            $scope.loadServices('');
            $scope.loadServicetemplates('');
        }, true);

        $scope.load();

    });