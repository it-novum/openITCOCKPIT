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
        $scope.colour = '#3788d8';
        $scope.post = {
            changeCalendar: {colour: '#3788d8'}
        }

        // get TimeZone beforee FullCalendar.C Calendar()
        /*
        $http.get("/angular/user_timezone.json", {
            params: {
                'angular': true
            }
        }
        */
        var renderCalendar = function(){
            var calendarEl = document.getElementById('changecalendar');

            $scope.calendar = new FullCalendar.Calendar(calendarEl, {
                timeZone: $scope.timeZone.user_timezone, //        timeZone : 'UTC',
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
                                let today = new Date(currentDate + "T00:00:00"),
                                    tomorrow = new Date(currentDate + "T00:00:00");
                                tomorrow.setDate(tomorrow.getDate() + 1);
                                $scope.modifyEvent = {
                                    title: '', start: today, end: tomorrow
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
                    $scope.postEvent(event, false);
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
            }), $http.get("/angular/user_timezone.json", {
                params: {
                    'angular': true
                }
            }), $http.get("/changecalendar_module/changecalendars/edit/" + $scope.id + ".json", {
                params: {
                    'angular': true
                }
            })]).then(function(results){
                $scope.containers = results[0].data.containers;
                $scope.timeZone = results[1].data.timezone;
                $scope.init = false;
                $scope.post = {
                    changeCalendar: results[2].data.changeCalendar, colour: results[2].data.changeCalendar.colour
                };
                $scope.events = results[2].data.changeCalendar.changecalendar_events;
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

        $scope.stripZone = function(date){

            let ZeroForMonth = (date.getMonth() + 1) < 10 ? '0' : '', ZeroForDay = date.getDate() < 10 ? '0' : '',
                ZeroForHour = date.getHours() < 10 ? '0' : '', ZeroForMinute = date.getMinutes() < 10 ? '0' : '',
                ZeroForSecond = date.getSeconds() < 10 ? '0' : '', Year = 1900 + date.getYear(),
                Month = ZeroForMonth + (date.getMonth() + 1), Day = ZeroForDay + date.getDate(),
                Hour = ZeroForHour + date.getHours(), Minute = ZeroForMinute + date.getMinutes(),
                Second = ZeroForSecond + date.getSeconds(), Zone = $scope.timeZone.user_offset / 60 / 60,
                ZeroForZone = Zone < 10 ? '0' : '', TimeZone = "+" + ZeroForZone + Zone,
                dS = Year + "-" + Month + "-" + Day + "T" + Hour + ":" + Minute + ":" + Second + TimeZone;

            return dS;
        }

        // Store the Event
        $scope.postEvent = function(event, strip = false){
            postEvent = {
                start: strip ? $scope.stripZone(event.start) : event.start,
                end: strip ? $scope.stripZone(event.end) : event.end,
                title: event.title,
                description: event.description,
                context: event.context,
                position: 0,
                id: event.id || null
            }
            $http.post("/changecalendar_module/changecalendars/events/" + $scope.id + ".json?angular=true", {event: postEvent}).then(function(result){
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
            $scope.postEvent($scope.modifyEvent, true);
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

            // Fullcalendar ignores the setting for it's original time zone it was using.
            // FC still uses the correct time, BUT claims it is in UTC zone.
            let newStart = luxon.DateTime.fromMillis(event.start.getTime(), {zone: 'UTC'});
            let newEnd = luxon.DateTime.fromMillis(event.end.getTime(), {zone: 'UTC'});
            // So we simply add the timeZone WITHOUT re-calculating the actual time.
            // We change the ZONE. NOT the TIME.
            newStart = newStart.setZone($scope.timeZone.user_timezone, {keepLocalTime: true});
            newEnd = newEnd.setZone($scope.timeZone.user_timezone, {keepLocalTime: true});

            let myStart = new Date(newStart.get('year'), newStart.get('month') - 1, newStart.get('day'))
            myStart.setHours(newStart.get('hour'), newStart.get('minute'), newStart.get('second'))


            let myEnd = new Date(newEnd.get('year'), newEnd.get('month') - 1, newEnd.get('day'))
            myEnd.setHours(newEnd.get('hour'), newEnd.get('minute'), newEnd.get('second'))

            $scope.modifyEvent = {
                id: event.id,
                title: event.title,
                start: myStart,
                end: myEnd,
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
