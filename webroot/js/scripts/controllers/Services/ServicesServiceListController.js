angular.module('openITCOCKPIT')
    .controller('ServicesServiceListController', function($scope, $http, SortService, MassChangeService, $stateParams){

        SortService.setSort('Servicestatus.current_state');
        SortService.setDirection('desc');
        $scope.currentPage = 1;

        $scope.data = {
            hostId: parseInt($stateParams.id),
            hostname: ''
        };

        $scope.massChange = {};
        $scope.selectedElements = 0;
        $scope.deleteUrl = '/services/delete/';
        $scope.deactivateUrl = '/services/deactivate/';
        $scope.activateUrl = '/services/enable/';
        $scope.mouseout = true;
        $scope.popoverTimer = null;

        $scope.activeTab = 'active';

        //There is no service status for not monitored services :)
        $scope.fakeServicestatus = {
            Servicestatus: {
                currentState: 5
            }
        };

        $scope.init = true;
        $scope.serverResult = [];

        $scope.changeTab = function(tab){
            if(tab !== $scope.activeTab){
                $scope.services = [];
                $scope.activeTab = tab;
                $scope.undoSelection();

                SortService.setSort('servicename');
                SortService.setDirection('asc');
                $scope.currentPage = 1;

                if($scope.activeTab === 'deleted'){
                    SortService.setSort('DeletedServices.name');
                }

                $scope.load();
            }

        };

        $scope.load = function(){
            switch($scope.activeTab){
                case 'active':
                    $scope.loadActiveServices();
                    break;

                case 'notMonitored':
                    $scope.loadNotMonitoredServices();
                    break;

                case 'disabled':
                    $scope.loadDisabledServices();
                    break;

                case 'deleted':
                    $scope.loadDeletedServices();
                    break;
            }
        };

        $scope.loadTimezone = function(){
            $http.get("/angular/user_timezone.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.timezone = result.data.timezone;
            });
        };

        $scope.loadHost = function(){
            $http.get("/hosts/loadHostById/" + $scope.data.hostId + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.host = result.data.host;
            });
        };

        $scope.loadActiveServices = function(){
            var params = {
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Hosts.id]': $scope.data.hostId
            };

            $http.get("/services/index.json", {
                params: params
            }).then(function(result){
                $scope.services = [];
                $scope.services = result.data.all_services;

                if($scope.services.length > 0){
                    $scope.data.hostname = $scope.services[0].Service.hostname;
                }

                $scope.paging = result.data.paging;
                $scope.init = false;
            });
        };

        $scope.loadNotMonitoredServices = function(){
            var params = {
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Hosts.id]': $scope.data.hostId
            };

            $http.get("/services/notMonitored.json", {
                params: params
            }).then(function(result){
                $scope.services = [];
                $scope.services = result.data.all_services;

                $scope.paging = result.data.paging;
                $scope.init = false;
            });
        };

        $scope.loadDisabledServices = function(){
            var params = {
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[Hosts.id]': $scope.data.hostId
            };

            $http.get("/services/disabled.json", {
                params: params
            }).then(function(result){
                $scope.services = [];
                $scope.services = result.data.all_services;

                $scope.paging = result.data.paging;
                $scope.init = false;
            });
        };

        $scope.loadDeletedServices = function(){
            var params = {
                'angular': true,
                'sort': SortService.getSort(),
                'page': $scope.currentPage,
                'direction': SortService.getDirection(),
                'filter[DeletedServices.host_id]': $scope.data.hostId
            };

            $http.get("/services/deleted.json", {
                params: params
            }).then(function(result){
                $scope.deletedServices = [];
                $scope.deletedServices = result.data.all_services;

                $scope.paging = result.data.paging;
                $scope.init = false;
            });
        };

        $scope.loadHosts = function(searchString){
            $http.get("/hosts/loadHostsByString.json", {
                params: {
                    'angular': true,
                    'filter[Hosts.name]': searchString,
                    'selected[]': $scope.data.hostId
                }
            }).then(function(result){
                $scope.hosts = result.data.hosts;
            });
        };

        $scope.changepage = function(page){
            $scope.undoSelection();
            if(page !== $scope.currentPage){
                $scope.currentPage = page;
                $scope.load();
            }
        };

        $scope.selectAll = function(){
            if($scope.services){
                for(var key in $scope.services){
                    if($scope.services[key].Service.allow_edit){
                        var id = $scope.services[key].Service.id;
                        $scope.massChange[id] = true;
                    }
                }
            }
        };

        $scope.undoSelection = function(){
            MassChangeService.clearSelection();
            $scope.massChange = MassChangeService.getSelected();
            $scope.selectedElements = MassChangeService.getCount();
        };

        $scope.getObjectForDelete = function(service){
            var object = {};
            object[service.Service.id] = service.Host.hostname + '/' + service.Service.servicename;
            return object;
        };

        $scope.getObjectsForDelete = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.services){
                for(var id in selectedObjects){
                    if(id == $scope.services[key].Service.id){
                        objects[id] =
                            $scope.services[key].Host.hostname + '/' +
                            $scope.services[key].Service.servicename;
                    }

                }
            }
            return objects;
        };

        $scope.getObjectsForExternalCommand = function(){
            var objects = {};
            var selectedObjects = MassChangeService.getSelected();
            for(var key in $scope.services){
                for(var id in selectedObjects){
                    if(id == $scope.services[key].Service.id){
                        objects[id] = $scope.services[key];
                    }

                }
            }
            return objects;
        };

        $scope.linkForCopy = function(){
            var ids = Object.keys(MassChangeService.getSelected());
            return ids.join(',');
        };

        $scope.$watch('data.hostId', function(){
            $scope.loadHost();
            $scope.load();
        });

        $scope.$watch('massChange', function(){
            MassChangeService.setSelected($scope.massChange);
            $scope.selectedElements = MassChangeService.getCount();
        }, true);

        $scope.loadTimezone();
        SortService.setCallback($scope.load);

        $scope.loadHosts('');

        $scope.getDowntimeDetails = function (id) {
            if($scope.popoverTimer === null) {
                $scope.popoverTimer = setTimeout(function () {
                    var selector = 'downtimeServicetip_' + id;
                    $http.get("/services/browser/" + Number(id) + ".json", {
                        params: {
                            'angular': true
                        }
                    }).then(function (result) {
                        var html = '<div>';
                        var text1 = '';
                        var text2 = '';
                        var text3 = '';
                        var text4 = '';
                        var text5 = '';
                        var end = '</div>';
                        var title = '';
                        if (result.data.downtime.scheduledStartTime && result.data.downtime.scheduledEndTime) {
                            text1 = "<h4>Downtime:</h4>";
                            text2 = "Start: " + result.data.downtime.scheduledStartTime + "<br/>";
                            text3 = "End: " + result.data.downtime.scheduledEndTime + "<br/>";
                            text4 = "Comment: " + result.data.downtime.commentData + "<br/>";
                            text5 = "Author: " + result.data.downtime.authorName + "<br/>";
                            title = html.concat(text1, text2, text3, text4, text5, end);
                        } else {
                            html = '<div>';
                            text1 = "<h4>No Downtime</h4><br/>Please refresh!";
                            title = html.concat(text1, end);
                        }

                        $('#' + selector).popover({
                            placement: "right",
                            template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>',
                            content: title,
                            html: true
                        });
                        $('#' + selector).popover('show');

                    }, function errorCallback(result) {
                        $('#' + selector).popover('dispose');
                    });
                }, 300);
            }
        };

        $scope.delPopover = function(){
            if($scope.popoverTimer !== null){
                clearTimeout($scope.popoverTimer);
                $scope.popoverTimer = null;
            }
            $('[data-toggle="popover"]').popover('dispose');
        };

        $scope.getAckDetails = function (id) {
            if ($scope.popoverTimer === null) {
                $scope.popoverTimer = setTimeout(function () {
                    var selector = 'ackServicetip_' + id;
                    $http.get("/services/browser/" + Number(id) + ".json", {
                        params: {
                            'angular': true
                        }
                    }).then(function (result) {
                        var html = '<div>';
                        var text1 = '';
                        var text2 = '';
                        var text3 = '';
                        var text4 = '';
                        var end = '</div>';
                        var title = '';
                        if (result.data.acknowledgement.comment_data && result.data.acknowledgement.author_name && result.data.acknowledgement.entry_time) {
                            if (result.data.acknowledgement.is_sticky) {
                                text1 = "<h4>State of service is acknowledged(sticky)</h4>";
                            } else {
                                text1 = "<h4>State of service is acknowledged</h4>";
                            }
                            text2 = "Set by: " + result.data.acknowledgement.author_name + "<br/>";
                            text3 = "Set at: " + result.data.acknowledgement.entry_time + "<br/>";
                            text4 = "Comment: " + result.data.acknowledgement.comment_data + "<br/>";
                            title = html.concat(text1, text2, text3, text4, end);
                        } else {
                            html = '<div>';
                            text1 = "<h4>Not acknowledeged</h4>";
                            title = html.concat(text1, end);
                        }
                        $('#' + selector).popover({
                            placement: "right",
                            template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>',
                            content: title,
                            html: true
                        });
                        $('#' + selector).popover('show');
                    }, function errorCallback(result) {
                        $('#' + selector).popover('dispose');
                    });
                }, 300);
            }
        };

    });
