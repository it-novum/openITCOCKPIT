angular.module('openITCOCKPIT')
    .controller('MessagesOTDAddController', function($scope, $http, $state, NotyService, $location, RedirectService, BBParserService){

        $scope.data = {
            createAnother: false
        };

        $scope.docu = {
            hyperlink: "",
            hyperlinkDescription: "",
            displayView: true
        };

        $scope.motdcontentPreview = '';
        $scope.bbcode = '';

        var clearForm = function(){
            $scope.post = {
                title: '',
                name: '',
                content: '',
                date: '',
                style: 'primary',
                expire: true
            };


        };
        clearForm();

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

        $scope.submit = function(){
            $http.post("/messagesOtd/add.json?angular=true",
                $scope.post
            ).then(function(result){

                var url = $state.href('MessagesOTDEdit', {id: result.data.id});
                $scope.content = $('#motdcontent').val();
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                if($scope.data.createAnother === false){
                    RedirectService.redirectWithFallback('MessagesOTDIndex');

                }else{
                    clearForm();
                    NotyService.scrollTop();
                }

            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.$watch('bbcode', function(){
            $scope.motdcontentPreview = BBParserService.parse($scope.bbcode);
        }, true);
    });
