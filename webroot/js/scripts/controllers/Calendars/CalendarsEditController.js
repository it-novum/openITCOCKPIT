/**
 * @link https://fullcalendar.io/docs/upgrading-from-v3
 */
angular.module('openITCOCKPIT')
    .controller('CalendarsEditController', function($scope, $http, $state, $stateParams, $q, $compile, NotyService, RedirectService){

        $scope.defaultDate = new Date();
        $scope.countryCode = 'de';
        $scope.countries = [];

        $scope.id = $stateParams.id;


        $scope.calendar = null;
        $scope.init = true;

        var renderCalendar = function(){
            var calendarEl = document.getElementById('calendar');

            $scope.calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
                customButtons: {
                    holidays: {
                        text: $scope.successMessage.addHoliday,
                        click: function(event){
                            event.stopPropagation();
                        }
                    },
                    deleteallholidays: {
                        text: $scope.successMessage.deleteAllHolidays,
                        click: function(){
                            var index = $scope.events.length;
                            while (index--) {
                                if($scope.events[index].default_holiday === true){
                                    $scope.events.splice(index, 1);
                                }
                            }
                            $scope.$apply();
                        }
                    },
                    deletemonthevents: {
                        text: $scope.successMessage.deleteMonthEvents,
                        click: function(){
                            var index = $scope.events.length;
                            while (index--) {
                                var start = new Date($scope.events[index].start);
                                if (start >= $scope.calendar.state.dateProfile.currentRange.start &&
                                    start < $scope.calendar.state.dateProfile.currentRange.end) {
                                    $scope.events.splice(index, 1);
                                }
                            }
                            $scope.$apply();
                        }
                    },
                    deleteallevents: {
                        text: $scope.successMessage.deleteAllEvents,
                        click: function(){
                            $scope.events = [];
                            $scope.$apply();
                        }
                    }
                },
                header: {
                    left: 'holidays deleteallholidays deletemonthevents deleteallevents',
                    center: 'title',
                    right: 'prev,next today',
                },
                defaultDate: $scope.defaultDate,
                navLinks: false, // can click day/week names to navigate views
                businessHours: true, // display business hours
                editable: true,
                weekNumbers: true,
                weekNumbersWithinDays: false,
                weekNumberCalculation: 'ISO',
                eventOverlap: false,
                eventDurationEditable: false,

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
                                        start: currentDate
                                    };
                                }
                            );

                        var events = $scope.getEvents(currentDate);
                        if(!$scope.hasEvents(currentDate)){
                            $parentTd.css('text-align', 'right').append($addButton);
                        }
                    });
                },
                eventPositioned: function(info){
                    var elements = $('[data-date="' + date('Y-m-d', info.event.start) + '"]');

                    var $editButton = $('<button>')
                        .html('<i class="fas fa-pencil-alt"></i>')
                        .attr({
                            title: 'edit',
                            type: 'button',
                            class: 'btn btn-xs btn-primary btn-edit calendar-button margin-right-5'
                        })
                        .click(function(){
                                var event = $scope.getEvents(date('Y-m-d', info.event.start));
                                $scope.editEvent = {
                                    start: event.start,
                                    title: event.title
                                };
                                $scope.$apply();
                                $('#editEventModal').modal('show');
                            }
                        );

                    var $deleteButton = $('<button>')
                        .html('<i class="fa fa-trash"></i>')
                        .attr({
                            title: 'delete',
                            type: 'button',
                            class: 'btn btn-xs btn-danger calendar-button'
                        })
                        .click(function(){
                                $scope.deleteEvent(date('Y-m-d', info.event.start));
                                $scope.$apply();
                            }
                        );

                    $(elements[1]).css('text-align', 'right').append($deleteButton);
                    $(elements[1]).append($editButton);
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
                for(var index in $scope.events){
                    if($scope.events[index].default_holiday === false){
                        customEvents.push($scope.events[index]);
                    }
                }
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
            $http.get("/calendars/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post = {
                    Calendar: result.data.calendar
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

        $scope.addEvent = function(title, date){
            $scope.events.push({
                title: title,
                start: date,
                default_holiday: false,
                className: 'bg-color-pinkDark'
            });
        };

        $scope.addEventFromModal = function(){
            if($scope.newEvent.title === ''){
                return;
            }

            //Add event to internal json
            $scope.addEvent($scope.newEvent.title, $scope.newEvent.start);

            //Reset modal and newEvent object
            $('#addEventModal').modal('hide');
            $scope.newEvent = {
                title: '',
                start: ''
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
                start: ''
            };
        };

        $scope.submit = function(){
            $scope.post.events = $scope.events;
            $http.post("/calendars/edit/" + $scope.id + ".json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('CalendarsEdit', {id: result.data.calendar.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('CalendarsIndex');

                console.log('Data saved successfully');
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
