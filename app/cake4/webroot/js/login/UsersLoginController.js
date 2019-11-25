loginApp.controller("UsersLoginController", function($scope, $http, $httpParamSerializerJQLike){

    $scope.post = {
        remember_me: 1
    };

    $scope.loadCsrf = function(){
        $http.get("/users/login.json",
            {}
        ).then(function(result){
            $scope._csrfToken = result.data._csrfToken;
        }, function errorCallback(result){
            if(result.data.hasOwnProperty('_csrfToken')){
                $scope._csrfToken = result.data._csrfToken;
            }else{
                console.log('Could not load _csrfToken');
            }
        });
    };

    $scope.submit = function(){

        //Submit as classic form (not as json data) so that
        //CakePHPs FormAuthenticator is able to parse the POST data
        //AngularJS $httpParamSerializerJQLike is going to encode the data for us...

        $scope.post._method = 'POST';
        $scope.post._csrfToken = $scope._csrfToken;

        var req = {
            method: 'POST',
            url: '/users/login',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-Token': $scope._csrfToken
            },
            data: $httpParamSerializerJQLike($scope.post)
        };

        $http(req).then(function(){
            new Noty({
                theme: 'metroui',
                type: 'success',
                layout: 'topCenter',
                text: 'Login successful',
                timeout: 3500
            }).show();
            window.location = '/';
        }, function(){
            $scope.loadCsrf();
            new Noty({
                theme: 'metroui',
                type: 'error',
                layout: 'topCenter',
                text: 'Invalid credentials',
                timeout: 3500
            }).show();
        });
    };

    //Fire on page load
    $scope.loadCsrf();

});
