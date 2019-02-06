angular.module('openITCOCKPIT')
    .controller('TimeperiodsAddController', function($scope, $http, SudoService, $state, NotyService){
        $scope.post = {
            Timeperiod: {
                container_id: '',
                name: '',
                timeperiodranges: []
            },
            Calendar: {
                id: ''
            }
        };

        $scope.init = true;
        $scope.hasError = null;

        $scope.timeperiodranges = [];

        $scope.load = function(){
            $http.get("/containers/loadContainersForAngular.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };


        $scope.removeArg = function(timeperiodrange){
            var timeperiodranges = [];
            for(var i in $scope.timeperiodranges){
                if($scope.timeperiodranges[i].id !== timeperiodrange.id){
                    timeperiodranges.push($scope.timeperiodranges[i])
                }
            }

            $scope.args = _.sortBy(args, 'day');
        };

        $scope.addArg = function(){
            var count = $scope.length(timeperiodranges)+1;

            $scope.timeperiodranges.push({
                id: count,
                day: '',
                start: '',
                end: ''

            });
            $scope.timeperiodranges = _.sortBy($scope.timeperiodranges, 'day');
        };


        $scope.submit = function(){
            return;
            var index = 0;
            for(var i in $scope.args){
                if(!/\S/.test($scope.args[i].human_name)){
                    continue;
                }
                $scope.post.Timeperiod.timeperiodranges[index] = {
                    'name': $scope.args[i].name,
                    'human_name': $scope.args[i].human_name
                };
                index++;
            }
            $http.post("/timeperiods/add.json?angular=true",
                $scope.post
            ).then(function(result){
                NotyService.genericSuccess();
                $state.go('TimeperiodsIndex');
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    NotyService.genericError();
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.load();
    });
