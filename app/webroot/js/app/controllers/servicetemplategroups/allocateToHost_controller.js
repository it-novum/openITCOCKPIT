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

App.Controllers.ServicetemplategroupsAllocateToHostController = Frontend.AppController.extend({

	components: ['Ajaxloader'],

	_initialize: function(){
		this.Ajaxloader.setup();

		var self = this;
		/*
		 * Bind change event on host selectbox
		 */
		$('#ServiceHostId').change(function(){
			self.enableAll();
			self.hideAllDuplicate();
			self.hideAllDuplicateDisabled();
			self.Ajaxloader.show();
			$.ajax({
				url: "/hosts/getHostByAjax/"+encodeURIComponent($(this).val())+".json",
				type: "POST",
				error: function(){},
				success: function(){},
				complete: function(response){
					self.Ajaxloader.hide();
					$(response.responseJSON.host.Service).each(function(intKey, serviceObject){
						//Disable checkbox, for services that already exist on the host
						$('#servicetemplate_'+serviceObject.servicetemplate_id).prop('checked', null);
						//If the service is disabled, display icon
						if($('#servicetemplate_'+serviceObject.servicetemplate_id).prop('checked') === false){
							if(serviceObject.disabled == 1 || serviceObject.disabled == '1'){
								$('#duplicateDisabled_'+serviceObject.servicetemplate_id).show();
							}
							//The service exists on the host and is enabled
							$('#duplicate_'+serviceObject.servicetemplate_id).show();
						}
					});
				}.bind(self)
			});
		});
	},

	enableAll: function(){
		$('.createThisService').prop('checked', true);
	},

	disableAll: function(){
		$('.createThisService').prop('checked', null);
	},

	hideAllDuplicate: function(){
		$('.createServiceDuplicate').hide();
	},

	hideAllDuplicateDisabled: function(){
		$('.createServiceDuplicateDisabled').hide();
	}
});
