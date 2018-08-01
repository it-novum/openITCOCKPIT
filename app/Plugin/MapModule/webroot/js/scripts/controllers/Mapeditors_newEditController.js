angular.module('openITCOCKPIT')
    .controller('Mapeditors_newEditController', function($scope, $http, QueryStringService){

        $scope.init = true;
        $scope.id = QueryStringService.getCakeId();
        $scope.backgrounds = [];

        $scope.addNewObject = false;
        $scope.action = null;

        $scope.currentItem = {};

        $scope.load = function(){
            $http.get("/map_module/mapeditors_new/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.map = result.data.map;
                $scope.maxUploadLimit = result.data.maxUploadLimit;

                $scope.currentBackground = $scope.map.Map.background;

                if($scope.init){
                    createDropzones();
                    loadBackgroundImages();
                }

                $scope.init = false;
            });
        };

        $scope.openChangeMapBackgroundModal = function(){
            $('#changeBackgroundModal').modal('show');
        };

        $scope.changeBackground = function(background){
            $scope.map.Map.background = background.image;
        };

        var loadBackgroundImages = function(selectedImage){
            $http.get("/map_module/mapeditors_new/backgroundImages.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.backgrounds = result.data.backgrounds;

                if(typeof selectedImage !== "undefined"){
                    $scope.changeBackground({
                        image: selectedImage
                    });
                }
            });
        };

        var createDropzones = function(){
            $('.background-dropzone').dropzone({
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

                        new Noty({
                            theme: 'metroui',
                            type: 'success',
                            text: response.response.message,
                            timeout: 3500
                        }).show();

                        loadBackgroundImages(response.response.filename);
                        return;
                    }

                    $previewElement.removeClass('dz-processing');
                    $previewElement.addClass('dz-error');
                    new Noty({
                        theme: 'metroui',
                        type: 'error',
                        text: response.response.message,
                        timeout: 3500
                    }).show();
                },
                error: function(obj, errorMessage, xhr){
                    var $previewElement = $(obj.previewElement);
                    $previewElement.removeClass('dz-processing');
                    $previewElement.addClass('dz-error');

                    if(typeof xhr === "undefined"){
                        new Noty({
                            theme: 'metroui',
                            type: 'error',
                            text: errorMessage,
                            timeout: 3500
                        }).show();
                    }else{
                        var response = JSON.parse(obj.xhr.response);
                        new Noty({
                            theme: 'metroui',
                            type: 'error',
                            text: response.response.message,
                            timeout: 3500
                        }).show();
                    }
                }
            });
        };

        $scope.deleteBackground = function(background){
            $http.post("/map_module/BackgroundUploads/delete.json?angular=true",
                {
                    'filename': background.image
                }
            ).then(function(result){
                loadBackgroundImages();
                new Noty({
                    theme: 'metroui',
                    type: 'success',
                    text: result.data.response.message,
                    timeout: 3500
                }).show();
            }, function errorCallback(result){
                var text = '';
                if(result.data.hasOwnProperty('message')){
                    text = result.data.message;
                }

                if(result.data.hasOwnProperty('response')){
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

        $scope.setCurrentIconset = function(iconset){
            $scope.currentItem.iconset = iconset;
        };

        var loadIconsets = function(){
            $http.get("/map_module/mapeditors_new/getIconsets.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.iconsets = result.data.iconsets;
            });
        };

        $scope.addNewObjectFunc = function($event){
            if(!$scope.addNewObject){
                return;
            }

            $('#map-editor').css('cursor', 'default');
            $scope.addNewObject = false;

            switch($scope.action){
                case 'item':
                    $('#addEditMapItemModal').modal('show');

                    break;

                default:
                    new Noty({
                        theme: 'metroui',
                        type: 'warning',
                        text: 'Unknown action - sorry :(',
                        timeout: 3500
                    }).show();
                    break;
            }
            $scope.action = null;

            //Set X and Y poss of the new object
            $scope.currentItem.x = $event.originalEvent.clientX;
            $scope.currentItem.y = $event.originalEvent.clientY;
            //console.log($event);
        };

        $scope.addItem = function(){
            new Noty({
                theme: 'metroui',
                type: 'info',
                layout: 'topCenter',
                text: 'Click at the position on the map, where you want to create a new object.',
                timeout: 3500
            }).show();
            $('#map-editor').css('cursor', 'crosshair');
            $scope.addNewObject = true;
            $scope.action = 'item';
        };

        /**
         * Load more objects if the user type to search in a select box.
         * @param searchString
         */
        $scope.loadMoreItemObjects = function(searchString){
            if(typeof $scope.currentItem.itemObjectType !== "undefined"){
                if($scope.currentItem.itemObjectType === 'host'){
                    loadHosts(searchString);
                }

                if($scope.currentItem.itemObjectType === 'service'){
                    loadServices(searchString);
                }

                if($scope.currentItem.itemObjectType === 'hostgroup'){
                    loadHostgroups(searchString);
                }

                if($scope.currentItem.itemObjectType === 'servicegroup'){
                    loadServicegroups(searchString);
                }

                if($scope.currentItem.itemObjectType === 'map'){
                    loadMaps(searchString);
                }
            }
        };

        var loadHosts = function(searchString, selected){
            if(typeof selected === "undefined"){
                selected = [];
            }

            $http.get("/hosts/loadHostsByString.json", {
                params: {
                    'angular': true,
                    'filter[Host.name]': searchString,
                    'selected[]': selected
                }
            }).then(function(result){
                $scope.itemObjects = result.data.hosts;
            });
        };

        var loadServices = function(searchString, selected){
            if(typeof selected === "undefined"){
                selected = [];
            }

            $http.get("/services/loadServicesByString.json", {
                params: {
                    'angular': true,
                    'filter[Host.name]': searchString,
                    'filter[Service.servicename]': searchString,
                    'selected[]': selected
                }
            }).then(function(result){

                var tmpServices = [];
                for(var i in result.data.services){
                    var tmpService = result.data.services[i];

                    console.log(tmpService);

                    var serviceName = tmpService.value.Service.name;
                    if(serviceName === null || serviceName === ''){
                        serviceName = tmpService.value.Servicetemplate.name;
                    }

                    tmpServices.push({
                        key: tmpService.key,
                        value: tmpService.value.Host.name + '/' + serviceName
                    });

                }

                $scope.itemObjects = tmpServices;
            });
        };


        var loadHostgroups = function(searchString, selected){
            if(typeof selected === "undefined"){
                selected = [];
            }

            $http.get("/hostgroups/loadHostgroupsByString.json", {
                params: {
                    'angular': true,
                    'filter[Container.name]': searchString,
                    'selected[]': selected
                }
            }).then(function(result){
                $scope.itemObjects = result.data.hostgroups;
            });
        };

        var loadServicegroups = function(searchString, selected){
            if(typeof selected === "undefined"){
                selected = [];
            }

            $http.get("/servicegroups/loadServicegroupsByString.json", {
                params: {
                    'angular': true,
                    'filter[Container.name]': searchString,
                    'selected[]': selected
                }
            }).then(function(result){
                $scope.itemObjects = result.data.servicegroups;
            });
        };

        var loadMaps = function(searchString, selected){
            if(typeof selected === "undefined"){
                selected = [];
            }

            $http.get("/map_module/mapeditors_new/loadMapsByString.json", {
                params: {
                    'angular': true,
                    'filter[Map.name]': searchString,
                    'selected[]': selected,
                    'excluded[]': $scope.id
                }
            }).then(function(result){
                $scope.itemObjects = result.data.maps;
            });
        };


        $scope.load();
        loadIconsets();

        $('#mapToolbar').draggable({
            handle: "#mapToolsDragger",
            containment: "parent"
        });

        $scope.$watch('currentItem.itemObjectType', function(){
            //Initial load objects (like hosts or services) if the user pick an object type
            //while creating a new object on the map
            if(typeof $scope.currentItem.itemObjectType !== "undefined"){
                if($scope.currentItem.itemObjectType === 'host'){
                    loadHosts('');
                }

                if($scope.currentItem.itemObjectType === 'service'){
                    loadServices('');
                }

                if($scope.currentItem.itemObjectType === 'hostgroup'){
                    loadHostgroups('');
                }

                if($scope.currentItem.itemObjectType === 'servicegroup'){
                    loadServicegroups('');
                }

                if($scope.currentItem.itemObjectType === 'map'){
                    loadMaps('');
                }
            }
        }, true);

    });