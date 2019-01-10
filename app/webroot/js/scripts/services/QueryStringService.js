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
                var query = parseUri(decodeURIComponent(window.location.href)).queryKey;
                if(query.hasOwnProperty(varName)){
                    return query[varName];
                }

                return defaultReturn;
            },

            getIds: function(varName, defaultReturn){
                defaultReturn = (typeof defaultReturn === 'undefined') ? null : defaultReturn;
                try{
                    //&filter[Service.id][]=861&filter[Service.id][]=53&filter[Service.id][]=860
                    var url = new URL(window.location.href);
                    var serviceIds = url.searchParams.getAll(varName);
                    //getAll('filter[Service.id][]'); returns [861, 53, 860]
                    if(serviceIds.length > 0){
                        return serviceIds;
                    }
                    return defaultReturn;
                }catch(e){
                    //IE or Edge??

                    ////&filter[Service.id][]=861&filter[Service.id][]=53&filter[Service.id][]=860&bert=123
                    var urlString = window.location.href;
                    var peaces = urlString.split(varName);
                    //split returns [ "https://foo.bar/services/index?angular=true&", "=861&", "=53&", "=860&", "=865&", "=799&", "=802&bert=123" ]
                    var ids = [];

                    for(var i = 0; i < peaces.length; i++){
                        if(peaces[i].charAt(0) === '='){
                            //Read from = to next &
                            var currentId = '';
                            for(var k = 0; k < peaces[i].length; k++){
                                var currentChar = peaces[i].charAt(k);

                                if(currentChar !== '='){
                                    if(currentChar === '&'){
                                        //Next variable in GET
                                        break;
                                    }
                                    currentId = currentId + currentChar;
                                }
                            }
                            ids.push(currentId);
                        }
                    }
                    if(ids.length > 0){
                        return ids;
                    }
                    return defaultReturn;
                }
            },

            hasValue: function(varName){
                var query = parseUri(decodeURIComponent(window.location.href)).queryKey;
                return query.hasOwnProperty(varName);
            },

            hoststate: function(){
                var query = parseUri(decodeURIComponent(window.location.href)).queryKey;

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
                var query = parseUri(decodeURIComponent(window.location.href)).queryKey;

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