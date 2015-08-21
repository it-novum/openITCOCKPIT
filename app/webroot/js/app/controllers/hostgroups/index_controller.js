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

App.Controllers.HostgroupsIndexController = Frontend.AppController.extend({
	$table: null,
	/**
	 * @constructor
	 * @return {void}
	 */

	components: ['Masschange'],

	_initialize: function() {
		var self = this;
		this.Masschange.setup({
			'controller': 'hostgroups',
			'checkboxattr': 'hostgroupname'
		});
		$('.select_datatable').click(function(){
			self.fnShowHide($(this).attr('my-column'), $(this).children());
		});

		$('#host_list').dataTable({
			"bPaginate": false,
			"bFilter": false,
			"bInfo": false,
			"bStateSave": true,
			"aoColumnDefs" : [ {
				"bSortable" : false,
				"aTargets" : [ "no-sort" ]
			}]
		});

		this.$table = $('#hostgroup_list');

		//Checkboxen aktivieren
		$('.select_datatable').find('input').prop('checked', true);

	},
	fnShowHide: function( iCol, inputObject){
		/* Get the DataTables object again - this is not a recreation, just a get of the object */
		var oTable = this.$table.dataTable();

		var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
		if(bVis == true){
			inputObject.prop('checked', false);
		}else{
			inputObject.prop('checked', true);
		}
		oTable.fnSetColumnVis( iCol, bVis ? false : true );
	}
});
