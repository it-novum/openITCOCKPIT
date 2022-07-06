statuspageFullscreenApp.controller("StatuspageFullscreenLayoutController", function($scope, $http, $stateParams){
    console.log('fullscreen layout controller');
    $scope.load = function(){
        $http.get("/statuspages/view/.json", {
            params: {
                'angular': true
            }
        }).then(function(result){

        })
    };

  //  $scope.load();

});
