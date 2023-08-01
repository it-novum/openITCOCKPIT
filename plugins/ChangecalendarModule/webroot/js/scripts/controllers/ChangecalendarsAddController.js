/**
 * @link https://fullcalendar.io/docs/upgrading-from-v3
 */
angular.module('openITCOCKPIT')
    .controller('ChangecalendarsAddController', function($scope, $http, $state, $stateParams, $q, $compile, NotyService, RedirectService){

        $scope.data = {
            createAnother: false
        };

        $scope.defaultDate = new Date();

        var clearForm = function(){
            $scope.post = {
                Calendar: {
                    container_id: 0,
                    name: '',
                    description: ''
                }
            };
        };
        clearForm();

        $scope.calendar = null;
        /**
         *  {
                daysOfWeek: [6], //Saturdays
                rendering: "background",
                color: "#bbdefb ",
                overLap: false,
                allDay: true
            },
         {
                daysOfWeek: [0], //Sundays
                rendering: "background",
                color: "#90caf9",
                overLap: false,
                allDay: true
            },
         * @type {*[]}
         */
        $scope.events = [];

        $scope.init = true;

        var renderCalendar = function(){
            var calendarEl = document.getElementById('calendar');

            $scope.calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
                customButtons: {
                    deletemonthevents: {
                        text: $scope.message.deleteMonthEvents,
                        click: function(){
                            var index = $scope.events.length;
                            while(index--){
                                var start = new Date($scope.events[index].start);
                                if(start >= $scope.calendar.state.dateProfile.currentRange.start &&
                                    start < $scope.calendar.state.dateProfile.currentRange.end){
                                    $scope.events.splice(index, 1);
                                }
                            }
                            $scope.$apply();
                        }
                    },
                    deleteallevents: {
                        text: $scope.message.deleteAllEvents,
                        click: function(){
                            $scope.events = [];
                            $scope.$apply();
                        }
                    }
                },
                header: {
                    left: '',
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
                                class: 'btn btn-success btn-xs btn-icon'
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
                            class: 'btn btn-primary btn-xs btn-icon margin-right-5'
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
                            class: 'btn btn-danger btn-xs btn-icon'
                        })
                        .click(function(){
                                $scope.deleteEvent(date('Y-m-d', info.event.start));
                                $scope.$apply();
                            }
                        );

                    $(elements[1]).append($editButton);
                    $(elements[1]).css('text-align', 'right').append($deleteButton);
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

            $scope.calendar.render();

        };


        $scope.load = function(){
            $q.all([
                $http.get("/containers/loadContainersForAngular.json", {
                    params: {
                        'angular': true
                    }
                }),
            ]).then(function(results){
                $scope.containers = results[0].data.containers;
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
            $http.post("/changecalendar_module/changecalendars/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('CalendarsEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.message.objectName
                        + '</a></u> ' + $scope.message.message
                });

                if($scope.data.createAnother === false){
                    //RedirectService.redirectWithFallback('ChangecalendarsIndex');
                }else{
                    //clearForm();
                    //$scope.errors = {};
                    //NotyService.scrollTop();
                }
            }, function errorCallback(result){

                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });

        };

        //Fire on page load
        $scope.load();

        $scope.$watch('events', function(){
            if($scope.init){
                return;
            }
            if($scope.calendar !== null){
                $scope.calendar.destroy();
            }
            renderCalendar();
        }, true);

    });
