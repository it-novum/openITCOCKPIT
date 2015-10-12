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

App.Components.WidgetTachoComponent = Frontend.Component.extend({

	Ajaxloader: null,

	tachos: {},

	setAjaxloader: function(Ajaxloader){
		this.Ajaxloader = Ajaxloader;
	},

	initTachos: function(){
		var self = this;
		$(document).on('change', '.tachoSelectService', function(e){
			var $object = $(e.target);
			var widgetId = parseInt($object.data('widget-id'), 10);
			var serviceId = parseInt($object.val(), 10);
			if(!isNaN(serviceId)){
				this.saveService(widgetId, serviceId);
			}
		}.bind(this));
		
		$(document).on('change', '.tacho-ds', function(){
			var $object = $(this);
			var widgetId = parseInt($object.data('widget-id'), 10);
			self.calculateThresholds(widgetId, $object.val());
			self.fillFilds(widgetId, $object.val());
		});
		
		$(document).on('click', '.previewTacho', function(e){
			var $object = $(e.target);
			var widgetId = parseInt($object.data('widget-id'), 10);
			var $previewContainer = this.tachos[widgetId].widgetContainer.find('.tachoPreviewContainer');
			$previewContainer.show();
			var $canvas = $('<canvas/>').uniqueId();
			$previewContainer.children('.tachometerContainer').html($canvas);
			
			this.drawTacho(widgetId, this.tachos[widgetId].dsSelect.val(), $canvas.attr('id'));
		}.bind(this));
		
		$(document).on('click', '.close-preview', function(e){
			var $object = $(e.target);
			var widgetId = parseInt($object.data('widget-id'), 10);
			var $previewContainer = this.tachos[widgetId].widgetContainer.find('.tachoPreviewContainer');
			$previewContainer.hide();
		}.bind(this));
		
		$('.tachometerContainer').each(function(key, object){
			this.initTacho(object);
		}.bind(this));
	},

	initTacho: function(object){
		var $widgetContainer = $(object).parents('.grid-stack-item');
		
		var $form = $widgetContainer.find('form');
		var changeCallback = function(e){
			var $object = $(e.target);
			var widgetId = parseInt($object.data('widget-id'), 10);
			this.updateValue($object.val(), $object.data('field'), widgetId);
		};
		
		$form.find('.tacho-min').change(changeCallback.bind(this));
		$form.find('.tacho-max').change(changeCallback.bind(this));
		$form.find('.tacho-warn').change(changeCallback.bind(this));
		$form.find('.tacho-crit').change(changeCallback.bind(this));
		
		var widgetId = parseInt($widgetContainer.data('widget-id'), 10);
		var $dsSelector = $widgetContainer.find('.tacho-ds');
		this.tachos[widgetId] = {
			widgetContainer: $widgetContainer,
			dsSelect: $dsSelector,
			perfdata: {}
		};
	},
	
	updateValue: function(value, field, widgetId){
		var ds = this.tachos[widgetId].dsSelect.val();
		this.tachos[widgetId].perfdata[ds][field] = parseFloat(value);
	},
	
	saveService: function(widgetId, serviceId){
		this.Ajaxloader.show();
		$.ajax({
			url: "/dashboards/saveTachoService.json",
			type: "POST",
			data: {widgetId: widgetId, serviceId: serviceId},
			error: function(){},
			success: function(response){
				if(typeof response.perfdata != 'undefined'){
					normalizedPerfdata = this.normalizedPerfdata(response.perfdata);
					this.tachos[widgetId].perfdata = normalizedPerfdata;
					var firstDS = null;
					this.tachos[widgetId].dsSelect.html('');
					for(var ds in normalizedPerfdata){
						if(firstDS === null){
							firstDS = ds;
						}
						this.tachos[widgetId].dsSelect.append($('<option></option>').val(ds).html(ds));
					}
					this.calculateThresholds(widgetId, firstDS);
					this.fillFilds(widgetId, firstDS);
				}
				this.Ajaxloader.hide();
			}.bind(this),
			complete: function(response) {
			}
		});
	},

	normalizedPerfdata: function(perfdata){
		var keys = ['current', 'unit', 'warn', 'crit', 'min', 'max'];
		var normalizedPerfdata = {};
		for(var key in perfdata){
			normalizedPerfdata[key] = {};
			for(var perfKey in keys){
				if(typeof perfdata[key][keys[perfKey]] == 'string' && keys[perfKey] !== 'unit'){
					perfdata[key][keys[perfKey]] = parseFloat(perfdata[key][keys[perfKey]]);
				}
				if(typeof perfdata[key][keys[perfKey]] == 'undefined'){
					normalizedPerfdata[key][keys[perfKey]] = 0;
				}else{
					normalizedPerfdata[key][keys[perfKey]] = perfdata[key][keys[perfKey]];
				}
			}
		}
		return normalizedPerfdata;
	},
	
	calculateThresholds: function(widgetId, ds){
		var perfdata = this.tachos[widgetId].perfdata[ds];
		var warning = parseFloat(perfdata.warn);
		if(isNaN(warning)){
			warning = 0;
		}
		var critical = parseFloat(perfdata.crit);
		if(isNaN(critical)){
			critical = 0;
		}
		
		if(perfdata.unit == '%'){
			var min = 0;
			var max = 100;
			this.tachos[widgetId].perfdata[ds].min = min;
			this.tachos[widgetId].perfdata[ds].max = max;
		}else{
			var min = 0;
			if(perfdata.min != ''){
				min = parseFloat(perfdata.min);
				if(isNaN(min)){
					min = 0;
				}
			}
			
			var max = critical + 50;
			if(perfdata.max != ''){
				max = critical + 50;
			}
		
			this.tachos[widgetId].perfdata[ds].min = min;
			this.tachos[widgetId].perfdata[ds].max = max;
		}
		
		warning = Math.round((warning / max * 100) * 100) / 100;
		critical = Math.round((critical / max * 100) * 100) / 100;
		this.tachos[widgetId].perfdata[ds].warn = warning;
		this.tachos[widgetId].perfdata[ds].crit = critical;
	},
	
	fillFilds: function(widgetId, ds){
		var perfdata = this.tachos[widgetId].perfdata[ds];
		var $widgetContainer = this.tachos[widgetId].widgetContainer;
		var $form = $widgetContainer.find('form');
		var $min = $form.find('.tacho-min');
		var $max = $form.find('.tacho-max');
		var $warn = $form.find('.tacho-warn');
		var $crit = $form.find('.tacho-crit');
		
		$min.val('');
		$max.val('');
		$warn.val('');
		$crit.val('');
		
		$min.prop("readonly", false);
		$max.prop("readonly", false);
		
		$min.val(this.tachos[widgetId].perfdata[ds].min);
		$max.val(this.tachos[widgetId].perfdata[ds].max);
		$warn.val(this.tachos[widgetId].perfdata[ds].warn);
		$crit.val(this.tachos[widgetId].perfdata[ds].crit);
		
		if(perfdata.unit == '%'){
			$min.prop("readonly", true);
			$max.prop("readonly", true);
		}
	},

	drawTacho:function(widgetId, ds, canvasId){
		var perfdata = this.tachos[widgetId].perfdata[ds];
		
		for(var key in perfdata){
			if(typeof perfdata[key] == 'undefined' && key !== 'unit'){
				perfdata[key] = 0;
			}
		}

		//Convert warn/crit percentage to absolute values
		var warning = perfdata.warn / 100 * perfdata.max;
		var critical = perfdata.crit / 100 * perfdata.max;
		
		var myHighlights = [
			{ from : perfdata.min, to : warning, color : '#449D44' },
			{ from : warning, to : critical, color : '#DF8F1D' },
			{ from : critical, to : perfdata.max, color : '#C9302C' }
		];

		//HDD usage for example
		if(perfdata.warn > perfdata.crit){
			myHighlights = [
				{ from : perfdata.min, to : critical, color : '#C9302C' },
				{ from : critical, to : warning, color : '#DF8F1D' },
				{ from : warning, to : perfdata.max, color : '#449D44' }
			];
		}

		var maxDecimalDigits = 3;
		var currentValueAsString = perfdata.current.toString();
		var intergetDigits = currentValueAsString.length;
		var decimalDigits = 0;
		
		if(currentValueAsString.indexOf('.') > 0){
			var splited = currentValueAsString.split('.');
			intergetDigits = splited[0].length;
			decimalDigits = splited[1].length;
			if(decimalDigits > maxDecimalDigits){
				decimalDigits = maxDecimalDigits;
			}
		}

		var showDecimalDigitsGauge = 0;
		if(decimalDigits > 0 || (perfdata.max - perfdata.min < 10)){
			showDecimalDigitsGauge = 1;
		}

		var gauge = new Gauge({
			renderTo: canvasId,
			height: 250,
			minValue: perfdata.min,
			maxValue: perfdata.max,
			units: perfdata.unit,
			strokeTicks: true,
			title: 'sdfsdf',
			glow: true,
			valueFormat : {
				int: intergetDigits,
				dec: decimalDigits
			},
			majorTicksFormat:{
				dec: showDecimalDigitsGauge
			},
			highlights: myHighlights,
			colors: {
				needle: { start : '#000', end : '#333' },
				plate: '#fff',
				majorTicks: '#444',
				minorTicks: '#666',
				title: '#888',
				units: '#888',
				numbers: '#444',
			},
			animation: {
				delay: 25,
				duration: 700,
				fn: 'cycle'
			}
		});

		gauge.onready = function() {
			gauge.setValue(perfdata.current);
		};
		gauge.draw();
	}

});
