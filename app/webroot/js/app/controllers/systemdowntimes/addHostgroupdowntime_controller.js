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

App.Controllers.SystemdowntimesAddHostgroupdowntimeController = Frontend.AppController.extend({
	$table: null,

	_initialize: function() {

		/*
		 * Check if the checkbox is checked and we need to display the hidden stuff
		 */

        if($('#SystemdowntimeIsRecurring').prop('checked') == true){
            $('.chosen-container').css('width', '100%');
            $('#recurringHost_settings').show();
            $('#SystemdowntimeFromDate').hide();
            $('#SystemdowntimeFromDate').parent().parent().removeClass('has-error');
            $('#SystemdowntimeFromDate').next().html('');
            $('#SystemdowntimeToDate').hide();
            $('#SystemdowntimeToDate').parent().parent().removeClass('has-error');
            $('#SystemdowntimeToDate').next().html('');
        }

		/*
		 * Bind click events for recurring downtimes
		 */

        $('#SystemdowntimeIsRecurring').change(function(){
            if($(this).prop('checked') == true){
                $('.chosen-container').css('width', '100%');
                $('#recurringHost_settings').show();
                $('#SystemdowntimeFromDate').parent().parent().removeClass('has-error');
                $('#SystemdowntimeFromDate').next().html('');
                $('#SystemdowntimeFromDate').hide();
                $('#SystemdowntimeToDate').hide();
                $('#SystemdowntimeToDate').parent().parent().removeClass('has-error');
                $('#SystemdowntimeToDate').next().html('');
                $('#recurringHost_settings').children('.form-group').addClass('required');
            }else{
                $('#recurringHost_settings').hide();
                $('#SystemdowntimeFromDate').show();
                $('#SystemdowntimeToDate').show();
            }
        });
		
		/*
		 * Render Datepickers
		 */
		$('#SystemdowntimeFromDate').datepicker({
			format: this.getVar('dateformat')
		});
		$('#SystemdowntimeToDate').datepicker({
			format: this.getVar('dateformat')
		});
		
	}
});

