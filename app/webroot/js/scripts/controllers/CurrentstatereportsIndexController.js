angular.module('openITCOCKPIT')
    .controller('CurrentstatereportsIndexController', function($scope, $http, $timeout){

        $scope.init = true;
        $scope.errors = null;

        $scope.services = {};
        $scope.InitialListLoaded = null;
        $scope.service_id = null;
        $scope.reportformat = "1";
        $scope.current_state = {
            ok: true,
            warning: true,
            critical: true,
            unknown: true
        };

        $scope.post = {
            Currentstatereport: {
                Service: null,
                current_state: [],
                report_format: 'pdf'
            }
        };

        $scope.createCurrentStateReport = function(){
            $scope.generatingReport = true;
            $scope.noDataFound = false;
            $scope.post.Currentstatereport.report_format = 'pdf'
            $scope.post.Currentstatereport.current_state = [];

            if($scope.reportformat == "2"){
                $scope.post.Currentstatereport.report_format = 'html';
            }

            if($scope.current_state.ok){
                $scope.post.Currentstatereport.current_state.push(0);
            }
            if($scope.current_state.warning){
                $scope.post.Currentstatereport.current_state.push(1);
            }
            if($scope.current_state.critical){
                $scope.post.Currentstatereport.current_state.push(2);
            }
            if($scope.current_state.unknown){
                $scope.post.Currentstatereport.current_state.push(3);
            }

            $http.post("/currentstatereports/index.json?angular=true", $scope.post).then(function(result){

                $scope.generatingReport = false;
                if(result.status === 200){
                    //No data found
                    $scope.noDataFoundMessage = result.data.response.message;
                    $scope.noDataFound = true;
                    return;
                }

                if($scope.post.Currentstatereport.report_format === 'pdf'){
                    window.location = '/currentstatereports/createPdfReport.pdf';
                }

                if($scope.post.Currentstatereport.report_format === 'html'){
                    window.location = '/currentstatereports/createHtmlReport';
                }

                $scope.errors = null;
            }, function errorCallback(result){
                $scope.generatingReport = false;
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };

        $scope.loadServices = function(searchString){
            $http.get("/services/loadServicesByString.json", {
                params: {
                    'angular': true,
                    'filter[Host.name]': searchString,
                    'filter[Service.servicename]': searchString,
                    'selected[]': $scope.post.Currentstatereport.Service
                }
            }).then(function(result){
                $scope.services = result.data.services;
            });

        };

        $scope.loadServices();

    });