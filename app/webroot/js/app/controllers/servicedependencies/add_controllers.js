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

App.Controllers.ServicedependenciesAddController = Frontend.AppController.extend({

	components: ['Ajaxloader', 'ContainerSelectbox'],

	_initialize: function(){
		var self = this;

		this.Ajaxloader.setup();
		this.ContainerSelectbox.setup(this.Ajaxloader);
		this.ContainerSelectbox.addContainerEventListener({
			selectBoxSelector: '#ServicedependencyContainerId',
			ajaxUrl: '/Servicedependencies/loadElementsByContainerId/:selectBoxValue:.json',
			optionGroupFieldTypes: {
				services: '#ServicedependencyService',
				servicesDependent: '#ServicedependencyServiceDependent'
			},
			fieldTypes: {
				servicegroups: '#ServicedependencyServicegroup',
				servicegroupsDependent: '#ServicedependencyServicegroupDependent',
				timeperiods: '#ServicedependencyTimeperiodId'
			},
			dataPlaceholderEmpty: self.getVar('data_placeholder_empty'),
			dataPlaceholder: self.getVar('data_placeholder')
		});

		$(document).on('click', '.group-result', function() {
		// Get unselected items in this group
			var unselected = $(this).nextUntil('.group-result').not('.result-selected');
			if(unselected.length){
				// Select all items in this group
				unselected.trigger('mouseup');
			}else{
				$(this).nextUntil('.group-result').each(function() {
					// Deselect all items in this group
					$('a.search-choice-close[data-option-array-index="' + $(this).data('option-array-index') + '"]').trigger('click');
				});
			}
		});
		$('[id^=ServicedependencyService]').change(function(){
			$this = $(this);
			self.refreshServices($this.val(), $this, $this.attr('target'));
		});
		if($('#ServicedependencyService').val() !== null || $('#ServicedependencyServiceDependent').val() !== null){
			$('#ServicedependencyService').children().each(function(intKey, OptionObject){
				if(in_array(OptionObject.value, $('#ServicedependencyServiceDependent').val())){
					$OptionObject = $(OptionObject);
					$OptionObject.prop('disabled', true);
				}
			});
			$('#ServicedependencyServiceDependent').children().each(function(intKey, OptionObject){
				if(in_array(OptionObject.value, $('#ServicedependencyService').val())){
					$OptionObject = $(OptionObject);
					$OptionObject.prop('disabled', true);
				}
			});
			$('#ServicedependencyService').trigger("chosen:updated").change();
			$('#ServicedependencyServiceDependent').trigger("chosen:updated").change();
		}
		if($('#ServicedependencyServicegroup').val() !== null || $('#ServicedependencyServicegroupDependent').val() !== null){
			$('#ServicedependencyServicegroup').children().each(function(intKey, OptionObject){
				if(in_array(OptionObject.value, $('#ServicedependencyServicegroupDependent').val())){
					$OptionObject = $(OptionObject);
					$OptionObject.prop('disabled', true);
				}
			});
			$('#ServicedependencyServicegroupDependent').children().each(function(intKey, OptionObject){
				if(in_array(OptionObject.value, $('#ServicedependencyServicegroup').val())){
					$OptionObject = $(OptionObject);
					$OptionObject.prop('disabled', true);
				}
			});
			$('#ServicedependencyServicegroup').trigger("chosen:updated").change();
			$('#ServicedependencyServicegroupDependent').trigger("chosen:updated").change();
		}
	},

	refreshServices: function(selected_services, selectboxObject, target){
		//Disable the selected option in $target selectbox, to avoid duplicate selections
		for (var key in selected_services){
			$(target).find('option').each(function(intKey, OptionObject){
				$OptionObject = $(OptionObject);
				if($OptionObject.val() == selected_services[key]){
					//This is the option we need to disable
					if(!$OptionObject.prop('disabled')){
						$OptionObject.prop('disabled', true);
					}
				}
			});
		}

		//Check if we need to re-enable something
		var targetValue = $(target).val();
		$(target).find('option').each(function(intKey, OptionObject){
			$OptionObject = $(OptionObject);
			if(targetValue == null){
				targetValue = [];
			}
			if(selected_services == null){
				selected_services = [];
			}

			if(!in_array($OptionObject.val(), selected_services) && !in_array($OptionObject.val(), targetValue)){
				//This is the option we need to enable
				if($OptionObject.prop('disabled')){
					$OptionObject.prop('disabled', null);
				}
			}

		});

		//Update chosen
		$(target).trigger("chosen:updated");
	}
});
