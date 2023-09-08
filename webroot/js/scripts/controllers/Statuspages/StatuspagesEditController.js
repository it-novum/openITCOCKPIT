angular.module('openITCOCKPIT')
    .controller('StatuspagesEditController', function($scope, $http, SudoService, $state, $stateParams, NotyService){

        $scope.id = $stateParams.id;

        $scope.showBase = false;

        $scope.post = {
            Statuspage: {},
        };
        $scope.post = {
            Statuspages: {}
        };

        $scope.currentItem = {};

        $scope.triggerBaseEdit = function(){
            $scope.showBase = !$scope.showBase === true;
        };

        $scope.triggerItemEdit = function(){
            $('#addEditItemModal').modal('show');

        };

       /* $scope.loadStatuspage = function(){
            var params = {
                'angular': true
            };

            $http.get("/statuspages/edit/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Statuspage = result.data.statuspage;
                $scope.post.Statuspage.public = +result.data.statuspage.public;
                $scope.post.Statuspage.show_comments = +result.data.statuspage.show_comments;
                $scope.init = false;
            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
            $scope.loadContainers();
        }; */

        $scope.loadStatuspage = function(){
            var params = {
                'angular': true
            };

            $http.get("/statuspages/setAlias/" + $scope.id + ".json", {
                params: params
            }).then(function(result){
                $scope.post.Statuspage = result.data.Statuspage;
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
            var params = {
                'angular': true
            };

            $http.get("/containers/loadContainersForAngular.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.submitBase = function() {
            console.log('submit');
            $http.post("/statuspages/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result) {
                var url = $state.href('StatuspagesEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });
                RedirectService.redirectWithFallback('MapsIndex');
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadMoreItemObjects = function(searchString){
            console.log('load');
            if(typeof $scope.currentItem.type !== "undefined"){

                //Avoid duplicate search requests because of $scope.currentItem.object_id will be set to
                //null if the search result will not contain the current selected object_id. If object_id is null
                //the watchGroup will be triggerd. This will cause duplicate search requests and overwrite results
                var objectId = undefined;
                if(typeof $scope.currentItem.object_id !== 'undefined'){
                    if($scope.currentItem.object_id !== null && $scope.currentItem.object_id > 0){
                        objectId = $scope.currentItem.object_id;
                    }
                }

                if($scope.currentItem.type === 'host'){
                    loadHosts(searchString, objectId);
                }

                if($scope.currentItem.type === 'service'){
                    loadServices(searchString, objectId);
                }

                if($scope.currentItem.type === 'hostgroup'){
                    loadHostgroups(searchString, objectId);
                }

                if($scope.currentItem.type === 'servicegroup'){
                    loadServicegroups(searchString, objectId);
                }

                if($scope.currentItem.type === 'container'){
                    loadContainers(searchString, objectId);
                }
            }
        };

        var loadHosts = function(searchString, selected){
            if(typeof selected === "undefined"){
                selected = [];
            }

            $http.get("/hosts/loadHostsByString.json", {
                params: {
                    'angular': true,
                    'filter[Hosts.name]': searchString,
                    'selected[]': selected,
                    'includeDisabled': 'true'
                }
            }).then(function(result){
                $scope.itemObjects = result.data.hosts;
            });
        };

        var loadHostgroups = function(searchString, selected){
            if(typeof selected === "undefined"){
                selected = [];
            }

            $http.get("/hostgroups/loadHostgroupsByString.json", {
                params: {
                    'angular': true,
                    'filter[Containers.name]': searchString,
                    'selected[]': selected
                }
            }).then(function(result){
                $scope.itemObjects = result.data.hostgroups;
            });
        };
        var loadServices = function(searchString, selected){
            if(typeof selected === "undefined"){
                selected = [];
            }

            $http.get("/services/loadServicesByString.json", {
                params: {
                    'angular': true,
                    //'filter[Hosts.name]': searchString,
                    'filter[servicename]': searchString,
                    'selected[]': selected,
                    'includeDisabled': 'true'
                }
            }).then(function(result){

                var tmpServices = [];
                for(var i in result.data.services){
                    var tmpService = result.data.services[i];

                    var serviceName = tmpService.value.Service.name;
                    if(serviceName === null || serviceName === ''){
                        serviceName = tmpService.value.Servicetemplate.name;
                    }

                    tmpServices.push({
                        key: tmpService.key,
                        value: tmpService.value.Host.name + '/' + serviceName
                    });

                }

                $scope.itemObjects = tmpServices;
            });
        };

        var loadServicegroups = function(searchString, selected){
            if(typeof selected === "undefined"){
                selected = [];
            }

            $http.get("/servicegroups/loadServicegroupsByString.json", {
                params: {
                    'angular': true,
                    'filter[Containers.name]': searchString,
                    'selected[]': selected
                }
            }).then(function(result){
                $scope.itemObjects = result.data.servicegroups;
            });
        };

        $scope.saveItem = function() {
            console.log($scope.currentItem);
        };

        var loadContainers = function(searchString, selected){ };

        $scope.$watchGroup(['currentItem.type', 'currentItem.object_id'], function(){
            //Initial load objects (like hosts or services) if the user pick an object type
            //while creating a new object on the map
            var objectId = undefined;
            if(typeof $scope.currentItem.object_id !== 'undefined'){
                if($scope.currentItem.object_id !== null && $scope.currentItem.object_id > 0){
                    objectId = $scope.currentItem.object_id;
                }
            }

            if(typeof $scope.currentItem.type !== "undefined"){
                if($scope.currentItem.type === 'host'){
                    loadHosts('', objectId);
                }

                if($scope.currentItem.type === 'service'){
                    loadServices('', objectId);
                }

                if($scope.currentItem.type === 'hostgroup'){
                    loadHostgroups('', objectId);
                }

                if($scope.currentItem.type === 'servicegroup'){
                    loadServicegroups('', objectId);
                }

                if($scope.currentItem.type === 'container'){
                    loadContainers('', objectId);
                }
            }
        }, true);
        $scope.submit = function() {
            $http.post("/statuspages/setAlias/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('StatuspagesEdit', {id: $scope.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                $state.go('StatuspagesIndex').then(function(){
                    NotyService.scrollTop();
                });

            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        }

        //Fire on page load
        $scope.loadStatuspage();

    });
