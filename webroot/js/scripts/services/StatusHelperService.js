angular.module('openITCOCKPIT')
    .service('StatusHelperService', function(){

        return {
            getHoststatusTextColor: function(currentState){
                switch(currentState){
                    case 0:
                    case '0':
                        return 'up';

                    case 1:
                    case '1':
                        return 'down';

                    case 2:
                    case '2':
                        return 'unreachable';
                }
                return 'txt-primary';
            },
            getServicestatusTextColor: function(currentState){
                switch(currentState){
                    case 0:
                    case '0':
                        return 'ok';

                    case 1:
                    case '1':
                        return 'warning';

                    case 2:
                    case '2':
                        return 'critical';

                    case 3:
                    case '3':
                        return 'unknown';
                }
                return 'txt-primary';
            }
        }
    });