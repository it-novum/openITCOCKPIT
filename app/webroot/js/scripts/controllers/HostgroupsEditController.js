angular.module('openITCOCKPIT')
    .controller('HostgroupsEditController', function($scope, $http, QueryStringService, $stateParams, $state, NotyService){


        $scope.post = {
            Container: {
                name: '',
                parent_id: 0
            },
            Hostgroup: {
                description: '',
                hostgroup_url: '',
                Host: [],
                Hosttemplate: []
            }
        };

        //$scope.id = QueryStringService.getCakeId();
        $scope.id = $stateParams.id;

        $scope.deleteUrl = "/hostgroups/delete/" + $scope.id + ".json?angular=true";
        $scope.sucessUrl = '/hostgroups/index';

        $scope.init = true;
        $scope.load = function(){
            $http.get("/hostgroups/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.hostgroup = result.data.hostgroup;

                var selectedHosts = [];
                var selectedHosttemplates = [];
                var key;
                for(key in $scope.hostgroup.Host){
                    selectedHosts.push(parseInt($scope.hostgroup.Host[key].id, 10));
                }
                for(key in $scope.hostgroup.Hosttemplate){
                    selectedHosttemplates.push(parseInt($scope.hostgroup.Hosttemplate[key].id, 10));
                }

                $scope.post.Hostgroup.Host = selectedHosts;
                $scope.post.Hostgroup.Hosttemplate = selectedHosttemplates;
                $scope.post.Container.name = $scope.hostgroup.Container.name;
                $scope.post.Container.parent_id = parseInt($scope.hostgroup.Container.parent_id, 10);
                $scope.post.Hostgroup.description = $scope.hostgroup.Hostgroup.description;
                $scope.post.Hostgroup.hostgroup_url = $scope.hostgroup.Hostgroup.hostgroup_url;
                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
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
            $http.get("/hostgroups/loadHosts.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Container.parent_id,
                    'filter[Host.name]': searchString,
                    'selected[]': $scope.post.Hostgroup.Host
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
            });
        };

        $scope.loadHosttemplates = function(searchString){
            $http.get("/hostgroups/loadHosttemplates.json", {
                params: {
                    'angular': true,
                    'containerId': $scope.post.Container.parent_id,
                    'filter[Hosttemplates.name]': searchString,
                    'selected[]': $scope.post.Hostgroup.Hosttemplate
                }
            }).then(function(result){
                $scope.hosttemplates = result.data.hosttemplates;
            });
        };

        $scope.submit = function(){
            $http.post("/hostgroups/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                console.log('Data saved successfully');
                NotyService.genericSuccess();
                $state.go('HostgroupsIndex');

            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };


        $scope.$watch('post.Container.parent_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadHosts('');
            $scope.loadHosttemplates('');
        }, true);

        //$scope.load();
        $scope.loadContainers();

    });