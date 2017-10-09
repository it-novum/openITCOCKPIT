angular.module('openITCOCKPIT')
    .controller('HostgroupsAddController', function($scope, $http, $state){


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

        $scope.init = true;
        $scope.load = function(){
            $http.get("/hostgroups/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
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
                    'filter[Hosttemplate.name]': searchString,
                    'selected[]': $scope.post.Hostgroup.Hosttemplate
                }
            }).then(function(result){
                $scope.hosttemplates = result.data.hosttemplates;
            });
        };

        $scope.submit = function(){
            $http.post("/hostgroups/add.json?angular=true",
                $scope.post
            ).then(function(result){
                console.log('Data saved successfully');
                window.location.href = '/hostgroups/index';
            }, function errorCallback(result){
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

        $scope.load();

    });