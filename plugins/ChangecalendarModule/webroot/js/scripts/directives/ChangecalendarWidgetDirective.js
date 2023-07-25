angular.module('openITCOCKPIT').directive('changecalendarWidget', function($http, $sce){
    return {
        restrict: 'E', templateUrl: '/changecalendar_module/changecalendars/widget.html', scope: {
            'widget': '='
        },

        controller: function($scope){
            $scope.currentChangeCalendar = {
                id : null
            };
            $scope.changeCalendars = [];
            var $widget = $('#widget-' + $scope.widget.id);
            $scope.frontWidgetHeight = parseInt(($widget.height()), 10); //-50px header

            $scope.fontSize = parseInt($scope.frontWidgetHeight / 3.8, 10);

            $scope.calendarTimeout = null;

            $scope.load = function(){
                $http.get("/changecalendar_module/changecalendars/widget.json", {
                    params: {
                        'angular': true, 'widgetId': $scope.widget.id
                    }
                }).then(function(result){
                    $scope.init = false;
                    $scope.currentChangeCalendar = result.data.changeCalendar;
                    $scope.events = result.data.events;


                    if($scope.currentChangeCalendar.id !== null){
                        $scope.renderCalendar();
                    }

                    setTimeout(function(){
                        $scope.init = false;
                    }, 250);
                });
            };

            $scope.renderCalendar = function(){
                var calendarEl = document.getElementById('changecalendar-'+$scope.widget.id);
                $scope.calendar = new FullCalendar.Calendar(calendarEl, {
                    plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
                    firstDay: 1, // monday as first day of the week
                    displayEventEnd: true,
                    allDaySlot: true,
                    navLinks: false, // can click day/week names to navigate views
                    weekNumbers: false,
                    weekNumbersWithinDays: false,
                    weekNumberCalculation: 'ISO',
                    eventOverlap: false,
                    eventDurationEditable: false,
                    defaultDate: $scope.defaultDate,
                    businessHours: true, // display business hours
                    editable: false,
                    events: $scope.events,
                    eventClick: function(info){
                        $scope.showEventDetails(info.event);
                    },
                });

                $scope.calendar.render();
            };

            // Show the modal and pre-fill the form with the given event.
            $scope.showEventDetails = function(event){
                let myModal = $('#changecalendar-'+$scope.widget.id+'-details');

                // move around
                $('body').append(myModal);
                console.log(event);

                // Move to end of body tag
                $scope.modifyEvent = {
                    id: event.id,
                    title: event.title,
                    start: event.start,
                    end: event.end,
                    description: event.extendedProps.description,
                    context: event.extendedProps.context
                };

                // Show modal
                myModal.modal('show');

                $scope.$apply();
            };

            $scope.loadChangeCalendars = function(){
                $http.get("/changecalendar_module/changecalendars/index.json?angular=true", {}).then(function(result){
                    var changeCalendars = [];
                    for(var i in result.data.all_changecalendars){
                        changeCalendars.push({
                            id: parseInt(result.data.all_changecalendars[i].id, 10),
                            name: result.data.all_changecalendars[i].name,
                            description: result.data.all_changecalendars[i].description
                        });
                    }
                    $scope.changeCalendars = changeCalendars;
                });
            };

            $scope.hideConfig = function(){
                $scope.$broadcast('FLIP_EVENT_IN');
                if($scope.init){
                    return;
                }
                if($scope.calendar !== null){
                    if (typeof($scope.calendar.destroy) === "function") {
                        $scope.calendar.destroy();
                    }
                }
                $scope.load();
            };
            $scope.showConfig = function(){
                $scope.$broadcast('FLIP_EVENT_OUT');
                $scope.loadChangeCalendars();
            };

            $scope.saveChangecalendar = function(){
                $http.post("/changecalendar_module/changecalendars/widget.json?angular=true", {
                    Widget: {
                        id: $scope.widget.id
                    },
                    changecalendar_id: $scope.currentChangeCalendar.id
                }).then(function(result){
                    //Update status
                    $scope.hideConfig();
                });
            };

            $widget.on('resize', function(event, items){
                hasResize();
            });
            var hasResize = function(){
                if($scope.init){
                    return;
                }
                if($scope.calendar !== null){
                    $scope.calendar.destroy();
                    $scope.calendar = null;
                }
                $scope.frontWidgetHeight = parseInt(($widget.height()), 10); //-50px header

                $scope.fontSize = parseInt($scope.frontWidgetHeight / 3.8, 10);

                if($scope.calendarTimeout){
                    clearTimeout($scope.calendarTimeout);
                }
                $scope.calendarTimeout = setTimeout(function(){
                    if($scope.init){
                        return;
                    }
                    if($scope.calendar !== null){
                        $scope.calendar.destroy();
                    }
                    $scope.load();
                }, 500);
            };

            /** Page load / widget get loaded **/
            $scope.load();


        },

        link: function($scope, element, attr){

        }
    };
});
