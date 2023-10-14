angular.module('openITCOCKPIT')
    .controller('SystemfailuresAddController', function($scope, $http, SudoService, $state, NotyService, RedirectService){

        $scope.data = {
            createAnother: false
        };

        var now = new Date();
        now.setHours(0, 0, 0);

        var clearForm = function(){


            $scope.post = {
                Systemfailure: {
                    from_date: '',
                    from_time: now,
                    to_date: '',
                    to_time: now,
                    comment: ''
                }
            };
        };
        clearForm();

        $scope.init = true;


        $scope.submit = function(){
            var POSTParams = {
                Systemfailure: {
                    from_date: ($scope.post.Systemfailure.from_date instanceof Date) ? date('d.m.Y', $scope.post.Systemfailure.from_date.getTime() / 1000) : '',
                    from_time: date('H:i', $scope.post.Systemfailure.from_time.getTime() / 1000),
                    to_date: ($scope.post.Systemfailure.to_date instanceof Date) ?date('d.m.Y', $scope.post.Systemfailure.to_date.getTime() / 1000) : '',
                    to_time: date('H:i', $scope.post.Systemfailure.to_time.getTime() / 1000),
                    comment: $scope.post.Systemfailure.comment
                }
            };
            $http.post("/systemfailures/add.json?angular=true",
                POSTParams
            ).then(function(result){
                NotyService.genericSuccess({
                    message: $scope.successMessage.objectName + $scope.successMessage.message
                });


                if($scope.data.createAnother === false){
                    RedirectService.redirectWithFallback('SystemfailuresIndex');
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

    });
