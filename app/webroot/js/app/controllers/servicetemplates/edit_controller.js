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

App.Controllers.ServicetemplatesEditController = Frontend.AppController.extend({
	$contacts: null,
	$contactgroups: null,

	components: ['Highlight', 'Ajaxloader', 'CustomVariables', 'ContainerSelectbox'],

	_initialize: function() {
		var self = this;

		self.Ajaxloader.setup();
		self.CustomVariables.setup({
			controller: 'Servicetemplates',
			ajaxUrl: 'Servicetemplates/addCustomMacro',
			macrotype: 'SERVICE'
		});
		this.ContainerSelectbox.setup(this.Ajaxloader);
		this.ContainerSelectbox.addContainerEventListener({
			selectBoxSelector: '#ServicetemplateContainerId',
			ajaxUrl: '/Servicetemplates/loadElementsByContainerId/:selectBoxValue:.json',
			fieldTypes: {
				timeperiods: '#ServicetemplateNotifyPeriodId',
				checkperiods: '#ServicetemplateCheckPeriodId',
				contacts: '#ServicetemplateContact',
				contactgroups: '#ServicetemplateContactgroup'
			},
			dataPlaceholderEmpty: self.getVar('data_placeholder_empty'),
			dataPlaceholder: self.getVar('data_placeholder')
		});

		// Fix chosen width, if rendered in a tab
		$("[data-toggle='tab']").click(function(){
			$('.chosen-container').css('width', '100%');
		});

		this.$contacts = $('#ContactContact');
		this.$contactgroups = $('#ContactgroupContactgroup');

		// Flapdetection checkbox control
		$('input[type="checkbox"]#ServicetemplateFlapDetectionEnabled').change(function(){
			self.checkFlapDetection();
		});
		self.checkFlapDetection();

		// Freshness settings checkbox control
		$('input[type="checkbox"]#ServicetemplateFreshnessChecksEnabled').change(function(){
			self.checkFreshnessSettings();
		});

		self.checkFreshnessSettings();

		// Tooltip for the sliders
		self.lang = [];
		self.lang[1] = this.getVar('lang_minutes');
		self.lang[2] = this.getVar('lang_seconds');
		self.lang[3] = this.getVar('lang_and');

		var onSlideStop = function(ev){
			if(ev.value == null){
				ev.value = 0;
			}

			$('#_' + $(this).attr('id')).val(ev.value);
			$(this)
				.val(ev.value)
				.trigger('change');
			var min = parseInt(ev.value / 60, 10);
			var sec = parseInt(ev.value % 60, 10);
			$($(this).attr('human')).html(min + " " + self.lang[1] + " " + self.lang[3] + " " + sec + " " + self.lang[2]);
		};

		var $slider = $('input.slider');
		$slider.slider({ tooltip: 'hide' });
		$slider.slider('on', 'slide', onSlideStop);
		$slider.slider('on', 'slideStop', onSlideStop);

		// Input this.fieldMap for sliders
		var onChangeSliderInput = function(){
			var $this = $(this);
			$('#' + $this.attr('slider-for'))
				.slider('setValue', parseInt($this.val(), 10), true)
				.val($this.val())
				.attr('value', $this.val());
			$serviceNotificationIntervalField.trigger('change');
			var min = parseInt($this.val() / 60, 10);
			var sec = parseInt($this.val() % 60, 10);
			$($this.attr('human')).html(min + " " + self.lang[1] + " " + self.lang[3] + " " + sec + " " + self.lang[2]);
		};
		$('.slider-input').on('change.slider', onChangeSliderInput);
			//.on('keyup', onChangeSliderInput);

		// Render fancy tags input
		$('.tagsinput').tagsinput();

		// Bind change event for the check command selectbox
		$('#ServicetemplateCommandId').change(function(){
			self.loadParametersByCommandId($(this).val(), $('#ServicetemplateId').val(), $('#CheckCommandArgs'));
		});

		// Bind change event for the `Eventhandler` select box.
		var $servicetemplateEventhandlerCommandId = $('#ServicetemplateEventhandlerCommandId'),
			$event_handler_command_args = $('#EventhandlerCommandArgs'),
			loadEventhandlerArgs = function(id){
				if(id && id != '0' && id > 0){
					self.loadNagParametersByCommandId(id, $('#ServicetemplateId').val(), $event_handler_command_args);
				}else{
					$event_handler_command_args.html('');
				}
			};
		loadEventhandlerArgs($servicetemplateEventhandlerCommandId.val());
		$servicetemplateEventhandlerCommandId.on('change.commandId', function(){
			var id = $(this).val();
			loadEventhandlerArgs(id);
		});
	},

	checkFlapDetection: function(){
		var disable = null;
		if(!$('input[type="checkbox"]#ServicetemplateFlapDetectionEnabled').prop('checked')){
			disable = true;
		}
		$('.flapdetection_control').prop('disabled', disable);
	},

	checkFreshnessSettings: function(){
		var readonly = null;
		if(!$('input[type="checkbox"]#ServicetemplateFreshnessChecksEnabled').prop('checked')){
			readonly = true;
			$('#ServicetemplateFreshnessThreshold').val('');
		}
		$('#ServicetemplateFreshnessThreshold').prop('readonly', readonly);
	},

	/**
	 * @param {String} command_id
	 * @param {jQuery} $target
	 */
	loadParameters: function(command_id, $target){
		this.Ajaxloader.show();
		$.ajax({
			url: "/Servicetemplates/loadArgumentsAdd/" + encodeURIComponent(command_id),
			type: "POST",
			cache: false,
			error: function(){},
			success: function(){},
			complete: function(response){
				$target.html(response.responseText);
				this.Ajaxloader.hide();
			}.bind(this)
		});
	},

	loadParametersByCommandId: function(command_id, servicetemplate_id, $target){
		var self = this;
		this.Ajaxloader.show();
		$.ajax({
			url: "/Servicetemplates/loadParametersByCommandId/"+encodeURIComponent(command_id)+"/"+encodeURIComponent(servicetemplate_id),
			type: "POST",
			cache: false,
			error: function(){},
			success: function(){},
			complete: function(response){
				$target.html(response.responseText);
				self.Ajaxloader.hide();
			}
		});
	},

	loadNagParametersByCommandId: function(command_id, servicetemplate_id, $target){
		var self = this;
		this.Ajaxloader.show();
		$.ajax({
			url: "/Servicetemplates/loadNagParametersByCommandId/"+encodeURIComponent(command_id)+"/"+encodeURIComponent(servicetemplate_id),
			type: "POST",
			cache: false,
			error: function(){},
			success: function(){},
			complete: function(response){
				$target.html(response.responseText);
				self.Ajaxloader.hide();
			}
		});
	}
});
