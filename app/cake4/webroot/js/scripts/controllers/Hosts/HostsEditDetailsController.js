angular.module('openITCOCKPIT')
    .controller('HostsEditDetailsController', function($scope, $http, SudoService, $state, $stateParams, NotyService, RedirectService){
        var ids = $stateParams.ids.split(',');

        if(ids.length === 0 || ids[0] === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('HostsIndex');
            return;
        }

        $scope.init = true;

        $scope.post = {
            Host: {
                hosts_to_containers_sharing: {
                    _ids: []
                },
                description: '',
                host_url: null,
                tags: null,

                check_interval: null,
                retry_interval: null,
                max_check_attempts: null,
                notification_interval: null,
                notes: null,
                priority: null,
                contacts: {
                    _ids: []
                },
                contactgroups: {
                    _ids: []
                }
            },
            keepSharedContainers: false,
            keepContacts: false,
            keepContactgroups: false,
            editSharedContainers: false,
            editDescription: false,
            editTags: false,
            editPriority: false,
            editCheckInterval: false,
            editRetryInterval: false,
            editMaxNumberOfCheckAttempts: false,
            editNotificationInterval: false,
            editContacts: false,
            editContactgroups: false,
            editHostUrl: false,
            editNotes: false
        }
        $scope.load = function(){

            $http.get("/hosts/edit_details/" + ids.join('/') + ".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.hostIds = ids;
                $scope.contacts = result.data.contacts;
                $scope.contactgroups = result.data.contactgroups;
                $scope.sharingContainers = result.data.sharingContainers;


                $scope.init = false;

            });
        };

        $scope.editDetails = function(){
            var params = {
                'hosts': $scope.hostIds,
                'details': $scope.post
            };
            console.log('TEST');
            console.log(params);
            $http.post("/hosts/edit_details/.json?angular=true", {
                    data: params
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

        jQuery(function(){
            $('.tagsinput').tagsinput();
        });

        $scope.$watch('post.editSharedContainers', function(){
            if($scope.post.editSharedContainers === false){
                $scope.post.Host.hosts_to_containers_sharing._ids = [];
                $scope.post.keepSharedContainers = false;
            }
        });
        $scope.$watch('post.editDescription', function(){
            if($scope.post.editDescription === false){
                $scope.post.Host.description = null;
            }
        });
        $scope.$watch('post.editTags', function(){
            if($scope.post.editTags === false){
                $scope.post.Host.tags = null;
                $('.tagsinput').tagsinput('removeAll');
            }
        });
        $scope.$watch('post.editPriority', function(){
            if($scope.post.editPriority === false){
                $scope.post.Host.priority = 0;
            }else{
                $scope.post.Host.priority = 1;
            }
        });
        $scope.$watch('post.editCheckInterval', function(){
            if($scope.post.editCheckInterval === false){
                $scope.post.Host.check_interval = null;
            }
        });
        $scope.$watch('post.editRetryInterval', function(){
            if($scope.post.editRetryInterval === false){
                $scope.post.Host.retry_interval = null;
            }
        });
        $scope.$watch('post.editMaxNumberOfCheckAttempts', function(){
            if($scope.post.editMaxNumberOfCheckAttempts === false){
                $scope.post.Host.max_check_attempts = null;
            }
        });
        $scope.$watch('post.editNotificationInterval', function(){
            if($scope.post.editNotificationInterval === false){
                $scope.post.Host.notification_interval = null;
            }
        });
        $scope.$watch('post.editContacts', function(){
            if($scope.post.editContacts === false){
                $scope.post.Host.contacts._ids = [];
                $scope.post.keepContacts = false;
            }
        });
        $scope.$watch('post.editContactgroups', function(){
            if($scope.post.editContactgroups === false){
                $scope.post.Host.contactgroups._ids = [];
                $scope.post.keepContactgroups = false;
            }
        });
        $scope.$watch('post.editHostUrl', function(){
            if($scope.post.editHostUrl === false){
                $scope.post.Host.host_url = null;
            }
        });
        $scope.$watch('post.editNotes', function(){
            if($scope.post.editNotes === false){
                $scope.post.Host.notes = null;
            }
        });
    });
