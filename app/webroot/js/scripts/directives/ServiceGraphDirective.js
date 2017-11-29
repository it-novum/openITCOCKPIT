angular.module('openITCOCKPIT').directive('serviceGraph', function($http, SudoService, $timeout){
    return {
        restrict: 'E',
        templateUrl: '/angular/service_graph.html?angular=true',
        scope: {
            service: '='
        },

        controller: function($scope){
            $scope.isLoading = false;

            $scope.mouseenter = function($event){
                console.log($event);


                $scope.isLoading = true;
                var offset = {
                    top: $event.relatedTarget.offsetTop + 15,
                    left: $event.relatedTarget.offsetLeft + 15
                };
                var currentScrollPosition = $(window).scrollTop();
                var margin = 15;
                var $popupGraphContainer = $('#serviceGraphContainer-'+$scope.service.Service.uuid);

                console.log($(window).innerHeight());
                console.log($event.relatedTarget.offsetTop+15);

                if((offset.top - currentScrollPosition + margin + $popupGraphContainer.height()) > $(window).innerHeight()){
                    //There is no space in the window for the popup, we need to set it to an higher point
                    $popupGraphContainer.css({
                        'top': parseInt(offset.top - $popupGraphContainer.height() - margin + 10),
                        'left': parseInt(offset.left + margin),
                        'padding': '6px'
                    });
                }else{
                    //Default Popup
                    $popupGraphContainer.css({
                        'top': parseInt(offset.top + margin),
                        'left': parseInt(offset.left + margin),
                        'padding': '6px'
                    });
                }

                $popupGraphContainer.show();
            };

            $scope.mouseleave = function(){
                $('#serviceGraphContainer-'+$scope.service.Service.uuid).hide();
            }

        }
    };
});