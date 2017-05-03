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

App.Controllers.DowntimesHostController = Frontend.AppController.extend({
	$table: null,

	components: ['WebsocketSudo', 'Externalcommand', 'Ajaxloader'],

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
			if(pageUrl.indexOf('DataTables_hostdowntimes_list_/downtimes/host') !== -1){
				if(dataTableValueParsed.time > highestTime){
					highestTime = dataTableValueParsed.time;
					highestValue = dataTableValue;
				}
			}
		}

		self.setDataTableFilter(highestValue);

		$('#hostdowntimes_list').dataTable({
			"bPaginate": false,
			"bFilter": false,
			"bInfo": false,
			"bStateSave": true,
			"aoColumnDefs" : [ {
				"bSortable" : false,
				"aTargets" : [ "no-sort" ]
			}],
			"fnInitComplete" : function(dtObject){
				var vCols = [];
				var $checkboxObjects = $('.select_datatable');
				
				//Enable all checkboxes
				$('.select_datatable').find('input').prop('checked', true);
				
				$.each(dtObject.aoColumns, function(count){
					if(dtObject.aoColumns[count].bVisible == false){
						//Uncheck checkboxes of hidden colums
						$checkboxObjects.each(function(intKey, object){
							var $object = $(object);
							if($object.attr('my-column') == count){
								var $input = $(object).find('input');
								$input.prop('checked', false);
							}
						});
					}
				});
			}
		});

		this.$table = $('#hostdowntimes_list');
		
		/*
		 * Bind listoptions events
		 */
		$('.listoptions_action').click(function(){
			$this = $(this);
			// Set selected value in "fance selectbox"
			$($this.attr('selector')).html($this.html());
			// Set selected value in hidden field, for HTLM submit
			$($this.attr('submit_target')).val($this.attr('value'));
		});
		
		/*
		 * Bind click evento to .listoptions_checkbox to make a `<a />` to a label
		 */
		$('.listoptions_checkbox').click(function(event){
			$this = $(this);
			if(event.target == event.currentTarget){
				$checkbox = $this.find(':checkbox');
				// Lets make t `<a />` to an 'label'
				if($checkbox.prop('checked') == true){
					// Checkbox is enabled, so we need to remove the 'check'
					$checkbox.prop('checked', false);
				}else{
					// Checkbox is disabled, so we set the 'check'
					$checkbox.prop('checked', true);
				}
			}
		});
		
		
		//Create sudo server websocket connection
		this.Ajaxloader.setup();
		
		this.WebsocketSudo.setup(this.getVar('websocket_url'), this.getVar('akey'));
		
		this.WebsocketSudo._errorCallback = function(){
			$('#error_msg').html('<div class="alert alert-danger alert-block"><a href="#" data-dismiss="alert" class="close">×</a><h5 class="alert-heading"><i class="fa fa-warning"></i> Error</h5>Could not connect to SudoWebsocket Server</div>');
		}
		
		this.WebsocketSudo.connect();
		this.WebsocketSudo._success = function(e){
			return true;
		}.bind(this)
		
		this.WebsocketSudo._callback = function(transmitted){
			return true;
		}.bind(this);
		
		this.Externalcommand.setup();
		
		/*
		 * Bind click event for delete downtime button
		 */
		$('.delete_downtime').click(function(){
			var $yes = $('#message_yes');
			var $no = $('#message_no');
			var $cancel = $('#message_cancel');

			$.SmartMessageBox({
				title : "<span class='text-info'>You are about to delete downtime for host: "+$('#downtime-host-name-'+$(this).attr('internal-downtime-id')).text()+"</span>",
				sound: false,
				sound_on: false,
				content : 'Do you want to delete service downtimes for this host too?',
				buttons : '['+$cancel.val()+']['+$no.val()+']['+$yes.val()+']'
			}, function(ButtonPressed) {
				if (ButtonPressed === $yes.val()) {
                    self.WebsocketSudo.send(self.WebsocketSudo.toJson('submitDeleteHostDowntime', [$(this).attr('internal-downtime-id'), $(this).attr('downtime-services-id')]));
                    self.Externalcommand.refresh();
				}else if(ButtonPressed === $no.val()){
                    self.WebsocketSudo.send(self.WebsocketSudo.toJson('submitDeleteHostDowntime', [$(this).attr('internal-downtime-id')]));
                    self.Externalcommand.refresh();
				}
			}.bind(this));

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
		var postTextURL = currentURL.substring(currentURL.indexOf('downtimes/host') + 14);
		localStorage.setItem('DataTables_hostdowntimes_list_/downtimes/host'+postTextURL, storageValue);
	}
});