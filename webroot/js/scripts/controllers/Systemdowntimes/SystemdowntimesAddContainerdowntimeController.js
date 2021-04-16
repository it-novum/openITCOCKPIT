angular.module('openITCOCKPIT')
    .controller('SystemdowntimesAddContainerdowntimeController', function($scope, $state, $http, QueryStringService, $stateParams, NotyService, RedirectService){

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
                        downtimetype: 'container',
                        downtimetype_id: '0',
                        objecttype_id: 4,     //OBJECT_NODE
                        object_id: [],
                        comment: '',
                        is_recursive: 0
                    }
                };
            };
            clearForm();

            if($stateParams.id !== null){
                $scope.post.Systemdowntime.object_id.push(parseInt($stateParams.id, 10));
            }
            $(function(){
                $('#HostdowntimeFromDate, #HostdowntimeToDate').datepicker({
                    'format': 'dd.mm.yyyy'
                });
            });

            $scope.loadDefaults = function(){
                $http.get("/angular/getDowntimeData.json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.post.Systemdowntime.from_date = result.data.defaultValues.from_date;
                    $scope.post.Systemdowntime.from_time = result.data.defaultValues.from_time;
                    $scope.post.Systemdowntime.to_date = result.data.defaultValues.to_date;
                    $scope.post.Systemdowntime.to_time = result.data.defaultValues.to_time;
                    $scope.post.Systemdowntime.comment = result.data.defaultValues.comment;
                    $scope.post.Systemdowntime.duration = result.data.defaultValues.duration;
                    $scope.post.Systemdowntime.downtimetype_id = result.data.defaultValues.downtimetype_id;
                });
            };

            $scope.loadContainers = function(){
                $http.get("/containers/loadContainersForAngular.json", {
                    params: {
                        'angular': true,
                        'onlyWritePermissions': true
                    }
                }).then(function(result){
                    $scope.containers = result.data.containers;
                });
            };

            $scope.submit = function(){
                $http.post("/systemdowntimes/addContainerdowntime.json?angular=true",
                    $scope.post
                ).then(function(result){
                    NotyService.genericSuccess({
                        message: $scope.successMessage.objectName + ' ' + $scope.successMessage.message
                    });

                    if($scope.data.createAnother === false){
                        if($scope.post.Systemdowntime.is_recurring){
                            RedirectService.redirectWithFallback('SystemdowntimesNode');
                            return;
                        }

                        RedirectService.redirectWithFallback('SystemdowntimesNode');
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
            $scope.loadContainers();
        }
    );
