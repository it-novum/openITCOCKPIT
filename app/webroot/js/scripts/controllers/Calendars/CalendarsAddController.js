/**
 * @link https://fullcalendar.io/docs/upgrading-from-v3
 */
angular.module('openITCOCKPIT')
    .controller('CalendarsAddController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){

        $scope.data = {
            createAnother: false
        };

        $scope.defaultDate = new Date();
        $scope.countryCode = 'de';

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
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                defaultDate: $scope.defaultDate,
                navLinks: false, // can click day/week names to navigate views
                businessHours: true, // display business hours
                editable: true,
                weekNumbers: true,
                weekNumbersWithinDays: false,
                weekNumberCalculation: 'ISO',
                eventOverlap: false,

                datesRender: function(info){
                    //Update default date to avoid "jumping" calendar on add/delete of events
                    $scope.defaultDate = info.view.currentStart;

                    $(".fc-day-number").each(function(index, obj){
                        //obj = fc-day-number <span>
                        var $span = $(obj);
                        var $parentTd = $span.parent();
                        var currentDate = $parentTd.data('date');

                        var $addButton = $('<button>')
                            .html('<i class="fa fa-plus-circle txt-color-green"></i>')
                            .attr({
                                title: 'add',
                                type: 'button',
                                class: 'btn btn-xs btn-default calendar-button'
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
                        .html('<i class="fa fa-pencil txt-color-blue"></i>')
                        .attr({
                            title: 'edit',
                            type: 'button',
                            class: 'btn btn-xs btn-default calendar-button',
                            'ng-click': 'testBob(info.event.start)'
                        })
                        .click(function(){
                                console.log('edit knöbbe');
                            }
                        );

                    var $deleteButton = $('<button>')
                        .html('<i class="fa fa-trash-o txt-color-red"></i>')
                        .attr({
                            title: 'delete',
                            type: 'button',
                            class: 'btn btn-xs btn-default calendar-button'
                        })
                        .click(function(){
                                $scope.deleteEvent(date('Y-m-d', info.event.start));
                                $scope.$apply();
                            }
                        );

                    $(elements[1]).css('text-align', 'right').append($deleteButton);
                    $(elements[1]).append($editButton);
                },
                events: $scope.events
            });

            //console.warn($calendar);

            $scope.calendar.render();
        };

        $scope.loadHolidays = function(){
            $http.get('/calendars/loadHolidays/' + $scope.countryCode + '.json', {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.init = false;
                $scope.events = result.data.holidays;
                renderCalendar();
            });
        };

        $scope.load = function(){
            $http.get("/containers/loadContainersForAngular.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
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
                    $scope.events.splice(index, 1);
                    return true;
                }
            }
            return false;
        };

        $scope.addEvent = function(title, date){
            $scope.events.push({
                title: title,
                start: date
            });
        };

        $scope.addEventFromModal = function(){
            //Add event to internal json
            $scope.addEvent($scope.newEvent.title, $scope.newEvent.start);

            //Reset modal and newEvent object
            $('#addEventModal').modal('hide');
            $scope.newEvent = {
                title: '',
                start: ''
            };
        };

        $scope.submit = function(){
            $http.post("/calendars/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('CalendarsEdit', {id: result.data.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                if($scope.data.createAnother === false){
                    RedirectService.redirectWithFallback('CalendarsIndex');
                }else{
                    clearForm();
                    $scope.errors = {};
                    NotyService.scrollTop();
                }

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
        $scope.loadHolidays();

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
