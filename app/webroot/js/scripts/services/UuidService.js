angular.module('openITCOCKPIT')
    .service('UuidService', function(){


        return {
            v4: function(){
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c){
                    var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                    return v.toString(16);
                });
            },

            isUuid: function(uuid){
                var RegExObject = new RegExp('([0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})', 'i');
                return uuid.match(RegExObject) !== null;
            }
        }
    });