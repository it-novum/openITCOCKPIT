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

App.Controllers.ServicetemplategroupsAddController = Frontend.AppController.extend({

	components: ['Ajaxloader'],

	_initialize: function(){
		this.Ajaxloader.setup();

		var self = this;
		/*
		 * Bind change event on host selectbox
		 */
		$('#ContainerParentId').change(function(){

			self.Ajaxloader.show();
			$.ajax({
				url: "/servicetemplategroups/loadServicetemplatesByContainerId/"+encodeURIComponent($(this).val())+".json",
				type: "POST",
				cache: false,
				error: function(){},
				success: function(){},
				complete: function(response){
					var $selectServicetemplates = $('#ServicetemplategroupServicetemplate');
					$selectServicetemplates.html('');
					$selectServicetemplates.attr('data-placeholder', self.getVar('data_placeholder_empty'));
					for(var key in response.responseJSON.servicetemplates){
						if(Object.keys(response.responseJSON.servicetemplates).length > 0){
							$selectServicetemplates.attr('data-placeholder', self.getVar('data_placeholder'));
							$selectServicetemplates.append('<option value="'+response.responseJSON.servicetemplates[key].key+'">'+response.responseJSON.servicetemplates[key].value+'</option>');
						}
					}
					$selectServicetemplates.trigger("chosen:updated");
					self.Ajaxloader.hide();
				}.bind(self)
			});
		});
	}

});
