angular.module('openITCOCKPIT')
    .controller('SystemdowntimesAddContainerdowntimeController', function($scope, $http, $timeout){

        $scope.init = true;
        $scope.errors = null;

        $scope.Downtime = {
            Type1: null,
            Type2: null,
            host_id: null,
            Recurring: {
                Style: {
                    "display": "none"
                },
                ReverseStyle: {
                    "display": "block"
                },
                AllWeekdays: {},
                is_recurring: null
            },
            SuggestedHosts: {},
            is_inherit: null
        };

        $scope.post = {
            params: {
                'angular': true
            },
            Systemdowntime: {
                is_recurring: 0,
                inherit_downtime: 0,
                weekdays: {},
                day_of_month: null,
                from_date: null,
                from_time: null,
                to_date: null,
                to_time: null,
                duration: null,
                downtimetype: 'container',
                downtimetype_id: 0,
                objecttype_id: null,
                object_id: {},
                comment: null
            }
        };

        $scope.loadRefillData = function(){
            $http.get("/systemdowntimes/getHostdowntimeRefillData.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post.Systemdowntime.from_date=result.data.from_date;
                $scope.post.Systemdowntime.from_time=result.data.from_time;
                $scope.post.Systemdowntime.to_date=result.data.to_date;
                $scope.post.Systemdowntime.to_time=result.data.to_time;
                $scope.post.Systemdowntime.comment=result.data.comment;
                $scope.post.Systemdowntime.duration=result.data.duration;
                $scope.errors = null;
            }, function errorCallback(result){
                console.error(result);
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadRefillData();

        $scope.saveNewContainerDowntime = function(){
            $scope.post.Systemdowntime.downtimetype_id="0";
            if($scope.Downtime.Type2){
                $scope.post.Systemdowntime.downtimetype_id="1";
            }
            if($scope.post.Systemdowntime.is_recurring){
                $scope.post.Systemdowntime.to_time=null;
                $scope.post.Systemdowntime.to_date=null;
                $scope.post.Systemdowntime.from_date=null;
            }

            $http.post("/systemdowntimes/addContainerdowntime.json?angular=true", $scope.post).then(
                function(result){
                    $scope.errors = null;
                    if($scope.Downtime.Recurring.is_recurring){
                        $('#RecurringDowntimeCreatedFlashMessage').show();
                        setTimeout(function(){ window.location.href = '/systemdowntimes'; }, 1000);
                    } else {
                        $('#DowntimeCreatedFlashMessage').show();
                        setTimeout(function(){ window.location.href = '/downtimes/host'; }, 1000);
                    }
                },
                function errorCallback(result){
                    console.error(result.data);
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                }
            );
        };

        $scope.loadContainerlist = function(){
            $http.get("/containers/loadContainersForAngular.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.Downtime.SuggestedHosts=result.data.containers;
                $scope.errors = null;
            }, function errorCallback(result){
                console.error(result);
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadContainerlist();

        $scope.$watch('Downtime.Recurring.is_recurring', function(){
            if($scope.Downtime.Recurring.is_recurring === true){
                $scope.post.Systemdowntime.is_recurring=1;
                $scope.Downtime.Recurring.Style["display"]="block";
                $scope.Downtime.Recurring.ReverseStyle["display"]="none";
                if($scope.errors && $scope.errors['from_time']){
                    delete $scope.errors['from_time'];
                }
            }
            if($scope.Downtime.Recurring.is_recurring === false){
                $scope.post.Systemdowntime.is_recurring=0;
                $scope.Downtime.Recurring.Style["display"]="none";
                $scope.Downtime.Recurring.ReverseStyle["display"]="block";
            }
        });

        $scope.$watch('Downtime.is_inherit', function(){
            if($scope.Downtime.is_inherit === true){
                $scope.post.Systemdowntime.inherit_downtime=1;
            }
            if($scope.Downtime.is_inherit === false){
                $scope.post.Systemdowntime.inherit_downtime=0;
            }
        });

        $scope.$watch('Downtime.host_id', function(){
            $scope.post.Systemdowntime.object_id = { 0: $scope.Downtime.host_id };
        });

    });