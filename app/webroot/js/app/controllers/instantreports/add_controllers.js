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

App.Controllers.InstantreportsAddController = Frontend.AppController.extend({

    _initialize: function() {
        var self = this;
        $(document).on('click', '.group-result', function() {
        // Get unselected items in this group
            var unselected = $(this).nextUntil('.group-result').not('.result-selected');
            if(unselected.length){
                // Select all items in this group
                unselected.trigger('mouseup');
            }else{
                $(this).nextUntil('.group-result').each(function() {
                    // Deselect all items in this group
                    $('a.search-choice-close[data-option-array-index="' + $(this).data('option-array-index') + '"]').trigger('click');
                });
            }
        });
        $("#InstantreportStartDate").datepicker({
            changeMonth: true,
            numberOfMonths: 3,
            todayHighlight:true,
            weekStart:1,
            calendarWeeks:true,
            autoclose: true,
            format: 'dd.mm.yyyy',
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>'
        });
        $("#InstantreportEndDate").datepicker({
            changeMonth: true,
            numberOfMonths: 3,
            todayHighlight:true,
            weekStart:1,
            calendarWeeks:true,
            autoclose: true,
            format: 'dd.mm.yyyy',
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>'
        });
        $('#InstantreportType').change(function(){
            self.changeInputFieldsByType();
        });
        self.changeInputFieldsByType();

        $('#InstantreportSendEmail').change(function(){
            self.changeSendMail();
        });
        self.changeSendMail();
    },

    changeInputFieldsByType: function(){
        $('.select-type').hide();
        $('.select-type-'+$('#InstantreportType').val()).show();
    },

    changeSendMail: function(){
        if($('#InstantreportSendEmail').is(':checked')){
            $('.send-interval-holder').show();
            $('#InstantreportStartDateVal').val($('#InstantreportStartDate').val());
            $('#InstantreportEndDateVal').val($('#InstantreportEndDate').val());
            $('#InstantreportStartDate').val('');
            $('#InstantreportEndDate').val('')
            $('#InstantreportStartDate').attr('disabled', 'disabled');
            $('#InstantreportEndDate').attr('disabled', 'disabled');
            $('div.start-end-date-holder > label').addClass('disabled');
        }else{
            $('.send-interval-holder').hide();
            if($('#InstantreportStartDate').val() == ''){
                $('#InstantreportStartDate').val($('#InstantreportStartDateVal').val());
            }
            if($('#InstantreportEndDate').val() == ''){
                $('#InstantreportEndDate').val($('#InstantreportEndDateVal').val());
            }
            $('#InstantreportStartDate').removeAttr('disabled');
            $('#InstantreportEndDate').removeAttr('disabled');
            $('div.start-end-date-holder > label').removeClass('disabled');
        }
    }
});
