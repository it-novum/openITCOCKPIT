angular.module('openITCOCKPIT')
    .controller('ConfigurationitemsImportController', function($scope, $http, NotyService){

        $scope.init = true;
        $scope.hasError = null;

        Dropzone.autoDiscover = false;


        $scope.load = function(){
            console.log('test 123');
            $http.get("/configurationitems/import.json", {
            }).then(function(result){
                $scope.maxUploadLimit = result.data.maxUploadLimit;
                $scope.init = false;
                createDropzone();
            });
        };

        $scope.removeFile = function(file){
            if(!$scope.uploadedFile || $scope.uploadedFilenameOrigin !== file.name){
                file.previewElement.parentNode.removeChild(file.previewElement);
                return;
            }
            $http.post("/configurationitems/deleteUploadedFile.json?angular=true",
                {
                    'filename': $scope.uploadedFile
                }
            ).then(function(result){
                var type = result.data.response.success === false ? 'error' : 'success';
                new Noty({
                    theme: 'metroui',
                    type: type,
                    text: result.data.response.message,
                    timeout: 3500
                }).show();
                // $('.dz-preview').remove();
                file.previewElement.parentNode.removeChild(file.previewElement);
                $scope.uploadedFile = null;
                $scope.uploadedFilenameOrigin = null;
                $scope.previewData = [];

            }, function errorCallback(result){
                var text = '';
                if(result.data && result.data.hasOwnProperty('message')){
                    text = result.data.message;
                }

                if(result.data && result.data.hasOwnProperty('response')){
                    text = result.data.response.message;
                }

                new Noty({
                    theme: 'metroui',
                    type: 'error',
                    text: text,
                    timeout: 3500
                }).show();
            });
        };

        $scope.deleteUploadedFile = function(){
            if(!$scope.uploadedFile){
                return;
            }
            $http.post("/import_module/imported_hosts/deleteUploadedFile.json?angular=true",
                {
                    'filename': $scope.uploadedFile
                }
            ).then(function(result){
                var type = result.data.response.success === false ? 'error' : 'success';
                new Noty({
                    theme: 'metroui',
                    type: type,
                    text: result.data.response.message,
                    timeout: 3500
                }).show();
                $scope.uploadedFile = null;
                $scope.uploadedFilenameOrigin = null;
                $scope.previewData = [];
            }, function errorCallback(result){
                var text = '';
                if(result.data && result.data.hasOwnProperty('message')){
                    text = result.data.message;
                }

                if(result.data && result.data.hasOwnProperty('response')){
                    text = result.data.response.message;
                }

                new Noty({
                    theme: 'metroui',
                    type: 'error',
                    text: text,
                    timeout: 3500
                }).show();
            });
        };

        $('#importJsonFile').on('hidden.bs.modal', function(){
            //reset importer id if modal has been closed
            $scope.selectedImporterId = null;

            $scope.jsonDropzone.disable();
            if($scope.importSuccessfullyFinished === false){
                $scope.deleteUploadedFile();
            }
        });

        var createDropzone = function(){
            if($scope.dropzoneCreated === true){
                $scope.jsonDropzone.options.url = '/configurationitems/import.json?angular=true';
                $scope.jsonDropzone.enable();
                $scope.jsonDropzone.removeAllFiles(true);
                return;
            }
            $scope.jsonDropzone = new Dropzone('#jsonDropzone', {
                method: 'post',
                maxFilesize: $scope.maxUploadLimit.value, //MB
                acceptedFiles: '.json', //mimetypes
                paramName: "file",
                uploadMultiple: false,
                parallelUploads: 1,
                clickable: true,
                maxFiles: 1,
                addRemoveLinks: true,
                sendingMultiple: false,
                url: '/configurationitems/import.json?angular=true',
                success: function(file, response){
                    var $previewElement = $(file.previewElement);
                    var xhrResponse = JSON.parse(file.xhr.response);
                    $scope.uploadedFile = response.response.filename;
                    $scope.uploadedFilenameOrigin = response.response.filenameOrigin;
                    if(xhrResponse.response.success){
                        $previewElement.removeClass('dz-processing');
                        $previewElement.addClass('dz-success');
                        $scope.previewData = xhrResponse.response.previewData;
                        $scope.numberOfHeaders = Object.keys($scope.previewData.headers).length;
                        $scope.importProcessRun = false;
                        $scope.errors = null;

                        if($scope.previewData.hasOwnProperty('errors')){
                            $scope.errors = $scope.previewData.errors;
                        }
                        $scope.$apply();
                        new Noty({
                            theme: 'metroui',
                            type: 'success',
                            text: xhrResponse.response.message,
                            timeout: 3500
                        }).show();
                        return;
                    }

                    $previewElement.removeClass('dz-processing');
                    $previewElement.addClass('dz-error');
                    if(xhrResponse.response.message){
                        new Noty({
                            theme: 'metroui',
                            type: 'error',
                            text: xhrResponse.response.message,
                            timeout: 3500
                        }).show();
                    }
                },
                removedfile: function(file){
                    $scope.removeFile(file);
                },
                sending: function(){
                    setTimeout(function(){
                    }, 1000);
                },
                error: function(file, errorMessage, xhr){
                    var $previewElement = $(file.previewElement);

                    $previewElement.removeClass('dz-processing');
                    $previewElement.addClass('dz-error');
                    $('.dz-error-message span').text(errorMessage);
                    $scope.errors = null;
                    $scope.$apply();

                    if(typeof xhr === "undefined"){
                        new Noty({
                            theme: 'metroui',
                            type: 'error',
                            text: errorMessage,
                            timeout: 3500
                        }).show();
                    }else{
                        var xhrResponse = JSON.parse(obj.xhr.response);
                        new Noty({
                            theme: 'metroui',
                            type: 'error',
                            text: xhrResponse.response.message,
                            timeout: 3500
                        }).show();
                    }
                },
            });
            $scope.dropzoneCreated = true;
        };



        $scope.submit = function(){

        };


        //Fire on page load
        $scope.load();
    })
;
