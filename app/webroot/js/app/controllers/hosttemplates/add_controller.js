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

App.Controllers.HosttemplatesAddController = Frontend.AppController.extend({
	$contacts: null,
	$contactgroups: null,

	components: ['Highlight', 'Ajaxloader', 'CustomVariables', 'ContainerSelectbox'],

	_initialize: function() {
		var self = this;

		this.Ajaxloader.setup();
		this.CustomVariables.setup({
			controller: 'Hosttemplates',
			ajaxUrl: 'addCustomMacro',
			macrotype: 'HOST',
			customVariablesCounter: this.getVar('customVariablesCount') + 1
		});

		this.$contacts = $('#ContactContact');
		this.$contactgroups = $('#ContactgroupContactgroup');

		this.ContainerSelectbox.setup(this.Ajaxloader);
		this.ContainerSelectbox.addContainerEventListener({
			selectBoxSelector: '#HosttemplateContainerId',
			ajaxUrl: '/Hosttemplates/loadElementsByContainerId/:selectBoxValue:.json',
			fieldTypes: {
				timeperiods: '#HosttemplateNotifyPeriodId',
				checkperiods: '#HosttemplateCheckPeriodId',
				contacts: '#HosttemplateContact',
				contactgroups: '#HosttemplateContactgroup'
			},
			dataPlaceholderEmpty: self.getVar('data_placeholder_empty'),
			dataPlaceholder: self.getVar('data_placeholder')
		});

		/*
		 * Fix chosen width, if rendered in a tab
		 */
		$("[data-toggle='tab']").on('click', function(){
			$('.chosen-container').css('width', '100%');
		});

		// Flapdetection checkbox control
		$('input[type="checkbox"]#HosttemplateFlapDetectionEnabled').change(function(){
			this.checkFlapDetection();
		}.bind(this));

		this.checkFlapDetection();

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

			var min = parseInt($this.val() / 60, 10);
			var sec = parseInt($this.val() % 60, 10);
			$($this.attr('human')).html(min + " " + self.lang[1] + " " + self.lang[3] + " " + sec + " " + self.lang[2]);
		};
		$('.slider-input').on('change.slider', onChangeSliderInput);
			//.on('keyup', onChangeSliderInput);

		// Render fancy tags input
		$('.tagsinput').tagsinput();

		// Bind change event for the check command selectbox
		$('#HosttemplateCommandId').change(function(){
			self.loadParameters($(this).val());
		});
	},

	checkFlapDetection: function(){
		var disable = null;
		if(!$('input[type="checkbox"]#HosttemplateFlapDetectionEnabled').prop('checked')){
			disable = true;
		}
		$('.flapdetection_control').prop('disabled', disable);
	},

	loadParameters: function(command_id){
		this.Ajaxloader.show();
		$.ajax({
			url: "/Hosttemplates/loadArgumentsAdd/"+encodeURIComponent(command_id),
			type: "POST",
			error: function(){},
			success: function(){},
			complete: function(response){
				$('#CheckCommandArgs').html(response.responseText);
				this.Ajaxloader.hide();
			}.bind(this)
		});
	}

});
