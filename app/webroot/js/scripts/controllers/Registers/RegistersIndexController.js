angular.module('openITCOCKPIT')
    .controller('RegistersIndexController', function($scope, $http, NotyService){

        $scope.post = {
            Registers: {
                license: ''
            }
        };

        $scope.license = {};
        $scope.valid = false;

        var handleLicenseResponse = function(licenseResponse, showNotyMsg){
            if(licenseResponse.success === true){
                if(showNotyMsg){
                    NotyService.genericSuccess({
                        message: 'Valid openITCOCKPIT Enterprise license.',
                        timeout: 5500
                    });
                }
                $scope.license = licenseResponse.license;
                $scope.valid = true;
                return;
            }

            if(licenseResponse.error.hasOwnProperty('error')){
                //error is an object

                var errorMsg = '[ ' + licenseResponse.error.errno + ' ] ' + licenseResponse.error.error;
                NotyService.genericError({
                    message: errorMsg,
                    timeout: 5500
                });
            }else{
                //error is a string
                NotyService.genericError({
                    message: licenseResponse.error
                });
            }
        };

        $scope.load = function(){
            $http.get("/registers/index.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.hasLicense = result.data.hasLicense;
                if($scope.hasLicense){
                    $scope.post.Registers = result.data.license;
                }
                var licenseResponse = result.data.licenseResponse;
                handleLicenseResponse(licenseResponse, false);
            });
        };


        $scope.submit = function(){
            $http.post("/registers/index.json?angular=true",
                $scope.post
            ).then(function(result){
                var licenseResponse = result.data.licenseResponse;
                handleLicenseResponse(licenseResponse, true);
            }, function errorCallback(result){
                console.log(result);
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };


        $scope.load();


    });