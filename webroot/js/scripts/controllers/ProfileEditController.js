angular.module('openITCOCKPIT')
    .controller('ProfileEditController', function($scope, $http, $state, $stateParams, $rootScope, NotyService){

        $scope.init = true;
        $scope.apikeys = [];
        $scope.isLdapAuth = false;
        $scope.localeOptions = [];

        $scope.post = {
            //contains every form from template
            User: {
                firstname: '',
                lastname: '',
                email: '',
                phone: '',
                showstatsinmenu: 0,
                recursive_browser: 0,
                paginatorlength: 25,
                dateformat: '',
                timezone: '',
                i18n: 'en_US'
            },
            Password: {
                current_password: null,
                password: null,
                confirm_password: null
            },
            Apikey: {
                apikey: '',
                description: '',
            }
        };

        $scope.load = function(){
            $http.get("/profile/edit.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){

                $scope.isLdapUser = result.data.isLdapUser;
                $scope.maxUploadLimit = result.data.maxUploadLimit;
                $scope.init = false;

                var data = result.data.user;
                data.password = '';
                data.confirm_password = '';

                $scope.post.User = data;

            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.loadApiKey = function(){
            $http.get("/profile/apikey.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.apikeys = result.data.apikeys;
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
            });
        };

        $scope.loadLocaleOptions = function(){
            return $http.get("/users/getLocaleOptions.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.localeOptions = result.data.localeOptions;
            });
        };

        createDropzone = function(){
            $('.profileImg-dropzone').dropzone({
                method: 'post',
                maxFilesize: $scope.maxUploadLimit.value, //MB
                acceptedFiles: 'image/gif,image/jpeg,image/png', //mimetypes
                paramName: "Picture",
                headers: {
                    'X-CSRF-TOKEN': $rootScope._csrfToken
                },
                success: function(obj){
                    var $previewElement = $(obj.previewElement);
                    var response = JSON.parse(obj.xhr.response);
                    if(response.success){
                        $previewElement.removeClass('dz-processing');
                        $previewElement.addClass('dz-success');
                        NotyService.genericSuccess({message: response.message});
                        $scope.showPageReloadRequired(); // defined in ReloadRequiredDirective
                        return;
                    }
                    $previewElement.removeClass('dz-processing');
                    $previewElement.addClass('dz-error');
                    NotyService.genericError({message: response.message});
                },
                error: function(obj, errorMessage, xhr){
                    var $previewElement = $(obj.previewElement);
                    $previewElement.removeClass('dz-processing');
                    $previewElement.addClass('dz-error');

                    if(typeof xhr === "undefined"){
                        NotyService.genericError({message: errorMessage});
                    }else{
                        var response = JSON.parse(obj.xhr.response);
                        NotyService.genericError({message: response.message});
                    }
                }
            });
        };

        $scope.createApiKey = function(apiKeyId){
            $scope.getNewApiKey();
            $('#angularCreateApiKeyModal').modal('show');
        };

        $scope.saveApiKey = function(){
            $http.post("/profile/create_apikey.json", {
                angular: true,
                Apikey: $scope.post.Apikey
            })
                .then(function(result){
                    $scope.newApiKey = null;
                    $scope.loadApiKey();
                    $('#angularCreateApiKeyModal').modal('hide');
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });
        };

        $scope.getNewApiKey = function(){
            $http.get("/profile/create_apikey.json?angular=true")
                .then(function(result){
                    $scope.newApiKey = result.data.apikey;
                    $scope.post.Apikey.apikey = $scope.newApiKey;

                    $scope.currentQrCode = result.data.qrcode;
                });
        };


        $scope.submitUser = function(){
            $http.post("/profile/edit.json?angular=true",
                {User: $scope.post.User}
            ).then(function(result){
                $scope.errors = {};
                NotyService.genericSuccess();
                $scope.showPageReloadRequired(); // defined in ReloadRequiredDirective

            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.submitPassword = function(){
            $http.post("/profile/changePassword.json?angular=true",
                {Password: $scope.post.Password}
            ).then(function(result){
                NotyService.genericSuccess({message: result.data.message});

                $scope.post.Password = {
                    current_password: null,
                    password: null,
                    confirm_password: null
                };
                $scope.errors = {};

            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.deleteUserImage = function(){
            $http.post("/profile/deleteImage.json?angular=true").then(function(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                    NotyService.genericError({message: $scope.errors});
                }else{
                    NotyService.genericSuccess();
                    $scope.showPageReloadRequired(); // defined in ReloadRequiredDirective
                }
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.load();
        $scope.loadDateformats();
        $scope.loadApiKey();
        $scope.loadLocaleOptions();

        $scope.$watch('maxUploadLimit', function(){
            if($scope.maxUploadLimit != null){
                createDropzone();
            }
        }, true);
    });
