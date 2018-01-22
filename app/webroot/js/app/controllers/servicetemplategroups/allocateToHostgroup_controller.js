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

App.Controllers.ServicetemplategroupsAllocateToHostgroupController = Frontend.AppController.extend({

	components: ['Ajaxloader'],

	_initialize: function(){
		this.Ajaxloader.setup();
		
		var self = this;
		
		/*
		 * Bind change event on hostgroup selectbox
		 */
		$('#HostgroupId').change(function(){
			self.Ajaxloader.show();
			$.ajax({
				url: "/servicetemplategroups/getHostsByHostgroupByAjax/"+encodeURIComponent($(this).val())+"/"+self.getVar('servicetemplategroup_id')+".json",
				type: "POST",
				cache: false,
				error: function(){},
				success: function(){},
				complete: function(response){
					self.Ajaxloader.hide();
					$('#ajaxContent').html('');
					// Create fieldset for every host of the hostgroup
					$(response.responseJSON.hosts).each(function(intKey, hostObject){
						var html = '';
						html += '<fieldset><legend><i class="fa fa-desktop"></i> '+htmlspecialchars(hostObject.Host.name)+'</legend>';
							// Create checkbox for each service out of the servicetemplategroup
							$(response.responseJSON.servicetemplategroup.Servicetemplate).each(function(_intKey, servicetemplateObject){
								// Checking if this service already exists or is disabled on the host
								var exists = false;
								var disabled = false;
								$(hostObject.Service).each(function(__intKey, serviceObject){
									if(serviceObject.servicetemplate_id == servicetemplateObject.id){
										exists = true;
										if(serviceObject.disabled == 1 || serviceObject.disabled == '1'){
											disabled = true;
										}
									}
								});
								
								var checked = '';
								if(exists === false && disabled === false){
									// The service does not exists and is not disabled on the host
									checked = 'checked="checked"';
								}
								
								html += '<div class="padding-left-10 padding-bottom-5">';
									html += '<input type="checkbox" '+checked+' id="servicetemplate_'+servicetemplateObject.id+'_'+hostObject.Host.id+'" value="'+servicetemplateObject.id+'" name="data[Host]['+hostObject.Host.id+'][ServicesToAdd][]" />';
									html += '<label for="servicetemplate_'+servicetemplateObject.id+'_'+hostObject.Host.id+'">'+htmlspecialchars(servicetemplateObject.name)+' <i class="text-info">('+htmlspecialchars(servicetemplateObject.description)+')</i></label>';
									if(exists === true){
										// the service exists on the host, show notice
										html += '<a href="javascript:void(0);" data-original-title="'+self.getVar('service_exists')+'" data-placement="right" rel="tooltip" data-container="body"><i class="padding-left-5 fa fa-info-circle text-info"></i></a>';
									}
									if(disabled === true){
										// the service exists and/or is disabled on the host, show notice
										html += '<a class="txt-color-blueDark" href="javascript:void(0);" data-original-title="'+self.getVar('service_disabled')+'" data-placement="right" rel="tooltip" data-container="body"><i class="padding-left-5 fa fa-plug"></i></a>';
									}
								html+= '</div>';
								
							});
						html += '</fieldset>';
						$('#ajaxContent').append(html);
						
						// Fixing tooltips
						$('[rel="tooltip"]').tooltip();
					});
				}.bind(self)
			});
		});
	}
});
