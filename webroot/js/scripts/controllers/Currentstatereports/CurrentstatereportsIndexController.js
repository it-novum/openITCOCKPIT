angular.module('openITCOCKPIT')
    .controller('CurrentstatereportsIndexController', function($rootScope, $scope, $http, $timeout, NotyService, QueryStringService, $httpParamSerializer){

        $scope.init = true;
        $scope.errors = null;
        $scope.hasEntries = null;

        $scope.post = {
            services: [],
            report_format: '2',
            current_state: {
                ok: true,
                warning: true,
                critical: true,
                unknown: true
            },
            Servicestatus: {
                hasBeenAcknowledged: null,
                inDowntime: null,
                passive: null
            }
        };

        $scope.filter = {
            Servicestatus: {
                acknowledged: QueryStringService.getValue('has_been_acknowledged', false) === '1',
                not_acknowledged: QueryStringService.getValue('has_not_been_acknowledged', false) === '1',
                in_downtime: QueryStringService.getValue('in_downtime', false) === '1',
                not_in_downtime: QueryStringService.getValue('not_in_downtime', false) === '1',
                passive: QueryStringService.getValue('passive', false) === '1',
                active: QueryStringService.getValue('active', false) === '1'
            }
        };


        $scope.servicestatus = {};

        $scope.createCurrentStateReport = function(){
            $scope.post.Servicestatus.current_state = [];
            if($scope.post.current_state.ok === true){
                $scope.post.Servicestatus.current_state.push(0);
            }
            if($scope.post.current_state.warning === true){
                $scope.post.Servicestatus.current_state.push(1);
            }
            if($scope.post.current_state.critical === true){
                $scope.post.Servicestatus.current_state.push(2);
            }
            if($scope.post.current_state.unknown === true){
                $scope.post.Servicestatus.current_state.push(3);
            }
            if($scope.filter.Servicestatus.acknowledged ^ $scope.filter.Servicestatus.not_acknowledged){
                $scope.post.Servicestatus.hasBeenAcknowledged = $scope.filter.Servicestatus.acknowledged === true;
            }else if($scope.filter.Servicestatus.acknowledged && $scope.filter.Servicestatus.not_acknowledged){
                $scope.post.Servicestatus.hasBeenAcknowledged = null;
            }
            if($scope.filter.Servicestatus.in_downtime ^ $scope.filter.Servicestatus.not_in_downtime){
                $scope.post.Servicestatus.inDowntime = $scope.filter.Servicestatus.in_downtime === true;
            }else if($scope.filter.Servicestatus.in_downtime && $scope.filter.Servicestatus.not_in_downtime){
                $scope.post.Servicestatus.inDowntime = null;
            }

            if($scope.filter.Servicestatus.passive ^ $scope.filter.Servicestatus.active){
                $scope.post.Servicestatus.passive = !$scope.filter.Servicestatus.passive;
            }else if($scope.filter.Servicestatus.passive && $scope.filter.Servicestatus.active){
                $scope.post.Servicestatus.passive = null;
            }


            if($scope.post.report_format === '1'){
                //PDF Report
                var GETParams = {
                    'angular': true,
                    'data[current_state][ok]': $scope.post.current_state.ok,
                    'data[current_state][warning]': $scope.post.current_state.warning,
                    'data[current_state][critical]': $scope.post.current_state.critical,
                    'data[current_state][unknown]': $scope.post.current_state.unknown,
                    'data[services][]': $scope.post.services,
                    'data[Servicestatus][hasBeenAcknowledged]': $scope.post.Servicestatus.hasBeenAcknowledged,
                    'data[Servicestatus][inDowntime]': $scope.post.Servicestatus.inDowntime,
                    'data[Servicestatus][passive]': $scope.post.Servicestatus.passive,
                    'data[Servicestatus][current_state][]': $scope.post.Servicestatus.current_state
                };

                $http.get("/currentstatereports/createPdfReport.json", {
                        params: GETParams
                    }
                ).then(function(result){
                    window.location = '/currentstatereports/createPdfReport.pdf?' + $httpParamSerializer(GETParams);
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });

            }else{
                //HTML Report
                $http.post("/currentstatereports/index.json", $scope.post
                ).then(function(result){
                    $scope.servicestatus = result.data.all_services;
                    if(result.data.all_services.length === 0){
                        $scope.hasEntries = false;
                    }else{
                        $scope.hasEntries = true;
                    }
                    NotyService.genericSuccess({
                        message: $scope.reportMessage.successMessage
                    });
                    $scope.errors = null;
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

        $scope.loadServices = function(searchString){
            $http.get("/services/loadServicesByString.json", {
                params: {
                    'angular': true,
                    //'filter[Hosts.name]': searchString,
                    'filter[servicename]': searchString,
                    'selected[]': $scope.post.services
                }
            }).then(function(result){
                $scope.services = result.data.services;
            });

        };

        $scope.loadServices();

        $scope.getProgressbarData = function(attributes, label){
            var start = (attributes.min !== null) ? attributes.min : 0;
            var end = (attributes.max !== null) ? attributes.max : (attributes.critical != null) ? attributes.critical : 0;
            var currentValue = Number(attributes.current);
            var warningValue = Number(attributes.warning);
            var criticalValue = Number(attributes.critical);
            var okColor = 'ok';
            var warningColor = 'warning';
            var criticalColor = 'critical';
            var backgroundColorClass = okColor;
            var unit = (attributes.unit !== null) ? attributes.unit: '';

            var percentVal = currentValue/(end - start)*100;

            if(currentValue >= warningValue){
                backgroundColorClass = warningColor;
            }
            if(currentValue >= criticalValue){
                backgroundColorClass = criticalColor;
            }

            var perfdataString = label +' '+ currentValue +' '+ unit;

            return {
                'currentPercentage': percentVal,
                'backgroundColorClass': backgroundColorClass,
                'perfdataString':perfdataString
            };
        }
    });
