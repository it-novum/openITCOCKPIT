angular.module('openITCOCKPIT').directive('changeLogEntry', function($interval){
    return {
        restrict: 'E',
        templateUrl: '/angular/changeLogEntry.html',
        scope: {
            'changelogentry': '='
        },
        controller: function($scope){
            // Show changes blockquote by default.
            $scope.changelogentry.showChanges = true;
            // Hide changes blockquote if no changes stored on history.
            if($scope.changelogentry.data_unserialized.constructor === Array){
                if($scope.changelogentry.data_unserialized.length === 0){
                    $scope.changelogentry.showChanges = false;
                }
            }else if($scope.changelogentry.data_unserialized.constructor === Object){
                if(Object.keys($scope.changelogentry.data_unserialized).length <= 0){
                    $scope.changelogentry.showChanges = false;
                }
            }
        },
        link: function(scope, element, attr){
        }
    };
});
