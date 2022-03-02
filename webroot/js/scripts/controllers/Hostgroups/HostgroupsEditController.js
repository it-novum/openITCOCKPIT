angular.module('openITCOCKPIT')
    .controller('HostgroupsEditController', function($scope, $http, QueryStringService, $stateParams, $state, NotyService, RedirectService){


        $scope.post = {
            Hostgroup: {
                description: '',
                hostgroup_url: '',
                container: {
                    name: '',
                    parent_id: 0
                },
                hosts: {
                    _ids: []
                },
                hosttemplates: {
                    _ids: []
                }
            }
        };

        //$scope.id = QueryStringService.getCakeId();
        $scope.id = $stateParams.id;

        $scope.deleteUrl = "/hostgroups/delete/" + $scope.id + ".json?angular=true";
        $scope.successState = 'HostgroupsIndex';

        $scope.init = true;
        $scope.load = function(){
            $http.get("/hostgroups/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post = result.data.hostgroup;

                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.loadContainers = function(){
            $http.get("/hostgroups/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.load();
            });
        };

        $scope.loadHosts = function(searchString){
            if($scope.post.Hostgroup.container.parent_id == 0){
                return;
            }
            $scope.params = {
                'containerId': $scope.post.Hostgroup.container.parent_id,
                'filter': {
                    'Hosts.name': searchString

                },
                'selected': $scope.post.Hostgroup.hosts._ids
            };

            $http.post("/hostgroups/loadHosts.json?angular=true",
                $scope.params
            ).then(function(result){
                $scope.hosts = result.data.hosts;
            });
        };

        $scope.loadHosttemplates = function(searchString){
            if($scope.post.Hostgroup.container.parent_id == 0){
                return;
            }
            $http.get("/hostgroups/loadHosttemplates.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Hostgroup.container.parent_id,
                    'filter[Hosttemplates.name]': searchString,
                    'selected[]': $scope.post.Hostgroup.hosttemplates._ids
                }
            }).then(function(result){
                $scope.hosttemplates = result.data.hosttemplates;
            });
        };

        $scope.submit = function(){
            $http.post("/hostgroups/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('HostgroupsEdit', {id: $scope.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('HostgroupsIndex');

            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };


        $scope.$watch('post.Hostgroup.container.parent_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadHosts('');
            $scope.loadHosttemplates('');
        }, true);

        //$scope.load();
        $scope.loadContainers();

    });
