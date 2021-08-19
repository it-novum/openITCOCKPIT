angular.module('openITCOCKPIT')
    .controller('TimeperiodsViewDetailsController', function($scope, $http, $q, $stateParams){
        $scope.id = $stateParams.id;

        $scope.init = true;
        $scope.languageCode = 'en';
        $scope.id = $stateParams.id;

        $scope.load = function(){
            $q.all([
                $http.get("/timeperiods/viewDetails/" + $scope.id + ".json", {
                    params: {
                        'angular': true
                    }
                }),
                $http.get("/profile/edit.json", {
                    params: {
                        'angular': true
                    }
                })
            ]).then(function(results){
                $scope.timeperiod = results[0].data.timeperiod;
                $scope.user = results[1].data.user;
                $scope.i18n = $scope.user.i18n.split('_');
                if($scope.i18n.length > 0){
                    $scope.languageCode = $scope.i18n[0];
                }
                $scope.init = false;

                var calendarEl = document.getElementById('calendar');
                $scope.calendar = new FullCalendar.Calendar(calendarEl, {
                    plugins: ['timeGrid'],
                    defaultView: 'timeGridWeek',
                    locale: $scope.languageCode,
                    theme: false,
                    header: false,
                    axisFormat: 'HH:mm',
                    selectOverlap: true,
                    allDaySlot: false,
                    contentHeight: 'auto',
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
                    displayEventTime: false,
                    events: $scope.timeperiod.events
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
