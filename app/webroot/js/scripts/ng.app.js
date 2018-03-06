angular.module('openITCOCKPIT', [])

    .factory("httpInterceptor", function($q, $rootScope, $timeout){
        return {
            response: function(result){
                $('#global_ajax_loader').fadeOut('slow');
                return result || $.then(result)
            },
            request: function(response){
                $('#global_ajax_loader').show();
                return response || $q.when(response);
            },
            responseError: function(rejection){
                console.log(rejection);
                $('#global_ajax_loader').fadeOut('slow');

                return $q.reject(rejection);
            }
        };
    })

    .config(function($httpProvider){
        $httpProvider.interceptors.push("httpInterceptor");

        $httpProvider.defaults.cache = false;
        if(!$httpProvider.defaults.headers.get){
            $httpProvider.defaults.headers.get = {};
        }
        // disable IE ajax request caching
        $httpProvider.defaults.headers.get['If-Modified-Since'] = '0';

    })

    /*
    .config(function($urlRouterProvider, $stateProvider){
        //$urlRouterProvider.otherwise("/dashboard");

        $stateProvider
            .state('HostgroupsIndex', {
                url: '/hostgroups/index',
                templateUrl: "/hostgroups/indexAngular.html",
                controller: "HostgroupsIndexController"
            })
    })
    */

    /*
    .config(function($routeProvider){
        $routeProvider
            .when("/hostgroups/index", {
                templateUrl : "/hostgroups/indexAngular.html",
                controller : "HostgroupsIndexController"
            })
            .otherwise({
                template : "<h1>None</h1><p>Nothing has been selected</p>"
            });
    })
    */


    .filter('hostStatusName', function(){
        return function(hoststatusId){
            if(typeof hoststatusId === 'undefined'){
                return false;
            }

            switch(hoststatusId){
                case 0:
                case '0':
                    return 'Up';
                case 1:
                case '1':
                    return 'Down';
                case 2:
                case '2':
                    return 'Unreachable';
                default:
                    return 'Not in monitoring';
            }

        }
    })

    .filter('serviceStatusName', function(){
        return function(servicestatusId){
            if(typeof servicestatusId === 'undefined'){
                return false;
            }

            switch(servicestatusId){
                case 0:
                case '0':
                    return 'Ok';
                case 1:
                case '1':
                    return 'Warning';
                case 2:
                case '2':
                    return 'Critical';
                case 3:
                case '3':
                    return 'Unknown';
                default:
                    return 'Not in monitoring';
            }

        }
    })

    .filter('encodeURI', function(){
        return function(str){
            return encodeURI(str);
        }
    })

    .filter('highlight', function($sce){
        return function(title, searchString){
            if(searchString) title = title.replace(new RegExp('(' + searchString + ')', 'gi'),
                '<span class="search-highlight">$1</span>');

            return $sce.trustAsHtml(title)
        }
    })

    .filter('trustAsHtml', function($sce){
        return function(text){
            return $sce.trustAsHtml(text);
        };
    })

    .run(function($rootScope, SortService){

        $rootScope.currentStateForApi = function(current_state){
            var states = [];
            for(var key in current_state){
                if(current_state[key] === true){
                    states.push(key);
                }
            }
            return states;
        };

        $rootScope.getSortClass = function(field){
            if(field === SortService.getSort()){
                if(SortService.getDirection() === 'asc'){
                    return 'fa-sort-asc';
                }
                return 'fa-sort-desc';
            }

            return 'fa-sort';
        };

        $rootScope.orderBy = function(field){
            if(field !== SortService.getSort()){
                SortService.setDirection('asc');
                SortService.setSort(field);
                SortService.triggerReload();
                return;
            }

            if(SortService.getDirection() === 'asc'){
                SortService.setDirection('desc');
            }else{
                SortService.setDirection('asc');
            }
            SortService.triggerReload();
        };

    })
;
