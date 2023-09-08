angular.module('openITCOCKPIT')
    .controller('StatuspagesAddController', function($scope, $http, SudoService, $state, NotyService, RedirectService){

        var clearForm = function(){
            $scope.post = {
                Statuspage: {
                    name: '',
                    description: '',
                    public: 0,
                    show_comments: 0,
                    containers: {
                        _ids: []
                    },
                    hosts: {
                        _ids: []
                    },
                    services: {
                        _ids: []
                    },
                }
            };
        };
        clearForm();
        $scope.selectedHosts = [];
        $scope.selectedServices = [];
        $scope.init = true;
        $scope.showHostAliasButton = false;
        $scope.showHostAlias = false;
        $scope.showServiceAliasButton = false;
        $scope.showServiceAlias = false;
        $scope.loadContainers = function(){
            var params = {
                'angular': true
            };
            $http.get("/statuspages/loadContainers.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.loadHosts = function(){
            $http.get("/statuspages/loadHostsByContainerIds.json", {
                params: {
                    'angular': true,
                    'containerIds[]': $scope.post.Statuspage.containers._ids,
                    'selected[]': $scope.post.Statuspage.hosts._ids
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
            });
        };

        $scope.loadServices = function(){
            $http.get("/statuspages/loadServicesByContainerIds.json", {
                params: {
                    'angular': true,
                    'containerIds[]': $scope.post.Statuspage.containers._ids,
                    'selected[]': $scope.post.Statuspage.services._ids,
                }
            }).then(function(result){
                $scope.services = result.data.services;
            });
        };

        $scope.toggleHostAlias = function () {
            $scope.showHostAlias = !$scope.showHostAlias;
        };

        $scope.toggleServiceAlias = function () {
            $scope.showServiceAlias = !$scope.showServiceAlias;
        };

        $scope.submit = function(){
            $http.post("/statuspages/add.json?angular=true",
                $scope.post
            ).then(function(result) {
                var url = $state.href('StatuspagesAdd', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });
                $state.go('StatuspagesIndex');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };



        $scope.$watch('post.Statuspage.containers._ids', function(){
            if($scope.post.Statuspage.containers._ids.length > 0){

                $scope.loadHosts('');
                $scope.loadServices('');
               // $scope.loadHostgroups('');
               // $scope.loadServicegroups('');
            }else{
                console.log('reset');
                //reset host,service hostgroup and service chosen boxes if all containers are deselected
                $scope.post.Statuspage.hosts._ids = [];
                $scope.hosts = [];
                $scope.post.Statuspage.services._ids = [];
                $scope.services = [];
              /*  $scope.post.Statuspage.hostgroups._ids = [];
                $scope.hostgroups = [];
                $scope.post.Statuspage.servicegroups._ids = [];
                $scope.servicegroups = []; */
            }
        }, true);


         $scope.$watch('post.Statuspage.hosts._ids', function(){
                if($scope.post.Statuspage.hosts._ids.length > 0) {
                    let filter = [];
                    for (let index in  $scope.post.Statuspage.hosts._ids) {
                        let object = {};
                        object.id = $scope.post.Statuspage.hosts._ids[index];
                        object.name = $scope.hosts.find(x => x.key === object.id).value;
                        object.display_alias = ($scope.selectedHosts.find(x => x.id === object.id) !== undefined) ? $scope.selectedHosts.find(x => x.id === object.id).display_alias: null;
                        filter.push(object);
                    }
                    $scope.selectedHosts = filter;
                    $scope.showHostAliasButton = true;
                } else {
                    $scope.showHostAliasButton = false;
                    $scope.showHostAlias = false;
                }
        }, true);

        $scope.$watch('post.Statuspage.services._ids', function(){
            if($scope.post.Statuspage.services._ids.length > 0) {
                let filter = [];
                for (let index in  $scope.post.Statuspage.services._ids) {
                    let object = {};
                    object.id = $scope.post.Statuspage.services._ids[index];
                    object.name = $scope.services.find(x => x.key === object.id).value.Service.servicename;
                    object.hostName = $scope.services.find(x => x.key === object.id).value.Host.name;
                    object.display_alias = ($scope.selectedServices.find(x => x.id === object.id) !== undefined) ? $scope.selectedServices.find(x => x.id === object.id).display_alias: null;
                    filter.push(object);
                }

                $scope.selectedServices = filter;
                $scope.showServiceAliasButton = true;
            } else {
                $scope.showServiceAliasButton = false;
                $scope.showServiceAlias = false;
            }
        }, true);



        //Fire on page load

        $scope.loadContainers();
    });

