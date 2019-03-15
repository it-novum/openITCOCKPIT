angular.module('openITCOCKPIT')
    .controller('UsersAddFromLdapController', function($scope, $http){


        $scope.init = true;

        $scope.post = {
            'User': {
                'status': '',
                'email': '',
                'samaccountname':'',
                'firstname': '',
                'lastname': '',
                'company': '',
                'position': '',
                'phone': '',
                'password': '',
                'usergroup_id': '',
                'showstatsinmenu': 0,
                'paginatorlength': 25,
                'dashboard_tab_rotation': 0,
                'recursive_browser': 0,
                'dateformat': '',
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
                'ContainersUsersMemberships': {}
            }
        };

        $scope.data = {
            selectedSamAccountName: ''
        };
        $scope.errors = false;

      /*  $scope.loadUsers = function(searchString){
            $http.get("/users/addFromLdap.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.users = result.data.usersForSelect;
                $scope.isPhp7Dot1 = result.data.isPhp7Dot1;
            });
        };*/

        $scope.loadContainer = function(){
            $http.get("/containers/loadContainersForAngular.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
            });
        };

        $scope.loadUsergroups = function(){
            $http.get("/usergroups/loadUsergroups.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.usergroups = result.data.usergroups;
            });
        };

        $scope.loadStatus = function(){
            $http.get("/users/loadStatus.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.status = result.data.status;
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

        $scope.loadUsersByString = function(searchString){
            $http.get("/users/loadLdapUserByString.json", {
                params: {
                    'angular': true,
                    'samaccountname': searchString
                }
            }).then(function(result){
                console.log(result.data);
                $scope.usersForSelect = result.data.usersForSelect;
            });
        };

        $scope.loadSystemsettings = function(){
            $http.get("/systemsettings/getSystemsettingsForAngularBySection.json", {
                params: {
                    'section': 'FRONTEND',
                    'angular': true,
                }
            }).then(function(result){
                $scope.systemsettings = result.data.systemsettings;
            });
        };
        $scope.submit = function(){

            console.log($scope.data.selectedSamAccountName);

            if($scope.data.selectedSamAccountName.length === 0){
                $scope.errors = [
                    'Please select one user'
                ];
                return false;
            }

            window.location.href = '/users/add/ldap:1/samaccountname:' + encodeURI($scope.data.selectedSamAccountName) + '/fix:1';
        };



        $scope.loadContainer();
        $scope.loadStatus();
        $scope.loadDateformats();
        $scope.loadUsergroups();
        $scope.loadSystemsettings();
        $scope.loadUsersByString();

    });