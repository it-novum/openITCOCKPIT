angular.module('openITCOCKPIT')
    .controller('ServicetemplategroupsAddController', function($scope, $http, SudoService, $state, NotyService, $stateParams, RedirectService){

        $scope.data = {
            createAnother: false
        };

        // preSelectedIds is used for "Append to service template group from /servicetemplates/index"
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

        var clearForm = function(){
            $scope.post = {
                Servicetemplategroup: {
                    description: '',
                    container: {
                        parent_id: 0,
                        name: ''
                    },
                    servicetemplates: {
                        _ids: preSelectedIds
                    }
                }
            };
        };
        clearForm();

        $scope.init = true;


        $scope.loadContainers = function(){
            var params = {
                'angular': true
            };

            $http.get("/servicetemplategroups/loadContainers.json", {
                params: params
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
            });
        };


        $scope.loadServicetemplates = function(){
            var containerId = $scope.post.Servicetemplategroup.container.parent_id;

            //May be triggered by watch from "Create another"
            if(containerId === 0){
                return;
            }

            $http.get("/servicetemplategroups/loadServicetemplatesByContainerId/" + containerId + ".json?angular=true")
                .then(function(result){
                    $scope.servicetemplates = result.data.servicetemplates;
                });
        };

        $scope.submit = function(){
            $http.post("/servicetemplategroups/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('ServicetemplategroupsEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                if($scope.data.createAnother === false){
                    RedirectService.redirectWithFallback('ServicetemplategroupsIndex');
                }else{
                    clearForm();
                    $scope.errors = {};
                    NotyService.scrollTop();
                }


                console.log('Data saved successfully');
            }, function errorCallback(result){

                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };

        $scope.loadContainers();


        $scope.$watch('post.Servicetemplategroup.container.parent_id', function(){
            if($scope.init){
                return;
            }
            $scope.loadServicetemplates();
        }, true);

    });
