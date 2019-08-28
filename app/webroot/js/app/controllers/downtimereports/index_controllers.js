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

App.Controllers.DowntimereportsIndexController = Frontend.AppController.extend({
    $hostDowntimes: null,
    $serviceDowntimes: null,

    components: ['Ajaxloader'],

    _initialize: function(){
        var self = this;
        $(document).on('click', '.group-result', function(){
            // Get unselected items in this group
            var unselected = $(this).nextUntil('.group-result').not('.result-selected');
            if(unselected.length){
                // Select all items in this group
                unselected.trigger('mouseup');
            }else{
                $(this).nextUntil('.group-result').each(function(){
                    // Deselect all items in this group
                    $('a.search-choice-close[data-option-array-index="' + $(this).data('option-array-index') + '"]').trigger('click');
                });
            }
        });
        $("#DowntimereportStartDate").datepicker({
            changeMonth: true,
            numberOfMonths: 3,
            todayHighlight: true,
            weekStart: 1,
            calendarWeeks: true,
            autoclose: true,
            format: 'dd.mm.yyyy',
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
        });
        $("#DowntimereportEndDate").datepicker({
            changeMonth: true,
            numberOfMonths: 3,
            todayHighlight: true,
            weekStart: 1,
            calendarWeeks: true,
            autoclose: true,
            format: 'dd.mm.yyyy',
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
        });
        if(this.getVar('downtimeReportDetails')){
            var downtimeReportDetails = this.getVar('downtimeReportDetails');
            var evaluationStart = new Date(downtimeReportDetails.startDate);
            var evaluationEnd = new Date(downtimeReportDetails.endDate);
        }
        var hostDowntimes = [];

        if(this.getVar('hostDowntimes')){
            $.each(this.getVar('hostDowntimes'), function(i, elem){
                hostDowntimes.push({
                    title: elem.host,
                    start: elem.scheduled_start_time,
                    end: elem.scheduled_end_time,
                    description: '<i class="fa fa-user"></i> ' + elem.author_name + ' <br /><i class="fa fa-comment"></i> ' + elem.comment_data,
                    className: ["event", "bg-color-blue"],
                    icon: 'fa-desktop',
                    allDay: false
                });
            });
        }
        var serviceDowntimes = [];
        if(this.getVar('serviceDowntimes')){
            $.each(this.getVar('serviceDowntimes'), function(i, elem){
                serviceDowntimes.push({
                    title: elem.host + ' | ' + elem.service,
                    start: elem.scheduled_start_time,
                    end: elem.scheduled_end_time,
                    description: '<i class="fa fa-user"></i> ' + elem.author_name + ' <br /><i class="fa fa-comment"></i> ' + elem.comment_data,
                    className: ["event", "bg-color-blueLight"],
                    icon: 'fa-gear',
                    allDay: false
                });
            });
        }
        var events = $.merge($.merge([], hostDowntimes), serviceDowntimes);

        /* initialize the calendar*/
        $('#calendar').fullCalendar({
            firstDay: 1, // monday as first day of the week
            titleFormat: {
                day: 'DD.MM.YYYY',
                week: 'DD.MM.YYYY',
            },
            displayEventEnd: {
                month: true,
                basicWeek: true,
                "default": true
            },
            timeFormat: 'DD.MM.YY h:mm',
            header: {
                left: 'title',
                center: 'month,agendaWeek,agendaDay',
                right: 'prev,today,next',
            },
            events: events,
            eventRender: function(event, element, icon){
                if(event.description != ""){
                    var fcEventTitle = element.find('.fc-event-title').append("<br/><span class='ultra-light'>" + event.description +
                        "</span>");
                    element.popover({
                        trigger: 'hover',
                        html: true,
                        title: event.title,
                        placement: "auto left",
                        content: event.description
                    });
                }
                if(event.icon != ""){
                    element.find('.fc-event-title').append("<i class='air air-top-right fa " + event.icon +
                        " '></i>");
                }
            },
            dayRender: function(date, cell){
                if(date >= evaluationStart && date <= evaluationEnd){
                    cell.addClass("evaluation-period");
                }
            },
            editable: false,
            droppable: false, // this allows things to be dropped onto the calendar !!!
            axisFormat: 'H:mm',
        });
    },
});
