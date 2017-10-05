angular.module('openITCOCKPIT')
    .service('MassChangeService', function(){
        var selected = {};
        var selectedCounter = 0;

        return {
            setSelected: function(_selected){
                selected = {};
                for(var id in _selected){
                    if(_selected[id]){
                        selected[id] = id;
                    }
                }
                selectedCounter = Object.keys(selected).length;
            },
            getSelected: function(){
                return selected;
            },
            clearSelection: function(){
                selected = {};
                selectedCounter = 0;
            },
            getCount: function(){
                return selectedCounter;
            }
        }
    });