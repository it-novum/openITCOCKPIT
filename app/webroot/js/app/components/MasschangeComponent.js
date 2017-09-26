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

App.Components.MasschangeComponent = Frontend.Component.extend({

    components: ['WebsocketSudo'],

	massCount: 0,
	controller: '',
	group: '',
	checkboxattr: 'hostname',
	useDeleteMessage: true,
	extendUrl: '',
	
	storeUuidsAsArray: false,
	uuidsArray: [],
	
	selectedIds: [],
	selectedUuids: [],

    selectedDTServiceIds: [],
    selectedDTUuids: [],
    selectedDTHistoryUuids: [],
	
	setup: function(conf){
		conf = conf || {};
		conf.controller = conf.controller || '';
		conf.group = conf.group || '';
		this.useDeleteMessage = conf.useDeleteMessage || this.useDeleteMessage;
		this.controller = conf.controller;
		this.group = conf.group;
		this.checkboxattr = conf.checkboxattr || this.checkboxattr;
		this.extendUrl = conf.extendUrl || this.extendUrl;
		this.storeUuidsAsArray = conf.storeUuidsAsArray || this.storeUuidsAsArray;

        this.Controller.WebsocketSudo.setup(this.Controller.getVar('websocket_url'), this.Controller.getVar('akey'));
        this.Controller.WebsocketSudo._errorCallback = function (){
            $('#error_msg').html('<div class="alert alert-danger alert-block"><a href="#" data-dismiss="alert" class="close">×</a><h5 class="alert-heading"><i class="fa fa-warning"></i> Error</h5>Could not connect to SudoWebsocket Server</div>');
        }
        this.Controller.WebsocketSudo.connect();
        this.Controller.WebsocketSudo._success = function (e) {
            return true;
        }.bind(this);
        this.Controller.WebsocketSudo._callback = function (transmitted) {
            return true;
        }.bind(this);

		var self = this;

		/*
		 * Bind "Select all"
		 */
		$('#selectAll').click(function(){
			self.selectedIds = [];
			self.selectedUuids = [];
			self.uuidsArray = [];
			$('.massChange').each(function(intIndex, checkboxObject){
				$(checkboxObject).prop('checked', true);
				self.addSelection($(checkboxObject).val(), $(checkboxObject).attr('uuid'));
				if(self.storeUuidsAsArray == true){
					self.uuidsArray[$(checkboxObject).attr('uuid')] = $(checkboxObject).attr('host-uuid');
				}
			});
			self.showCount();
		});

        $('#selectAllDowntimes').click(function(){
            self.selectedDTServiceIds = [];
            self.selectedDTUuids = [];
            self.selectedDTHistoryUuids = [];
            $('.massChangeDT').each(function(intIndex, checkboxObject){
                $(checkboxObject).prop('checked', true);
                self.addDTSelection($(checkboxObject).attr('downtimeServicesId'), $(checkboxObject).attr('internalDowntimeId'), $(checkboxObject).attr('downtimehistoryId'));
            });
            self.showCountDowntimes();
        });
		
		/*
		 * Bind "Undo selection"
		 */
		$('#untickAll').click(function(){
			$('.massChange').prop('checked', null);
			self.selectedIds = [];
			self.selectedUuids = [];
			self.uuidsArray = [];
			self.showCount();
		});

        $('#untickAllDowntimes').click(function(){
            $('.massChangeDT').prop('checked', null);
            self.selectedDTServiceIds = [];
            self.selectedDTUuids = [];
            self.selectedDTHistoryUuids = [];
            self.showCountDowntimes();
        });
		
		/*
		 * Check if there are some picked select boxes, browsers like firefox check them on page reload for example
		 */
		$('.massChange').each(function(intIndex, checkboxObject){
			if($(checkboxObject).prop('checked') == true){
				self.addSelection($(checkboxObject).val(), $(checkboxObject).attr('uuid'));
				if(self.storeUuidsAsArray == true){
					self.uuidsArray[$(checkboxObject).attr('uuid')] = $(checkboxObject).attr('host-uuid');
				}
			}
		});
		self.showCount();

        $('.massChangeDT').each(function(intIndex, checkboxObject){
            if($(checkboxObject).prop('checked') == true){
                self.addDTSelection($(checkboxObject).attr('downtimeServicesId'), $(checkboxObject).attr('internalDowntimeId'), $(checkboxObject).attr('downtimehistoryId'));
            }
        });
        self.showCountDowntimes();
		
		/*
		 * Bind change event
		 */
		$('.massChange').change(function(){
			var $clickedObject = $(this);
			if($clickedObject.prop('checked') == true){
				self.addSelection($clickedObject.val(), $clickedObject.attr('uuid'));
				if(self.storeUuidsAsArray == true){
					self.uuidsArray[$clickedObject.attr('uuid')] = $clickedObject.attr('host-uuid');
				}
			}else{
				self.removeSelection($clickedObject.val(), $clickedObject.attr('uuid'));
			}
			
			self.showCount();
		});

        $('.massChangeDT').change(function(){
            var $clickedObject = $(this);
            if($clickedObject.prop('checked') == true){
                self.addDTSelection($clickedObject.attr('downtimeServicesId'), $clickedObject.attr('internalDowntimeId'), $clickedObject.attr('downtimehistoryId'));
            }else{
                self.removeDTSelection($clickedObject.attr('downtimeServicesId'), $clickedObject.attr('internalDowntimeId'), $clickedObject.attr('downtimehistoryId'));
            }
            self.showCountDowntimes();
        });

    },
	
	addSelection: function(id, uuid){
		this.selectedIds.push(id);
		this.selectedUuids.push(uuid);
	},

    addDTSelection: function(dtsid, dtuuid, dthistuuid){
        if(dtsid!="") this.selectedDTServiceIds.push(dtsid);
        if(dtuuid!="") this.selectedDTUuids.push(dtuuid);
        if(dthistuuid!="") this.selectedDTHistoryUuids.push(dthistuuid);
    },
	
	removeSelection: function(id, uuid){
		var index = this.selectedIds.indexOf(id);
		if(index != -1){
			this.selectedIds.splice(index, 1);
		}
		
		var index = this.selectedUuids.indexOf(uuid);
		if(index != -1){
			this.selectedUuids.splice(index, 1);
		}
		
		if(this.storeUuidsAsArray == true){
			if(typeof this.uuidsArray[uuid] != 'undefined'){
				delete this.uuidsArray[uuid];
			}
		}
	},

    removeDTSelection: function(dtsid, dtuuid, dthistuuid){
        var index = this.selectedDTServiceIds.indexOf(dtsid);
        if(index != -1){
            this.selectedDTServiceIds.splice(index, 1);
        }

        var index = this.selectedDTUuids.indexOf(dtuuid);
        if(index != -1){
            this.selectedDTUuids.splice(index, 1);
        }

        var index = this.selectedDTHistoryUuids.indexOf(dthistuuid);
        if(index != -1){
            this.selectedDTHistoryUuids.splice(index, 1);
        }
    },
	
	showCount: function(){
		if(this.selectedIds.length > 0){
			$('#selectionCount').html('(' + this.selectedIds.length + ')');
		}else{
			$('#selectionCount').html('');
		}
		this.createDeleteAllHref();
		this.createEditDetailAllHref();
		this.createCopyAllHref();
		this.createDisableAllHref();
		this.createAppendGroupAllHref();
	},

    showCountDowntimes: function(){
        if(this.selectedDTUuids.length > 0){
            $('#selectionCount').html('(' + this.selectedDTUuids.length + ')');
        }else{
            $('#selectionCount').html('');
        }
        this.createDeleteAllHref();
    },
	
	createDeleteAllHref: function(){
		if(this.selectedIds.length > 0 || this.selectedDTUuids.length > 0){
			if(this.useDeleteMessage === true){
				var hostnames = this.fetchHostnames();
				var $yes = $('#message_yes');
				var $no = $('#message_no');
                var $cancel = $('#message_cancel');

				$('#deleteAll').off('click').on('click', function(e){
					SmartMSGboxCount = 0;
					$.SmartMessageBox({
						title : "<span class='text-danger'>"+$('#delete_message_h1').val()+"</span>",
						sound: false,
						sound_on: false,
						content : $('#delete_message_h2').val()+hostnames,
						buttons : '['+$no.val()+']['+$yes.val()+']'
					}, function(ButtonPressed) {
						if (ButtonPressed === $yes.val()) {
							window.location = '/'+this.controller+'/mass_delete/'+this.selectedIds.join('/')+this.extendUrl;
						}else{
							$('#MsgBoxBack').fadeOut();
						}
					}.bind(this));
					//e.preventDefault();
				}.bind(this));
                $('#deleteAllDowntimes').off('click').on('click', function(e){
                    hostnames = this.fetchDTHostnames();
                    SmartMSGboxCount = 0;
                    $.SmartMessageBox({
                        title : "<span class='text-danger'>"+$('#delete_message_h1').val()+"</span>",
                        sound: false,
                        sound_on: false,
                        content : $('#delete_message_h2').val()+hostnames,
                        buttons : '['+$cancel.val()+']['+$no.val()+']['+$yes.val()+']'
                    }, function(ButtonPressed) {
                        if (ButtonPressed === $yes.val()) {
                            var i=0;
                            while(i<this.selectedDTServiceIds.length){
                                this.Controller.WebsocketSudo.send(this.Controller.WebsocketSudo.toJson('submitDeleteHostDowntime', [this.selectedDTUuids[i], this.selectedDTServiceIds[i]]));
                                this.Controller.Externalcommand.refresh();
                                i=i+1;
                            }
                        } else if (ButtonPressed === $no.val()) {
                            var i=0;
                            while(i<this.selectedDTServiceIds.length){
                                this.Controller.WebsocketSudo.send(this.Controller.WebsocketSudo.toJson('submitDeleteHostDowntime', [this.selectedDTUuids[i]]));
                                this.Controller.Externalcommand.refresh();
                            }
                        } else {
                            $('#MsgBoxBack').fadeOut();
                        }
                    }.bind(this));
                    //e.preventDefault();
                }.bind(this));
			}else{
                $('#deleteAll').off('click').on('click', function(e){
                    $('#deleteAll').attr('href', '/' + this.controller + '/mass_delete/' + this.selectedIds.join('/') + this.extendUrl);
                });
                $('#deleteAllDowntimes').off('click').on('click', function(e){
                    var i=0;
                    while(i<this.selectedDTServiceIds.length){
                        this.Controller.WebsocketSudo.send(this.Controller.WebsocketSudo.toJson('submitDeleteHostDowntime', [this.selectedDTUuids[i], this.selectedDTServiceIds[i]]));
                        this.Controller.Externalcommand.refresh();
                        i=i+1;
                    }
                }.bind(this));
                $('#deleteAllServiceDowntimes').off('click').on('click', function(e){
                    var i=0;
                    while(i<this.selectedDTUuids.length){
                        this.Controller.WebsocketSudo.send(this.Controller.WebsocketSudo.toJson('submitDeleteServiceDowntime', [this.selectedDTUuids[i]]));
                        this.Controller.Externalcommand.refresh();
                        i=i+1;
                    }
                }.bind(this));
			}
		}else{
			$('#deleteAll').attr('href', 'javascript:void(0);');
			$('#deleteAll').unbind('click');
            $('#deleteAllDowntimes').attr('href', 'javascript:void(0);');
            $('#deleteAllDowntimes').unbind('click');
		}
	},
	
	createDisableAllHref: function(){
		if(this.selectedIds.length > 0){
			var hostnames = this.fetchHostnames();
			var $yes = $('#message_yes');
			var $no = $('#message_no');
			$('#disableAll').click(function(e){
				$.SmartMessageBox({
					title : "<span class='text-info'>"+$('#disable_message_h1').val()+"</span>",
					sound: false,
					sound_on: false,
					content : $('#disable_message_h2').val()+hostnames,
					buttons : '['+$no.val()+']['+$yes.val()+']'
				}, function(ButtonPressed) {
					if (ButtonPressed === $yes.val()) {
						window.location = '/'+this.controller+'/mass_deactivate/'+this.selectedIds.join('/')+this.extendUrl;
					}else{
						$('#MsgBoxBack').fadeOut();
					}
				}.bind(this));
				
				//e.preventDefault();
			}.bind(this));
			
		}else{
			$('#disableAll').attr('href', 'javascript:void(0);');
			$('#disableAll').unbind('click');
		}
	},
	
	createCopyAllHref: function(){
		if(this.selectedIds.length > 0){
			$('#copyAll').attr('href', '/'+this.controller+'/copy/'+this.selectedIds.join('/')+this.extendUrl);
			var mySelectedIds = this.selectedIds;
			var myController = this.controller;
			var myExtendUrl = this.extendUrl;
			$('.copyAll-too').each(function(){
				var myObj = $(this);
				myObj.attr('href', '/'+myController+'/'+myObj.attr('data-action')+'/'+mySelectedIds.join('/')+myExtendUrl);
			});
		}else{
			$('#copyAll').attr('href', 'javascript:void(0);');
			$('.copyAll-too').each(function(){
				var myObj = $(this);
				myObj.attr('href', 'javascript:void(0);');
			});
		}
	},
	
	createAppendGroupAllHref: function(){
		if(this.selectedIds.length > 0){
			$('#addToGroupAll').attr('href', '/'+this.group+'/mass_add/'+this.selectedIds.join('/')+this.extendUrl);
		}else{
			$('#addToGroupAll').attr('href', 'javascript:void(0);');
		}
	},
	
	createEditDetailAllHref: function(){
		if(this.selectedIds.length > 0){
			$('#editDetailAll').attr('href', '/'+this.controller+'/edit_details/'+this.selectedIds.join('/')+this.extendUrl);
		}else{
			$('#editDetailAll').attr('href', 'javascript:void(0);');
		}
	},
	
	fetchHostnames: function(){
		var html = '<br />';
		$('.massChange').each(function(intIndex, checkboxObject){
			if(this.selectedIds.indexOf($(checkboxObject).val()) != -1){
				html+=' - '+$(checkboxObject).attr(this.checkboxattr)+'<br />';
			}
		}.bind(this));
		return html;
	},

    fetchDTHostnames: function(){
        var html = '<br />';
        $('.massChangeDT').each(function(intIndex, checkboxObject){
            if(this.selectedDTUuids.indexOf($(checkboxObject).attr('internalDowntimeId')) != -1){
                html+=' - '+$(checkboxObject).attr(this.checkboxattr)+'<br />';
            }
        }.bind(this));
        return html;
    }
});
