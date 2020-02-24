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

            getCakeIds: function(){
                var url = window.location.href;
                var ids = [];

                url = url.split('/');
                if(url.length > 5){
                    //Ignore protocol, controller and action
                    //[ "https:", "", "example.com", "commands", "copy", "39", "31" ]

                    for(var i = 5; i < url.length; i++){
                        if(isNaN(url[i]) === false && url[i] !== null && url[i] !== ''){
                            ids.push(parseInt(url[i], 10));
                        }
                    }
                }
                return ids;
            },

            getValue: function(varName, defaultReturn){
                defaultReturn = (typeof defaultReturn === 'undefined') ? null : defaultReturn;
                var sourceUrl = parseUri(decodeURIComponent(window.location.href)).source;
                if(sourceUrl.includes('/#!/')){
                    sourceUrl = sourceUrl.replace('/#!', '');
                }
                var query = parseUri(sourceUrl).queryKey;
                if(query.hasOwnProperty(varName)){
                    return query[varName];
                }

                return defaultReturn;
            },

            getIds: function(varName, defaultReturn){
                defaultReturn = (typeof defaultReturn === 'undefined') ? null : defaultReturn;
                try{
                    //&filter[Service.id][]=861&filter[Service.id][]=53&filter[Service.id][]=860
                    var sourceUrl = parseUri(decodeURIComponent(window.location.href)).source;
                    if(sourceUrl.includes('/#!/')){
                        sourceUrl = sourceUrl.replace('/#!', '');
                    }
                    var url = new URL(sourceUrl);
                    var serviceIds = url.searchParams.getAll(varName);
                    //getAll('filter[Service.id][]'); returns [861, 53, 860]
                    if(serviceIds.length > 0){
                        return serviceIds;
                    }
                    return defaultReturn;
                }catch(e){
                    //IE or Edge??

                    ////&filter[Service.id][]=861&filter[Service.id][]=53&filter[Service.id][]=860&bert=123
                    var sourceUrl = parseUri(decodeURIComponent(window.location.href)).source;
                    if(sourceUrl.includes('/#!/')){
                        sourceUrl = sourceUrl.replace('/#!', '');
                    }
                    var urlString = sourceUrl;
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
                var sourceUrl = parseUri(decodeURIComponent(window.location.href)).source;
                if(sourceUrl.includes('/#!/')){
                    sourceUrl = sourceUrl.replace('/#!', '');
                }
                var query = parseUri(sourceUrl).queryKey;
                return query.hasOwnProperty(varName);
            },

            hoststate: function(stateParams){
                var states = {
                    up: false,
                    down: false,
                    unreachable: false
                };

                if(typeof stateParams === "undefined"){
                    return states;
                }

                if(typeof stateParams.hoststate === "undefined"){
                    return states;
                }

                for(var index in stateParams.hoststate){
                    switch(stateParams.hoststate[index]){
                        case 0:
                        case '0':
                            states.up = true;
                            break;

                        case 1:
                        case '1':
                            states.down = true;
                            break;

                        case 2:
                        case '2':
                            states.unreachable = true;
                            break;
                    }
                }

                return states;
            },


            servicestate: function(stateParams){
                var states = {
                    ok: false,
                    warning: false,
                    critical: false,
                    unknown: false
                };

                if(typeof stateParams === "undefined"){
                    return states;
                }

                if(typeof stateParams.servicestate === "undefined"){
                    return states;
                }

                for(var index in stateParams.servicestate){
                    switch(stateParams.servicestate[index]){
                        case 0:
                        case '0':
                            states.ok = true;
                            break;

                        case 1:
                        case '1':
                            states.warning = true;
                            break;

                        case 2:
                        case '2':
                            states.critical = true;
                            break;

                        case 3:
                        case '3':
                            states.unknown = true;
                            break;
                    }
                }

                return states;
            },

            getStateValue: function(stateParams, varName, defaultReturn){
                defaultReturn = (typeof defaultReturn === 'undefined') ? null : defaultReturn;

                if(typeof stateParams[varName] !== "undefined"){
                    if(stateParams[varName] === null){
                        return defaultReturn;
                    }

                    return stateParams[varName];
                }

                return defaultReturn;
            }
        }
    });
