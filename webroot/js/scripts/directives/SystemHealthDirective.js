angular.module('openITCOCKPIT').directive('systemHealth', function($http, $interval){
    return {
        restrict: 'E',
        templateUrl: '/angular/system_health.html',

        controller: function($scope){

            $scope.systemHealthDefault = {
                state: 'unknown',
                update: 'n/a',
                errorCount: 0
            };

            $scope.class = 'not-monitored';

            $scope.systemHealth = $scope.systemHealthDefault;

            $scope.load = function(){
                $http.get("/angular/system_health.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    if(result.data.status.cache_readable){
                        $scope.systemHealth = result.data.status;
                    }else{
                        $scope.systemHealth = $scope.systemHealthDefault;
                    }

                    $scope.class = $scope.getHealthClass();
                    $scope.bgClass = $scope.getHealthBgClass();
                    $scope.btnClass = $scope.getHealthBtnClass();
                });
            };

            $scope.getHealthClass = function(){
                switch($scope.systemHealth.state){
                    case 'ok':
                        return 'up';

                    case 'warning':
                        return 'warning';

                    case 'critical':
                        return 'down';

                    default:
                        return 'not-monitored';
                }
            };

            $scope.getHealthBgClass = function(){
                switch($scope.systemHealth.state){
                    case 'ok':
                        return 'bg-up';

                    case 'warning':
                        return 'bg-warning';

                    case 'critical':
                        return 'bg-down';

                    default:
                        return 'bg-not-monitored';
                }
            };

            $scope.getHealthBtnClass = function(){
                switch($scope.systemHealth.state){
                    case 'ok':
                        return 'btn-success';

                    case 'warning':
                        return 'btn-warning';

                    case 'critical':
                        return 'btn-danger';

                    default:
                        return 'btn-primary';
                }
            };

            $interval($scope.load, 60000);

            $scope.load();

        },

        link: function(scope, element, attr){
            jQuery(element).find("[rel=tooltip]").tooltip();

            jQuery('#activity').click(function(e){
                $this = $(this);

                if(!$this.next('.ajax-dropdown').is(':visible')){
                    $this.next('.ajax-dropdown').fadeIn(150);
                    $this.addClass('active');
                }else{
                    $this.next('.ajax-dropdown').fadeOut(150);
                    $this.removeClass('active')
                }

                e.preventDefault();
            });


        }
    };
});
