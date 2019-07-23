angular.module('openITCOCKPIT')
    .controller('CurrentstatereportsIndexController', function($rootScope, $scope, $http, $timeout, NotyService, QueryStringService){

        $scope.init = true;
        $scope.errors = null;

        $scope.post = {
            services: [],
            report_format: '1',
            current_state: {
                ok: true,
                warning: true,
                critical: true,
                unknown: true
            }
        };

        $scope.filter = {
            Servicestatus: {
                current_state: {
                    ok: true,
                    warning: true,
                    critical: true,
                    unknown: true
                },
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
            var hasBeenAcknowledged = '';
            var inDowntime = '';
            if($scope.filter.Servicestatus.acknowledged ^ $scope.filter.Servicestatus.not_acknowledged){
                hasBeenAcknowledged = $scope.filter.Servicestatus.acknowledged === true;
            }
            if($scope.filter.Servicestatus.in_downtime ^ $scope.filter.Servicestatus.not_in_downtime){
                inDowntime = $scope.filter.Servicestatus.in_downtime === true;
            }

            var passive = '';
            if($scope.filter.Servicestatus.passive ^ $scope.filter.Servicestatus.active){
                passive = !$scope.filter.Servicestatus.passive;
            }

            $http.post("/currentstatereports/index.json",
                $scope.post
            ).then(function(result){
                $scope.servicestatus = result.data.all_services;
                NotyService.genericSuccess();

                if($scope.post.report_format === 'pdf'){
                    window.location = '/currentstatereports/createPdfReport.pdf';
                }

                if($scope.post.report_format === 'html'){
                    window.location = '/currentstatereports/createHtmlReport';
                }

                $scope.errors = null;
            }, function errorCallback(result){
                NotyService.genericError();
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
