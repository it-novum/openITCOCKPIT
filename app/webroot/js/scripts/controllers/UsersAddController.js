angular.module('openITCOCKPIT')
    .controller('UsersAddController', function($scope, $http, $rootScope, $state, NotyService){

        $scope.post = {
            'User': {
                'status': '',
                'email': '',
                'firstname': '',
                'lastname': '',
                'company': '',
                'position': '',
                'phone': '',
                'password': '',
                'usergroup_id': '',
                'showstatsinmenu':'',
                'paginatorlength':'',
                'dashboard_tab_rotation':'',
                'recursive_browser':'',
                'dateformat':'',
                'containers': {
                    /* example data CURRENTLY NOT USED!
                    0: {
                        'id': null, //container ID
                        '_joinData':{ //saving additional data to "through" table
                            'permission_level':null //radio button value
                        }
                    }
                    */
                },
                'ContainersUsersMemberships':{

                }
            }
        };



        $scope.loadContainer = function(){
            $http.get("/containers/loadContainersForAngular.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };

        $scope.loadUsergroups = function(){
            $http.get("/usergroups/loadUsergroups.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.usergroups = result.data.usergroups;
                $scope.init = false;
            });
        };

        $scope.loadStatus = function(){
            $http.get("/users/loadStatus.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.status = result.data.status;
                $scope.init = false;
            });
        };

        $scope.loadDateformats = function(){
            $http.get("/users/loadDateformats.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.dateformats = result.data.dateformats;
                $scope.post.User.dateformat = result.data.defaultDateFormat;

                $scope.init = false;
            });
        };



        $scope.submit = function(){
            console.log($scope.post);
            $http.post("/users/add.json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess();
                //$state.go('UsersIndex');

            }, function errorCallback(result){
                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.getContainerName = function(id){
            for(var c in $scope.containers){
                if($scope.containers[c].key == id){
                    return $scope.containers[c].value;
                }
            }
            return null;
        };


        $scope.loadContainer();
        $scope.loadUsergroups();
        $scope.loadStatus();
        $scope.loadDateformats();
    });

