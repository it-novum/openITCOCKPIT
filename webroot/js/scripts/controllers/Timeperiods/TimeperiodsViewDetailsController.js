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
                    //slotLabelFormat:"HH:mm",
                    theme: false,
                    header: false,
                    allDaySlot: false,
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

                    slotDuration: '00:15', // 15 minutes
                    minTime: '00:00:00',
                    maxTime: '24:00:00',
                    firstDay: 1, // monday as first day of the week
                    editable: false,
                    //events: $scope.timeperiod.timeperiod_timeranges

                    events: [
                        {

                            daysOfWeek: [1],
                            start: new Date('2021-08-12T10:00:00'),
                            end: new Date('2021-08-12T10:00:00'),

                            //    startTime: '08:00',
                            //    endTime: '15:10'
                        },
                        {
                            daysOfWeek: [3],
                            startTime: '13:00',
                            endTime: '20:00'
                        },
                        {
                            title: "My repeating event",
                            startTime: '10:00', // a start time (10am in this example)
                            startRecur: '10:00', // a start time (10am in this example)
                            endTime: '14:00', // an end time (6pm in this example)
                            endRecur: '14:00', // an end time (6pm in this example)

                            daysOfWeek: [1, 4] // Repeat monday and thursday
                        }
                    ],
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
