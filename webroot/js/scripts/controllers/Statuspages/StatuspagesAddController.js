angular.module('openITCOCKPIT')
    .controller('StatuspagesAddController', function($scope, $http, $state, $stateParams, NotyService) {

        var clearForm = function() {
            $scope.post = {
                Statuspage: {
                    name: '',
                    description: '',
                    public: 0,
                    show_comments: 0,
                    containers: {
                        _ids: []
                    },
                }
            };
        };
        clearForm();
        $scope.container_id = null;
        $scope.hosts_ids = [];
        $scope.services_ids = [];
        $scope.hostgroups_ids = [];
        $scope.servicegroups_ids = [];
        $scope.selectedHosts = [];
        $scope.selectedServices = [];
        $scope.selectedHostgroups = [];
        $scope.selectedServicegroups = [];
        $scope.init = true;


        $scope.loadContainers = function() {
            var params = {
                'angular': true
            };
            $http.get("/statuspages/loadContainers.json", {
                params: params
            }).then(function(result) {
                $scope.containers = result.data.containers;

                $scope.init = false;
            });
        };

        $scope.loadHosts = function(searchString, selected = true) {
            if ($scope.init) {
                return;
            }
            if ($scope.container_id !== null) {
                $http.get("/hosts/loadHostsByContainerId.json", {
                    params: {
                        'angular': true,
                        'containerId': $scope.container_id,
                        'filter[Hosts.name]': searchString,
                        'selected[]': selected ? $scope.hosts_ids  : [],
                    }
                }).then(function(result) {
                    $scope.hosts = result.data.hosts;
                });
            }
        };

        $scope.loadServices = function(searchString, selected = true) {
            if ($scope.init) {
                return;
            }
            if ($scope.container_id) {
                $scope.params = {
                    'angular': true,
                    'containerId': $scope.container_id,
                    'filter': {
                        'servicename': searchString,
                    },
                    'selected': selected ? $scope.services_ids : []
                };

                $http.get("/services/loadServicesByStringCake4.json", {
                   params: $scope.params
                }).then(function(result) {
                    $scope.services = result.data.services;
                });
            }
        };

        $scope.loadHostgroups = function(searchString, selected = true) {
            if ($scope.init) {
                return;
            }
            if ($scope.container_id) {
                $http.get("/hostgroups/loadHostgroupsByStringAndContainers.json", {
                        params: {
                            'angular': true,
                            'filter[Containers.name]': searchString,
                            'selected[]': selected ? $scope.hostgroups_ids : [],
                            'containerId': $scope.container_id,
                        }
                }).then(function(result) {
                    $scope.hostgroups = result.data.hostgroups;
                });
            }
        };

        $scope.loadServicegroups = function(searchString, selected = true) {
            if ($scope.init) {
                return;
            }
            if ($scope.container_id) {
                $http.get("/servicegroups/loadServicegroupsByStringAndContainers.json", {
                    params: {
                        'angular': true,
                        'filter[Containers.name]': searchString,
                        'selected[]': selected ? $scope.servicegroups_ids: [],
                        'containerId': $scope.container_id,
                    }
                }).then(function(result) {
                    $scope.servicegroups = result.data.servicegroups;
                });
            }
        };


        $scope.submit = function() {
            let hostgroupsub = $scope.transform('hostgroups');
            let hostsub = $scope.transform('hosts');
            let servicesub = $scope.transform('services');
            let servicegroupsub = $scope.transform('servicegroups');
            $scope.post.Statuspage.containers._ids.push($scope.container_id);
            let data = $scope.post.Statuspage;
            data.hosts = hostsub;
            data.services = servicesub;
            data.hostgroups = hostgroupsub;
            data.servicegroups = servicegroupsub;
            $http.post("/statuspages/add.json?angular=true",
                data
            ).then(function(result) {
                var url = $state.href('StatuspagesAdd', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });
                $state.go('StatuspagesIndex').then(function() {
                    NotyService.scrollTop();
                });
            }, function errorCallback(result) {
                NotyService.genericError();
                if (result.data.hasOwnProperty('error')) {
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.transform = function(type) {
            let typeObjects = [];
            if (type === 'hosts') {
                typeObjects = $scope.selectedHosts
            }
            if (type === 'hostgroups') {
                typeObjects = $scope.selectedHostgroups
            }
            if (type === 'services') {
                typeObjects = $scope.selectedServices
            }
            if (type === 'servicegroups') {
                typeObjects = $scope.selectedServicegroups
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
        };

        $scope.proofSelected = function() {
            if ($scope.hostgroups_ids.length > 0) {
                let tmp = [];
                for (let index in $scope.hostgroups_ids) {
                    let id = $scope.hostgroups_ids[index];
                    if( $scope.hostgroups.find(elem => elem.key === id)) {
                        tmp.push(id);
                    }
                }
                $scope.hostgroups_ids = tmp;
            }

            if ($scope.servicegroups_ids.length > 0) {
                let tmp = [];
                for (let index in $scope.servicegroups_ids) {
                    let id = $scope.servicegroups_ids[index];
                    if( $scope.servicegroups.find(elem => elem.key === id)) {
                        tmp.push(id);
                    }
                }
                $scope.servicegroups_ids = tmp;
            }

            if ($scope.hosts_ids.length > 0) {
                let tmp = [];
                for (let index in $scope.hosts_ids) {
                    let id = $scope.hosts_ids[index];
                    if( $scope.hosts.find(elem => elem.key === id)) {
                        tmp.push(id);
                    }
                }
                $scope.hosts_ids = tmp;
            }
        };

        $scope.$watch('container_id', function() {
            if ($scope.init) {
                return;
            }
            if ($scope.container_id !== null) {
                $scope.loadHostgroups('', false);
                $scope.loadHosts('');
                $scope.loadServices('');
                $scope.loadServicegroups('');
                $scope.proofSelected();
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


        $scope.$watch('hosts_ids', function() {
            if ($scope.hosts_ids.length > 0) {
                let filter = [];
                for (let index in $scope.hosts_ids) {
                    let object = {};
                    object.id = $scope.hosts_ids[index];
                    object.name = $scope.hosts.find(x => x.key === object.id).value;
                    object.display_alias = ($scope.selectedHosts.find(x => x.id === object.id) !== undefined) ? $scope.selectedHosts.find(x => x.id === object.id).display_alias : null;
                    filter.push(object);
                }
                $scope.selectedHosts = filter;
            } else {
                $scope.selectedHosts = [];
            }
        }, true);

        $scope.$watch('services_ids', function() {
            if ($scope.services_ids.length > 0) {
                let filter = [];
                for (let index in $scope.services_ids) {
                    let object = {};
                    object.id = $scope.services_ids[index];
                    object.name = $scope.services.find(x => x.key === object.id).value.servicename;
                    object.hostName = $scope.services.find(x => x.key === object.id).value._matchingData.Hosts.name;
                    object.display_alias = ($scope.selectedServices.find(x => x.id === object.id) !== undefined) ? $scope.selectedServices.find(x => x.id === object.id).display_alias : null;
                    filter.push(object);
                }
                $scope.selectedServices = filter;
            } else {
                $scope.selectedServices = [];
            }
        }, true);

        $scope.$watch('hostgroups_ids', function() {
            if ($scope.hostgroups_ids.length > 0) {
                let filter = [];
                for (let index in $scope.hostgroups_ids) {
                    let object = {};
                    object.id = $scope.hostgroups_ids[index];
                    object.name = $scope.hostgroups.find(x => x.key === object.id).value;
                    object.display_alias = ($scope.selectedHostgroups.find(x => x.id === object.id) !== undefined) ? $scope.selectedHostgroups.find(x => x.id === object.id).display_alias : null;
                    filter.push(object);
                }
                $scope.selectedHostgroups = filter;
            } else {
                $scope.selectedHostgroups = [];
            }
        }, true);

        $scope.$watch('servicegroups_ids', function() {
            if ($scope.servicegroups_ids.length > 0) {
                let filter = [];
                for (let index in $scope.servicegroups_ids) {
                    let object = {};
                    object.id = $scope.servicegroups_ids[index];
                    object.name = $scope.servicegroups.find(x => x.key === object.id).value;
                    object.display_alias = ($scope.selectedServicegroups.find(x => x.id === object.id) !== undefined) ? $scope.selectedServicegroups.find(x => x.id === object.id).display_alias : null;
                    filter.push(object);
                }
                $scope.selectedServicegroups = filter;
            } else {
                $scope.selectedServicegroups = [];
            }
        }, true);


        //Fire on page load

        $scope.loadContainers();
    });

