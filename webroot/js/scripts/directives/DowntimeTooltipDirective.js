angular.module('openITCOCKPIT').directive('downtimeTooltip', function($http, UuidService){
    return {
        restrict: 'E',
        templateUrl: '/angular/downtimeTooltip.html',

        controller: function($scope){
            $scope.popoverTimer = null;
            $scope.downtimePopoverId = UuidService.v4();
            $scope.author = '';
            $scope.start = '';
            $scope.end = '';
            $scope.comment = '';
            $scope.displayType = 'service';
            $scope.popoverOffset = {
                relativeTop: 0,
                relativeLeft: 0,
                absoluteTop: 0,
                absoluteLeft: 0
            };

          //  $('#tooltipContainer-' + $scope.ackPopoverId).hide();

            $scope.placeDowntimePopoverTooltip = function(){

                var margin = 5;
                var popupContainer = $('#tooltipContainer-' + $scope.downtimePopoverId);
                var popupContainerHeight = popupContainer.height();

                var absoluteBottomPositionOfPopoverContainer = $scope.popoverOffset.absoluteTop + margin + popupContainerHeight;

                if(absoluteBottomPositionOfPopoverContainer > $(window).innerHeight()){
                    //There is no space in the window for the popup, we need to place it above the mouse cursor
                    var marginTop = parseInt($scope.popoverOffset.relativeTop - popupContainerHeight - margin + 10);
                    popupContainer.css({
                        'top': (marginTop > 1) ? marginTop : 1,
                        'left': parseInt($scope.popoverOffset.relativeLeft),
                        'padding': '6px'
                    });
                }else{
                    //Default Popup
                    popupContainer.css({
                        'top': parseInt($scope.popoverOffset.relativeTop),
                        'left': parseInt($scope.popoverOffset.relativeLeft),
                        'padding': '6px'
                    });
                }
            };

        },

        link: function($scope, element, attr){
            $scope.enterDowntimeEl = function ($event, type, id) {
                if(type === 'services'){
                    $scope.displayType = 'service';
                }
                if(type === 'hosts'){
                    $scope.displayType = 'host';
                }
                if ($scope.popoverTimer === null) {
                    $scope.popoverTimer = setTimeout(function () {
                        $http.get("/" + type + "/browser/" + Number(id) + ".json", {
                            params: {
                                'angular': true
                            }
                        }).then(function (result) {
                            $scope.author = result.data.downtime.authorName ?? '';
                            $scope.start = result.data.downtime.scheduledStartTime ?? '';
                            $scope.end = result.data.downtime.scheduledEndTime ?? '';
                            $scope.comment = result.data.downtime.commentData ?? '';
                        }, function errorCallback(result) {
                            console.log('error');
                        });
                        var position = $event.target.getBoundingClientRect();
                        var offset = {
                            relativeTop: $event.relatedTarget.offsetTop + 40,
                            relativeLeft: $event.relatedTarget.offsetLeft + 40,
                            absoluteTop: position.top,
                            absoluteLeft: position.left,
                        };

                        if ($event.relatedTarget.offsetParent && $event.relatedTarget.offsetParent.offsetTop) {
                            offset.relativeTop += $event.relatedTarget.offsetParent.offsetTop;
                        }
                        $scope.popoverOffset = offset;
                        $scope.placeDowntimePopoverTooltip();

                        $('#tooltipContainer-' + $scope.downtimePopoverId).show();
                        $scope.popoverTimer = null;
                    }, 150);
                }
            };

            $scope.leaveDowntimeEl = function(){
                if($scope.popoverTimer !== null){
                    clearTimeout($scope.popoverTimer);
                    $scope.popoverTimer = null;
                }
                $('#tooltipContainer-' + $scope.downtimePopoverId).hide();

            }

        }
    };
});
