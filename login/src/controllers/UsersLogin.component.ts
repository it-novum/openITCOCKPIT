import * as angular from 'angular';
import {parseUri} from "../../../webroot/js/lib/parseuri";
import Noty from "noty";


let UsersLoginComponent = {
    selector: "usersLogin", // maps to <users-login>
    templateUrl: '/users/login.html?template=true',
    bindings: {},
    controller: class LoginLayoutController {
        public post = {
            remember_me: 1,
            email: '',
            password: '',
            _method: 'POST',
            _csrfToken: '',
        };
        public disableLogin = false;
        public hasValidSslCertificate = false;
        public images;

        private $http;
        private $httpParamSerializerJQLike;
        private _csrfToken;

        static $inject = ['$http', '$httpParamSerializerJQLike'];

        constructor($http, $httpParamSerializerJQLike) {
            this.$http = $http;
            this.$httpParamSerializerJQLike = $httpParamSerializerJQLike;
            this.loadCsrf();
        }


        private isOAuthResponse(hasSsoError) {
            if (hasSsoError === true) {
                return;
            }

            var sourceUrl = parseUri(decodeURIComponent(window.location.href)).source;
            if (sourceUrl.includes('/#!/')) {
                sourceUrl = sourceUrl.replace('/#!', '');
            }

            var query = parseUri(sourceUrl).queryKey;
            if (query.hasOwnProperty('code') && query.hasOwnProperty('state')) {
                // User got redirected back from oAuth servers login screen to openITCOCKPIT

                new Noty({
                    theme: 'metroui',
                    type: 'success',
                    layout: 'topCenter',
                    text: 'Login successful',
                    timeout: 3500
                }).show();

                console.log(this.getLocalStorageItemWithDefaultAndRemoveItem('lastPage', '/'));
                window.location.href = this.getLocalStorageItemWithDefaultAndRemoveItem('lastPage', '/');
            }

        };

        public loadCsrf() {
            //Check if a state is stored in the URL
            var location = window.location.toString();
            if (location.includes('#!/')) {
                //Save state from URL into local storage because oAuth login force an reload of the page...
                //console.log('SAVE: ' + '/' + location.substring(location.indexOf('#!/')));
                window.localStorage.setItem('lastPage', '/' + location.substring(location.indexOf('#!/')));
            }

            this.$http.get("/users/login.json",
                {}
            ).then((result) => {
                this._csrfToken = result.data._csrfToken;
                this.hasValidSslCertificate = result.data.hasValidSslCertificate;

                var hasSsoError = false;
                if (result.data.hasOwnProperty('errorMessages')) {
                    for (var index in result.data.errorMessages) {
                        hasSsoError = true;
                        new Noty({
                            theme: 'metroui',
                            type: 'error',
                            layout: 'topCenter',
                            text: result.data.errorMessages[index],
                            timeout: 5500
                        }).show();
                    }
                }

                if (result.data.isLoggedIn === true) {
                    //User maybe logged in via oAuth?
                    this.isOAuthResponse(hasSsoError);
                }

                if (result.data.isLoggedIn === false && hasSsoError === false) {
                    if (result.data.isSsoEnabled === true && result.data.forceRedirectSsousersToLoginScreen === true) {
                        setTimeout(function () {
                            window.location.href = '/users/login?redirect_sso=true';
                        }, 10);
                    }
                }

            }, (result) => {
                if (result.data.hasOwnProperty('_csrfToken')) {
                    this._csrfToken = result.data._csrfToken;
                } else {
                    console.log('Could not load _csrfToken');
                }
            });
        };

        private getLocalStorageItemWithDefaultAndRemoveItem(key, defaultValue) {
            var val = window.localStorage.getItem(key);
            if (val === null) {
                return defaultValue;
            }
            //window.localStorage.removeItem(key);
            return val;
        };

        public submit() {
            this.disableLogin = true;

            //Submit as classic form (not as json data) so that
            //CakePHPs FormAuthenticator is able to parse the POST data
            //AngularJS $httpParamSerializerJQLike is going to encode the data for us...

            this.post._method = 'POST';
            this.post._csrfToken = this._csrfToken;

            var req = {
                method: 'POST',
                url: '/users/login',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-Token': this._csrfToken
                },
                data: this.$httpParamSerializerJQLike(this.post)
            };

            this.$http(req).then(() => {
                //Login successfully
                this.disableLogin = false;

                new Noty({
                    theme: 'metroui',
                    type: 'success',
                    layout: 'topCenter',
                    text: 'Login successful',
                    timeout: 3500
                }).show();

                window.location = this.getLocalStorageItemWithDefaultAndRemoveItem('lastPage', '/');
            }, (result) => {
                //Error

                this.loadCsrf();
                this.disableLogin = false;

                if (result.data.hasOwnProperty('errors')) {
                    for (var key in result.data.errors) {
                        if (typeof result.data.errors[key] === "string") {
                            new Noty({
                                theme: 'metroui',
                                type: 'error',
                                layout: 'topCenter',
                                text: result.data.errors[key],
                                timeout: 5500
                            }).show();
                        } else {
                            for (var index in result.data.errors[key]) {
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


    }
}

angular
    .module("openITCOCKPITLogin")
    .component(UsersLoginComponent.selector, UsersLoginComponent);

