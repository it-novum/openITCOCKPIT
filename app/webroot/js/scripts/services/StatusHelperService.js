angular.module('openITCOCKPIT')
    .service('StatusHelperService', function(){

        return {
            getHoststatusTextColor: function(currentState){
                switch(currentState){
                    case 0:
                    case '0':
                        return 'txt-color-green';

                    case 1:
                    case '1':
                        return 'txt-color-red';

                    case 2:
                    case '2':
                        return 'txt-color-blueLight';
                }
                return 'txt-primary';
            },
            getServicestatusTextColor: function(currentState){
                switch(currentState){
                    case 0:
                    case '0':
                        return 'txt-color-green';

                    case 1:
                    case '1':
                        return 'warning';

                    case 2:
                    case '2':
                        return 'txt-color-red';

                    case 3:
                    case '3':
                        return 'txt-color-blueLight';
                }
                return 'txt-primary';
            }
        }
    });