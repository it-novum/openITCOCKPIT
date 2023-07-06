/**
 * @link https://fullcalendar.io/docs/upgrading-from-v3
 */
angular.module('openITCOCKPIT')
    .controller('ChangecalendarsEditController', function($scope, $http, $state, $stateParams, $q, $compile, NotyService, RedirectService){

        $scope.defaultDate = new Date();
        $scope.countryCode = 'de';
        $scope.countries = [];

        $scope.id = $stateParams.id;


        $scope.calendar = null;
        $scope.init = true;

        var renderCalendar = function(){
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
                editable: true,

                datesRender: function(info){
                    //Update default date to avoid "jumping" calendar on add/delete of events
                    $scope.defaultDate = info.view.currentStart;

                    $(".fc-day-number").each(function(index, obj){
                        //obj = fc-day-number <span>
                        var $span = $(obj);
                        var $parentTd = $span.parent();
                        var currentDate = $parentTd.data('date');

                        var $addButton = $('<button>')
                            .html('<i class="fa fa-plus"></i>')
                            .attr({
                                title: 'add',
                                type: 'button',
                                class: 'btn btn-xs btn-success calendar-button'
                            })
                            .click(function(){
                                    $('#addEventModal').modal('show');
                                    $scope.newEvent = {
                                        title: '',
                                        start: new Date(currentDate + "T00:00:00"),
                                        end: new Date(currentDate + "T00:00:00")
                                    };
                                }
                            );

                        var events = $scope.getEvents(currentDate);
                        if(!$scope.hasEvents(currentDate)){
                            $parentTd.css('text-align', 'right').append($addButton);
                        }
                    });
                },
                eventDrop: function(info){
                    //Move event in json
                    var event = $scope.deleteEvent(date('Y-m-d', info.oldEvent.start));
                    if(!event){
                        return;
                    }
                    event = event[0];

                    //Set new start date
                    event.start = date('Y-m-d', info.event.start);

                    //Add event back to json
                    $scope.addEvent(event.title, event.start);

                    $scope.$apply();
                },

                events: $scope.events
            });

            //console.warn($calendar);

            $scope.calendar.render();
            $(".fc-holidays-button")
                .wrap("<span class='dropdown'></span>")
                .addClass('btn btn-secondary dropdown-toggle')
                .attr({
                    'data-toggle': 'dropdown',
                    'type': 'button',
                    'aria-expanded': false,
                    'aria-haspopup': true
                })
                .append($('<span/>', {'class': 'flag-icon flag-icon-' + $scope.countryCode}))
                .append('<span class="caret caret-with-margin-left-5"></span>');
            $('.fc-holidays-button')
                .parent().append(
                $('<ul/>', {
                        'id': 'countryList',
                        'class': 'dropdown-menu',
                        'role': 'button'
                    }
                )
            );

            for(var key in $scope.countries){
                $('#countryList').append($compile(
                        $('<li/>', {
                            'ng-click': 'setSelected("' + key + '")'
                        }).append(
                            $('<a/>', {
                                // 'class': 'dropdown-item'
                            }).append(
                                $('<span/>', {
                                    'class': 'flag-icon flag-icon-' + key
                                })
                            ).append(
                                $('<span/>', {
                                    'class': 'padding-left-5',
                                    'text': $scope.countries[key]
                                })
                            )
                        )
                    )($scope)
                );
            }
        };

        $scope.setSelected = function(countryCode){
            $scope.countryCode = countryCode;
        };

        $scope.loadHolidays = function(){
            $http.get('/calendars/loadHolidays/' + $scope.countryCode + '.json', {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.init = false;
                var customEvents = [];
                $scope.events = result.data.holidays;
                for(var index in customEvents){
                    if($scope.hasEvents(customEvents[index].start)){
                        $scope.deleteEvent(customEvents[index].start);
                    }
                    $scope.events.push(customEvents[index]);
                }
                if($scope.init){
                    return;
                }
                if($scope.calendar !== null){
                    $scope.calendar.destroy();
                }
                renderCalendar();
            });
        };

        $scope.loadContainers = function(){
            $q.all([
                $http.get("/containers/loadContainersForAngular.json", {
                    params: {
                        'angular': true
                    }
                }),
                $http.get("/calendars/loadCountryList.json", {
                    params: {
                        'angular': true
                    }
                })
            ]).then(function(results){
                $scope.containers = results[0].data.containers;
                $scope.countries = results[1].data.countries;
                $scope.init = false;
            });
        };

        $scope.load = function(){
            $http.get("/changecalendar_module/changecalendars/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post = {
                    changeCalendar: result.data.changeCalendar
                };
                $scope.events = result.data.events;
                $scope.init = false;
            });
        };

        $scope.getEvents = function(date){
            for(var index in $scope.events){
                if($scope.events[index].start === date){
                    return $scope.events[index]
                }
            }
            return null;
        };

        $scope.hasEvents = function(date){
            for(var index in $scope.events){
                if($scope.events[index].start === date){
                    return true;
                }
            }
            return false;
        };

        $scope.deleteEvent = function(date){
            for(var index in $scope.events){
                if($scope.events[index].start === date){
                    return $scope.events.splice(index, 1);
                }
            }
            return false;
        };

        $scope.addEvent = function(title, start, end){
            $scope.events.push({
                title: title,
                start: start,
                end: end,
                className: 'bg-color-pinkDark'
            });
        };

        $scope.addEventFromModal = function(){
            if($scope.newEvent.title === ''){
                return;
            }

            //Add event to internal json
            $scope.addEvent($scope.newEvent.title, $scope.newEvent.start, $scope.newEvent.end);

            //Reset modal and newEvent object
            $('#addEventModal').modal('hide');
            $scope.newEvent = {
                title: '',
                start: '',
                end: ''
            };
        };

        $scope.editEventFromModal = function(){
            if($scope.editEvent.title === ''){
                return;
            }

            //Get old event from json
            var event = $scope.deleteEvent($scope.editEvent.start);
            if(!event){
                return;
            }

            event = event[0];

            //Add event back to json with new name and old date
            $scope.addEvent($scope.editEvent.title, event.start);

            //Reset modal and newEvent object
            $('#editEventModal').modal('hide');
            $scope.editEvent = {
                title: '',
                start: '',
                end: ''
            };
        };

        $scope.submit = function(){
            $scope.post.events = $scope.events;
            $http.post("/changecalendar_module/changecalendars/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('ChangecalendarsEdit', {id: result.data.changeCalendar.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('ChangecalendarsIndex');

            }, function errorCallback(result){

                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };

        //Fire on page load
        $scope.load();
        $scope.loadContainers();

        $scope.$watch('events', function(){
            if($scope.init){
                return;
            }

            if($scope.calendar !== null){
                $scope.calendar.destroy();
            }
            renderCalendar();
        }, true);

        $scope.$watch('countryCode', function(){
            if($scope.init){
                return;
            }
            $scope.loadHolidays();
        }, true);
    });
