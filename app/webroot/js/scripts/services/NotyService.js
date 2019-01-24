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
            }
        }
    });