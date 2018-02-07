angular.module('openITCOCKPIT')
    .service('SortService', function(){
        var _callback = null;
        var sort = null;
        var direction = null;
        var initialize = true;


        var triggerCallback = function(){
            if(_callback !== null){
                _callback();
            }
        };

        return {
            setCallback: function(callback){
                _callback = callback;
            },
            triggerReload: triggerCallback,
            getDirection: function(){
                return direction;
            },
            getSort: function(){
                return sort;
            },
            setDirection: function(value){
                direction = value;
            },
            setSort: function(value){
                sort = value;
            },
            setInitialize: function(value){
                initialize = value;
            }
        }
    });