angular.module('openITCOCKPIT')
    .controller('SystemdowntimesAddHostdowntimeController', function($scope, $http, $timeout){

        $scope.init = true;
        $scope.errors = null;

        $scope.Downtime = {
            Type1: null,
            Type2: null,
            Recurring: {
                Style: {
                    "display": "none"
                },
                AllWeekdays: {}
            },
            SuggestedHosts: {}
        };
        /*
        post.Systemdowntime.day_of_month
        */
        $scope.post = {
            angular: true,
            Systemdowntime: {
                is_recurring: false,
                weekdays: {},
                day_of_month: null,
                from_date: null,
                from_time: null,
                to_date: null,
                to_time: null,
                downtimetype: 'host',
                downtimetype_id: 0,
                objecttype_id: null,
                object_id: null,
                comment: null
            }
        };


        $scope.loadRefillData = function(){
            $http.get("/systemdowntimes/getHostdowntimeRefillData.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post.Systemdowntime.from_date=result.data.from_date;
                $scope.post.Systemdowntime.from_time=result.data.from_time;
                $scope.post.Systemdowntime.to_date=result.data.to_date;
                $scope.post.Systemdowntime.to_time=result.data.to_time;
                $scope.post.Systemdowntime.comment=result.data.comment;
                $scope.errors = null;
            }, function errorCallback(result){
                console.error(result);
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadRefillData();

        $scope.saveNewHostDowntime = function(){
            $scope.post.Systemdowntime.downtimetype_id="0";
            if($scope.Downtime.Type2){
                $scope.post.Systemdowntime.downtimetype_id="1";
            }

            $http.post("/systemdowntimes/addHostdowntime", $scope.post).then(function(result){
                //$('#nodeCreatedFlashMessage').show();
                $scope.errors = null;
            }, function errorCallback(result){
                console.error(result);
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadHostlist = function(needle){
            $http.get("/hosts/loadHostsByString.json", {
                params: {
                    'angular': true,
                    'filter[Host.name]': needle
                }
            }).then(function(result){
                $scope.Downtime.SuggestedHosts=result.data.hosts;
                $scope.errors = null;
            }, function errorCallback(result){
                console.error(result);
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };



        $scope.$watch('post.Systemdowntime.is_recurring', function(){
            if($scope.post.Systemdowntime.is_recurring === true){
                $scope.Downtime.Recurring.Style["display"]="block";
            }
            if($scope.post.Systemdowntime.is_recurring === false){
                $scope.Downtime.Recurring.Style["display"]="none";
            }
        });


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
                    $scope.loadHostlist(needle);
                }
            } ));
        });
    });