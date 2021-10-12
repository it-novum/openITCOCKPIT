angular.module('openITCOCKPIT')
    .controller('MessagesOTDEditController', function($scope, $http, $state, $stateParams, NotyService, $location, RedirectService, BBParserService){

        $scope.id = $stateParams.id;
        $scope.init = true;

        $scope.docu = {
            hyperlink: "",
            hyperlinkDescription: "",
            displayView: true
        };

        $scope.motdcontentPreview = '';
        $scope.useScroll = false;

        $scope.load = function(){
            $http.get("/messagesOtd/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post = {
                    MessagesOtd: result.data.messageOtd
                };
                $scope.post.MessagesOtd.expire = $scope.post.MessagesOtd.expiration_duration > 0;
                $scope.loadUsergroups();
                $scope.init = false;
            });
        };

        $scope.loadUsergroups = function(){
            $http.get("/usergroups/index.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'sort': 'Usergroups.name',
                    'direction': 'asc'
                }
            }).then(function(result){
                $scope.usergroups = result.data.allUsergroups;
                $scope.init = false;

            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.load();

        //jQuery Bases WYSIWYG Editor
        $("[wysiwyg='true']").click(function(){
            var $textarea = $('#motdcontent');
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
            var $textarea = $('#motdcontent');
            $textarea.surroundSelectedText("[color='" + color + "']", '[/color]');
        });

        // Bind click event for font size selector
        $("[select-fsize='true']").click(function(){
            var fontSize = $(this).attr('fsize');
            var $textarea = $('#motdcontent');
            $textarea.surroundSelectedText("[text='" + fontSize + "']", "[/text]");
        });

        $scope.prepareHyperlinkSelection = function(){
            var $textarea = $('#motdcontent');
            var selection = $textarea.getSelection();
            if(selection.length > 0){
                $scope.docu.hyperlinkDescription = selection.text;
            }
        };

        $scope.insertWysiwygHyperlink = function(){
            var $textarea = $('#motdcontent');
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

        $scope.notifyUsers = function(messageOtdId){
            $http.post("/messagesOtd/notifyUsersViaMail/" + messageOtdId + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                if(result.data.success && result.data.success === true){
                    NotyService.genericSuccess({message: result.data.message});
                }else{
                    NotyService.genericError();
                }
            });
        };

        $scope.submit = function(){
            $http.post("/messagesOtd/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('MessagesOTDEdit', {id: $scope.id});
                $scope.content = $('#motdcontent').val();
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                if($scope.post.MessagesOtd.notify_users){
                    $scope.notifyUsers($scope.id);
                }

                RedirectService.redirectWithFallback('MessagesOTDIndex');

            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.$watch('post.MessagesOtd.content', function(){
            if($scope.init){
                return;
            }
            $scope.motdcontentPreview = BBParserService.parse($scope.post.MessagesOtd.content);
        }, true);
    });
