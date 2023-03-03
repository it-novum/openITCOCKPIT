angular.module('openITCOCKPIT').directive('ackTooltip', function($http, UuidService){
    return {
        restrict: 'E',
        templateUrl: '/angular/ackTooltip.html',

        controller: function($scope){
            $scope.popoverTimer = null;
            $scope.ackPopoverId = UuidService.v4();
            $scope.title = '';
            $scope.author = '';
            $scope.entry = '';
            $scope.comment = '';
            $scope.sticky = false;
            $scope.displayType = 'service';
            $scope.popoverOffset = {
                relativeTop: 0,
                relativeLeft: 0,
                absoluteTop: 0,
                absoluteLeft: 0
            };

          //  $('#tooltipContainer-' + $scope.ackPopoverId).hide();

            $scope.placeAckPopoverTooltip = function(){
                var margin = 5;
                var popupContainer = $('#tooltipContainer-' + $scope.ackPopoverId);
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
            $scope.enterAckEl = function ($event, type, id) {
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
                            if (result.data.acknowledgement.is_sticky) {
                                $scope.sticky = true;
                            } else {
                                $scope.sticky = false;
                            }
                            $scope.author = result.data.acknowledgement.author_name ?? '';
                            $scope.entry = result.data.acknowledgement.entry_time ?? '';
                            $scope.comment = result.data.acknowledgement.comment_data ?? '';
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
                        $scope.placeAckPopoverTooltip();

                        $('#tooltipContainer-' + $scope.ackPopoverId).show();

                        $scope.popoverTimer = null;
                    }, 150);
                }
            };

            $scope.leaveAckEl = function(){
                if($scope.popoverTimer !== null){
                    clearTimeout($scope.popoverTimer);
                    $scope.popoverTimer = null;
                }
                $('#tooltipContainer-' + $scope.ackPopoverId).hide();

            }

        }
    };
});
