angular.module('openITCOCKPIT')
    .controller('WizardHostConfigurationController', function($scope, $http, $state, $stateParams, QueryStringService, NotyService, LocalStorageService){
        /** public vars **/
        $scope.init = true;
        $scope.state = QueryStringService.getStateValue($stateParams, 'state', null);
        $scope.selectedOs = QueryStringService.getStateValue($stateParams, 'selectedOs', null);
        $scope.typeId = QueryStringService.getStateValue($stateParams, 'typeId', null);
        $scope.title = QueryStringService.getStateValue($stateParams, 'title', null);
        $scope.hostId = QueryStringService.getStateValue($stateParams, 'hostId', null);

        $scope.data = {
            dnsLookUp: LocalStorageService.getItemWithDefault('HostsDnsLookUpEnabled', 'false') === 'true',
            dnsHostnameNotFound: false,
            dnsAddressNotFound: false
        };

        $scope.useExistingHost = ($scope.typeId === 'prometheus') ? true : false;


        $scope.post = {
            Host: {
                name: '',
                description: '',
                hosttemplate_id: 0,
                address: '',
                container_id: 0,
                hosts_to_containers_sharing: {
                    _ids: []
                },
                satellite_id: 0
            }
        };

        $scope.isHostnameInUse = false;

        var setValuesFromHosttemplate = function(){
            var fields = [
                'description',
                'hosttemplate_id',
                'address'
            ];

            for(var index in fields){
                var field = fields[index];
                if($scope.hosttemplate.Hosttemplate.hasOwnProperty(field)){
                    $scope.post.Host[field] = $scope.hosttemplate.Hosttemplate[field];
                }
            }
        };

        var highlight = function($selector){
            $selector = $selector.parent();
            var $div = $('<div class="highlight"></div>');
            $div.css({
                'width': $selector.width() + 'px',
                'height': $selector.height() + 'px',
                'left': $selector.css('padding-left')
            });
            $selector.append($div);
            $div.fadeOut(800, function(){
                $div.remove();
            });
        };

        $scope.loadContainers = function(){
            var params = {
                'angular': true
            };

            $http.get("/hosts/loadContainers.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.loadElements = function(){
            var containerId = $scope.post.Host.container_id;
            //May be triggered by watch from "Create another"
            if(containerId === 0){
                return;
            }

            $http.post("/wizards/loadElementsByContainerId/" + containerId + ".json?angular=true", {
                empty: true
            }).then(function(result){
                $scope.hosttemplates = result.data.hosttemplates;
                $scope.exporters = result.data.exporters;
                $scope.satellites = result.data.satellites;
            });
        };

        $scope.loadHosts = function(searchString){
            $http.get("/wizards/loadHostsByString.json", {
                params: {
                    'angular': true,
                    'typeId': $scope.typeId,
                    'filter[Hosts.name]': searchString
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
                $scope.additionalInfo = result.data.additionalInfo;
            });
        };

        $scope.loadHosttemplate = function(){
            var hosttemplateId = $scope.post.Host.hosttemplate_id;
            if(hosttemplateId === 0){
                //May be triggered by watch from "Create another"
                $scope.init = false;
                return;
            }

            $http.post("/hosts/loadHosttemplate/" + hosttemplateId + ".json?angular=true", {
                empty: true
            }).then(function(result){
                $scope.hosttemplate = result.data.hosttemplate;
                setValuesFromHosttemplate();
            });

            setTimeout(function(){
                //Enable post.Host.command_id $watch
                $scope.init = false;
            }, 500);
        };

        $scope.runDnsLookup = function(lookupByHostname){
            $scope.data.dnsHostnameNotFound = false;
            $scope.data.dnsAddressNotFound = false;
            if($scope.data.dnsLookUp === false){
                return;
            }
            var data = {
                hostname: null,
                address: null
            };

            if(lookupByHostname){
                if($scope.post.Host.name == ''){
                    return;
                }
                data.hostname = $scope.post.Host.name;
            }else{
                if($scope.post.Host.address == ''){
                    return;
                }
                data.address = $scope.post.Host.address;
            }

            $http.post("/hosts/runDnsLookup.json?angular=true",
                data
            ).then(function(result){
                if(lookupByHostname){
                    var address = result.data.result.address;
                    if(address === null){
                        $scope.data.dnsHostnameNotFound = true;
                    }else{
                        $scope.data.dnsHostnameNotFound = false;
                        $scope.post.Host.address = address;
                        highlight($('#HostAddress'));
                    }
                }else{
                    var hostname = result.data.result.hostname;
                    if(hostname === null){
                        $scope.data.dnsAddressNotFound = true;
                    }else{
                        $scope.data.dnsAddressNotFound = false;
                        $scope.post.Host.name = hostname;
                        highlight($('#HostName'));
                        $scope.checkForDuplicateHostname();
                    }
                }
            }, function errorCallback(result){
                NotyService.genericError({
                    message: 'Error while running DNS lookup'
                });
            });
        };

        $scope.submit = function(){
            if($scope.useExistingHost === false){
                $http.post("/hosts/add.json?angular=true",
                    $scope.post
                ).then(function(result){
                    var hostId = result.data.id;
                    NotyService.genericSuccess();
                    $state.go($scope.state, {
                        hostId: hostId,
                        selectedOs: $scope.selectedOs,
                        typeId: $scope.typeId
                    }).then(function(){
                        NotyService.scrollTop();
                    });
                    console.log('Data saved successfully');
                }, function errorCallback(result){

                    NotyService.genericError();

                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });
            }else{
                $http.post("/wizards/validateInputFromAngular.json?angular=true", {
                        Host: {
                            id: $scope.hostId
                        }
                    }
                ).then(function(result){
                    $state.go($scope.state, {
                        hostId: $scope.hostId,
                        selectedOs: $scope.selectedOs,
                        typeId: $scope.typeId
                    }).then(function(){
                        NotyService.scrollTop();
                    });
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });
            }
        };

        // Fire on page load
        $scope.checkForDuplicateHostname = function(){
            $scope.checkedName = $scope.post.Host.name;
            var data = {
                hostname: $scope.checkedName
            };
            $http.post("/hosts/checkForDuplicateHostname.json?angular=true",
                data
            ).then(function(result){
                $scope.isHostnameInUse = result.data.isHostnameInUse;
            });
        };

        $scope.$watch('useExistingHost', function(){
            if($scope.init){
                return;
            }
            if($scope.useExistingHost === false){
                return;
            }

            $scope.loadHosts('');
        }, true);

        $scope.$watch('post.Host.container_id', function(){
            if($scope.post.Host.container_id == 1){
                $scope.showRootAlert = true;
            }else{
                $scope.showRootAlert = false;
            }
            $scope.loadElements();
        }, true);

        $scope.$watch('post.Host.hosttemplate_id', function(){
            if($scope.init){
                return;
            }

            $scope.init = true; //Disable post.Host.command_id $watch
            $scope.loadHosttemplate();
        }, true);

        $scope.$watch('data.dnsLookUp', function(){
            if($scope.init){
                return;
            }
            if($scope.useExistingHost === true){ //not necessary for existing host
                return;
            }

            if($scope.data.dnsLookUp === false){
                $scope.data.dnsHostnameNotFound = false;
                $scope.data.dnsAddressNotFound = false;
            }

            LocalStorageService.setItem('HostsDnsLookUpEnabled', $scope.data.dnsLookUp);
        }, true);

        $scope.loadContainers();
        $scope.loadHosts();
    });
