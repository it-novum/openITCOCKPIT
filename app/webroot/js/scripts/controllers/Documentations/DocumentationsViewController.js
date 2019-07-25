angular.module('openITCOCKPIT')
    .controller('DocumentationsViewController', function($scope, $sce, $http, QueryStringService, MassChangeService, NotyService, $stateParams, $state, BBParserService){

        $scope.uuid = $stateParams.uuid;
        $scope.type = $stateParams.type;

        $scope.docu = {
            hyperlink: "",
            hyperlinkDescription: "",
            displayView: true
        };

        $scope.load = function(){
            $http.get("/documentations/view/" + $scope.uuid + "/" + $scope.type + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.docuExists = result.data.docuExists;
                $scope.html = BBParserService.parse(result.data.bbcode);
                $scope.bbcode = result.data.bbcode;
                $scope.lastUpdate = result.data.lastUpdate;
                $scope.allowEdit = result.data.allowEdit;

                if($scope.type === 'host'){
                    if(typeof $scope.hostBrowserMenuConfig === "undefined"){
                        $scope.hostBrowserMenuConfig = {
                            autoload: true,
                            hostId: result.data.objectId,
                            includeHoststatus: true
                        };
                    }
                }

                if($scope.type === 'service'){
                    if(typeof $scope.serviceBrowserMenuConfig === "undefined"){
                        $scope.serviceBrowserMenuConfig = {
                            autoload: true,
                            serviceId: result.data.objectId,
                            includeServicestatus: true
                        };
                    }
                }

            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };


        $scope.saveText = function(action){
            //Get current value from jQuery because AngularJS get us old values...
            $scope.bbcode = $('#docuText').val();

            $http.post("/documentations/view/" + $scope.uuid + "/" + $scope.type + ".json?angular=true",
                {
                    content: $scope.bbcode
                }
            ).then(function(result){
                $scope.errors = {};

                $scope.showView();
                NotyService.genericSuccess();
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
                NotyService.genericError();
            });
        };

        $scope.showView = function(){
            $scope.load();
            $scope.docu.displayView = true;
        };

        $scope.showEdit = function(){
            $scope.docu.displayView = false;
        };

        //Load data on page load
        $scope.load();


        //jQuery Bases WYSIWYG Editor
        $("[wysiwyg='true']").click(function(){
            var $textarea = $('#docuText');
            var task = $(this).attr('task');
            switch(task){
                case 'bold':
                    $textarea.surroundSelectedText('[b]', '[/b]');
                    break;

                case 'italic':
                    $textarea.surroundSelectedText('[i]', '[/i]');
                    break;

                case 'underline':
                    $textarea.surroundSelectedText('[u]', '[/u]');
                    break;

                case 'left':
                    $textarea.surroundSelectedText('[left]', '[/left]');
                    break;

                case 'center':
                    $textarea.surroundSelectedText('[center]', '[/center]');
                    break;

                case 'right':
                    $textarea.surroundSelectedText('[right]', '[/right]');
                    break;

                case 'justify':
                    $textarea.surroundSelectedText('[justify]', '[/justify]');
                    break;
            }
        });

        // Bind click event for color selector
        $("[select-color='true']").click(function(){
            var color = $(this).attr('color');
            var $textarea = $('#docuText');
            $textarea.surroundSelectedText("[color='" + color + "']", '[/color]');
        });

        // Bind click event for font size selector
        $("[select-fsize='true']").click(function(){
            var fontSize = $(this).attr('fsize');
            var $textarea = $('#docuText');
            $textarea.surroundSelectedText("[text='" + fontSize + "']", "[/text]");
        });

        $scope.prepareHyperlinkSelection = function(){
            var $textarea = $('#docuText');
            var selection = $textarea.getSelection();
            if(selection.length > 0){
                $scope.docu.hyperlinkDescription = selection.text;
            }
        };

        $scope.insertWysiwygHyperlink = function(){
            var $textarea = $('#docuText');
            var selection = $textarea.getSelection();
            var newTab = $('#modalLinkNewTab').is(':checked') ? " tab" : "";
            if(selection.length > 0){
                $textarea.surroundSelectedText("[url='" + $scope.docu.hyperlink + "'" + newTab + "]", "[/url]");
            }else{
                $textarea.insertText("[url='" + $scope.docu.hyperlink + "'" + newTab + "]" + $scope.docu.hyperlinkDescription + '[/url]', selection.start, "collapseToEnd");
            }
            $scope.docu.hyperlink = "";
            $scope.docu.hyperlinkDescription = "";
            $scope.addLink = false;
        };
        /***** End WYSIWYG *****/

    });