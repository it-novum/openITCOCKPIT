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
                }
            };
        };
        clearForm();
        $scope.hosts_ids = [];
        $scope.services_ids = []


        $scope.selectedHosts = [];
        $scope.selectedServices = [];
        $scope.init = true;
        $scope.showHostAliasButton = false;
        $scope.showHostAlias = false;
        $scope.showServiceAliasButton = true;
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
                    'selected[]': $scope.hosts._ids
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
                    'selected[]': $scope.services._ids,
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
            let hostsub = $scope.transformHosts();
            let servicesub = $scope.transformServices();
            let data = $scope.post.Statuspage;
            data.hosts = hostsub;
            data.services = servicesub;
            $http.post("/statuspages/add.json?angular=true",
                data
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

        $scope.transformHosts = function (){
            let hostsconv = [];
            for(let index in $scope.selectedHosts) {
                let hostObject = {};
                hostObject.id = $scope.selectedHosts[index].id;
                hostObject._joinData = {
                    display_alias: $scope.selectedHosts[index].display_alias
                }
                hostsconv.push(hostObject);
            }
            return hostsconv;
        }

        $scope.transformServices = function (){
            let servicesconv = [];
            for(let index in $scope.selectedServices) {
                let serviceObject = {};
                serviceObject.id = $scope.selectedServices[index].id;
                serviceObject._joinData = {
                    display_alias: $scope.selectedServices[index].display_alias
                }
                servicesconv.push(serviceObject);
            }
            return servicesconv;
        }

        $scope.$watch('post.Statuspage.containers._ids', function(){
            if($scope.post.Statuspage.containers._ids.length > 0){

                $scope.loadHosts('');
                $scope.loadServices('');
               // $scope.loadHostgroups('');
               // $scope.loadServicegroups('');
            }else{
                console.log('reset');
                //reset host,service hostgroup and service chosen boxes if all containers are deselected
                $scope.hosts_ids = [];
                $scope.hosts = [];
                $scope.services_ids = [];
                $scope.services = [];
              /*  $scope.post.Statuspage.hostgroups._ids = [];
                $scope.hostgroups = [];
                $scope.post.Statuspage.servicegroups._ids = [];
                $scope.servicegroups = []; */
            }
        }, true);


         $scope.$watch('hosts_ids', function(){
                if($scope.hosts_ids.length > 0) {
                    let filter = [];
                    for (let index in  $scope.hosts_ids) {
                        let object = {};
                        object.id = $scope.hosts_ids[index];
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

        $scope.$watch('services_ids', function(){
            if($scope.services_ids.length > 0) {
                let filter = [];
                for (let index in  $scope.services_ids) {
                    let object = {};
                    object.id = $scope.services_ids[index];
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

