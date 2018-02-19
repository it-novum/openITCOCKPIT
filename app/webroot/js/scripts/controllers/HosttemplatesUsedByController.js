angular.module('openITCOCKPIT')
    .controller('HosttemplatesUsedByController', function($scope, $http, QueryStringService, MassChangeService){

        $scope.id = QueryStringService.getCakeId();
        $scope.total = 0;
        $scope.hosttemplate = null;
        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/hosts/delete/';


        $scope.load = function(){
            $http.get("/hosttemplates/usedBy/"+$scope.id+".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.allHosts = result.data.all_hosts;
                $scope.hosttemplate = result.data.hosttemplate;
                $scope.total = result.data.all_hosts.length;
            }, function errorCallback(result){
                if(result.status === 404){
                    window.location.href = '/angular/not_found';
                }
            });
        };

        $scope.undoSelection = function(){
            MassChangeService.clearSelection();
            $scope.massChange = MassChangeService.getSelected();
            $scope.selectedElements = MassChangeService.getCount();
        };

        $scope.selectAll = function(){
            if($scope.allHosts){
                for(var key in $scope.allHosts){
                    if($scope.allHosts[key].Host.allow_edit){
                        var id = $scope.allHosts[key].Host.id;
                        $scope.massChange[id] = true;
                    }
                }
            }
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.allHosts){
                for(var id in selectedObjects){
                    if(id == $scope.allHosts[key].Host.id){
                        objects[id] = $scope.allHosts[key].Host.name;
                    }

                }
            }
            return objects;
        };

        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);

        $scope.load();
    });