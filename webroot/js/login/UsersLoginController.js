loginApp.controller("UsersLoginController", function($scope, $http, $httpParamSerializerJQLike){

    $scope.post = {
        remember_me: 1
    };

    $scope.disableLogin = false;
    $scope.hasValidSslCertificate = false;

    var isOAuthResponse = function(){
        var sourceUrl = parseUri(decodeURIComponent(window.location.href)).source;
        if(sourceUrl.includes('/#!/')){
            sourceUrl = sourceUrl.replace('/#!', '');
        }

        var query = parseUri(sourceUrl).queryKey;
        if(query.hasOwnProperty('code') && query.hasOwnProperty('state')){
            // User got redirected back from oAuth servers login screen to openITCOCKPIT

            new Noty({
                theme: 'metroui',
                type: 'success',
                layout: 'topCenter',
                text: 'Login successful',
                timeout: 3500
            }).show();

            var location = window.location.toString();
            if(location.includes('#!/')){
                window.location = '/' + location.substring(location.indexOf('#!/'));
            }else{
                window.location = $scope.getLocalStorageItemWithDefaultAndRemoveItem('lastPage', '/');
            }

        }

    };

    $scope.loadCsrf = function(){
        $http.get("/users/login.json",
            {}
        ).then(function(result){
            $scope._csrfToken = result.data._csrfToken;
            $scope.hasValidSslCertificate = result.data.hasValidSslCertificate;

            if(result.data.isLoggedIn === true){
                //User maybe logged in via oAuth?
                isOAuthResponse();
            }

        }, function errorCallback(result){
            if(result.data.hasOwnProperty('_csrfToken')){
                $scope._csrfToken = result.data._csrfToken;
            }else{
                console.log('Could not load _csrfToken');
            }
        });
    };

    $scope.getLocalStorageItemWithDefaultAndRemoveItem = function(key, defaultValue){
        var val = window.localStorage.getItem(key);
        if(val === null){
            return defaultValue;
        }
        window.localStorage.removeItem(key);
        return val;
    };

    $scope.submit = function(){
        $scope.disableLogin = true;

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
            //Login successfully
            $scope.disableLogin = false;

            new Noty({
                theme: 'metroui',
                type: 'success',
                layout: 'topCenter',
                text: 'Login successful',
                timeout: 3500
            }).show();

            var location = window.location.toString();
            if(location.includes('#!/')){
                window.location = '/' + location.substring(location.indexOf('#!/'));
            }else{
                window.location = $scope.getLocalStorageItemWithDefaultAndRemoveItem('lastPage', '/');
            }
        }, function(result){
            //Error

            $scope.loadCsrf();
            $scope.disableLogin = false;

            if(result.data.hasOwnProperty('errors')){
                for(var key in result.data.errors){
                    if(typeof result.data.errors[key] === "string"){
                        new Noty({
                            theme: 'metroui',
                            type: 'error',
                            layout: 'topCenter',
                            text: result.data.errors[key],
                            timeout: 5500
                        }).show();
                    }else{
                        for(var index in result.data.errors[key]){
                            new Noty({
                                theme: 'metroui',
                                type: 'error',
                                layout: 'topCenter',
                                text: result.data.errors[key][index],
                                timeout: 5500
                            }).show();
                        }
                    }
                }

                return;
            }

            new Noty({
                theme: 'metroui',
                type: 'error',
                layout: 'topCenter',
                text: 'Unknown error',
                timeout: 5500
            }).show();
        });
    };

    //Fire on page load
    $scope.loadCsrf();

});
