angular.module('openITCOCKPIT').directive('chosen', function($http, $filter, $rootScope, $timeout){
    return {
        restrict: 'A',
        /*scope: {
            chosen: '='
        },*/

        controller: function($scope){

        },

        link: function($scope, element, attrs){

            var oldTimeout = false;
            var callback = false;
            if(attrs.callback){
                callback = attrs.callback;
            }

            var unwatchModel = $scope.$watch(attrs.ngModel, function(){
                element.trigger('chosen:updated');
            }, true);

            var unwatchSource = $scope.$watch(attrs.chosen, function(){
                element.trigger('chosen:updated');
            }, true);

            var unwatchDisabled = function(){};
            if(attrs.hasOwnProperty('ngDisabled')){
                unwatchDisabled = $scope.$watch(attrs.ngDisabled, function(){
                    //If we set disabled=false in the view, the value of disabled is true here.
                    //So, if disabled === false, we negate it to true to enable the element again. ¯\_(ツ)_/¯
                    element[0].disabled = !element[0].disabled;
                    element.trigger('chosen:updated');
                }, true);
            }

            var defaultOptions = {
                placeholder_text_single: 'Please choose',
                placeholder_text_multiple: 'Please choose',
                allow_single_deselect: true, // This will only work if the first option has a blank text.
                search_contains: true,
                enable_split_word_search: true,
                width: '100%',
                search_callback: function(searchString){
                    if(callback){

                        if(oldTimeout){
                            $timeout.cancel(oldTimeout);
                        }

                        oldTimeout = $timeout(function(){
                            $scope[callback](searchString);
                        }, 500);
                    }
                }
            };

            if(attrs.hasOwnProperty('multiple') === true){
                defaultOptions['select_all_buttons'] = true;
            }

            if(callback){
                defaultOptions['no_results_text'] = 'Search for ';
            }

            element.chosen(defaultOptions);


            $scope.$on('$destroy', function(){
                unwatchModel();
                unwatchSource();
                unwatchDisabled();
            });
        }
    };
});
