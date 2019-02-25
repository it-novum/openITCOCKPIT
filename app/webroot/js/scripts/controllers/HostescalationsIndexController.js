angular.module('openITCOCKPIT')
    .controller('HostescalationsIndexController', function($scope, $http, $sce){

        $scope.currentPage = 1;
        $scope.useScroll = true;
        $scope.init = true;

        $scope.load = function(){

            $http.get("/hostescalations/index.json", {
                params: {
                    'angular': true,
                    'scroll': $scope.useScroll,
                    'page': $scope.currentPage
                }
            }).then(function(result){
                $scope.hostescalations = result.data.all_hostescalations;
                $scope.paging = result.data.paging;
                $scope.scroll = result.data.scroll;

                $scope.init = false;
            });

        };

        $scope.changepage = function(page){
            console.log(page);
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

        $scope.viewHostescalationOptions = function(hostescalation){
            var options = {
                'escalate_on_recovery': 'txt-color-greenLight',
                'escalate_on_down': 'txt-color-redLight',
                'escalate_on_unreachable': 'txt-color-blueDark'
            };
            var esc_class = 'fa fa-square ';
            var html = '';

            for(var option in options){
                var color = options[option];
                if(hostescalation.Hostescalation[option] != null && hostescalation.Hostescalation[option] == 1){
                    html += '<i class="' + esc_class + color + '"></i>&nbsp';
                }
            }

            return $sce.trustAsHtml(html);
        }

    });