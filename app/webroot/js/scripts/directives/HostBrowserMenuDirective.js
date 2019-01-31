angular.module('openITCOCKPIT').directive('hostBrowserMenu', function($http){
    return {
        restrict: 'E',
        templateUrl: '/hosts/host_browser_menu.html',
        scope: {
            'hostId': '=',
            'hostUuid': '=',
            'hostUrl': '=',
            'action': '=',
            'controller': '=',
        },
        controller: function($scope){

            $scope.docuExists = true;
            $scope.allowEdit = true;
            $scope.additionalLinksList = [
                '1', '2'
            ];
            console.log($scope.hostId);

            $scope.loadAdditionalLinksList = function(){
                $http.get("/services/index.json", {
                    params: {
                        'angular': true,
                        'filter[Host.id]': $scope.hostId,
                        'filter[Service.servicename]': $scope.filter.Service.name
                    }
                }).then(function(result){
                    $scope.services = result.data.all_services;
                    $scope.servicesStateFilter = {
                        0: true,
                        1: true,
                        2: true,
                        3: true
                    };
                    $scope.init = false;
                });
            };

        }
    };

});
