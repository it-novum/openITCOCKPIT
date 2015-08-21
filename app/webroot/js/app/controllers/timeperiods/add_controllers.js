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

App.Controllers.TimeperiodsAddController = Frontend.AppController.extend({
	$table: null,
	timeperiodRow: 1,
	cloneCount: 1,
	/**
	 * @constructor
	 * @return {void}
	 */

	_initialize: function() {
		var self = this;
		$('.addTimeRangeDivButton').click(function(){
			self.addTimeRangeFields();
			//$this.parent().parent().trigger("liszt:updated");
		});
		this.bindEvents();
	},
	addTimeRangeFields: function(){
		var regex = /^(.*)(\d)+$/i;
		var index = $('.weekdays').length;
		$('#timerange_template')
			.clone(true, true)
			.removeClass('invisible template')
			.attr('id', 'id'+ index)
			.attr('clone-number', index)
			.insertBefore('#addTimerangeButton');
		$('#id'+index).find('input:text, select').each(function() {
			if(typeof $(this).attr('name') !== 'undefined'){
				$(this).attr('name', $(this).attr('name').replace(/\[template]*[[\d]*\]/,'[Timerange]['+index+']'));
				if(typeof $(this).prop("type") !== 'undefined'){
					if($(this).prop("type")== 'select-one'){
						$(this).chosen();
					}
				}
			}
		});
	},

	bindEvents: function(){
		var self = this;
		$('.removeTimeRangeDivButton').click(function(){
			var $this = $(this);
			$this.parent().parent().remove();
		});
	},
});
