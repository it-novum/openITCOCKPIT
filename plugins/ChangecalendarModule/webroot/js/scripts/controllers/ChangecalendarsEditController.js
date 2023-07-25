/**
 * @link https://fullcalendar.io/docs/upgrading-from-v3
 */
angular.module('openITCOCKPIT')
    .controller('ChangecalendarsEditController', function($scope, $http, $state, $stateParams, $q, $compile, NotyService, RedirectService, BBParserService){

        $scope.defaultDate = new Date();
        $scope.countryCode = 'de';
        $scope.countries = [];

        $scope.descriptionPreview = '';
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
                                    $scope.modifyEvent = {
                                        title: '',
                                        start: new Date(currentDate + "T00:00:00"),
                                        end: new Date(currentDate + "T23:59:59")
                                    };
                                }
                            );

                        $parentTd.css('text-align', 'right').append($addButton);
                    });
                },
                eventDrop: function(info){
                    //Move event in json
                    var event = $scope.getEventById(info.oldEvent.id);
                    if(!event){
                        return;
                    }

                    // Calculate difference between dates
                    let diffTime = new Date(info.event.start) - info.oldEvent.start;
                    let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    //Set new start date
                    event.start = new Date(event.start);
                    event.end = new Date(event.end);
                    event.start.setDate(event.start.getDate() + diffDays);
                    event.end.setDate(event.end.getDate() + diffDays);

                    //Add event back to json
                    $scope.addEvent(event);

                    $scope.$apply();
                },
                eventClick: function(info){
                    $scope.editEventFromModal(info.event);
                },
                events: $scope.events
            });

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

        $scope.getEventById = function(id){
            for(var index in $scope.events) {
                let event = $scope.events[index];
                if(event.id == id){
                    return event;
                }
            }
            return false;
        };

        $scope.addEvent = function(event){
            // If the event is new
            if(typeof (event.id) === "undefined"){
                $scope.events.push(event);
                return;
            }

            // Otherwise traverse for an update
            let eventCount = $scope.events.length,
                eventIndex = 0;

            for(eventIndex = 0; eventIndex <= eventCount; eventIndex++){
                let myEvent = $scope.events[eventIndex];
                event.id = parseInt(event.id);
                if(parseInt(myEvent.id) !== event.id){
                    continue;
                }

                $scope.events[eventIndex].start = event.start;
                $scope.events[eventIndex].end = event.end;
                $scope.events[eventIndex].description = event.description;
                $scope.events[eventIndex].title = event.title;
                break;
            }
        };

        $scope.modifyEventFromModal = function(){
            if($scope.modifyEvent.title === ''){
                return;
            }
            if($scope.modifyEvent.start === ''){
                return;
            }
            if($scope.modifyEvent.end === ''){
                return;
            }

            //Add event to internal json
            $scope.addEvent($scope.modifyEvent);

            //Reset modal and newEvent object
            $('#addEventModal').modal('hide');
            $scope.modifyEvent = {
                title: '',
                start: '',
                end: '',
                description: '',
                id: '',
                context: []
            };
        };

        // Show the modal and pre-fill the form with the given event.
        $scope.editEventFromModal = function(event){
            $scope.modifyEvent = {
                id: event.id,
                title: event.title,
                start: event.start,
                end: event.end,
                description: event.extendedProps.description,
                context: event.extendedProps.context
            };


            $scope.$apply();


            //jQuery Bases WYSIWYG Editor
            $("[wysiwyg='true']").unbind('click').click(function(){
                var $textarea = $('#description');
                var task = $(this).attr('task');
                switch(task){
                    case 'bold':
                        $textarea.surroundSelectedText('[b]', '[/b]');
                        break;

                    case 'italic':
                        $textarea.surroundSelectedText('[i]', '[/i]');
                        break;

                    case 'underline':
                        $textarea.surroundSelectedText('[u]', '[/u]');
                        break;

                    case 'left':
                        $textarea.surroundSelectedText('[left]', '[/left]');
                        break;

                    case 'center':
                        $textarea.surroundSelectedText('[center]', '[/center]');
                        break;

                    case 'right':
                        $textarea.surroundSelectedText('[right]', '[/right]');
                        break;

                    case 'justify':
                        $textarea.surroundSelectedText('[justify]', '[/justify]');
                        break;
                }
            });

            // Bind click event for color selector
            $("[select-color='true']").click(function(){
                var color = $(this).attr('color');
                var $textarea = $('#motdcontent');
                $textarea.surroundSelectedText("[color='" + color + "']", '[/color]');
            });

            // Bind click event for font size selector
            $("[select-fsize='true']").click(function(){
                var fontSize = $(this).attr('fsize');
                var $textarea = $('#motdcontent');
                $textarea.surroundSelectedText("[text='" + fontSize + "']", "[/text]");
            });

            $scope.prepareHyperlinkSelection = function(){
                var $textarea = $('#motdcontent');
                var selection = $textarea.getSelection();
                if(selection.length > 0){
                    $scope.docu.hyperlinkDescription = selection.text;
                }
            };

            $scope.insertWysiwygHyperlink = function(){
                var $textarea = $('#motdcontent');
                var selection = $textarea.getSelection();
                var newTab = $('#modalLinkNewTab').is(':checked') ? " tab" : "";
                if(selection.length > 0){
                    $textarea.surroundSelectedText("[url='" + $scope.docu.hyperlink + "'" + newTab + "]", "[/url]");
                }else{
                    $textarea.insertText("[url='" + $scope.docu.hyperlink + "'" + newTab + "]" + $scope.docu.hyperlinkDescription + '[/url]', selection.start, "collapseToEnd");
                }
                $scope.docu.hyperlink = "";
                $scope.docu.hyperlinkDescription = "";
                $scope.addLink = false;
            };

            //Get old event from json
            $('#addEventModal').modal('show');
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

        $scope.$watch('events', function(){
            if($scope.init){
                return;
            }

            if($scope.calendar !== null){
                $scope.calendar.destroy();
            }
            renderCalendar();
        }, true);
        $scope.$watch('modifyEvent.description', function(){
            if($scope.init){
                return;
            }

            $scope.descriptionPreview = BBParserService.parse($scope.modifyEvent.description);
        }, true);

    });
