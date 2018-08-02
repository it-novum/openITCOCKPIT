angular.module('openITCOCKPIT')
    .controller('Mapeditors_newEditController', function($scope, $http, QueryStringService){

        $scope.init = true;
        $scope.id = QueryStringService.getCakeId();
        $scope.backgrounds = [];

        $scope.addNewObject = false;
        $scope.action = null;

        $scope.currentItem = {};
        $scope.maxZIndex = 0;

        $scope.grid = {
            enabled: true,
            size: 15
        };


        $scope.load = function(){
            $http.get("/map_module/mapeditors_new/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.map = result.data.map;
                $scope.maxUploadLimit = result.data.maxUploadLimit;
                $scope.maxZIndex = result.data.max_z_index;
                $scope.layers = result.data.layers;

                $scope.currentBackground = $scope.map.Map.background;

                if($scope.init){
                    createDropzones();
                    loadBackgroundImages();

                    setTimeout(makeDraggable, 250);
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

        $scope.addNewLayer = function(){
            $scope.maxZIndex++;

            var newZIndex = $scope.maxZIndex;
            newZIndex = newZIndex.toString();

            $scope.layers[newZIndex] = 'Layer ' + newZIndex;

            if($scope.currentItem.hasOwnProperty('z_index')){
                $scope.currentItem.z_index = newZIndex;
            }
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

            // Create currentItem skeleton
            // Set X and Y poss of the new object
            $scope.currentItem = {
                iconset: 'std_mid_64px',
                z_index: '0', //Yes we need this as a string!
                x: $event.originalEvent.clientX,
                y: $event.originalEvent.clientY
            };
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

        $scope.editItem = function(item){
            $scope.action = 'item';
            $scope.currentItem = item;
            $('#addEditMapItemModal').modal('show');
        };

        /**
         * Load more objects if the user type to search in a select box.
         * @param searchString
         */
        $scope.loadMoreItemObjects = function(searchString){
            if(typeof $scope.currentItem.type !== "undefined"){
                if($scope.currentItem.type === 'host'){
                    loadHosts(searchString);
                }

                if($scope.currentItem.type === 'service'){
                    loadServices(searchString);
                }

                if($scope.currentItem.type === 'hostgroup'){
                    loadHostgroups(searchString);
                }

                if($scope.currentItem.type === 'servicegroup'){
                    loadServicegroups(searchString);
                }

                if($scope.currentItem.type === 'map'){
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

        $scope.changeGridSize = function(size){
            $scope.grid.size = parseInt(size, 10);
            if($scope.grid.enabled){
                makeDraggable();
            }
        };

        var makeDraggable = function(){
            var options = {
                grid: false
            };

            if($scope.grid.enabled){
                options['grid'] = [
                    $scope.grid.size,
                    $scope.grid.size
                ];
            }

            $('.draggable').draggable(options);
        };


        $scope.load();
        loadIconsets();

        $('#mapToolbar').draggable({
            handle: "#mapToolsDragger",
            containment: "parent"
        });

        $scope.$watchGroup(['currentItem.type', 'currentItem.object_id'], function(){
            //Initial load objects (like hosts or services) if the user pick an object type
            //while creating a new object on the map
            var objectId = undefined;
            if(typeof $scope.currentItem.object_id !== 'undefined'){
                if($scope.currentItem.object_id !== null && $scope.currentItem.object_id > 0){
                    objectId = $scope.currentItem.object_id;
                }
            }

            if(typeof $scope.currentItem.type !== "undefined"){
                if($scope.currentItem.type === 'host'){
                    loadHosts('', objectId);
                }

                if($scope.currentItem.type === 'service'){
                    loadServices('', objectId);
                }

                if($scope.currentItem.type === 'hostgroup'){
                    loadHostgroups('', objectId);
                }

                if($scope.currentItem.type === 'servicegroup'){
                    loadServicegroups('', objectId);
                }

                if($scope.currentItem.type === 'map'){
                    loadMaps('', objectId);
                }
            }
        }, true);

        $scope.$watch('grid.enabled', function(){
            if($scope.init){
                return;
            }
            makeDraggable();
        }, true);

    });