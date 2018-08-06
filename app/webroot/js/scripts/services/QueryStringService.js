angular.module('openITCOCKPIT')
    .service('QueryStringService', function(){

        return {
            getCakeId: function(){
                var url = window.location.href;
                url = url.split('/');
                var id = url[url.length - 1];
                id = parseInt(id, 10);
                return id;
            },

            getValue: function(varName, defaultReturn){

                defaultReturn = (typeof defaultReturn === 'undefined') ? null : defaultReturn;
                var query = parseUri(decodeURIComponent(location.href)).queryKey;
                if(query.hasOwnProperty(varName)){
                    return query[varName];
                }

                return defaultReturn;
            },

            getIds: function(varName, defaultReturn){
                defaultReturn = (typeof defaultReturn === 'undefined') ? null : defaultReturn;
                var url = new URL(location.href);
                var serviceIds = url.searchParams.getAll(varName);
                if(serviceIds.length > 0){
                    return serviceIds;
                }
                return defaultReturn;
            },

            hasValue: function(varName){
                var query = parseUri(decodeURIComponent(location.href)).queryKey;
                return query.hasOwnProperty(varName);
            },

            hoststate: function(){
                var query = parseUri(decodeURIComponent(location.href)).queryKey;

                var states = {
                    up: false,
                    down: false,
                    unreachable: false
                };

                for(var key in query){
                    if(key === 'filter[Hoststatus.current_state][0]'){
                        states.up = true;
                    }
                    if(key === 'filter[Hoststatus.current_state][1]'){
                        states.down = true;
                    }
                    if(key === 'filter[Hoststatus.current_state][2]'){
                        states.unreachable = true;
                    }
                }
                return states;

            },

            servicestate: function(){
                var query = parseUri(decodeURIComponent(location.href)).queryKey;

                var states = {
                    ok: false,
                    warning: false,
                    critical: false,
                    unknown: false
                };

                for(var key in query){
                    if(key === 'filter[Servicestatus.current_state][0]'){
                        states.ok = true;
                    }
                    if(key === 'filter[Servicestatus.current_state][1]'){
                        states.warning = true;
                    }
                    if(key === 'filter[Servicestatus.current_state][2]'){
                        states.critical = true;
                    }
                    if(key === 'filter[Servicestatus.current_state][3]'){
                        states.unknown = true;
                    }
                }
                return states;
            }
        }
    });