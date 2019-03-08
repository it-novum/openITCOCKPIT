angular.module('openITCOCKPIT')
    .service('LocalStorageService', function(){

        return {
            hasItem: function(key){
                return window.localStorage.getItem(key) === null;
            },

            getItem: function(key){
                return window.localStorage.getItem(key);
            },
            getItemWithDefault: function(key, defaultValue){
                var val = window.localStorage.getItem(key);
                if(val === null){
                    return defaultValue;
                }

                return val;
            },
            setItem: function(key, value){
                window.localStorage.setItem(key, value);
            },

            deleteItem: function(key){
                window.localStorage.removeItem(key);
            }
        }
    });