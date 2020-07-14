angular.module('openITCOCKPIT').directive('downtimecalendar', function($http){
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
            var pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
            var fromDate = new Date($scope.fromDate.replace(pattern, '$3-$2-$1'));
            var toDate = new Date($scope.toDate.replace(pattern, '$3-$2-$1'));
            $scope.events.isMultipleDay = true;
            for(var hostDowntimeKey in $scope.downtimes.Hosts){
                $scope.events.push({
                    id: $scope.downtimes.Hosts[hostDowntimeKey].id,
                    title: $scope.downtimes.Hosts[hostDowntimeKey].Hosts.name,
                    start: $scope.downtimes.Hosts[hostDowntimeKey].scheduled_start_time,
                    end: $scope.downtimes.Hosts[hostDowntimeKey].scheduled_end_time,
                    extendedProps: {
                        comment: $scope.downtimes.Hosts[hostDowntimeKey].comment_data,
                        author: $scope.downtimes.Hosts[hostDowntimeKey].author_name,
                        type: 'host'
                    }
                });
            }
            for(var hostDowntimeKey in $scope.downtimes.Services){
                var serviceName = ($scope.downtimes.Services[hostDowntimeKey].Services.name === null) ?
                    $scope.downtimes.Services[hostDowntimeKey].Servicetemplates.name : $scope.downtimes.Services[hostDowntimeKey].Services.name;
                $scope.events.push({
                    id: $scope.downtimes.Services[hostDowntimeKey].id,
                    title: $scope.downtimes.Services[hostDowntimeKey].Hosts.name + '|' + serviceName,
                    start: $scope.downtimes.Services[hostDowntimeKey].scheduled_start_time,
                    end: $scope.downtimes.Services[hostDowntimeKey].scheduled_end_time,
                    extendedProps: {
                        comment: $scope.downtimes.Services[hostDowntimeKey].comment_data,
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
                businessHours: true, // display business hours
                weekNumbers: true,
                weekNumbersWithinDays: false,
                weekNumberCalculation: 'ISO',
                eventOverlap: false,
                eventDurationEditable: false,
                events: $scope.events,
                eventRender: function(info){
                    var nonStandardFields = info.event.extendedProps; //description,...
                    if(info.view.type !== 'listWeek'){
                        if(nonStandardFields.type === 'host'){
                            $(info.el).addClass('bg-color-blueDark');
                            $(info.el).find('.fc-title').before('<i class="fa fa-desktop fa-md"></i> ');
                        }else{
                            $(info.el).addClass('bg-color-blueLight');
                            $(info.el).find('.fc-title').before('<i class="fa fa-cog fa-md"></i> ');
                        }
                        $(info.el).popover({
                            html: true,
                            title: '<div class="ellipsis">' + info.event.title + '</div>',
                            placement: 'top',
                            trigger: 'hover',
                            container: 'body',
                            content: '<div class="font-xs"><i class="fa fa-hourglass-start fa-xs"></i> ' + date('d.m.Y H:i:s', info.event.start.getTime() / 1000) +
                                '<br /><i class="fa fa-hourglass-end fa-xs"></i> ' + date('d.m.Y H:i:s', info.event.end.getTime() / 1000) +
                                '<br /><i class="fa fa-user fa-xs"></i> ' + info.event.extendedProps.author +
                                '<br /><i class="fa fa-comment fa-xs"></i> ' + info.event.extendedProps.comment +
                                '</div>'
                        });
                    }
                },
                dayRender: function(dayRenderInfo){
                    if(dayRenderInfo.date >= fromDate && dayRenderInfo.date <= toDate){
                        $(dayRenderInfo.el).addClass("evaluation-period");
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
