angular.module('openITCOCKPIT')
    .controller('SystemdowntimesAddServicedowntimeController', function($scope, $state, $http, QueryStringService, $stateParams, NotyService, RedirectService){

            $scope.init = true;

            $scope.data = {
                createAnother: false
            };

            var clearForm = function(){
                $scope.post = {
                    Systemdowntime: {
                        is_recurring: 0,
                        weekdays: {},
                        day_of_month: '',
                        from_date: '',
                        from_time: '',
                        to_date: '',
                        to_time: '',
                        duration: 15,
                        downtimetype: 'service',
                        downtimetype_id: '0',
                        objecttype_id: 2048,     //OBJECT_SERVICE
                        object_id: [],
                        comment: '',
                        is_recursive: 0
                    }
                };
            };
            clearForm();
            $scope.userTimezone = ''

            if($stateParams.id !== null){
                $scope.post.Systemdowntime.object_id.push(parseInt($stateParams.id, 10));
            }

            $scope.loadDefaults = function(){
                $http.get("/angular/getDowntimeData.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    var now = new Date(result.data.defaultValues.from_date_js);
                    var toDate = new Date(result.data.defaultValues.to_date_js);

                    $scope.post.Systemdowntime.from_date = now;
                    $scope.post.Systemdowntime.from_time = new Date(now.getFullYear(), now.getMonth(), now.getDate(), now.getHours(), now.getMinutes(), 0, 0);
                    $scope.post.Systemdowntime.to_date = toDate;
                    $scope.post.Systemdowntime.to_time = new Date(toDate.getFullYear(), toDate.getMonth(), toDate.getDate(), toDate.getHours(), toDate.getMinutes(), 0, 0);
                    $scope.post.Systemdowntime.comment = result.data.defaultValues.comment;
                    $scope.post.Systemdowntime.duration = result.data.defaultValues.duration;
                    $scope.post.Systemdowntime.downtimetype_id = result.data.defaultValues.downtimetype_id;
                    $scope.userTimezone = result.data.defaultValues.user_timezone;
                });
            };

            $scope.loadServices = function(searchString){
                $http.get("/services/loadServicesByStringCake4/1.json", {
                    params: {
                        'angular': true,
                        'filter[servicename]': searchString,
                        'selected[]': $scope.post.Systemdowntime.object_id
                    }
                }).then(function(result){
                    $scope.services = result.data.services;
                });
            };

            $scope.submit = function(){
                var post = JSON.parse(JSON.stringify($scope.post)); // Remove JS binding

                post.Systemdowntime.from_date = date('d.m.Y', $scope.post.Systemdowntime.from_date);
                post.Systemdowntime.to_date = date('d.m.Y', $scope.post.Systemdowntime.to_date);
                post.Systemdowntime.from_time = date('H:i', $scope.post.Systemdowntime.from_time);
                post.Systemdowntime.to_time = date('H:i', $scope.post.Systemdowntime.to_time);
                $http.post("/systemdowntimes/addServicedowntime.json?angular=true",
                    post
                ).then(function(result){
                    NotyService.genericSuccess({
                        message: $scope.successMessage.objectName + ' ' + $scope.successMessage.message
                    });

                    if($scope.data.createAnother === false){
                        if($scope.post.Systemdowntime.is_recurring){
                            RedirectService.redirectWithFallback('SystemdowntimesService');
                            return;
                        }

                        RedirectService.redirectWithFallback('DowntimesService');
                    }else{
                        clearForm();
                        $scope.loadDefaults();
                        $scope.errors = {};
                        NotyService.scrollTop();
                    }


                    console.log('Data saved successfully');
                }, function errorCallback(result){

                    NotyService.genericError();

                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });
            };

            //Fire on page load
            $scope.loadDefaults();
            $scope.loadServices('');
        }
    );
