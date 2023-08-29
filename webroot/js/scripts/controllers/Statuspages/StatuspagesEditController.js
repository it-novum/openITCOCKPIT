angular.module('openITCOCKPIT')
    .controller('StatuspagesEditController', function($scope, $http, SudoService, $state, $stateParams, NotyService){

        $scope.id = $stateParams.id;

        $scope.showBase = false;

        $scope.post = {
            Statuspage: {
                containers: {
                    _ids: []
                },
                name: '',
                description: '',
                public: false,
                show_comments: false,
                statuspage_items: [],

            },

        };

        $scope.triggerBaseEdit = function(){
            $scope.showBase = !$scope.showBase === true;
            console.log($scope.post.Statuspage);
        };

        $scope.loadStatuspage = function(){
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


        //Fire on page load
        $scope.loadStatuspage();

    });
