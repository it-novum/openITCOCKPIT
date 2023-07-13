angular.module('openITCOCKPIT').directive('changecalendarWidget', function($http, $sce){
    return {
        restrict: 'E',
        templateUrl: '/changecalendar_module/changecalendars/widget.html',
        scope: {
            'widget': '='
        },

        controller: function($scope){
            $scope.currentChangeCalendar = {};
            $scope.changeCalendars = [];
            var $widget = $('#widget-' + $scope.widget.id);
            $scope.frontWidgetHeight = parseInt(($widget.height()), 10); //-50px header

            $scope.fontSize = parseInt($scope.frontWidgetHeight / 3.8, 10);

            $scope.calendarTimeout = null;

            $scope.load = function(){
                $http.get("/changecalendar_module/changecalendars/widget.json", {
                    params: {
                        'angular': true,
                        'widgetId': $scope.widget.id
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


                var calendarEl = document.getElementById('changecalendar');

                $scope.calendar = new FullCalendar.Calendar(calendarEl, {
                    plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    firstDay: 1, // monday as first day of the week
                    titleFormat: { // will produce something like "Tuesday, September 18, 2018"
                        month: '2-digit',
                        year: 'numeric',
                        day: '2-digit',
                        weekday: 'long',
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    },
                    slotLabelFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        omitZeroMinute: false,
                        hour12: false
                    },
                    eventTimeFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    },
                    displayEventEnd: true,
                    allDaySlot: true,
                    eventLimit: 10, // for all non-TimeGrid views
                    navLinks: false, // can click day/week names to navigate views
                    weekNumbers: true,
                    weekNumbersWithinDays: false,
                    weekNumberCalculation: 'ISO',
                    eventOverlap: false,
                    eventDurationEditable: false,
                    defaultDate: $scope.defaultDate,
                    businessHours: true, // display business hours
                    editable: false,
                    events: $scope.events
                });

                $scope.calendar.render();
            };

            $scope.loadChangeCalendars = function(){
                $http.get("/changecalendar_module/changecalendars/index.json?angular=true", {}
                ).then(function(result){
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
                $scope.load();
            };
            $scope.showConfig = function(){
                $scope.$broadcast('FLIP_EVENT_OUT');
                $scope.loadChangeCalendars();
            };

            $scope.saveChangecalendar = function(){
                $http.post("/changecalendar_module/changecalendars/widget.json?angular=true",
                    {
                        Widget: {
                            id: $scope.widget.id
                        },
                        changecalendar_id: $scope.currentChangeCalendar.id
                    }
                ).then(function(result){
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
