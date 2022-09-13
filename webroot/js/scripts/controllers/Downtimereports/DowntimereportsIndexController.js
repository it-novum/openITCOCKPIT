angular.module('openITCOCKPIT')
    .controller('DowntimereportsIndexController', function($rootScope, $scope, $http, $timeout, NotyService, QueryStringService, $httpParamSerializer){
        $scope.init = true;
        $scope.errors = null;
        $scope.hasEntries = null;
        $scope.setColorDynamically = false;
        var now = new Date();

        $scope.tabName = 'reportConfig';

        $scope.post = {
            evaluation_type: 0,
            report_format: 2,
            reflection_state: 1,
            timeperiod_id: null,
            from_date: date('d.m.Y', now.getTime() / 1000 - (3600 * 24 * 30)),
            to_date: date('d.m.Y', now.getTime() / 1000)
        };
        $scope.from = new Date(now.getTime() - (3600 * 24 * 30 * 1000));
        $scope.to = new Date(now.getTime());

        $scope.timeperiods = {};
        $scope.reportData = {
            hostsWithOutages: null,
            hostsWithoutOutages: null,
            downtimes: null
        };

        $scope.loadTimeperiods = function(searchString){
            $http.get("/timeperiods/index.json", {
                params: {
                    'angular': true,
                    'filter[Timeperiod.name]': searchString
                }
            }).then(function(result){
                $scope.timeperiods = result.data.all_timeperiods;
            });
        };

        $scope.createDowntimeReport = function(){
            if($scope.post.report_format === 1){
                //PDF Report
                var GETParams = {
                    'angular': true,
                    'data[from_date]': date('d.m.Y', $scope.from.getTime() / 1000),
                    'data[to_date]': date('d.m.Y', $scope.to.getTime() / 1000),
                    'data[evaluation_type]': $scope.post.evaluation_type,
                    'data[reflection_state]': $scope.post.reflection_state,
                    'data[timeperiod_id]': $scope.post.timeperiod_id
                };
                $http.get("/downtimereports/createPdfReport.json", {
                        params: GETParams
                    }
                ).then(function(result){
                    window.location = '/downtimereports/createPdfReport.pdf?' + $httpParamSerializer(GETParams);
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });

            }else{
                //HTML Report
                var POSTParams = {
                    evaluation_type: $scope.post.evaluation_type,
                    report_format: $scope.post.report_format,
                    reflection_state: $scope.post.reflection_state,
                    timeperiod_id: $scope.post.timeperiod_id,
                    from_date: date('d.m.Y', $scope.from.getTime() / 1000),
                    to_date: date('d.m.Y', $scope.to.getTime() / 1000)
                };

                $http.post("/downtimereports/index.json", POSTParams
                ).then(function(result){
                    NotyService.genericSuccess({
                        message: $scope.reportMessage.successMessage
                    });
                    $scope.errors = null;
                    $scope.reportData.downtimes = result.data.downtimeReport.downtimes;
                    $scope.reportData.hostsWithOutages = result.data.downtimeReport.hostsWithOutages;
                    $scope.reportData.hostsWithoutOutages = result.data.downtimeReport.hostsWithoutOutages;
                    $scope.tabName = 'calendarOverview';

                }, function errorCallback(result){
                    NotyService.genericError({
                        message: $scope.reportMessage.errorMessage
                    });
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });
            }
        };
        $scope.loadTimeperiods();
    });
