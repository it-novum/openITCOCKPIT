angular.module('openITCOCKPIT')
    .controller('HostsAddwizardController', function($scope, $http, DnsLookupService){
        $scope.post = {
            Container: {
                Container: [],
                container_id: 0
            },
            Host: {
                name: '',
                address: '',
                satellite_id: 0,
                container_id: 0,
                own_contacts: 0,
                own_contactgroups: 0,
                own_customvariables: 0,
                description: '',
                host_url: '',
                hosttemplate_id: 0,
                Contact: [],
                Contactgroup: [],
                Hostgroup: [],
                Parenthost: [],
                notify_period_id: 0,
                check_period_id: 0,
                command_id: 0,
                host_type: 1
            }
        };

        $scope.selectedContainer = 0;
        $scope.selectedHosttemplate = 0;
        $scope.hostname = 0;
        $scope.hostaddress = 0;
        $scope.init = true;
        $scope.dnsLookup = true;

        $scope.load = function(){
            $http.get("/hosts/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.containerSelected = function(){
            var contianerID = $scope.post.Container.container_id;
            $scope.selectedContainer = contianerID;
            $scope.post.Host.container_id = contianerID;
        };

        $scope.hosttemplateSelected = function(){
            $scope.selectedHosttemplate = $scope.post.Host.hosttemplate_id;
        };


        $scope.loadData = function(){
            if($scope.init){
                return;
            }
            $http.get("/hosts/loadElementsByContainerId/" + $scope.selectedContainer + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.hosttemplates = result.data.hosttemplates;
            });
        };

        $scope.loadHosttemplateData = function(){
            if($scope.init){
                return;
            }
            $http.get("/hosts/loadHosttemplateData/" + $scope.selectedHosttemplate + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post.Host.description = result.data.hosttemplate.Hosttemplate.description;
                $scope.post.Host.host_type = result.data.hosttemplate.Hosttemplate.host_type;
                $scope.post.Host.command_id = result.data.hosttemplate.Hosttemplate.command_id;
                $scope.post.Host.Contact = result.data.hosttemplate.ContactIds;
                $scope.post.Contact = result.data.hosttemplate.ContactIds;
                $scope.post.Contact = result.data.hosttemplate.ContactIds;
                $scope.post.Contactgroup = result.data.hosttemplate.ContactgroupIds;
                $scope.post.Host.Contactgroup = result.data.hosttemplate.ContactgroupIds;
                $scope.post.Host.Hostgroup = result.data.hosttemplate.HostgroupIds;
                $scope.post.Host.check_period_id = result.data.hosttemplate.Hosttemplate.check_period_id;
                $scope.post.Host.notify_period_id = result.data.hosttemplate.Hosttemplate.notify_period_id;
            });
        };


        $scope.submit = function(){
            $http.post("/hosts/add.json?angular=true",
                $scope.post
            ).then(function(result){
                console.log('Data saved successfully');
                var id = result.data.id;
                window.location.href = '/hosts/addwizardservices/'+id;
            }, function errorCallback(result){
                console.info('save failed');
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };

        $scope.$watch('selectedContainer', function(){
            if($scope.init){
                return;
            }
            $scope.loadData('');
        }, true);

        $scope.$watch('selectedHosttemplate', function(){
            $scope.loadHosttemplateData();
        }, true);


        $scope.$watch('post.Host.name', function(){
            if($scope.init){
                return;
            }

            if(!$scope.dnsLookup){
                return;
            }
            $scope.hostname = $scope.post.Host.name;
            DnsLookupService.getHostip($scope.hostname).then(function(data){
                $scope.post.Host.address = data.data.hostaddress;
            });
        }, true);

        $scope.$watch('post.Host.address', function(){
            if($scope.init){
                return;
            }

            if(!$scope.dnsLookup){
                return;
            }
            $scope.address = $scope.post.Host.address;
            DnsLookupService.getHostname($scope.address).then(function(data){
                $scope.post.Host.name = data.data.fqdn;
            });
        }, true);

        $scope.load();

    });