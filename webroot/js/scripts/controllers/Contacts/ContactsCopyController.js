angular.module('openITCOCKPIT')
    .controller('ContactsCopyController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){

        var ids = $stateParams.ids.split(',');

        if(ids.length === 0 || ids[0] === ''){
            //No ids to copy given - redirect
            RedirectService.redirectWithFallback('ContactsIndex');
            return;
        }


        $scope.load = function(){
            $http.get("/contacts/copy/" + ids.join('/') + ".json", {
                params: {
                    'angular': true,
                }
            }).then(function(result){
                $scope.sourceContacts = [];
                for(var key in result.data.contacts){
                    $scope.sourceContacts.push({
                        Source: {
                            id: result.data.contacts[key].Contact.id,
                            name: result.data.contacts[key].Contact.name,
                        },
                        Contact: {
                            name: result.data.contacts[key].Contact.name,
                            description: result.data.contacts[key].Contact.description,
                            email: result.data.contacts[key].Contact.email,
                            phone: result.data.contacts[key].Contact.phone,
                        }
                    });
                }

                $scope.init = false;

            });
        };

        $scope.copy = function(){
            $http.post("/contacts/copy/.json?angular=true",
                {
                    data: $scope.sourceContacts
                }
            ).then(function(result){
                NotyService.genericSuccess();
                RedirectService.redirectWithFallback('ContactsIndex');


            }, function errorCallback(result){
                //Print errors
                NotyService.genericError();
                $scope.sourceContacts = result.data.result;
            });
        };


        $scope.load();


    });