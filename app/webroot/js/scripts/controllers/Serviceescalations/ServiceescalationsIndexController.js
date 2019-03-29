angular.module('openITCOCKPIT')
    .controller('ServiceescalationsIndexController', function($scope, $http, $sce){

        $scope.currentPage = 1;
        $scope.useScroll = true;
        $scope.init = true;

        $scope.load = function(){

            $http.get("/serviceescalations/index.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'page': $scope.currentPage
                }
            }).then(function(result){
                $scope.serviceescalations = result.data.all_serviceescalations;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;

                $scope.init = false;
            });

        };

        $scope.changepage = function(page){
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.changeMode = function(val){
            $scope.useScroll = val;
            $scope.load();
        };

        //Fire on page load
        $scope.load();

        $scope.viewServiceescalationOptions = function(serviceescalation){
            var options = {
                'escalate_on_recovery': 'txt-color-greenLight',
                'escalate_on_warning': 'txt-color-orange',
                'escalate_on_critical': 'txt-color-redLight',
                'escalate_on_unknown': 'txt-color-blueDark'
            };
            var esc_class = 'fa fa-square ';
            var html = '';

            for(var option in options){
                var color = options[option];
                if(serviceescalation.Serviceescalation[option] != null && serviceescalation.Serviceescalation[option] == 1){
                    html += '<i class="' + esc_class + color + '"></i>&nbsp';
                }
            }

            return $sce.trustAsHtml(html);
        }

    });