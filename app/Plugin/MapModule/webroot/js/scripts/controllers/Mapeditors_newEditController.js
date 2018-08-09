angular.module('openITCOCKPIT')
    .controller('Mapeditors_newEditController', function($scope, $http, QueryStringService){

        $scope.init = true;
        $scope.id = QueryStringService.getCakeId();
        $scope.backgrounds = [];

        $scope.addNewObject = false;
        $scope.action = null;

        $scope.currentItem = {};
        $scope.maxZIndex = 0;
        $scope.clickCount = 1;

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

        //Gets called by on-click on the map editor contain
        $scope.addNewObjectFunc = function($event){
            if(!$scope.addNewObject){
                return;
            }

            var $mapEditor = $('#map-editor');

            switch($scope.action){
                case 'item':
                    $mapEditor.css('cursor', 'default');
                    $scope.addNewObject = false;

                    $('#addEditMapItemModal').modal('show');

                    // Create currentItem skeleton
                    // Set X and Y poss of the new object
                    $scope.currentItem = {
                        iconset: 'std_mid_64px',
                        z_index: '0', //Yes we need this as a string!
                        x: $event.offsetX,
                        y: $event.offsetY,
                        show_label: false,
                        label_possition: 2
                        //x: $event.pageX,
                        //y: $event.pageY
                    };

                    $scope.action = null;
                    break;

                case 'line':
                    if($scope.clickCount === 2){
                        //Endpoint of the line
                        $mapEditor.css('cursor', 'default');
                        $scope.addNewObject = false;
                        $scope.action = null;


                        $scope.currentItem['endX'] = $event.offsetX;
                        $scope.currentItem['endY'] = $event.offsetY;

                        $('#addEditMapLineModal').modal('show');
                    }

                    if($scope.clickCount === 1){

                        $scope.currentItem = {
                            z_index: '0', //Yes we need this as a string!
                            startX: $event.offsetX,
                            startY: $event.offsetY,
                            show_label: false
                        };

                        new Noty({
                            theme: 'metroui',
                            type: 'info',
                            layout: 'topCenter',
                            text: 'Click a second time to define the endpoint of the line.',
                            timeout: 3500
                        }).show();
                    }

                    $scope.clickCount++;
                    break;

                case 'gadget':
                    $mapEditor.css('cursor', 'default');
                    $scope.addNewObject = false;

                    $('#addEditMapGadgetModal').modal('show');

                    // Create currentItem skeleton
                    // Set X and Y poss of the new object
                    $scope.currentItem = {
                        type: 'service', //Gadgets are only available for services
                        z_index: '0', //Yes we need this as a string!
                        x: $event.offsetX,
                        y: $event.offsetY,
                        show_label: false,
                        label_possition: 2,
                        gadget: 'RRDGraph',
                        size_x: null,
                        size_y: null,
                        metric: null
                    };

                    $scope.action = null;
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

        $scope.saveItem = function(action){
            if(typeof action === 'undefined'){
                action = 'add_or_edit';
            }

            $scope.currentItem.map_id = $scope.id;
            $http.post("/map_module/mapeditors_new/saveItem.json?angular=true",
                {
                    'Mapitem': $scope.currentItem,
                    'action': action
                }
            ).then(function(result){
                $scope.errors = {};
                //Update possition in current scope json data
                if($scope.currentItem.hasOwnProperty('id')){
                    for(var i in $scope.map.Mapitem){
                        if($scope.map.Mapitem[i].id == $scope.currentItem.id){
                            $scope.map.Mapitem[i].x = $scope.currentItem.x;
                            $scope.map.Mapitem[i].y = $scope.currentItem.y;

                            //We are done here
                            break;
                        }
                    }
                }else{
                    //New created item
                    $scope.map.Mapitem.push(result.data.Mapitem.Mapitem);
                    setTimeout(makeDraggable, 250);
                }

                $('#addEditMapItemModal').modal('hide');
                genericSuccess();
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
                genericError();
            });
        };

        $scope.deleteItem = function(){
            $scope.currentItem.map_id = $scope.id;
            $http.post("/map_module/mapeditors_new/deleteItem.json?angular=true",
                {
                    'Mapitem': $scope.currentItem,
                    'action': 'delete'
                }
            ).then(function(result){
                //Remove item from current scope
                for(var i in $scope.map.Mapitem){
                    if($scope.map.Mapitem[i].id == $scope.currentItem.id){
                        $scope.map.Mapitem.splice(i, 1);

                        //We are done here
                        break;
                    }
                }

                $('#addEditMapItemModal').modal('hide');
                genericSuccess();
                $scope.currentItem = {};
            }, function errorCallback(result){
                genericError();
            });
        };

        $scope.addLine = function(){
            new Noty({
                theme: 'metroui',
                type: 'info',
                layout: 'topCenter',
                text: 'Click at the position on the map, where the line should start.',
                timeout: 3500
            }).show();
            $scope.clickCount = 1;
            $('#map-editor').css('cursor', 'crosshair');
            $scope.addNewObject = true;
            $scope.action = 'line';
        };

        $scope.editLine = function(lineItem){
            $scope.action = 'line';
            $scope.currentItem = lineItem;
            $('#addEditMapLineModal').modal('show');
        };

        $scope.saveLine = function(action){
            if(typeof action === 'undefined'){
                action = 'add_or_edit';
            }

            $scope.currentItem.map_id = $scope.id;

            if($scope.currentItem.type === 'stateless'){
                $scope.currentItem.object_id = null;
            }

            $http.post("/map_module/mapeditors_new/saveLine.json?angular=true",
                {
                    'Mapline': $scope.currentItem,
                    'action': action
                }
            ).then(function(result){
                $scope.errors = {};
                //Update possition in current scope json data
                if($scope.currentItem.hasOwnProperty('id')){
                    for(var i in $scope.map.Mapline){
                        if($scope.map.Mapline[i].id == $scope.currentItem.id){
                            $scope.map.Mapline[i].startX = $scope.currentItem.startX;
                            $scope.map.Mapline[i].startY = $scope.currentItem.startY;
                            $scope.map.Mapline[i].endx = $scope.currentItem.endX;
                            $scope.map.Mapline[i].endY = $scope.currentItem.endY;

                            //We are done here
                            break;
                        }
                    }
                }else{
                    //New created item
                    $scope.map.Mapline.push(result.data.Mapline.Mapline);
                }

                $('#addEditMapLineModal').modal('hide');
                genericSuccess();
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
                genericError();
            });
        };

        $scope.deleteLine = function(){
            $scope.currentItem.map_id = $scope.id;
            $http.post("/map_module/mapeditors_new/deleteLine.json?angular=true",
                {
                    'Mapline': $scope.currentItem,
                    'action': 'delete'
                }
            ).then(function(result){
                //Remove item from current scope
                for(var i in $scope.map.Mapline){
                    if($scope.map.Mapline[i].id == $scope.currentItem.id){
                        $scope.map.Mapline.splice(i, 1);

                        //We are done here
                        break;
                    }
                }

                $('#addEditMapLineModal').modal('hide');
                genericSuccess();
                $scope.currentItem = {};
            }, function errorCallback(result){
                genericError();
            });
        };

        $scope.addGadget = function(){
            new Noty({
                theme: 'metroui',
                type: 'info',
                layout: 'topCenter',
                text: 'Click at the position on the map, where you want to place the new Gadget.',
                timeout: 3500
            }).show();
            $('#map-editor').css('cursor', 'crosshair');
            $scope.addNewObject = true;
            $scope.action = 'gadget';
        };

        $scope.editGadget = function(gadgetItem){
            $scope.action = 'gadget';
            $scope.currentItem = gadgetItem;
            $('#addEditMapGadgetModal').modal('show');
        };

        $scope.saveGadget = function(action){
            if(typeof action === 'undefined'){
                action = 'add_or_edit';
            }

            $scope.currentItem.map_id = $scope.id;

            if(action === 'add_or_edit'){
                if($scope.currentItem.gadget !== 'TrafficLight'){
                    if($scope.currentItem.hasOwnProperty('metric') === false || $scope.currentItem.metric === null){
                        $scope.errors = {
                            metric: [
                                'Please select a metric.'
                            ]
                        };
                        return;
                    }
                }
            }

            $http.post("/map_module/mapeditors_new/saveGadget.json?angular=true",
                {
                    'Mapgadget': $scope.currentItem,
                    'action': action
                }
            ).then(function(result){
                if(action === 'resizestop'){
                    genericSuccess();
                    //Nothing needs to be updated
                    return;
                }

                $scope.errors = {};
                //Update possition in current scope json data
                if($scope.currentItem.hasOwnProperty('id')){
                    for(var i in $scope.map.Mapgadget){
                        if($scope.map.Mapgadget[i].id == $scope.currentItem.id){
                            $scope.map.Mapgadget[i].x = $scope.currentItem.x;
                            $scope.map.Mapgadget[i].y = $scope.currentItem.y;

                            //We are done here
                            break;
                        }
                    }
                }else{
                    //New created item
                    $scope.map.Mapgadget.push(result.data.Mapgadget.Mapgadget);
                    setTimeout(makeDraggable, 250);
                }

                $('#addEditMapGadgetModal').modal('hide');
                genericSuccess();
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
                genericError();
            });
        };

        $scope.deleteGadget = function(){
            $scope.currentItem.map_id = $scope.id;
            $http.post("/map_module/mapeditors_new/deleteGadget.json?angular=true",
                {
                    'Mapgadget': $scope.currentItem,
                    'action': 'delete'
                }
            ).then(function(result){
                //Remove item from current scope
                for(var i in $scope.map.Mapgadget){
                    if($scope.map.Mapgadget[i].id == $scope.currentItem.id){
                        $scope.map.Mapgadget.splice(i, 1);

                        //We are done here
                        break;
                    }
                }

                $('#addEditMapGadgetModal').modal('hide');
                genericSuccess();
                $scope.currentItem = {};
            }, function errorCallback(result){
                genericError();
            });
        };

        var loadMetrics = function(){
            $http.get("/map_module/mapeditors_new/getPerformanceDataMetrics/" + $scope.currentItem.object_id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                var metrics = {};

                var firstMetric = null;

                for(var metricName in result.data.perfdata){
                    if(firstMetric === null){
                        firstMetric = metricName;
                    }

                    metrics[metricName] = metricName;
                }

                if($scope.currentItem.metric === null){
                    $scope.currentItem.metric = firstMetric;
                }

                $scope.metrics = metrics;
            });
        };

        var genericSuccess = function(){
            new Noty({
                theme: 'metroui',
                type: 'success',
                text: 'Data saved successfully',
                timeout: 3500
            }).show();
        };

        var genericError = function(){
            new Noty({
                theme: 'metroui',
                type: 'error',
                text: 'Error while saving data',
                timeout: 3500
            }).show();
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
                grid: false,
                stop: function(event){
                    var $this = $(this);
                    var x = $this.css('left');
                    var y = $this.css('top');
                    var id = $this.data('id');
                    var type = $this.data('type');

                    x = parseInt(x.replace('px', ''), 10);
                    y = parseInt(y.replace('px', ''), 10);

                    switch(type){
                        case 'item':
                            $scope.currentItem = {
                                id: id,
                                x: x,
                                y: y
                            };
                            $scope.saveItem('dragstop');
                            break;

                        case 'gadget':
                            $scope.currentItem = {
                                id: id,
                                x: x,
                                y: y
                            };
                            $scope.saveGadget('dragstop');
                            break;

                        default:
                            console.log('Unknown map type');
                            genericError();
                    }
                }
            };

            if($scope.grid.enabled){
                options['grid'] = [
                    $scope.grid.size,
                    $scope.grid.size
                ];
            }

            $('.draggable').draggable(options);

            $('.resizable').resizable({
                aspectRatio: true,
                helper: 'ui-resizable-helper',
                stop: function(event, ui){
                    var $this = $(this);
                    var id = $this.data('id');

                    var newWidth = parseInt(ui.size.width);
                    var newHeight = parseInt(ui.size.height);

                    for(var key in $scope.map.Mapgadget){
                        if($scope.map.Mapgadget[key].id === id){
                            $scope.map.Mapgadget[key].size_y = newHeight;
                            //Set Y value first because $scope.$watch is listening to X value!
                            $scope.map.Mapgadget[key].size_x = newWidth;
                        }
                    }

                    $scope.currentItem = {
                        id: id,
                        size_x: newWidth,
                        size_y: newHeight
                    };
                    $scope.saveGadget('resizestop');
                }
            });
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

            if($scope.currentItem.hasOwnProperty('gadget') && typeof objectId !== "undefined"){
                if($scope.currentItem.gadget !== 'TrafficLight'){
                    loadMetrics();
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