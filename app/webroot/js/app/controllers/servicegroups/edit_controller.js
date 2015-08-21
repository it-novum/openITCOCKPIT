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

App.Controllers.ServicegroupsEditController = Frontend.AppController.extend({
	$services: null,
	lang: null,

	components: ['Highlight', 'Ajaxloader'],

	_initialize: function(){
		var self = this;

		this.Ajaxloader.setup();

		$(document).on('click', '.group-result', function(){
			// Get unselected items in this group
			var unselected = $(this).nextUntil('.group-result').not('.result-selected');
			if(unselected.length){
				// Select all items in this group
				unselected.trigger('mouseup');
			}else{
				$(this).nextUntil('.group-result').each(function(){
					// Deselect all items in this group
					var selector ='a.search-choice-close[data-option-array-index="' +
						$(this).data('option-array-index') + '"]';
					$(selector).trigger('click');
				});
			}
		});
		$('#ServicegroupService').trigger("chosen:updated");

		// Bind change event for Container Selectbox
		$('#ContainerParentId').change(function(){
			var container_id = $(this).val();
			if(parseInt(container_id, 10) > 0){
				self.Ajaxloader.show();
				$.ajax({
					url: "/Servicegroups/loadServices/" + encodeURIComponent(container_id) + '.json',
					type: "POST",
					dataType: "json",
					error: function(){
					},
					success: function(){
					},
					complete: function(response){

						// Fill fields of fieldTypes Object by ajax
						var $ServicegroupService = $('#ServicegroupService');
						var html = '';
						$ServicegroupService.html('');
						$ServicegroupService.attr('data-placeholder', self.getVar('data_placeholder_empty'));

						if(Object.keys(response.responseJSON.services).length > 0){
							$ServicegroupService.attr('data-placeholder', self.getVar('data_placeholder'));
						}

						for(var key in response.responseJSON.services){
							//console.log(key, response.responseJSON.services[key]);
							for(var hostname in response.responseJSON.services[key].value){
								html += '<optgroup label="' + hostname + '">';
								for(var value in response.responseJSON.services[key].value[hostname]){
									html += '<option value="' + value + '">' +
										response.responseJSON.services[key].value[hostname][value] +
										'</option>';
								}
								html += '</optgroup>';
							}
						}

						$ServicegroupService.html(html);
						$ServicegroupService.trigger("chosen:updated");
						self.Ajaxloader.hide();
					}
				});
			}
		});
	}
});
