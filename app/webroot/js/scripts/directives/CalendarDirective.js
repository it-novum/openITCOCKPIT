angular.module('openITCOCKPIT').directive('calendar', function($http){
    return {
        restrict: 'E',
        templateUrl: '/angular/calendar.html',
        scope: {
            'downtimes': '=?',
            'fromDate': '=?',
            'toDate': '=?'
        },
        controller: function($scope){
            $scope.events = [];
            for(var hostDowntimeKey in $scope.downtimes.Hosts){
                $scope.events.push({
                    title: $scope.downtimes.Hosts[hostDowntimeKey].Hosts.name,
                    start: $scope.downtimes.Hosts[hostDowntimeKey].scheduled_start_time,
                    end: $scope.downtimes.Hosts[hostDowntimeKey].scheduled_end_time,
                    extendedProps: {
                        description: $scope.downtimes.Hosts[hostDowntimeKey].comment_data,
                        author: $scope.downtimes.Hosts[hostDowntimeKey].author_name,
                        type: 'host'
                    }
                });
            }
            for(var hostDowntimeKey in $scope.downtimes.Services){
                var serviceName = ($scope.downtimes.Services[hostDowntimeKey].Services.name === null) ?
                    $scope.downtimes.Services[hostDowntimeKey].Servicetemplates.name : $scope.downtimes.Services[hostDowntimeKey].Services.name;
                $scope.events.push({
                    title: $scope.downtimes.Services[hostDowntimeKey].Hosts.name + '|' + serviceName,
                    start: $scope.downtimes.Services[hostDowntimeKey].scheduled_start_time,
                    end: $scope.downtimes.Services[hostDowntimeKey].scheduled_end_time,
                    extendedProps: {
                        description: $scope.downtimes.Services[hostDowntimeKey].comment_data,
                        author: $scope.downtimes.Services[hostDowntimeKey].author_name,
                        type: 'service'
                    }
                });
            }
            var calendarEl = document.getElementById('calendar');

            $scope.calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
                defaultView: 'dayGridMonth',
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
                displayEventEnd: {
                    month: true,
                    basicWeek: true,
                    "default": true
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
                views: {
                    dayGrid: {
                        displayEventTime: false
                    },
                    timeGrid: {
                        displayEventTime: false
                    },
                    week: {
                        dayGridWeek: {
                            displayEventTime: false
                        },
                        timeGridWeek: {
                            displayEventTime: true
                        }
                    },
                    day: {
                        displayEventTime: false
                    }
                },
                allDaySlot: false,
                eventLimit: 10, // for all non-TimeGrid views
                //  defaultDate: $scope.fromDate,
                navLinks: false, // can click day/week names to navigate views
                businessHours: true, // display business hours
                weekNumbers: true,
                weekNumbersWithinDays: false,
                weekNumberCalculation: 'ISO',
                eventOverlap: false,
                eventDurationEditable: false,
                events: $scope.events,
                eventRender: function(info){
                    var nonStandardFields = info.event.extendedProps; //description,...
                    if(info.view.view.type !== 'listWeek'){
                        if(nonStandardFields.type === 'host'){
                            $(info.el).addClass('bg-color-blueDark');
                            $(info.el).find('.fc-title').before('<i class="fa fa-desktop fa-md"></i> ');
                        }else{
                            $(info.el).addClass('bg-color-blueLight');
                            $(info.el).find('.fc-title').before('<i class="fa fa-cog fa-md"></i> ');
                        }
                        $(info.el).find('.fc-title').append("<br/><span class='ultra-light'>" + nonStandardFields.description +
                            "</span>");
                        $(info.el).popover({
                            trigger: 'hover',
                            html: true,
                            title: info.event.title,
                            placement: "auto",
                            content: info.event.description
                        });
                    }
                    if(info.event.icon != ""){
                        $(info.el).find('.fc-title').append("<i class='air air-top-right fa " + info.event.icon +
                            " '></i>");
                    }
                },
                eventAfterAllRender: function(view) {
                    $('.fc-more-cell').parent('tr').each(function(){
                        $(this).appendTo($(this).parent());
                    });
                },
                dayRender: function(date, cell){
                    if(date >= $scope.fromDate && date <= $scope.toDate){
                        cell.addClass("evaluation-period");
                    }
                },
                editable: false
            });
            $scope.calendar.render();
        },

        link: function($scope, element, attr){
        }
    };
});
