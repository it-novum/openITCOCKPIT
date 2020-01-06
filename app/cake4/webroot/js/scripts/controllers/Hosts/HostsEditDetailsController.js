angular.module('openITCOCKPIT')
    .controller('HostsEditDetailsController', function($scope, $http, SudoService, $state, $stateParams, NotyService, RedirectService){
        var ids = $stateParams.ids.split(',');

        if(ids.length === 0 || ids[0] === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('HostsIndex');
            return;
        }

        $scope.init = true;
        $scope.id = $stateParams.id;

        $scope.post = {
            Host: {
                hosts_to_containers_sharing: {
                    _ids: []
                },
                description: '',
                contacts: {
                    _ids: []
                },
                contactgroups: {
                    _ids: []
                },
                host_url: '',
                tags: '',
                priority: null
            }
        };
        $scope.editSharedContainers = false;
        $scope.editDescription = false;
        $scope.editTags = false;
        $scope.editPriority = false;
        $scope.editCheckInterval = false;
        $scope.editRetryInterval = false;
        $scope.editMaxNumberOfCheckAttempts = false;
        $scope.editNotificationInterval = false;
        $scope.editContacts = false;
        $scope.editContactgroups = false;
        $scope.editHostUrl = false;
        $scope.editNotes = false;


        $scope.load = function(){

            $http.get("/hosts/edit_details/" + ids.join('/') + ".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.contacts = result.data.contacts;
                $scope.contactgroups = result.data.contactgroups;
                $scope.sharingContainers = result.data.sharingContainers;


                $scope.init = false;

            });
        };

        $scope.editDetails = function(){
            $http.post("/hosts/edit_details/.json?angular=true",
                {
                    data: $scope.sourceHosts
                }
            ).then(function(result){
                NotyService.genericSuccess();
                //RedirectService.redirectWithFallback('HostsIndex');
            }, function errorCallback(result){
                //Print errors
                NotyService.genericError();
                $scope.sourceHosts = result.data.result;
            });
        };

        $scope.setPriority = function(priority){
            $scope.post.Host.priority = parseInt(priority, 10);
        };

        $scope.load();
    });
