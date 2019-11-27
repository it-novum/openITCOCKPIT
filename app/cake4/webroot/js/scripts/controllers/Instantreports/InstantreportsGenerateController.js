angular.module('openITCOCKPIT')
    .controller('InstantreportsGenerateController', function($rootScope, $scope, $stateParams, $http, $timeout, NotyService, QueryStringService, $httpParamSerializer){
        $scope.init = true;
        $scope.errors = null;
        var now = new Date();

        $scope.tabName = 'reportConfig';

        $scope.post = {
            instantreport_id: null,
            report_format: 2,
            from_date: date('d.m.Y', now.getTime() / 1000 - (3600 * 24 * 30)),
            to_date: date('d.m.Y', now.getTime() / 1000)
        };
        $scope.post.instantreport_id = parseInt($stateParams.id, 10);

        $scope.reportData = null;
        $scope.instantreports = [];

        $scope.loadInstantreports = function(searchString){
            $http.get("/instantreports/index.json", {
                params: {
                    'angular': true,
                    'filter[Instantreport.name]': searchString
                }
            }).then(function(result){
                $scope.instantreports = result.data.instantreports;
            });
        };

        $scope.createInstantReport = function(){
            if($scope.post.report_format === 1){
                //PDF Report
                var GETParams = {
                    'angular': true,
                    'data[from_date]': $scope.post.from_date,
                    'data[to_date]': $scope.post.to_date
                };

                $http.get("/instantreports/createPdfReport.json", {
                        params: GETParams
                    }
                ).then(function(result){
                    window.location = '/instantreports/createPdfReport.pdf?' + $httpParamSerializer(GETParams);
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });

            }else{
                //HTML Report
                $http.post("/instantreports/generate/" + $scope.post.instantreport_id + ".json", $scope.post
                ).then(function(result){
                    NotyService.genericSuccess({
                        message: $scope.reportMessage.successMessage
                    });
                    $scope.errors = null;
                    $scope.reportData = result.data.instantReport;
                    console.log($scope.reportData);

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

        $scope.loadInstantreports();
    });
