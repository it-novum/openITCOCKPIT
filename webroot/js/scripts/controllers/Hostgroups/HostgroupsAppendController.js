angular.module('openITCOCKPIT')
    .controller('HostgroupsAppendController', function($scope, $http, QueryStringService, $stateParams, $state, NotyService, RedirectService){

        // preSelectedIds is used for "Append hosts to host group from /hosts/index"
        var preSelectedIds = $stateParams.ids;
        if(preSelectedIds !== null){
            var idsAsString = preSelectedIds.split(',');
            preSelectedIds = [];
            //int ids are required for AngularJS
            for(var i in idsAsString){
                preSelectedIds.push(parseInt(idsAsString[i], 10));
            }
        }

        if(preSelectedIds === null){
            preSelectedIds = [];
        }

        $scope.post = {
            Hostgroup: {
                id: 0,
                hosts: {
                    _ids: preSelectedIds
                }
            }
        };

        $scope.loadHostgroups = function(searchString, selected){
            if(typeof selected === "undefined"){
                selected = [];
            }

            if($scope.post.Hostgroup.id){
                selected = [$scope.post.Hostgroup.id];
            }

            $http.get("/hostgroups/loadHostgroupsByString.json", {
                params: {
                    'angular': true,
                    'filter[Containers.name]': searchString,
                    'selected[]': selected
                }
            }).then(function(result){
                $scope.hostgroups = result.data.hostgroups;
            });
        };


        $scope.submit = function(){
            $scope.errors = {};
            if($scope.post.Hostgroup.id < 1){
                $scope.errors.hostgroup = 'You need to selected at least one host group';
                return;
            }

            $http.post("/hostgroups/append/.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('HostgroupsEdit', {id: $scope.post.Hostgroup.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('HostgroupsIndex');

            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadHostgroups('');
    });