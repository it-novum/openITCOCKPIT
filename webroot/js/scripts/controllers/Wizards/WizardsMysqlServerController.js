angular.module('openITCOCKPIT')
    .controller('WizardsMysqlServerController', function($scope, $state, $http, $stateParams, QueryStringService, NotyService, RedirectService){
        $scope.hostId = QueryStringService.getStateValue($stateParams, 'hostId', false);
        /** public vars **/
        $scope.init = true;
        $scope.disableSubmit = false;
        $scope.post = {
            username: '',
            password: '',
            database: 'information_schema',
            services: []
        };

        $scope.load = function(){
            $http.get("/wizards/mysqlserver/" + $scope.hostId + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.servicetemplates = result.data.servicetemplates;
                $scope.servicesNamesForExistCheck = result.data.servicesNamesForExistCheck;
                $scope.post.username = result.data.username;
                $scope.post.password = result.data.password;

                for(var key in $scope.servicetemplates){
                    $scope.post.services.push(
                        {
                            'host_id': $scope.hostId,
                            'servicetemplate_id': $scope.servicetemplates[key].id,
                            'name': $scope.servicetemplates[key].name,
                            'description': $scope.servicetemplates[key].description,
                            'servicecommandargumentvalues': $scope.servicetemplates[key].servicetemplatecommandargumentvalues,
                            'createService': !(in_array($scope.servicetemplates[key].name, $scope.servicesNamesForExistCheck))
                        });
                }


                $scope.init = false;
            });
        };

        $scope.submit = function(){
            $scope.disableSubmit = true;

            var services = [];
            for(var index in $scope.post.services){
                if($scope.post.services[index].createService === true && $scope.filteredItems.includes($scope.post.services[index])){
                    services.push($scope.post.services[index]);
                }
            }

            var post = JSON.parse(JSON.stringify($scope.post)); // Remove JS binding
            post.host_id = $scope.hostId;
            post.services = services;

            $http.post("/wizards/mysqlserver.json?angular=true",
                post
            ).then(function(result){
                $scope.disableSubmit = false;
                NotyService.genericSuccess();
                $scope.errors = {};
                NotyService.scrollTop();
                $state.go('WizardsIndex');
                console.log('Data saved successfully');
            }, function errorCallback(result){
                $scope.disableSubmit = false;

                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                    console.log($scope.errors);

                }
            });

        };

        $scope.detectColor = function(label){
            if(label.match(/warning/gi)){
                return 'warning';
            }

            if(label.match(/critical/gi)){
                return 'critical';
            }

            return '';
        };

        //Fire on page load
        $scope.load();

    });
