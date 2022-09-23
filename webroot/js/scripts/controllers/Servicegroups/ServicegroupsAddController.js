angular.module('openITCOCKPIT')
    .controller('ServicegroupsAddController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){

        $scope.data = {
            createAnother: false
        };

        // preSelectedIds is used for "Append services to service group from /services/index"
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
                    services: {
                        _ids: preSelectedIds
                    },
                    servicetemplates: {
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
            if($scope.post.Servicegroup.container.parent_id == 0){
                return;
            }
            $scope.params = {
                'containerId': $scope.post.Servicegroup.container.parent_id,
                'filter': {
                    'servicename': searchString,
                },
                'selected': $scope.post.Servicegroup.services._ids
            };
            $http.post("/services/loadServicesByContainerIdCake4.json?angular=true",
                $scope.params
            ).then(function(result){
                $scope.services = result.data.services;
            });
        };

        $scope.loadServicetemplates = function(searchString){
            if($scope.post.Servicegroup.container.parent_id == 0){
                return;
            }
            $http.get("/servicegroups/loadServicetemplates.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Servicegroup.container.parent_id,
                    'filter[Servicetemplates.name]': searchString,
                    'selected[]': $scope.post.Servicegroup.servicetemplates._ids
                }
            }).then(function(result){
                $scope.servicetemplates = result.data.servicetemplates;
            });
        };

        $scope.submit = function(){
            //clean up services and service templates -> remove not visible ids
            $scope.post.Servicegroup.services._ids = _.intersection(
                _.map($scope.services, 'key'),
                $scope.post.Servicegroup.services._ids
            );
            $scope.post.Servicegroup.servicetemplates._ids = _.intersection(
                _.map($scope.servicetemplates, 'key'),
                $scope.post.Servicegroup.servicetemplates._ids
            );
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
