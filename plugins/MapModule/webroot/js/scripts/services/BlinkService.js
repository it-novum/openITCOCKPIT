angular.module('openITCOCKPIT')
    .service('BlinkService', function($interval){

        var objects = {};
        var interval = null;


        var period = 5000;


        interval = $interval(function(){
            for(var i in objects){
                //Call callback function
                objects[i]();
            }
        }, period);

        return {
            registerNewObject: function(uuid, callback){
                if(!objects.hasOwnProperty(uuid)){
                    objects[uuid] = callback;
                }
            },
            unregisterObject: function(uuid){
                if(objects.hasOwnProperty(uuid)){
                    delete objects[uuid];
                }
            }
        }
    });