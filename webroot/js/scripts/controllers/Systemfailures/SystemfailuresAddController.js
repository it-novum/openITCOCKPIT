angular.module('openITCOCKPIT')
    .controller('SystemfailuresAddController', function($scope, $http, SudoService, $state, NotyService, RedirectService){

        $scope.data = {
            createAnother: false
        };

        var clearForm = function(){

            var now = new Date();

            $scope.post = {
                Systemfailure: {
                    from_date: '',
                    from_time: '00:00',
                    to_date: '',
                    to_time: '00:00',
                    comment: ''
                }
            };
        };
        clearForm();

        $scope.init = true;


        $scope.submit = function(){
            $http.post("/systemfailures/add.json?angular=true",
                $scope.post
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

        jQuery(function(){
            $('#SystemfailureFromDate').datepicker({
                format: 'dd.mm.yy'
            });
            $('#SystemfailureToDate').datepicker({
                format: 'dd.mm.yy'
            });
        });


    });
