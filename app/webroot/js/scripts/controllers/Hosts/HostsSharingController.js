angular.module('openITCOCKPIT')
    .controller('HostsSharingController', function($scope, $http, SudoService, $state, $stateParams, NotyService, RedirectService){

        $scope.init = true;

        $scope.id = $stateParams.id;

        $scope.post = {
            Host: {
                id: 0,
                hosts_to_containers_sharing: {
                    _ids: []
                }
            }
        };


        $scope.load = function(){
            var params = {
                'angular': true
            };

            $http.get("/hosts/sharing/"+$scope.id+".json", {
                params: params
            }).then(function(result){
                $scope.primaryContainerPathSelect = result.data.primaryContainerPathSelect;
                $scope.host = result.data.host.Host;
                $scope.sharingContainers = result.data.sharingContainers;

                $scope.post.Host.id = result.data.host.Host.id;
                $scope.post.Host.hosts_to_containers_sharing._ids = result.data.host.Host.hosts_to_containers_sharing._ids;

                $scope.init = false;
            });
        };

        $scope.submit = function(){
            $http.post("/hosts/sharing/"+$scope.id+".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('HostsSharing', {id: $scope.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });
                RedirectService.redirectWithFallback('HostsIndex');

                console.log('Data saved successfully');
            }, function errorCallback(result){

                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;

                    if($scope.errors.hasOwnProperty('customvariables')){
                        if($scope.errors.customvariables.hasOwnProperty('custom')){
                            $scope.errors.customvariables_unique = [
                                $scope.errors.customvariables.custom
                            ];
                        }
                    }
                }
            });

        };

        $scope.load();

    });
