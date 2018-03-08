angular.module('openITCOCKPIT')
    .controller('SystemdowntimesAddServicedowntimeController', function($scope, $http, QueryStringService){

        $scope.init = true;
        $scope.errors = null;

        $scope.serviceIds = [];
        $scope.serviceIds.push(QueryStringService.getCakeId().toString());

        $scope.Downtime = {
            is_recurring: false
        };

        $scope.post = {
            params: {
                'angular': true
            },
            Systemdowntime: {
                is_recurring: false,
                weekdays: {},
                day_of_month: null,
                from_date: null,
                from_time: null,
                to_date: null,
                to_time: null,
                duration: null,
                downtimetype: 'service',
                objecttype_id: 2048,        //1 << 11
                object_id: null,
                comment: null
            }
        };

        $scope.loadRefillData = function(){
            $http.get("/angular/getDowntimeData.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post.Systemdowntime.from_date = result.data.refill.from_date;
                $scope.post.Systemdowntime.from_time = result.data.refill.from_time;
                $scope.post.Systemdowntime.to_date = result.data.refill.to_date;
                $scope.post.Systemdowntime.to_time = result.data.refill.to_time;
                $scope.post.Systemdowntime.comment = result.data.refill.comment;
                $scope.post.Systemdowntime.duration = result.data.refill.duration;
                $scope.errors = null;
            }, function errorCallback(result){
                console.error(result);
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadRefillData();

        $scope.saveNewServiceDowntime = function(){
            $scope.post.Systemdowntime.object_id = $scope.serviceIds;
            if($scope.post.Systemdowntime.is_recurring){
                $scope.post.Systemdowntime.to_time = null;
                $scope.post.Systemdowntime.to_date = null;
                $scope.post.Systemdowntime.from_date = null;
            }
            $http.post("/systemdowntimes/addServicedowntime.json?angular=true", $scope.post).then(
                function(result){
                    $scope.errors = null;
                    if($scope.post.Systemdowntime.is_recurring){
                        window.location.href = '/systemdowntimes/service';
                    }else{
                        window.location.href = '/downtimes/service';
                    }
                },
                function errorCallback(result){
                    console.error(result.data);
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error[0];
                    }
                }
            );
        };

        $scope.loadServices = function(searchString){
            $http.get("/services/loadServicesByString.json", {
                params: {
                    'angular': true,
                    'filter[Service.servicename]': searchString,
                    'selected[]': $scope.serviceIds
                }
            }).then(function(result){

                $scope.services = [];
                result.data.services.forEach(function(obj, index){
                    $scope.services[index] = {
                        "id": obj.value.Service.id,
                        "group": obj.value.Host.name,
                        "label": obj.value.Host.name + "/" + obj.value.Servicetemplate.name
                    };
                });

                $scope.errors = null;
            }, function errorCallback(result){
                console.error(result);
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadServices('');

        $scope.$watch('Downtime.is_recurring', function(){
            if($scope.Downtime.is_recurring === true){
                $scope.post.Systemdowntime.is_recurring = 1;
                if($scope.errors && $scope.errors['from_time']){
                    delete $scope.errors['from_time'];
                }
            }
            if($scope.Downtime.is_recurring === false){
                $scope.post.Systemdowntime.is_recurring = 0;
            }
        });

    });
