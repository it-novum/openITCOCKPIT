'use strict';
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

App.Components.FullcalendarComponent = Frontend.Component.extend({
    setup: function($calendar, events){
        var self = this;
        self.$calendar = $calendar;
        self.events = [];
        $.each(events, function(i, elem){
            var className = (elem.default_holiday === '1') ? 'defaultHoliday' : '';
            self.events.push({
                title: elem.name,
                start: i,
                allDay: true,
                durationEditable: false,
                overlap: false,
                className: className
            });
            self.createHiddenFieldsForEvent(elem.name, i, className);
        });
        self.originalDate = null;
        self.$calendar.fullCalendar({
            header: {
                left: false,
                center: 'title',
                right: 'prev, next',
            },
            firstDay: 1,
            weekNumbers: true,
            editable: true,
            disableResizing: true,
            allDayDefault: true,
            slotEventOverlap: false,
            events: self.events,
            eventOverlap: function(stillEvent, movingEvent){
                return stillEvent.allDay && movingEvent.allDay;
            },
            viewRender: function(view, element){
                var $addButton = $('<button>')
                    .html('<i class="fa fa-plus-circle txt-color-green"></i>')
                    .attr({title: 'add', class: 'btn btn-xs btn-default calendar-button calendar-button-add'})
                    .click(function(){
                            var title = prompt('Holiday Name:');
                            if(title){
                                $calendar.fullCalendar('renderEvent', {
                                        title: title,
                                        start: $(this).closest('td').attr('data-date'),
                                        allDay: true,
                                        durationEditable: false,
                                        overlap: false
                                    },
                                    true // make the event "stick"
                                );
                            }
                            return false;
                        }
                    );
                $(".fc-day-number").css('text-align', 'left').append($addButton);
            },
            eventAfterRender: function(event, element, view){
                var $selectedElement = $('.fc-day-number[data-date="' + $.fullCalendar.moment(event.start).format() + '"]');
                var $editButton = $('<button>')
                    .html('<i class="fa fa-pencil txt-color-blue"></i>')
                    .attr({title: 'edit', class: "btn btn-xs btn-default calendar-button calendar-button-edit"})
                    .click(function(){
                        var title = prompt('Holiday Name:', event.title);
                        if(title){
                            if(title){
                                event.title = title;
                                event.className = '';
                                self.$calendar.fullCalendar('updateEvent', event);
                            }
                        }
                        return false;
                    });
                var $deleteButton = $('<button>')
                    .html('<i class="fa fa-trash txt-color-red"></i>')
                    .attr({title: 'delete', class: 'btn btn-xs btn-default calendar-button calendar-button-delete'})
                    .click(function(){
                        self.$calendar.fullCalendar('removeEvents', event._id);
                        $("input[name^='data[CalendarHoliday][" + $.fullCalendar.moment(event.start).format() + "]']").remove();
                    });
                $selectedElement.has('button.calendar-button-add').each(function(){
                    $selectedElement.find('.calendar-button-add').remove();
                });
                $selectedElement.not(':has(button.calendar-button-delete)').each(function(){
                    $selectedElement.append($deleteButton);
                });
                $selectedElement.not(':has(button.calendar-button-edit)').each(function(){
                    $selectedElement.append($editButton);
                });
                self.createHiddenFieldsForEvent(event.title, $.fullCalendar.moment(event.start).format(), event.className);
            },
            eventDestroy: function(event, element, view){
                var $selectedElement = $('.fc-day-number[data-date="' + $.fullCalendar.moment(event.start).format() + '"]');
                $selectedElement.find("button[class$='-edit'],button[class$='-delete']").remove();
                var $addButton = $('<button>')
                    .html('<i class="fa fa-plus-circle txt-color-green"></i>')
                    .attr({title: 'add', class: 'btn btn-xs btn-default calendar-button calendar-button-add'})
                    .click(function(){
                        var title = prompt('Holiday Name:');
                        if(title){
                            self.$calendar.fullCalendar('renderEvent', {
                                    title: title,
                                    start: $(this).closest('td').attr('data-date'),
                                    allDay: true,
                                    durationEditable: false,
                                    overlap: false
                                },
                                true // make the event "stick"
                            );
                        }
                        return false;
                    });
                $selectedElement.append($addButton);
            },
            eventDragStart: function(event){
                self.originalDate = $.fullCalendar.moment(event.start).format()// Make a copy (before event drop) of the event date
            },
            eventDragStop: function(event){
                //override className if event has been dragged
                event.className = '';
                self.createHiddenFieldsForEvent(event.title, $.fullCalendar.moment(event.start).format(), event.className);
                self.$calendar.fullCalendar('updateEvent', event);
            },
            eventDrop: function(event, dayDelta, minuteDelta, allDay, revertFunc){
                var $selectedElement = $('.fc-day-number[data-date="' + self.originalDate + '"]');
                $selectedElement.find("button[class$='-edit'],button[class$='-delete']").remove();
                $("input[name^='data[CalendarHoliday][" + self.originalDate + "]']").remove();
                var addButton = $('<button class="btn btn-xs btn-default calendar-button ' +
                    'calendar-button-add" title="add" type="button">' +
                    '<i class="fa fa-plus-circle txt-color-green"></i></button>').click(function(){
                    var eventTitle = prompt('Holiday Name:');
                    if(eventTitle){
                        $calendar.fullCalendar('renderEvent', {
                                title: eventTitle,
                                start: $(this).closest('td').attr('data-date'),
                                allDay: true,
                                durationEditable: false,
                                overlap: false,
                                className: ''
                            },
                            true // make the event "stick"
                        );
                    }
                });
                $selectedElement.append(addButton);
            }
        });
        $('.fc-left').append($('#calendar-buttons').removeClass('hidden'));

        $('#btn-delete-all-events').click(function(){
            $calendar.fullCalendar('removeEvents');
            $("input[name^='data[CalendarHoliday]']").remove();
            return false;
        });
        $('#btn-delete-month-events').click(function(){
            var currentViewStart = $.fullCalendar.moment($calendar.fullCalendar('getView').intervalStart).format();
            var currentViewEnd = $.fullCalendar.moment($calendar.fullCalendar('getView').intervalEnd).format()
            var events = $calendar.fullCalendar('clientEvents', function(event){
                var eventDate = $.fullCalendar.moment(event.start).format();
                if(eventDate >= currentViewStart && eventDate < currentViewEnd){
                    $calendar.fullCalendar('removeEvents', event._id);
                    $("input[name^='data[CalendarHoliday][" + eventDate + "]']").remove();
                }
            });
            return false;
        });
        $("ul.dropdown-menu").delegate("a", "click", function(){
            $calendar.fullCalendar('removeEvents', function(event){
                //delete all hidden fields for default holidays
                $("input[name^='data[CalendarHoliday][" + $.fullCalendar.moment(event.start).format() + "]']").remove();
                return event.className == "defaultHoliday";
            });
            if($(this).attr('id') != 'removeDefaultHolidays'){
                $.ajax({
                    url: '/calendars/loadHolidays/' + $(this).attr('id') + '.json',
                    type: 'POST',
                    dataType: 'json',
                    success: function(response){
                        var currentEventDates = []; //all dates in use
                        var events = [];
                        $calendar.fullCalendar('clientEvents', function(event){
                            currentEventDates.push($.fullCalendar.moment(event.start).format());
                        });

                        $.each(response.holidays, function(eventStart, eventTitle){
                            //check if day event already exists
                            if($.inArray(eventStart, currentEventDates) === -1){
                                var event = {
                                    title: eventTitle,
                                    start: eventStart,
                                    allDay: true,
                                    durationEditable: false,
                                    overlap: false,
                                    className: 'defaultHoliday'
                                };
                                events.push(event);
                                self.createHiddenFieldsForEvent(eventTitle, eventStart, event.className);
                            }
                        });
                        $calendar.fullCalendar('addEventSource', events);
                    }
                });
            }
        });
    },
    createHiddenFieldsForEvent: function(eventTitle, eventStart, className){
        var $hiddenFieldElementTitle = $("input[name^='data[CalendarHoliday][" + eventStart + "][name]']");
        var $hiddenFieldElementDefaultHoliday = $("input[name^='data[CalendarHoliday][" + eventStart + "][default_holiday]']");
        //if hidden field exists update name and default holiday value and return
        if($hiddenFieldElementTitle.length && $hiddenFieldElementDefaultHoliday.length){
            $hiddenFieldElementTitle.val(eventTitle);
            $hiddenFieldElementDefaultHoliday.val((className == 'defaultHoliday') ? 1 : 0)
            return true;
        }
        $('[id^=Calendar]').append(
            $('<input/>', {
                type: 'hidden',
                name: 'data[CalendarHoliday][' + eventStart + '][name]',
                value: eventTitle
            }),
            $('<input/>', {
                type: 'hidden',
                name: 'data[CalendarHoliday][' + eventStart + '][default_holiday]',
                value: ((className == 'defaultHoliday') ? 1 : 0)
            })
        );
    }
});
