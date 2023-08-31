/**
 * @link https://fullcalendar.io/docs/upgrading-from-v3
 */
angular.module('openITCOCKPIT')
    .controller('ChangecalendarsAddController', function($scope, $http, $state, $stateParams, $q, $compile, NotyService, RedirectService){

        $scope.data = {
            createAnother: false
        };

        $scope.defaultDate = new Date();

        var clearForm = function(){
            $scope.post = {
                Calendar: {
                    container_id: 0,
                    name: '',
                    description: ''
                }
            };
        };
        clearForm();

        $scope.events = [];

        $scope.init = true;

        $scope.load = function(){
            $q.all([
                $http.get("/containers/loadContainersForAngular.json", {
                    params: {
                        'angular': true
                    }
                }),
            ]).then(function(results){
                $scope.containers = results[0].data.containers;
                $scope.init = false;
            });
        };

        $scope.submit = function(){
            $scope.post.events = $scope.events;
            $http.post("/changecalendar_module/changecalendars/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('ChangecalendarsEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.message.objectName
                        + '</a></u> ' + $scope.message.message
                });

                if($scope.data.createAnother === false){
                    //RedirectService.redirectWithFallback('ChangecalendarsIndex');
                }else{
                    //clearForm();
                    //$scope.errors = {};
                    //NotyService.scrollTop();
                }
            }, function errorCallback(result){

                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };

        //Fire on page load
        $scope.load();
    });
