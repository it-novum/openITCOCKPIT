angular.module('openITCOCKPIT')
    .service('NotyService', function(){

        var NotyMsg = function(options){

            new Noty({
                theme: 'metroui',
                type: options.type,
                text: options.message,
                timeout: options.timeout
            }).show();

        };

        return {
            scrollTop: function(){
                $('body,html').animate({
                    scrollTop: 0
                }, 800);
                return false;
            },

            genericSuccess: function(options){
                options = options || {};
                options.message = options.message || 'Data saved successfully';
                options.timeout = options.timeout || 3500;

                options.type = 'success';

                NotyMsg(options);
            },

            genericError: function(options){
                options = options || {};
                options.message = options.message || 'Error while saving data';
                options.timeout = options.timeout || 3500;

                options.type = 'error';

                NotyMsg(options);
            },

            genericWarning: function(options){
                options = options || {};
                options.message = options.message || 'Warning - something unexpected happened';
                options.timeout = options.timeout || 3500;

                options.type = 'warning';

                NotyMsg(options);
            },

            deleteSuccess: function(options){
                options = options || {};
                options.message = options.message || 'Record deleted successfully';
                options.timeout = options.timeout || 3500;

                options.type = 'success';

                NotyMsg(options);
            },

            deleteError: function(options){
                options = options || {};
                options.message = options.message || 'Error while deleting record';
                options.timeout = options.timeout || 3500;

                options.type = 'error';

                NotyMsg(options);
            },

            info: function(options){
                options = options || {};
                options.message = options.message || 'Please wait while processing ...';
                options.timeout = options.timeout || 3500;

                options.type = 'info';

                NotyMsg(options);
            }
        }
    });
