angular.module('openITCOCKPIT')
    .controller('WizardsAgentController', function($scope, $http){

        /** public vars **/
        $scope.init = true;
        $scope.useExistingHost = 0;


        $(function() {
            $('#new-host-toggle').bootstrapToggle();
        });


        $scope.loadHosts = function(searchString){
            $http.get("/hosts/loadHostsByString.json", {
                params: {
                    'angular': true,
                    'filter[Hosts.name]': searchString
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
            });
        };

        $scope.$watch('post.useExistingHost', function(){
            if($scope.init){
                return;
            }
            if($scope.post.useExistingHost == 0){
                //Create another
                return;
            }

            $scope.loadHosts('');
        }, true);


        $scope.loadHosts('');

    });
