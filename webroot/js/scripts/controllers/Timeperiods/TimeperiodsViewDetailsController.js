angular.module('openITCOCKPIT')
    .controller('TimeperiodsViewDetailsController', function($scope, $http, $stateParams){
        $scope.id = $stateParams.id;

        $scope.init = true;

        $scope.load = function(){
            $http.get("/timeperiods/viewDetails/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.timeperiod = result.data.timeperiod;
                $scope.init = false;

                var calendarEl = document.getElementById('calendar');
                $scope.calendar = new FullCalendar.Calendar(calendarEl, {
                    plugins: ['timeGrid'],
                    defaultView: 'timeGridWeek',
                    locale: 'ru',
                    theme: false,
                    header: false,
                    allDaySlot: false,
                    contentHeight: 'auto',
                    duration: {
                        days: 7
                    },
                    columnHeaderFormat: {
                        weekday: 'long'
                    },
                    eventTimeFormat: {
                        hour: '2-digit', //2-digit, numeric
                        minute: '2-digit', //2-digit, numeric
                        second: '2-digit', //2-digit, numeric
                        meridiem: true, //lowercase, short, narrow, false (display of AM/PM)
                        hour12: false //true, false
                    },
                    slotLabelFormat: {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: false
                    },

                    slotDuration: '01:00', // 1 hour
                    minTime: '00:00:00',
                    maxTime: '24:00:00',
                    firstDay: 1, // monday as first day of the week
                    editable: false,
                    nowIndicator: true,
                    events: [
                        {
                            daysOfWeek: [0, 6], //Sundays and saturdays
                            rendering: "background",
                            className: "fc-nonbusiness",
                            overLap: false,
                            allDay: true
                        },

                        {
                            daysOfWeek: [0, 1, 2, 5, 6], //Sundays and saturdays
                            rendering: "background",
                            className: "no-events",
                        },
                        {
                            daysOfWeek: [3],
                            startTime: '13:00',
                            endTime: '20:00'
                        },
                        {
                            daysOfWeek: [3],
                            startTime: '22:30',
                            endTime: '24:00'
                        }
                    ]
                });
                $scope.calendar.render();

            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };
        $scope.load();
    });
