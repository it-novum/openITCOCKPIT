angular.module('openITCOCKPIT')
    .controller('ServicegroupsAppendController', function($scope, $http, QueryStringService, $stateParams, $state, NotyService, RedirectService){

        // preSelectedIds is used for "Append services to service group from /services/index"
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
            Servicegroup: {
                id: 0,
                services: {
                    _ids: preSelectedIds
                }
            }
        };

        $scope.loadServicegroups = function(searchString, selected){
            if(typeof selected === "undefined"){
                selected = [];
            }

            if($scope.post.Servicegroup.id){
                selected = [$scope.post.Servicegroup.id];
            }

            $http.get("/servicegroups/loadServicegroupsByString.json", {
                params: {
                    'angular': true,
                    'filter[Containers.name]': searchString,
                    'selected[]': selected
                }
            }).then(function(result){
                $scope.servicegroups = result.data.servicegroups;
            });
        };


        $scope.submit = function(){
            $scope.errors = {};
            if($scope.post.Servicegroup.id < 1){
                $scope.errors.servicegroup = 'You need to selected at least one service group';
                return;
            }

            $http.post("/servicegroups/append/.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('ServicegroupsEdit', {id: $scope.post.Servicegroup.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('ServicegroupsIndex');

            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadServicegroups('');
    });