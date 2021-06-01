angular.module('openITCOCKPIT')
    .controller('NotificationMessagesIndexController', function($scope, $http){

        $scope.init = true;
        $scope.showFilter = false;
        $scope.currentPage = 1;
        $scope.useScroll = true;

        var defaultFilter = function(){
            $scope.filter = {
                Messages: {
                    // id: QueryStringService.getStateValue($stateParams, 'id', []),
                    title: '',
                    output: ''
                }
            };
        }

        var genericError = function(){
            new Noty({
                theme: 'metroui',
                type: 'error',
                text: 'Error while deleting message',
                timeout: 3500
            }).show();
        };


        $scope.load = function(){
            $http.get("/Notificationmessages/index.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'page': $scope.currentPage,
                    'filter[NotificationMessages.name]': $scope.filter.Messages.title,
                    'filter[NotificationMessages.message]': $scope.filter.Messages.output
                }
            }).then(function(result){
                $scope.messages = result.data.messages;

            });
        }

        // $scope.changepage = function(page){
        //     if(page !== $scope.currentPage){
        //         $scope.currentPage = page;
        //         $scope.load();
        //     }
        // };

        $scope.triggerFilter = function(){
            $scope.showFilter = !$scope.showFilter === true;
        };

        $scope.resetFilter = function(){
            defaultFilter();
        };

        // delete method
        $scope.deleteMessage = function(id){
            postData = {
                Message: {
                    id: id
                }
            };
            $http.post("/Notificationmessages/deleteMessage/.json?angular=true", postData).then(
                function(result){
                    if(result.data.hasOwnProperty('success')){
                        $scope.successMessage = result.data.success;
                        var genericSuccess = function(){
                            new Noty({
                                theme: 'metroui',
                                type: 'success',
                                text: $scope.successMessage,
                                timeout: 3500
                            }).show();
                        };
                        genericSuccess();
                        $scope.load();
                    }
                }, function errorCallback(result){
                    genericError();
                });
        };

        defaultFilter();
        $scope.load();
        $scope.$watch('filter', function(){
            // $scope.currentPage = 1;
            // $scope.undoSelection();
            $scope.load();
        }, true);

    });
