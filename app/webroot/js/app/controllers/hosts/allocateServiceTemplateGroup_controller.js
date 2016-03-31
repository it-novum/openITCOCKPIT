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

App.Controllers.HostsAllocateServiceTemplateGroupController = Frontend.AppController.extend({

	components: ['Ajaxloader'],

	_initialize: function(){
		this.Ajaxloader.setup();

		var self = this;
		/*
		 * Bind change event on servicetemplategroup selectbox
		 */
		$('#ServicetemplategroupId').change(function(){

			self.Ajaxloader.show();
			$.ajax({
				url: "/hosts/getServiceTemplatesfromGroup/"+encodeURIComponent($(this).val())+".json",
				type: "POST",
				data: ({host_id:7}),
				error: function(){},
				success: function(){},
				complete: function(response){
					self.Ajaxloader.hide();
					var existantServiceIds = [];
					$('#allServicesFromGroup').html('');
					$(response.responseJSON.host.Service).each(function(intKey, service){
						existantServiceIds.push(service.servicetemplate_id);
					});

					$(response.responseJSON.servicetemplategroup.Servicetemplate).each(function(intKey, id){
						$('#allServicesFromGroup').append('<input type=\"checkbox\" class=\"createThisService\" id=\"servicetemplate_'+id.id+'\" value=\"'+id.id+'\" name=\"data[Service][ServicesToAdd][]\" />&nbsp;<label for=\"servicetemplate_'+id.id+'\">'+id.name+' <i class=\"text-info\">('+id.description+')</i></label><a style=\"display: none;\" class=\"createServiceDuplicate\" id=\"duplicate_'+id.id+'\" href=\"javascript:void(0);\" data-original-title=\"Service already exist on selected host. Tick the box to create duplicate.\" data-placement=\"right\" rel=\"tooltip\" data-container=\"body\"><i class=\"padding-left-5 fa fa-info-circle text-info\"></i></a><a style=\"display: none;\" class=\"txt-color-blueDark createServiceDuplicateDisabled\" id=\"duplicateDisabled_'+id.id+'\" href=\"javascript:void(0);\" data-original-title=\"Service already exist on selected host but is disabled. Tick the box to create duplicate.\" data-placement=\"right\" rel=\"tooltip\" data-container=\"body\"><i class=\"padding-left-5 fa fa-plug\"></i></a></br>');

						//Disable checkbox, for services that already exist on the host
						$('#servicetemplate_'+id.id).prop('checked', true);

						if($.inArray(id.id,existantServiceIds) !== -1){
							$('#servicetemplate_'+id.id).prop('checked', false);
						}

						//If the service is disabled, display icon
						if($('#servicetemplate_'+id.id).prop('checked') === false){
							if(id.disabled == 1 || id.disabled == '1'){
								$('#duplicateDisabled_'+id.id).show();
							}
							//The service exists on the host and is enabled
							$('#duplicate_'+id.id).show();
						}
					});
					$('[rel="tooltip"]').tooltip();
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