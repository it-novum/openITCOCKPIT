angular.module('openITCOCKPIT')
    .controller('CurrentstatereportsIndexController', function($scope, $http, $timeout){

        $scope.init = true;
        $scope.errors = null;

        $scope.SuggestedServices = {};
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

        $scope.load = function(){
            $scope.loadContainers();
            $scope.loadContainerlist();
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

        $scope.loadServicelist = function(needle){
            http_params = {
                'angular': true,
                'filter[Service.servicename]': needle
            };
            if($scope.InitialListLoaded!=true){
                http_params = {
                    'angular': true
                };
                $scope.InitialListLoaded=true;
            }

            $http.get("/services/loadServicesByString.json", {
                params: http_params
            }).then(function(result){
                $scope.SuggestedServices = {};

                function search(nameKey, myArray){
                    for (var i=0; i < myArray.length; i++) {
                        if (myArray[i].key === parseInt(nameKey)) {
                            return myArray[i];
                        }
                    }
                }

                if((window.location+"").split("/")[(window.location+"").split("/").length-1].split(":")[1]){
                    $scope.service_id=(window.location+"").split("/")[(window.location+"").split("/").length-1].split(":")[1];
                    if(!search($scope.service_id, result.data.services)){
                        $http.get("/services/view/"+$scope.service_id+".json").then(function(result2){
                            $scope.SuggestedServices[0] = {
                                "id": $scope.service_id,
                                "group": result2.data.service.Host.name,
                                "label": result2.data.service.Servicetemplate.name
                            };
                        });
                    }
                }

                result.data.services.forEach(function(obj, index) {
                    if($scope.service_id){
                        index=index+1;
                    }
                    $scope.SuggestedServices[index] = {
                        "id": obj.value.Service.id,
                        "group": obj.value.Host.name,
                        "label": obj.value.Servicetemplate.name
                    };
                });

                $scope.errors = null;
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadServicelist();


        $( document ).ready(function(){

            var $ = window.jQuery || window.Cowboy || ( window.Cowboy = {} ), jq_throttle;
            $.throttle = jq_throttle = function( delay, no_trailing, callback, debounce_mode ) {
                var timeout_id,
                    last_exec = 0;
                if ( typeof no_trailing !== 'boolean' ) {
                    debounce_mode = callback;
                    callback = no_trailing;
                    no_trailing = undefined;
                }
                function wrapper() {
                    var that = this,
                        elapsed = +new Date() - last_exec,
                        args = arguments;
                    function exec() {
                        last_exec = +new Date();
                        callback.apply( that, args );
                    };
                    function clear() {
                        timeout_id = undefined;
                    };

                    if ( debounce_mode && !timeout_id ) {
                        exec();
                    }
                    timeout_id && clearTimeout( timeout_id );
                    if ( debounce_mode === undefined && elapsed > delay ) {
                        exec();
                    } else if ( no_trailing !== true ) {
                        timeout_id = setTimeout( debounce_mode ? clear : exec, debounce_mode === undefined ? delay - elapsed : delay );
                    }
                };
                if ( $.guid ) {
                    wrapper.guid = callback.guid = callback.guid || $.guid++;
                }
                return wrapper;
            };

            $.debounce = function( delay, at_begin, callback ) {
                return callback === undefined
                    ? jq_throttle( delay, at_begin, false )
                    : jq_throttle( delay, callback, at_begin !== false );
            };

            var search = $('select').chosen().data('chosen');
            search.search_field.on('keyup', $.debounce( 250, function(e){
                var needle = $(this).val();
                if(needle != false){
                    $scope.loadServicelist(needle);
                } else {
                    $scope.loadServicelist();
                }
            } ));

        });

    });