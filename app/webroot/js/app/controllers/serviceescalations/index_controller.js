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

App.Controllers.ServiceescalationsIndexController = Frontend.AppController.extend({
	$table: null,

	_initialize: function() {
		var self = this;
		$('.select_datatable').click(function(){
			self.fnShowHide($(this).attr('my-column'), $(this).children());
		});

		var highestTime = 0, highestValue, pageUrl, dataTableValue, dataTableValueParsed;
		for ( var i = 0, len = localStorage.length; i < len; ++i ) {
			pageUrl = localStorage.key(i);
			dataTableValue = localStorage.getItem(pageUrl);
			if(typeof dataTableValue == 'undefined' || dataTableValue == 'undefined') continue;
			dataTableValueParsed = JSON.parse(dataTableValue);
			if(pageUrl.indexOf('DataTables_serviceescalation_list_/serviceescalations') !== -1){
				if(dataTableValueParsed.time > highestTime){
					highestTime = dataTableValueParsed.time;
					highestValue = dataTableValue;
				}
			}
		}

		self.setDataTableFilter(highestValue);

		$('#serviceescalation_list').dataTable({
			"bPaginate": false,
			"bFilter": false,
			"bInfo": false,
			"bStateSave": true,
			"aoColumnDefs" : [ {
				"bSortable" : false,
				"aTargets" : [ "no-sort" ]
			} ]
		});

		this.$table = $('#serviceescalation_list');

		//Checkboxen aktivieren
		$('.select_datatable').find('input').each(function () {
			$(this).prop('checked', false);
			var myCol = ($(this).parent().attr('my-column'));
			var isVisible = self.$table.dataTable().fnSettings().aoColumns[myCol].bVisible;
			if (isVisible == true) {
				$(this).prop('checked', true);
			}
		})

        /* After you click a value, it prevents the closure of drop-down */
        $('.stayOpenOnClick').click(function (event) {
            event.stopPropagation();
        });

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
	},
	setDataTableFilter: function(storageValue){
		var currentURL = window.location.href;
		var postTextURL = currentURL.substring(currentURL.indexOf('serviceescalations') + 18);
		localStorage.setItem('DataTables_serviceescalation_list_/serviceescalations'+postTextURL, storageValue);
	}
});
