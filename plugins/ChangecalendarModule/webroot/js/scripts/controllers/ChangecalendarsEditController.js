/**
 * @link https://fullcalendar.io/docs/upgrading-from-v3
 */
angular.module('openITCOCKPIT')
    .controller('ChangecalendarsEditController', function($scope, $http, $state, $stateParams, $q, $compile, NotyService, RedirectService, BBParserService){
        $scope.defaultDate = new Date();
        $scope.descriptionPreview = '';
        $scope.id = $stateParams.id;
        $scope.calendar = null;
        $scope.init = true;
        $scope.removedEvents = [];

        var renderCalendar = function(){
            var calendarEl = document.getElementById('changecalendar');

            $scope.calendar = new FullCalendar.Calendar(calendarEl, {
                plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
                header: {
                    left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
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
                    hour: '2-digit', minute: '2-digit', omitZeroMinute: false, hour12: false
                },
                eventTimeFormat: {
                    hour: '2-digit', minute: '2-digit', hour12: false
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
                                title: 'add', type: 'button', class: 'btn btn-xs btn-success calendar-button'
                            })
                            .click(function(){
                                $('#addEventModal').modal('show');
                                $scope.modifyEvent = {
                                    title: '',
                                    start: new Date(currentDate + "T00:00:00"),
                                    end: new Date(currentDate + "T23:59:59")
                                };
                            });

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
                    $scope.postEvent(event);
                },
                eventClick: function(info){
                    $scope.editEventFromModal(info.event);
                },
                events: $scope.events
            });

            $scope.calendar.render();

        };

        $scope.load = function(){
            $q.all([$http.get("/containers/loadContainersForAngular.json", {
                params: {
                    'angular': true
                }
            }),]).then(function(results){
                $scope.containers = results[0].data.containers;
                $scope.init = false;
            });

            $http.get("/changecalendar_module/changecalendars/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.post = {
                    changeCalendar: result.data.changeCalendar, colour: result.data.changeCalendar.colour
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

        $scope.getEventById = function(id){
            for(var index in $scope.events){
                let event = $scope.events[index];
                if(event.id == id){
                    return event;
                }
            }
            return false;
        };

        // Delete the event.
        $scope.deleteEventFromModal = function(){
            $http.post("/changecalendar_module/changecalendars/deleteEvent/" + $scope.id + ".json?angular=true", {event: $scope.modifyEvent}).then(function(result){
                $scope.events = result.data.changeCalendar.changecalendar_events;
                $scope.reset();
            });
        }

        // Store the Event
        $scope.postEvent = function(event){
            $http.post("/changecalendar_module/changecalendars/events/" + $scope.id + ".json?angular=true", {event: event}).then(function(result){
                $scope.events = result.data.changeCalendar.changecalendar_events;
                $scope.reset();
            });
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

            //Save
            $scope.postEvent($scope.modifyEvent);
        };

        //Hide modal and reset modal form.
        $scope.reset = function(){
            //Reset modal and newEvent object
            $('#addEventModal').modal('hide');
            //Reset edit form.
            $scope.modifyEvent = {
                title: '', start: '', end: '', description: '', id: '', context: [], position: null
            };
        };

        $scope.eventEquals = function(a, b){
            if(typeof (a) !== "object"){
                return false;
            }
            if(typeof (b) !== "object"){
                return false;
            }
            // ID is equal
            if(typeof (a.id) !== "undefined" && typeof (b.id) !== "undefined" && parseInt(a.id) === parseInt(b.id)){
                return true;
            }

            // Data is equal
            if(a.title === b.title && a.start === b.start && a.end === b.end){
                return true;
            }
            return false;
        }

        $scope.getPositionOfEvent = function(event){
            let eventCount = $scope.events.length, eventPosition;
            for(eventPosition = 0; eventPosition <= eventCount; eventPosition++){
                let myEvent = $scope.events[eventPosition];
                if($scope.eventEquals(event, myEvent)){
                    return eventPosition;
                }
            }
            return null;
        }

        // Show the modal and pre-fill the form with the given event.
        $scope.editEventFromModal = function(event){
            $scope.modifyEvent = {
                id: event.id,
                title: event.title,
                start: event.start,
                end: event.end,
                description: event.extendedProps.description,
                context: event.extendedProps.context,
                position: $scope.getPositionOfEvent(event)
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
            $scope.post.removedEvents = $scope.removedEvents;
            $http.post("/changecalendar_module/changecalendars/edit/" + $scope.id + ".json?angular=true", $scope.post).then(function(result){
                var url = $state.href('ChangecalendarsEdit', {id: result.data.changeCalendar.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> ' + $scope.successMessage.objectName + '</a></u> ' + $scope.successMessage.message
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

            $scope.descriptionPreview = BBParserService.parse($scope.modifyEvent.description || '');
        }, true);

    });
