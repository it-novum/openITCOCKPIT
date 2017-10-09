angular.module('openITCOCKPIT')
    .service('QueryStringService', function(){

        return {
            getCakeId: function(){
                var url = window.location.href;
                url = url.split('/');
                var id = url[url.length -1];
                id = parseInt(id, 10);
                return id;
            },

            getValue: function(varName){
                var uri = new URLSearchParams(window.location.search);
                return uri.get(varName);
            }
        }
    });