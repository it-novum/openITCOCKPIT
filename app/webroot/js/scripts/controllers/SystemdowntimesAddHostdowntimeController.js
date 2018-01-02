angular.module('openITCOCKPIT')
    .controller('SystemdowntimesAddHostdowntimeController', function($scope, $http, $timeout){

        $scope.init = true;
        $scope.errors = null;

        $scope.Downtime = {
            InitialListLoaded: null,
            Type1: null,
            Type2: null,
            host_id: null,
            Recurring: {
                Style: {
                    "display": "none"
                },
                ReverseStyle: {
                    "display": "block"
                },
                AllWeekdays: {},
                is_recurring: null
            },
            SuggestedHosts: {}
        };

        $scope.post = {
            params: {
                'angular': true
            },
            Systemdowntime: {
                is_recurring: false,
                weekdays: {},
                day_of_month: null,
                from_date: null,
                from_time: null,
                to_date: null,
                to_time: null,
                duration: null,
                downtimetype: 'host',
                downtimetype_id: 0,
                objecttype_id: null,
                object_id: {},
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
                $scope.post.Systemdowntime.duration=result.data.duration;
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

            $http.post("/systemdowntimes/addHostdowntime.json?angular=true", $scope.post).then(
                function(result){
                    $scope.errors = null;
                    if($scope.Downtime.Recurring.is_recurring){
                        $('#RecurringDowntimeCreatedFlashMessage').show();
                        setTimeout(function(){ window.location.href = '/systemdowntimes'; }, 1000);
                    } else {
                        $('#DowntimeCreatedFlashMessage').show();
                        setTimeout(function(){ window.location.href = '/downtimes/host'; }, 1000);
                    }
                },
                function errorCallback(result){
                    console.error(result.data);
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                }
            );
        };

        $scope.loadHostlist = function(needle){
            http_params = {
                'angular': true,
                'filter[Host.name]': needle
            };
            if($scope.Downtime.InitialListLoaded!=true){
                http_params = {
                    'angular': true
                };
                $scope.Downtime.InitialListLoaded=true;
            }

            $http.get("/hosts/loadHostsByString.json", {
                params: http_params
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

        $scope.loadHostlist();

        $scope.$watch('Downtime.Recurring.is_recurring', function(){
            if($scope.Downtime.Recurring.is_recurring === true){
                $scope.post.Systemdowntime.is_recurring=1;
                $scope.Downtime.Recurring.Style["display"]="block";
                $scope.Downtime.Recurring.ReverseStyle["display"]="none";
                if($scope.errors && $scope.errors['from_time']){
                    delete $scope.errors['from_time'];
                }
            }
            if($scope.Downtime.Recurring.is_recurring === false){
                $scope.post.Systemdowntime.is_recurring=0;
                $scope.Downtime.Recurring.Style["display"]="none";
                $scope.Downtime.Recurring.ReverseStyle["display"]="block";
            }
        });

        $scope.$watch('Downtime.host_id', function(){
            $scope.post.Systemdowntime.object_id = { 0: $scope.Downtime.host_id };
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