angular.module('openITCOCKPIT').directive('topSearch', function($state, $http, NotyService, UuidService){
    return {
        restrict: 'A',
        templateUrl: '/angular/topSearch.html',

        controller: function($scope){

            $scope.searchStr = '';
            $scope.isSearching = false;

            $scope.setSearchType = function(type, name){
                $scope.type = type;
                $scope.name = name;
            };

            $scope.doSearch = function(){
                if($scope.searchStr === ''){
                    return;
                }

                $scope.isSearching = true;

                if(UuidService.isUuid($scope.searchStr)){
                    $scope.setSearchType('uuid', 'UUID');
                }

                switch($scope.type){
                    case 'host':
                        $state.go('HostsIndex', {
                            hostname: $scope.searchStr
                        }).then(function(d){
                            $scope.isSearching = false;
                        });
                        break;

                    case 'service':
                        $state.go('ServicesIndex', {
                            servicename: $scope.searchStr
                        }).then(function(d){
                            $scope.isSearching = false;
                        });
                        break;

                    case 'uuid':
                        $http.post("/angular/topSearch.json?angular=true",
                            {
                                type: $scope.type,
                                searchStr: $scope.searchStr
                            }
                        ).then(function(result){
                            if(result.data.hasOwnProperty('state')){
                                $state.go(result.data.state, {
                                    id: result.data.id
                                }).then(function(d){
                                    $scope.isSearching = false;
                                });
                            }else{
                                //Result is missing the AngularJS state???
                                //How ever - re-enable search
                                $scope.isSearching = false;
                            }

                        }, function errorCallback(result){
                            $scope.isSearching = false;

                            if(result.status === 403){
                                $state.go('403');
                                return;
                            }

                            if(result.status === 404){
                                $state.go('404');
                                return;
                            }

                            NotyService.genericError({
                                message: 'Unknown error'
                            });
                        });

                        break;
                }
            };

            $scope.isReturnKey = function($event){
                const RETURN_KEY = 13;
                var keyCode = $event.keyCode;

                if(keyCode === RETURN_KEY){
                    $scope.searchStr = $scope.searchStr.trim();
                    $scope.doSearch();
                }
            };

            //Fire on pageload

        },

        link: function($scope, element, attrs){

        }
    };
});
