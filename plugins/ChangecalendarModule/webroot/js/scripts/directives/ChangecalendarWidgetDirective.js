angular.module('openITCOCKPIT').directive('changecalendarWidget', function($http, $sce, BBParserService, $q){
    return {
        restrict: 'E', templateUrl: '/changecalendar_module/changecalendars/widget.html', scope: {
            'widget': '='
        },

        controller: function($scope){
            $scope.currentChangeCalendars =[];
            $scope.changeCalendars = [];
            var $widget = $('#widget-' + $scope.widget.id);
            $scope.frontWidgetHeight = parseInt(($widget.height()), 10); //-50px header

            $scope.fontSize = parseInt($scope.frontWidgetHeight / 3.8, 10);

            $scope.calendarTimeout = null;
            $scope.displayType = 'dayGridMonth';

            $scope.load = function(){

                $q.all([

                    $http.get("/changecalendar_module/changecalendars/widget.json", {
                        params: {
                            'angular': true, 'widgetId': $scope.widget.id
                        }
                    }),
                    $http.get("/angular/user_timezone.json", {
                        params: {
                            'angular': true
                        }
                    })
                ]).then(function(results){
                    $scope.init = false;
                    $scope.currentChangeCalendars = results[0].data.changeCalendars;
                    $scope.displayType = results[0].data.displayType;
                    $scope.changeCalendarIds = [];
                    $scope.timeZone   = results[1].data.timezone;

                    let evenz = [];
                    for(var index in $scope.currentChangeCalendars) {
                        let myChangeCalendar = $scope.currentChangeCalendars[index];

                        $scope.changeCalendarIds.push($scope.currentChangeCalendars[index].id);

                        for(var eventIndex in myChangeCalendar.changecalendar_events) {
                            let myEvent = myChangeCalendar.changecalendar_events[eventIndex];
                            evenz.push(myEvent);
                        }
                    }

                    $scope.events = evenz;


                    if($scope.currentChangeCalendars !== null){
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
                    timeZone: $scope.timeZone.user_timezone,
                    plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    eventTimeFormat: {
                        hour: '2-digit', minute: '2-digit', hour12: false
                    },
                    defaultView: $scope.displayType,
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

                // Fullcalendar ignores the setting for it's original time zone it was using.
                // FC still uses the correct time, BUT claims it is in UTC zone.
                let newStart = luxon.DateTime.fromMillis(event.start.getTime(), {zone: 'UTC'});
                let newEnd   = luxon.DateTime.fromMillis(event.end.getTime(),   {zone: 'UTC'});
                // So we simply add the timeZone WITHOUT re-calculating the actual time.
                // We change the ZONE. NOT the TIME.
                newStart = newStart.setZone($scope.timeZone.user_timezone, {keepLocalTime: true});
                newEnd   =   newEnd.setZone($scope.timeZone.user_timezone, {keepLocalTime: true});

                let myStart = new Date(newStart.get('year'), newStart.get('month')-1, newStart.get('day'))
                myStart.setHours(newStart.get('hour'), newStart.get('minute'), newStart.get('second'))


                let myEnd = new Date(newEnd.get('year'), newEnd.get('month')-1, newEnd.get('day'))
                myEnd.setHours(newEnd.get('hour'), newEnd.get('minute'), newEnd.get('second'))

                $scope.modifyEvent = {
                    id: event.id,
                    title: event.title,
                    start: myStart,
                    end: myEnd,
                    description: event.extendedProps.description,
                    context: event.extendedProps.context
                };


                $scope.descriptionPreview = BBParserService.parse($scope.modifyEvent.description);

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
                    changecalendar_ids: $scope.changeCalendarIds,
                    displayType : $scope.displayType
                }).then(function(result){
                    //Update status
                    $scope.hideConfig();
                });
            };

            /** Page load / widget get loaded **/
            $scope.load();
        },

        link: function($scope, element, attr){

        }
    };
});
