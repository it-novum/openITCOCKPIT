angular.module('openITCOCKPIT')
    .controller('HostgroupsAddController', function($scope, $http, $state){


        $scope.post = {
            Container: {
                name: null,
                parent_id: null
            },
            Hostgroup: {
                description: null,
                hostgroup_url: null,
                Host: [],
                Hosttemplate: []
            }
        };

        $scope.init = true;
        $scope.load = function(){
            $http.get("/hostgroups/add.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.loadHosts = function(searchString){
            console.log($scope.post.Hostgroup.Host);
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


        $scope.$watch('post.Container.parent_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadHosts('');
        }, true);

        $scope.load();

    });