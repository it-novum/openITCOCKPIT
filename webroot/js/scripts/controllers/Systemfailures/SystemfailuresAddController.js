angular.module('openITCOCKPIT')
    .controller('SystemfailuresAddController', function($scope, $http, SudoService, $state, NotyService, RedirectService){

        $scope.data = {
            createAnother: false
        };

        var now = new Date();
        now.setHours("00", "00", "00");

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
            var submitObject = {
                Systemfailure: {
                    from_date: ($scope.post.Systemfailure.from_date instanceof Date) ? $scope.post.Systemfailure.from_date.toLocaleDateString('de-DE', {day:"2-digit", month: "2-digit", year:"numeric"}) : '',
                    from_time: $scope.post.Systemfailure.from_time.toLocaleTimeString('de-DE', {hour:"2-digit", minute: "2-digit"}),
                    to_date: ($scope.post.Systemfailure.to_date instanceof Date) ? $scope.post.Systemfailure.to_date.toLocaleDateString('de-DE', {day:"2-digit", month: "2-digit", year:"numeric"}) : '',
                    to_time: $scope.post.Systemfailure.to_time.toLocaleTimeString('de-DE', {hour:"2-digit", minute: "2-digit"}),
                    comment: $scope.post.Systemfailure.comment
                }
            };
            $http.post("/systemfailures/add.json?angular=true",
                submitObject
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
