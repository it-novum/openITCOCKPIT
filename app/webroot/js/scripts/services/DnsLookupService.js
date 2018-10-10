angular.module('openITCOCKPIT').service('DnsLookupService', function($http){
    return {
        getHostname: function(address){
            //gethostnamebyaddr
            if(!address){
                return;
            }
            return $http.get('/hosts/gethostnamebyaddr/' + address + '.json', {
                params: {
                    'angular': true
                }
            })
        },

        getHostip: function(hostname){
            //gethostipbyname
            if(!hostname){
                return;
            }
            return $http.get('/hosts/gethostipbyname/' + hostname + '.json', {
                params: {
                    'angular': true
                }
            })
        },


        setAutoLookup: function(value){
            if(typeof value === "boolean"){
                window.localStorage.setItem('autoDNSLookup', value);
            }
        },

        getAutoLookup: function(){
            return window.localStorage.getItem('autoDNSLookup');
        }

    }
});