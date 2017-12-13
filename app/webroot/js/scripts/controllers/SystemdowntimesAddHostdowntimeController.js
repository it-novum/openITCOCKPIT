angular.module('openITCOCKPIT')
    .controller('SystemdowntimesAddHostdowntimeController', function($scope, $http, $timeout){

        $scope.init = true;
        $scope.selectedTenant = null;
        $scope.selectedTenantForNode = null;
        $scope.errors = null;

        $scope.Downtime = {
            Hostname: null,
            Comment: null,
            Type1: null,
            Type2: null,
            Recurring: {
                Style: {
                    "display": "none"
                },
                IsRecurring: null,
                DaysOfMonth: null,
                Weekdays: {},
                SelectedWeekdays: {}
            },
            FromDate: null,
            FromTime: null,
            ToDate: null,
            ToTime: null
        };

        $scope.post = {
            Container: {
                parent_id: null,
                name: null,
                containertype_id: '5'
            }
        };

        $scope.load = function(){
            $scope.loadContainers();
            $scope.loadContainerlist();
        };

        $scope.saveNewNode = function(){
            $http.post("/containers/add.json?angular=true", $scope.post).then(function(result){
                $('#nodeCreatedFlashMessage').show();
                $scope.post.Container.name = null;
                $scope.load();
                $timeout(function(){
                    $('#nodeCreatedFlashMessage').hide();
                },3000);
                $scope.errors = null;
            }, function errorCallback(result){
                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };

        $scope.loadTenants = function(){
            $http.get("/tenants/index.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.tenants = result.data.all_tenants;
                $scope.init = false;
            });
        };

        $scope.loadContainers = function(){
            $http.get('/containers/byTenant/' + $scope.selectedTenant + '.json', {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.nest;
                $('#nestable').nestable({
                    noDragClass: 'dd-nodrag'
                });
            });
        };

        $scope.loadContainerlist = function(){
            $http.get('/containers/byTenantForSelect/' + $scope.selectedTenant + '.json').then(function(result){
                $scope.containerlist = result.data.paths;
            });
        };

        $scope.loadTenants();

        $scope.$watch('Downtime.Recurring.SelectedWeekdays', function(){
            if($scope.Downtime.Recurring.SelectedWeekdays !== null){
                //$scope.load();
                console.log($scope.Downtime.Recurring.SelectedWeekdays);
            }
        });
        $scope.$watch('Downtime.Recurring.IsRecurring', function(){
            if($scope.Downtime.Recurring.IsRecurring === true){
                $scope.Downtime.Recurring.Style["display"]="block";
            }
            if($scope.Downtime.Recurring.IsRecurring === false){
                $scope.Downtime.Recurring.Style["display"]="none";
            }
        });

    });