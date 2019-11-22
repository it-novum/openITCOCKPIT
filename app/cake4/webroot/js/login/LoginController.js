loginApp.controller("LoginController", function($scope, $http, $httpParamSerializerJQLike){

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
    //$scope.loadCsrf();

    localStorage.removeItem('browserUuid');

    particlesJS('particles-js',

        {
            "particles": {
                "number": {
                    "value": 50,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": "#ffffff"
                },
                "shape": {
                    "type": "circle",
                    "stroke": {
                        "width": 0,
                        "color": "#000000"
                    },
                    "polygon": {
                        "nb_sides": 5
                    },
                    "image": {
                        "width": 100,
                        "height": 100
                    }
                },
                "opacity": {
                    "value": 0.5,
                    "random": false,
                    "anim": {
                        "enable": false,
                        "speed": 1,
                        "opacity_min": 0.1,
                        "sync": false
                    }
                },
                "size": {
                    "value": 5,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 40,
                        "size_min": 0.1,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#ffffff",
                    "opacity": 0.4,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 2,
                    "direction": "none",
                    "random": false,
                    "straight": false,
                    "out_mode": "out",
                    "attract": {
                        "enable": false,
                        "rotateX": 600,
                        "rotateY": 1200
                    }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "repulse"
                    },
                    "onclick": {
                        "enable": true,
                        "mode": "push"
                    },
                    "resize": true
                },
                "modes": {
                    "grab": {
                        "distance": 400,
                        "line_linked": {
                            "opacity": 1
                        }
                    },
                    "bubble": {
                        "distance": 400,
                        "size": 40,
                        "duration": 2,
                        "opacity": 8,
                        "speed": 3
                    },
                    "repulse": {
                        "distance": 200
                    },
                    "push": {
                        "particles_nb": 4
                    },
                    "remove": {
                        "particles_nb": 2
                    }
                }
            },
            "retina_detect": true,
            "config_demo": {
                "hide_card": false,
                "background_color": "#b61924",
                "background_image": "",
                "background_position": "50% 50%",
                "background_repeat": "no-repeat",
                "background_size": "cover"
            }
        }
    );

});
