angular.module('openITCOCKPIT')
    .controller('ServicegroupsExtendedController', function($scope, $http, QueryStringService){
        $scope.post = {
            Container: {
                name: '',
                parent_id: 0
            },
            Servicegroup: {
                description: '',
                servicegroup_url: '',
                Service: [],
                Servicetemplate: []
            }
        };

        /*** Filter Settings ***/
        var defaultFilter = function(){
            $scope.filter = {
                Servicestatus: {
                    current_state: QueryStringService.servicestate(),
                    acknowledged: QueryStringService.getValue('has_been_acknowledged', false) === '1',
                    not_acknowledged: QueryStringService.getValue('has_not_been_acknowledged', false) === '1',
                    in_downtime: QueryStringService.getValue('in_downtime', false) === '1',
                    not_in_downtime: QueryStringService.getValue('not_in_downtime', false) === '1',
                    passive: QueryStringService.getValue('passive', false) === '1',
                    active: QueryStringService.getValue('active', false) === '1',
                    output: ''
                },
                Service: {
                    name: QueryStringService.getValue('filter[Service.servicename]', ''),
                    keywords: ''
                },
                Host: {
                    name: QueryStringService.getValue('filter[Host.name]', '')
                }
            };
        };
        /*** Filter end ***/

        $scope.init = true;
        $scope.load = function(){
            console.log('test');
            $http.get("/servicegroups/extended.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.servicegroups = result.data.all_servicegroups;
                $scope.init = false;
            });
        };

        $scope.loadServices = function(searchString){
            if($scope.post.Container.parent_id) {
                $http.get("/servicegroups/loadServices.json", {
                    params: {
                        'angular': true,
                        'id': $scope.post.Servicegroup.id,
                        'filter[Servicegroup.name]': searchString
                    }
                }).then(function (result) {
                    $scope.services = result.data.services;
                });
            }

        };


        $scope.$watch('post.Container.parent_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadServices('');
        }, true);

        $scope.load();
    });
