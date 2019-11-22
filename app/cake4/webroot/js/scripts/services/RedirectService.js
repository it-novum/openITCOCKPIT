angular.module('openITCOCKPIT')
    .service('RedirectService', function($state, NotyService){

        return {
            redirectWithFallback: function(fallbackState){
                if($state.previous.name !== "" && $state.previous.url !== "^"){
                    $state.go($state.previous.name, $state.previous.params).then(function(){
                        NotyService.scrollTop();
                    });
                }else{
                    $state.go(fallbackState).then(function(){
                        NotyService.scrollTop();
                    });
                }
            }
        }
    });