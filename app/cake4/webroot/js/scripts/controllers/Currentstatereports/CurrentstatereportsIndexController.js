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

        $scope.createBackgroundForPerfdataMeter = function(attributes){
            var background = {
                'background': 'none'
            };

            if(!(attributes.min && attributes.current && attributes.warning && attributes.critical && attributes.min && attributes.max)){
                return background;
            }
            var linearGradientArray = ['to right'];
            var start = (attributes.min !== null) ? attributes.min : 0;
            var end = (attributes.max !== null) ? attributes.max : (attributes.critical != null) ? attributes.critical : 0;
            var currentValue = Number(attributes.current);
            var warningValue = Number(attributes.warning);
            var criticalValue = Number(attributes.critical);

            //if warning value < critical value, inverse
            if(!isNaN(warningValue) && !isNaN(criticalValue) && warningValue < criticalValue){
                var curValPosInPercent = currentValue / (end - start) * 100;
                curValPosInPercent = (curValPosInPercent > 100) ? 100 : curValPosInPercent;
                if((!isNaN(warningValue) && currentValue >= warningValue) &&
                    (!isNaN(criticalValue) && currentValue < criticalValue)
                ){
                    //if current state > warning and current state < critical
                    linearGradientArray.push(
                        '#5CB85C 0%',
                        '#F0AD4E ' + curValPosInPercent + '%'
                    );
                }else if((!isNaN(warningValue) && currentValue > warningValue) &&
                    (!isNaN(criticalValue) && currentValue >= criticalValue)
                ){
                    //if current state > warning and current state > critical
                    linearGradientArray.push(
                        '#5CB85C 0%',
                        '#F0AD4E ' + (warningValue / (end - start) * 100) + '%',
                        '#D9534F ' + curValPosInPercent + '%'
                    );
                }else if(currentValue < warningValue){
                    linearGradientArray.push('#5CB85C ' + curValPosInPercent + '%');
                }
                //set white color for gradient for empty area
                if(curValPosInPercent > 0 && curValPosInPercent < 100){
                    linearGradientArray.push('transparent ' + curValPosInPercent + '%');
                }
            }
            return {
                'background': 'linear-gradient(' + linearGradientArray.join(', ') + ')'
            };
        }
    });
