angular.module('openITCOCKPIT')
    .service('MapItemReloadService', function($interval, $http){

        var items = {};
        var interval = null;

        var refreshInterval = 0;

        var loadData = function(){

            var postData = [];

            for(var uuid in items){
                postData.push({
                    'objectId': items[uuid].item.object_id,
                    'mapId': items[uuid].item.map_id,
                    'type': items[uuid].item.type,
                    'uuid': uuid
                });
            }

            $http.post("/map_module/mapeditors/mapitemMulti.json?angular=true", {
                    items: postData,
                    disableGlobalLoader: true
                }
            ).then(function(result){

                var data = result.data.mapitems;

                for(var uuid in items){
                    if(data.hasOwnProperty(uuid)){
                        items[uuid].callback({
                            data: data[uuid]
                        });
                    }
                }
            });
        };

        var startInterval = function(){
            if(refreshInterval > 0){
                interval = $interval(function(){
                    loadData();
                }, refreshInterval);
            }
        };

        return {
            registerNewItem: function(uuid, item, callback){
                if(!items.hasOwnProperty(uuid)){
                    items[uuid] = {
                        item: item,
                        callback: callback
                    };
                }

                if(interval === null){
                    startInterval();
                }
            },
            unregisterItem: function(uuid){
                if(items.hasOwnProperty(uuid)){
                    delete items[uuid];
                }

                if(Object.keys(items).length === 0){
                    if(interval !== null){
                        $interval.cancel(interval);
                        interval = null;
                    }
                }

            },
            setRefreshInterval: function(value){
                if(value > 0 && value != refreshInterval){
                    refreshInterval = value;

                    if(interval !== null){
                        $interval.cancel(interval);
                        interval = null;
                    }

                    startInterval();
                }
            }
        }
    });
