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

App.Components.ContainerSelectboxComponent = Frontend.Component.extend({
	Ajaxloader: null,

	setup: function(Ajaxloader){
		this.Ajaxloader = Ajaxloader;
	},

	addContainerEventListener: function(options){
		var self = this;

		var defaults = {
			event: 'change',
			optionGroupFieldTypes: {}
		};

		options = $.extend({}, defaults, options);

		$(options.selectBoxSelector).on(options.event, function(){
			var containerId = parseInt($(this).val(), 10),
				ajaxUrl;

			if(isNaN(containerId) || containerId <= 0){
				return;
			}

			ajaxUrl = options.ajaxUrl.replace(':selectBoxValue:', containerId);

			self.Ajaxloader.show();
			$.ajax({
				url: ajaxUrl,
				type: 'post',
				dataType: 'json',
				error: function(){},
				success: function(){},
				complete: function(response){
					var fieldType,
						key,
						$querySelect;

					if(Object.keys(options.optionGroupFieldTypes).length > 0){
						for(fieldType in response.responseJSON){
							$querySelect = $(options.optionGroupFieldTypes[fieldType]);
							$querySelect.html('');
							$querySelect.attr('data-placeholder', options.dataPlaceholderEmpty);

							if(Object.keys(response.responseJSON[fieldType]).length > 0){
								$querySelect.attr('data-placeholder', options.dataPlaceholder);
							}
							var html = '';
							for(key in response.responseJSON[fieldType]){
								for(var displayName in response.responseJSON[fieldType][key].value){
									html += '<optgroup label="' + displayName + '">';
									for(var value in response.responseJSON[fieldType][key].value[displayName]){
										html += '<option value="' + value + '">' + response.responseJSON[fieldType][key].value[displayName][value] + '</option>';
									}
									html += '</optgroup>';
								}
							}
							$querySelect.html(html);
							$querySelect.trigger('chosen:updated');
						}
					}

					for(fieldType in response.responseJSON){
						$querySelect = $(options.fieldTypes[fieldType]);
						$querySelect.html('');
						$querySelect.attr('data-placeholder', options.dataPlaceholderEmpty);
						for(key in response.responseJSON[fieldType]){
							if(Object.keys(response.responseJSON[fieldType]).length > 0){
								$querySelect.attr('data-placeholder', options.dataPlaceholder);
								$querySelect.append('<option value="' + response.responseJSON[fieldType][key].key + '">' + response.responseJSON[fieldType][key].value + '</option>');
							}
						}
						$querySelect.trigger("chosen:updated");
					}
					self.Ajaxloader.hide();
				}
			});
		});
	}
});
