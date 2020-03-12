angular.module('openITCOCKPIT')
    .controller('RegistersIndexController', function($scope, $http, NotyService){

        $scope.post = {
            Registers: {
                license: ''
            }
        };

        $scope.license = {};
        $scope.valid = false;
        $scope.scrollInterval = null;

        document.addEventListener("fullscreenchange", function(){
            if(document.fullscreenElement === null){
                $scope.fullscreen = false;

                if($scope.scrollInterval !== null){
                    clearInterval($scope.scrollInterval);
                }
                $('#credits-container').hide();
            }

        }, false);

        $scope.$on('$destroy', function(){
            if($scope.scrollInterval !== null){
                clearInterval($scope.scrollInterval);
            }
        });

        var handleLicenseResponse = function(licenseResponse, showNotyMsg, isCommunityLicense){
            if(licenseResponse === null){
                return;
            }

            var msg = 'Valid openITCOCKPIT Enterprise license.';
            if(isCommunityLicense){
                msg = 'Valid openITCOCKPIT Community license.';
            }

            $scope.hasLicense = false;
            if(licenseResponse.success === true){
                $scope.hasLicense = true;
                if(showNotyMsg){
                    NotyService.genericSuccess({
                        message: msg,
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
                var isCommunityLicense = result.data.isCommunityLicense;
                if($scope.hasLicense){
                    $scope.post.Registers = result.data.license;
                }
                var licenseResponse = result.data.licenseResponse;
                handleLicenseResponse(licenseResponse, false, isCommunityLicense);

                $scope.certImage = 'license-certificate-enterprise.svg';
                if(isCommunityLicense === true){
                    $scope.certImage = 'license-certificate-community.svg';
                }

            });
        };


        $scope.submit = function(){
            $http.post("/registers/index.json?angular=true",
                $scope.post
            ).then(function(result){
                var isCommunityLicense = result.data.isCommunityLicense;
                var licenseResponse = result.data.licenseResponse;
                handleLicenseResponse(licenseResponse, true, isCommunityLicense);

                $scope.certImage = 'license-certificate-enterprise.svg';
                if(isCommunityLicense === true){
                    $scope.certImage = 'license-certificate-community.svg';
                }

            }, function errorCallback(result){
                console.log(result);
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.toggleFullscreenMode = function(){

            $('#credits-container').show();

            var elem = document.getElementById('credits-container');
            if($scope.fullscreen === true){
                if(document.exitFullscreen){
                    document.exitFullscreen();
                }else if(document.webkitExitFullscreen){
                    document.webkitExitFullscreen();
                }else if(document.mozCancelFullScreen){
                    document.mozCancelFullScreen();
                }else if(document.msExitFullscreen){
                    document.msExitFullscreen();
                }
            }else{
                if(elem.requestFullscreen){
                    elem.requestFullscreen();
                }else if(elem.mozRequestFullScreen){
                    elem.mozRequestFullScreen();
                }else if(elem.webkitRequestFullscreen){
                    elem.webkitRequestFullscreen();
                }else if(elem.msRequestFullscreen){
                    elem.msRequestFullscreen();
                }

                $('#credits-container').css({
                    'width': $(window).width(),
                    'height': $(window).height()
                });

                //Move credits to the bottom out of the monitor
                var bottom = $('#credits').height() + 10;
                bottom = bottom * -1;
                $('#credits').css('bottom', bottom + 'px');

                var stopPosition = ($(window).height() / 2) - (250 / 2); //114 is height of oITC logo in px
                var stopInterval = ($(window).height() / 2) + 100;
                var marginTop = 1;

                $scope.scrollInterval = null;
                $scope.scrollInterval = setInterval(function(){
                    var bottom = parseInt($('#credits').css('bottom'), 10);
                    bottom++;
                    if(bottom > stopPosition){
                        marginTop++;
                        $('#credits-oitc-logo').css('margin-top', marginTop + 'px');
                    }else{
                        $('#credits').css('bottom', bottom + 'px');
                    }

                    if(marginTop > stopInterval){
                        clearInterval($scope.scrollInterval);
                    }

                }, 15);

                $scope.fullscreen = true;
            }
        };


        $scope.load();


    });
