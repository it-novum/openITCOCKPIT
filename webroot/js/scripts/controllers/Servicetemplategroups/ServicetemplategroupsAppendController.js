angular.module('openITCOCKPIT')
    .controller('ServicetemplategroupsAppendController', function($scope, $http, QueryStringService, $stateParams, $state, NotyService, RedirectService){

        // preSelectedIds is used for "Append service templates to service template group from /servicetemplates/index"
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
            Servicetemplategroup: {
                id: 0,
                servicetemplates: {
                    _ids: preSelectedIds
                }
            }
        };

        $scope.loadServicetemplategroups = function(searchString, selected){
            if(typeof selected === "undefined"){
                selected = [];
            }

            if($scope.post.Servicetemplategroup.id){
                selected = [$scope.post.Servicetemplategroup.id];
            }

            $http.get("/servicetemplategroups/loadServicetemplategroupsByString.json", {
                params: {
                    'angular': true,
                    'filter[Containers.name]': searchString,
                    'selected[]': selected
                }
            }).then(function(result){
                $scope.servicetemplategroups = result.data.servicetemplategroups;
            });
        };


        $scope.submit = function(){
            $scope.errors = {};
            if($scope.post.Servicetemplategroup.id < 1){
                $scope.errors.servicetemplategroup = 'You need to selected at least one host group';
                return;
            }

            $http.post("/servicetemplategroups/append/.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('ServicetemplategroupsEdit', {id: $scope.post.Servicetemplategroup.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('ServicetemplategroupsIndex');

            }, function errorCallback(result){
                NotyService.genericError();
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadServicetemplategroups('');
    });
