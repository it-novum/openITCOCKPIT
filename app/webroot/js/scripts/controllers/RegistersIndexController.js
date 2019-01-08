angular.module('openITCOCKPIT')
    .controller('RegistersIndexController', function($scope, $http){

        $scope.post = {
            'Registers': {
                'license': ''
            }
        };

        $scope.license = null;
        $scope.errors = null;

        $scope.load = function(){
            $http.get("/registers/loadLicense.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post.Registers.license = result.data.license.license;
                $scope.isProductionEnv = result.data.license.productionEnv;
            });
        };

        $scope.checkLicense = function(){
            $http.get("/registers/checkLicense/" + $scope.post.Registers.license + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                console.log(result);
                if(result.data.hasOwnProperty('license')){
                    $scope.license = result.data.license;
                }

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error
                }

                // $scope.license.expire = $scope.formatDate($scope.license.expire);
                /* console.log($scope.license.expire);
                 console.log($scope.formatDate($scope.license.expire)); */
            });
        };


        $scope.submit = function(){
            $http.post("/registers/index.json?angular=true",
                $scope.post
            ).then(function(result){
                console.log('Data saved successfully');
                // window.location.href = '/registers/index';
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };


        $scope.load();

        $scope.$watch('post.Registers.license', function(){
            if($scope.post.Registers.license.length > 0){
                $scope.checkLicense();
            }
        }, true);

    });