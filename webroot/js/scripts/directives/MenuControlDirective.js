angular.module('openITCOCKPIT').directive('menuControl', function($http, $timeout, $httpParamSerializer, $state){
    return {
        restrict: 'E',
        templateUrl: '/angular/menuControl.html',
        scope: {},

        controller: function($scope){

            $scope.setMenuState = function(key, value){
                localStorage.setItem(key, value);
            };

            $scope.setMenuMinify = function(boolstate = true){
                $scope.setMenuState('menuStateMinify', boolstate ? '1' : '0');
            };
            $scope.setMenuFixed = function(boolstate = true){
                $scope.setMenuState('menuStateFixed', boolstate ? '1' : '0');
            };
            $scope.setMenuHidden = function(boolstate = true){
                $scope.setMenuState('menuStateHidden', boolstate ? '1' : '0');
            };

            $scope.getMenuMinify = function(){
                return localStorage.getItem('menuStateMinify') === '1';
            };
            $scope.getMenuFixed = function(){
                return localStorage.getItem('menuStateFixed') === '1';
            };
            $scope.getMenuHidden = function(){
                return localStorage.getItem('menuStateHidden') === '1';
            };

            $scope.toggleMenuMinify = function(){
                var body = document.getElementsByTagName('body')[0];
                if(body){
                    var storeMinifiedState = !body.classList.contains('nav-function-minify');
                    $scope.setMenuMinify(storeMinifiedState);
                }
            };
            $scope.toggleMenuFixed = function(){
                var body = document.getElementsByTagName('body')[0];
                if(body){
                    var storeFixedState = !body.classList.contains('nav-function-fixed');
                    $scope.setMenuFixed(storeFixedState);
                }
            };
            $scope.toggleMenuHidden = function(){
                var body = document.getElementsByTagName('body')[0];
                if(body){
                    var storeHiddenState = !body.classList.contains('nav-function-hidden');
                    $scope.setMenuHidden(storeHiddenState);
                }
            };

            $scope.load = function(){
                var body = document.getElementsByTagName('body')[0];
                if(body){
                    if($scope.getMenuMinify()){
                        body.classList.add('nav-function-minify');
                    }
                    if($scope.getMenuFixed()){
                        body.classList.add('nav-function-fixed');
                    }
                    if($scope.getMenuHidden()){
                        body.classList.add('nav-function-hidden');
                    }
                }
            };

            $scope.load();
        },

        link: function($scope, element, attrs){

        }
    };
});
