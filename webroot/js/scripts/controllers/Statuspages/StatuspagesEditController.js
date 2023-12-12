angular.module('openITCOCKPIT')
    .controller('StatuspagesEditController', function($scope, $http, SudoService, $state, $stateParams, NotyService) {

        $scope.id = $stateParams.id;


        $scope.post = {
            Statuspage: {},
        };

        $scope.init = true;
        $scope.hasError = null;
        $scope.errors = {};

        $scope.container_id = null;
        $scope.hosts_ids = [];
        $scope.services_ids = [];
        $scope.hostgroups_ids = [];
        $scope.servicegroups_ids = [];
        $scope.aliasHosts = [];
        $scope.aliasServices = [];
        $scope.aliasHostgroups = [];
        $scope.aliasServicegroups = [];
        $scope.hostgroups = [];
        $scope.servicegroups = [];
        $scope.hosts = [];


        $scope.createIdsArray = function (item){
            return item.id;
        }

        $scope.createAliasArray = function (item){
            let obj = {}
            if(item._joinData.host_id){
               obj.id =  item._joinData.host_id;
               obj.name = item.name;
               obj.display_alias = item._joinData.display_alias;
            }
            if(item._joinData.service_id){
                obj.id =  item._joinData.service_id;
                obj.name = item.servicename;
                obj.hostName = item.hostname;
                obj.display_alias = item._joinData.display_alias;
            }
            if(item._joinData.servicegroup_id){
                obj.id =  item._joinData.servicegroup_id;
                obj.name = item.name;
                obj.display_alias = item._joinData.display_alias;
            }
            if(item._joinData.hostgroup_id){
                obj.id =  item._joinData.hostgroup_id;
                obj.name = item.name;
                obj.display_alias = item._joinData.display_alias;
            }
            return obj;
        }

        $scope.loadStatuspage = function() {
            var params = {
                'angular': true
            };

            $http.get("/statuspages/edit/" + $scope.id + ".json", {
                params: params
            }).then(function(result) {
                $scope.post.Statuspage = result.data.Statuspage;
                $scope.post.Statuspage.containers = {_ids: [result.data.Statuspage.containers[0].id ?? 1]}
                $scope.post.Statuspage.public = +result.data.Statuspage.public;
                $scope.post.Statuspage.show_comments = +result.data.Statuspage.show_comments;
                $scope.container_id = $scope.post.Statuspage.containers._ids[0];
                $scope.hosts_ids = $scope.post.Statuspage.hosts.map($scope.createIdsArray);
                $scope.services_ids = $scope.post.Statuspage.services.map($scope.createIdsArray);
                $scope.hostgroups_ids = $scope.post.Statuspage.hostgroups.map($scope.createIdsArray);
                $scope.servicegroups_ids = $scope.post.Statuspage.servicegroups.map($scope.createIdsArray);
                $scope.aliasHosts = $scope.post.Statuspage.hosts.map($scope.createAliasArray);
                $scope.aliasServices = $scope.post.Statuspage.services.map($scope.createAliasArray);
                $scope.aliasHostgroups = $scope.post.Statuspage.hostgroups.map($scope.createAliasArray);
                $scope.aliasServicegroups = $scope.post.Statuspage.servicegroups.map($scope.createAliasArray);
            },
                function errorCallback(result) {
                if (result.status === 403) {
                    $state.go('403');
                }

                if (result.status === 404) {
                    $state.go('404');
                }
            });

        };

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

        $scope.loadHostgroups = function(searchString){
            if($scope.container_id !== null){
                $http.get("/hostgroups/loadHostgroupsByString.json", {
                    params: {
                        'angular': true,
                        'filter[Containers.name]': searchString,
                        'selected[]': $scope.hostgroups_ids,
                        'resolveContainerIds': true
                    }
                }).then(function(result){
                    $scope.hostgroups = result.data.hostgroups;
                });
            }
        };

        $scope.loadServicegroups = function(searchString) {
            if ($scope.container_id) {
                $http.get("/servicegroups/loadServicegroupsByString.json", {
                    params: {
                        'angular': true,
                        'filter[Containers.name]': searchString,
                        'selected[]': $scope.servicegroups_ids
                    }
                }).then(function(result) {
                    $scope.servicegroups = result.data.servicegroups;
                });
            }
        };

        $scope.loadHosts = function(searchString) {
            if ($scope.container_id !== null) {
                $http.get("/hosts/loadHostsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.container_id,
                        'filter[Hosts.name]': searchString,
                        'selected[]': $scope.hosts_ids
                    }
                }).then(function(result) {
                    $scope.hosts = result.data.hosts;
                });
            }
        };

        $scope.loadServices = function(searchString) {
            if ($scope.container_id) {
                $scope.params = {
                    'angular': true,
                    'containerId': $scope.container_id,
                    'filter': {
                        'servicename': searchString,
                    },
                    'selected': $scope.services_ids
                };

                $http.get("/services/loadServicesByStringCake4.json", {
                    params: $scope.params
                }).then(function(result) {
                    $scope.services = result.data.services;
                });
            }
        };

        $scope.transform = function(type) {
            let typeObjects = [];
            if (type === 'hosts') {
                typeObjects = $scope.aliasHosts
            }
            if (type === 'hostgroups') {
                typeObjects = $scope.aliasHostgroups
            }
            if (type === 'services') {
                typeObjects = $scope.aliasServices
            }
            if (type === 'servicegroups') {
                typeObjects = $scope.aliasServicegroups
            }
            let typeconv = [];
            for (let index in typeObjects) {
                let typeObject = {};
                typeObject.id = typeObjects[index].id;
                typeObject._joinData = {
                    display_alias: typeObjects[index].display_alias
                }
                typeconv.push(typeObject);
            }
            return typeconv;
        }

        $scope.submit = function() {

            let hostgroupsub = $scope.transform('hostgroups');
            let hostsub = $scope.transform('hosts');
            let servicesub = $scope.transform('services');
            let servicegroupsub = $scope.transform('servicegroups');
            $scope.post.Statuspage.containers._ids[0] = $scope.container_id;
            let data = $scope.post.Statuspage;
            //data.Statuspage = $scope.post.Statuspage;
            data.hosts = hostsub;
            data.services = servicesub;
            data.hostgroups = hostgroupsub;
            data.servicegroups = servicegroupsub;


            $http.post("/statuspages/edit/" + $scope.id + ".json?angular=true",
                data
            ).then(function(result) {
                var url = $state.href('StatuspagesIndex', {id: $scope.id});
                NotyService.genericSuccess({
                    message: 'Alias update succesfull'
                });

                $state.go('StatuspagesIndex').then(function() {
                    NotyService.scrollTop();
                });

            }, function errorCallback(result) {
                if (result.data.hasOwnProperty('error')) {
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.$watch('container_id', function() {
            if ($scope.container_id !== null) {

                $scope.loadHosts('');
                $scope.loadServices('');
                $scope.loadHostgroups('');
                $scope.loadServicegroups('');
            }
            else {
                $scope.hosts_ids = [];
                $scope.hosts = [];
                $scope.services_ids = [];
                $scope.services = [];
                $scope.hostgroups_ids = [];
                $scope.hostgroups = [];
                $scope.servicegroups_ids = [];
                $scope.servicegroups = [];
            }
        }, true);

        $scope.$watch('hostgroups_ids', function() {
            if($scope.init) {
                return;
            }
            if ($scope.hostgroups_ids.length > 0 ) {
                let filter = [];
                for (let index in $scope.hostgroups_ids) {
                    let object = {};
                    object.id = $scope.hostgroups_ids[index];
                    object.name = $scope.hostgroups.find(x => x.key === object.id).value;
                    object.display_alias = ($scope.aliasHostgroups.find(x => x.id === object.id) !== undefined) ? $scope.aliasHostgroups.find(x => x.id === object.id).display_alias : null;
                    filter.push(object);
                }
                $scope.aliasHostgroups = filter;

            } else {
                $scope.aliasHostgroups = [];
            }
        }, true);

        $scope.$watch('servicegroups_ids', function() {
            if($scope.init) {
                return;
            }
            if ($scope.servicegroups_ids.length > 0 ) {
                let filter = [];
                for (let index in $scope.servicegroups_ids) {
                    let object = {};
                    object.id = $scope.servicegroups_ids[index];
                    object.name = $scope.servicegroups.find(x => x.key === object.id).value;
                    object.display_alias = ($scope.aliasServicegroups.find(x => x.id === object.id) !== undefined) ? $scope.aliasServicegroups.find(x => x.id === object.id).display_alias : null;
                    filter.push(object);
                }
                $scope.aliasServicegroups = filter;
            } else {
                $scope.aliasServicegroups = [];
            }
        }, true);

        $scope.$watch('hosts_ids', function() {
            if($scope.init) {
                return;
            }
            if ($scope.hosts_ids.length > 0 ) {
                let filter = [];
                for (let index in $scope.hosts_ids) {
                    let object = {};
                    object.id = $scope.hosts_ids[index];
                    object.name = $scope.hosts.find(x => x.key === object.id).value;
                    object.display_alias = ($scope.aliasHosts.find(x => x.id === object.id) !== undefined) ? $scope.aliasHosts.find(x => x.id === object.id).display_alias : null;
                    filter.push(object);
                }
                $scope.aliasHosts = filter;
            } else {
                $scope.aliasHosts = [];
            }
        }, true);

        $scope.$watch('services_ids', function() {
            if($scope.init) {
                return;
            }
            if ($scope.services_ids.length > 0 ) {
                let filter = [];
                for (let index in $scope.services_ids) {
                    let object = {};
                    object.id = $scope.services_ids[index];
                    object.name = $scope.services.find(x => x.key === object.id).value.servicename;
                    object.hostName = $scope.services.find(x => x.key === object.id).value._matchingData.Hosts.name;
                    object.display_alias = ($scope.aliasServices.find(x => x.id === object.id) !== undefined) ? $scope.aliasServices.find(x => x.id === object.id).display_alias : null;
                    filter.push(object);
                }
                $scope.aliasServices = filter;
            } else {
                $scope.aliasServices = [];
            }
        }, true);

        //Fire on page load
        $scope.loadStatuspage();

    });
