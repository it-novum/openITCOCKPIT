angular.module('openITCOCKPIT')
    .controller('ProfileEditController', function($scope, $http, $state, $stateParams, NotyService){

        $scope.init = true;
        $scope.apikeys = [];
        $scope.isLdapAuth = false;

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
            },
            Picture: {
                picture: ''
            },
            Password: {
                current_password: null,
                password: null,
                confirm_password: null
            },
            Apikey: {
                apikey: '',
                description: ''
            }
        };


        $scope.load = function(){
            $http.get("/profile/edit.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post.User = result.data.user;
                $scope.maxUploadLimit = result.data.maxUploadLimit;
                $scope.init = false;
                if(result.data.user.samaccountname != null){
                    $scope.isLdapAuth = true;
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

        createDropzone = function(){
            $('.profileImg-dropzone').dropzone({
                method: 'post',
                maxFilesize: $scope.maxUploadLimit.value, //MB
                acceptedFiles: 'image/*', //mimetypes
                paramName: "file",
                success: function(obj){
                    var $previewElement = $(obj.previewElement);

                    var response = JSON.parse(obj.xhr.response);
                    if(response.response.success){
                        $previewElement.removeClass('dz-processing');
                        $previewElement.addClass('dz-success');

                        NotyService.genericSuccess({message: response.response.message});
                        return;
                    }

                    $previewElement.removeClass('dz-processing');
                    $previewElement.addClass('dz-error');

                    NotyService.genericError({message: response.response.message});
                },
                error: function(obj, errorMessage, xhr){
                    var $previewElement = $(obj.previewElement);
                    $previewElement.removeClass('dz-processing');
                    $previewElement.addClass('dz-error');

                    if(typeof xhr === "undefined"){
                        NotyService.genericError({message: errorMessage});
                    }else{
                        var response = JSON.parse(obj.xhr.response);
                        NotyService.genericError({message: response.response.message});
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
                    $scope.load();
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
                });
        };


        $scope.submitUser = function(){
            $http.post("/profile/edit.json?angular=true",
                {User: $scope.post.User}
            ).then(function(result){
                NotyService.genericSuccess();
                $scope.load();
            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.submitPicture = function(){
            $http.post("/profile/edit.json?angular=true",
                {Picture: $scope.post.Picture}
            ).then(function(result){
                NotyService.genericSuccess();
                $scope.load();
            }, function errorCallback(result){
                NotyService.genericError({message: result.error});
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.submitPassword = function(){
            $http.post("/profile/edit.json?angular=true",
                {Password: $scope.post.Password}
            ).then(function(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                    NotyService.genericError({message: $scope.errors});
                }else{
                    NotyService.genericSuccess();
                    $scope.load();
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

        $scope.$watch('init', function(){
            if($scope.maxUploadLimit != null){
                createDropzone();
            }
        }, true);
    });