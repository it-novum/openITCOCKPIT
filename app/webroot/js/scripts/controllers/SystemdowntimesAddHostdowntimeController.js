angular.module('openITCOCKPIT')
    .controller('SystemdowntimesAddHostdowntimeController', function($scope, $http, $timeout){

        $scope.init = true;
        $scope.selectedTenant = null;
        $scope.selectedTenantForNode = null;
        $scope.errors = null;

        $scope.Downtime = {
            Hostname: null,
            Comment: null,
            Type1: null,
            Type2: null,
            Recurring: {
                Style: {
                    "display": "none"
                },
                IsRecurring: null,
                DaysOfMonth: null,
                Weekdays: {},
                SelectedWeekdays: {}
            },
            FromDate: null,
            FromTime: null,
            ToDate: null,
            ToTime: null,
            SuggestedHosts: {}
        };

        $scope.post = {
            Container: {
                parent_id: null,
                name: null,
                containertype_id: '5'
            }
        };

        $scope.load = function(){
            $scope.loadContainers();
            $scope.loadContainerlist();
        };

        $scope.saveNewNode = function(){
            $http.post("/containers/add.json?angular=true", $scope.post).then(function(result){
                $('#nodeCreatedFlashMessage').show();
                $scope.post.Container.name = null;
                $scope.load();
                $timeout(function(){
                    $('#nodeCreatedFlashMessage').hide();
                },3000);
                $scope.errors = null;
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };

        $scope.loadTenants = function(){
            $http.get("/tenants/index.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.tenants = result.data.all_tenants;
                $scope.init = false;
            });
        };

        $scope.loadContainers = function(){
            $http.get('/containers/byTenant/' + $scope.selectedTenant + '.json', {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.nest;
                $('#nestable').nestable({
                    noDragClass: 'dd-nodrag'
                });
            });
        };

        $scope.loadHostlist = function(needle){
            $http.get("/hosts/loadHostsByString.json", {
                params: {
                    'angular': true,
                    'filter[Host.name]': needle
                }
            }).then(function(result){
                console.log(result.data.hosts);
                $scope.Downtime.SuggestedHosts=result.data.hosts;
                /*
                $timeout(function(){
                    $('#nodeCreatedFlashMessage').hide();
                },3000);
                $scope.errors = null;
                */
            }, function errorCallback(result){
                console.log(result);
                console.log("badguy");
                /*
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }*/
            });
        };

        $scope.loadTenants();

        $scope.$watch('Downtime.Recurring.SelectedWeekdays', function(){
            if($scope.Downtime.Recurring.SelectedWeekdays !== null){
                //$scope.load();
                console.log($scope.Downtime.Recurring.SelectedWeekdays);
            }
        });
        $scope.$watch('Downtime.Recurring.IsRecurring', function(){
            if($scope.Downtime.Recurring.IsRecurring === true){
                $scope.Downtime.Recurring.Style["display"]="block";
            }
            if($scope.Downtime.Recurring.IsRecurring === false){
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
                    console.log(needle);
                    $scope.loadHostlist(needle);
                }
            } ));
        });
    });