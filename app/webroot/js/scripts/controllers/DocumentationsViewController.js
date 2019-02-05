angular.module('openITCOCKPIT')
    .controller('DocumentationsViewController', function($scope, $http, QueryStringService, MassChangeService, NotyService, $stateParams){

        $scope.uuid = $stateParams.uuid;
        $scope.type = $stateParams.type;

        $scope.contentEdit = "";
        $scope.contentView = "";

        $scope.load = function(){
            $http.get("/documentations/view/" + $scope.uuid + "/" + $scope.type + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.host = result.data.host;
                $scope.service = result.data.service;
                $scope.post = result.data.post;
                $scope.docuExists = result.data.docuExists;

                if($scope.post.Documentation.content_html){
                    $scope.contentView = $scope.post.Documentation.content_html;
                }
                if($scope.post.Documentation.content){
                    $scope.contentEdit = $scope.post.Documentation.content;
                }

                if($scope.host.Host.id) {
                    $scope.id = $scope.host.Host.id;
                    $http.get("/hosts/hostBrowserMenu/" + $scope.host.Host.id + ".json", {
                        params: {
                            'angular': true
                        }
                    }).then(function(result) {
                        $scope.host = result.data.host;

                        $scope.hostBrowserMenu = {
                            hostId: $scope.host.Host.id,
                            hostUuid: $scope.host.Host.uuid,
                            allowEdit: $scope.host.Host.allowEdit,
                            hostUrl: $scope.host.Host.host_url_replaced,
                            docuExists: result.data.docuExists,
                            isHostBrowser: false
                        };
                    });
                }

            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
                }
            });


        };

        $scope.saveText = function(action){
            if(typeof action === 'undefined'){
                action = 'add_or_edit';
            }

            $http.post("/documentations/view/" + $scope.uuid + "/" + $scope.type + ".json?angular=true",
                $scope.post
            ).then(function(result){
                $scope.errors = {};

                genericSuccess();
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
                genericError();
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

        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);


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
            $scope.post.Documentation.content = $textarea.update;
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

        $('#perform-insert-link').click(function(){
            var $textarea = $('#docuText');
            var url = $('#modalLinkUrl').val();
            var description = $('#modalLinkDescription').val();
            var selection = $textarea.getSelection();
            var newTab = $('#modalLinkNewTab').is(':checked') ? " tab" : "";
            $textarea.insertText("[url='" + url + "'" + newTab + "]" + description + '[/url]', selection.start, "collapseToEnd");
            $('#modalLinkUrl').val('');
            $('#modalLinkDescription').val('');
            $scope.addLink = false;
        });
        /***** End WYSIWYG *****/

    });