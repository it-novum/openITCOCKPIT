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

	setup: function(options, allWidgetParameters){
		var self = this;
		var defaults = {
			ids:[]
		};
		self.intervalIds = {};

		self.options = $.extend({}, defaults, options);

		$(document).on('submit','.tacho_form', function(event){
			event.preventDefault();

			var $form = $(this),
				widget_id = self.getWidgetId($form),
				dataToSave = self.getData($form),
				error = false,
				$widgetContainer = $form.parents('.tacho-body'),
				$current = $widgetContainer.find('.current').val(),
				$unit = $widgetContainer.find('.unit').val();

			if($widgetContainer.find('.data-source-select-box').val()){
				$title = $widgetContainer.find('.data-source-select-box').val();
			}else{
				$title = '';
			}

			$form.find('.tacho_preview_canvas').html('');
			$form.find('.tacho_preview_canvas').css('display','none');

			self.options.loadWidgetData(widget_id, function(data){
				if(data.data.Widget.service_id){
					//Update
					error = self.drawTacho($widgetContainer.find('.tacho'),{
						title : $title,
						min : dataToSave.min,
						max : dataToSave.max,
						warn : dataToSave.warn,
						crit : dataToSave.crit,
						current : $current,
						unit : $unit,
						id : widget_id
					});

					if(!error){
						$widgetContainer.find('.tacho canvas').css('display','block');
						$form.css('display','none');
						$form.next().html($form.find('select option:selected').html()+' <i class="fa fa-cog "></i>');
						$form.next().css('display','block');
						saveData = {
							'Widget': {
								'id' : widget_id,
								'service_id' : dataToSave.serviceId,
							},
							'WidgetTacho':{
								'id' : data.data.WidgetTacho.id,
								'min' : dataToSave.min,
								'max' : dataToSave.max,
								'warn' : dataToSave.warn,
								'crit' : dataToSave.crit,
								'data_source' : dataToSave.dataSource
							}
						};
						self.options.saveWidgetData(saveData);

						var checkInterval = data.data.Service.check_interval;
						//if there is no checkInterval defined get standard checkInterval for service
						if(!checkInterval){
							checkInterval = data.data.Service.Servicetemplate.check_interval;
						}
						checkInterval = checkInterval*1000;

						var intervalId = setInterval(function() {
							self.createUpdateInterval(
								$form.parents('.tacho-body'),
								dataToSave.serviceId,
								dataToSave.dataSource,
								dataToSave.min,
								dataToSave.max,
								dataToSave.warn,
								dataToSave.crit,
								$unit
							);
						}, checkInterval);
						self.intervalIds[widget_id] = intervalId;
					}else{
						//self.options.updateWidgetHeight($widgetContainer);
					}
				}else{
					//New Entry
					error = self.drawTacho($widgetContainer.find('.tacho'),{
						title : $title,
						min : dataToSave.min,
						max : dataToSave.max,
						warn : dataToSave.warn,
						crit : dataToSave.crit,
						current : $current,
						unit : $unit,
						id : widget_id
					});
					if(!error){
						$widgetContainer.find('.tacho canvas').css('display','block');
						$form.css('display','none');
						$form.next().html($form.find('select option:selected').html()+' <i class="fa fa-cog "></i>');
						$form.next().css('display','block');
						saveData = {
							'Widget': {
								'id' : widget_id,
								'service_id' : dataToSave.serviceId,
							},
							'WidgetTacho':{
								'min' : dataToSave.min,
								'max' : dataToSave.max,
								'warn' : dataToSave.warn,
								'crit' : dataToSave.crit,
								'data_source' : dataToSave.dataSource
							}
						};
						self.options.saveWidgetData(saveData);
					}else{
						//self.options.updateWidgetHeight($widgetContainer);
					}
				}
			});

			//restart Tab Rotation if needed
			if(allWidgetParameters['tabRotation'].tabRotationInterval > 0){
				allWidgetParameters['tabRotation'].tabRotationInterval = allWidgetParameters['tabRotation'].tabRotationInterval - 2000;
				var intervalId = setInterval(function() {
					$('.rotateTabs').find('.fa-refresh').addClass('fa-spin');
					setTimeout(function(){
						allWidgetParameters['nextTab'].nextTab();
					},2000);
				}, allWidgetParameters['tabRotation'].tabRotationInterval);
				allWidgetParameters['tabRotation'].tabIntervalId = intervalId;
			}

		});

		$(document).on('click','.service-title-tacho', function(event){
			var $div = $(this),
				widgetId = self.getWidgetId($div);

			$div.css('display','none');
			$div.prev().css('display','block');
			$div.parents('.tacho-body').find('.tacho canvas').css('display','none');

			if(allWidgetParameters['tabRotation'].tabIntervalId){
				clearInterval(allWidgetParameters['tabRotation'].tabIntervalId);
				allWidgetParameters['tabRotation'].tabIntervalId = null;
			}

			if(self.intervalIds[widgetId]){
				clearInterval(self.intervalIds[widgetId]);
				self.intervalIds[widgetId] = 0;
			}
		});



		$(document).on('change','.data-source-select-box', function(e){
			var $widgetContainer = $(this).parents('.tacho-body');

			if(self.lastResponse && self.lastResponse.responseJSON){
				var res = self.lastResponse.responseJSON,
					chosenDataSource = $(this).val();

				$widgetContainer.find('.min').val(res[chosenDataSource].min);
				$widgetContainer.find('.max').val(res[chosenDataSource].max);
				$widgetContainer.find('.warn').val(res[chosenDataSource].warn);
				$widgetContainer.find('.crit').val(res[chosenDataSource].crit);
				$widgetContainer.find('.current').val(res[chosenDataSource].current);
				$widgetContainer.find('.unit').val(res[chosenDataSource].unit);

				$widgetContainer.find('.dataSourceValues').css('display','block');
				$widgetContainer.find('.tacho_preview').css('display','block');
			}else{
				console.log('There is no response');
			}

		});
		$(document).on('click','.tacho_preview', function(e){

			var error = false,
				$widgetContainer = $(this).parents('.tacho-body'),
				$previewStatus = $widgetContainer.find('.tacho_preview_canvas').css('display');

			if($previewStatus == 'none'){
				$widgetContainer.find('.tacho_preview_canvas').html('');
				$widgetContainer.find('.tacho_preview_canvas').html('<canvas></canvas>');

				var $min = $widgetContainer.find('.min').val(),
					$max = $widgetContainer.find('.max').val(),
					$warn = $widgetContainer.find('.warn').val(),
					$crit = $widgetContainer.find('.crit').val(),
					$title,
					$current = $widgetContainer.find('.current').val(),
					$unit = $widgetContainer.find('.unit').val();

				if($widgetContainer.find('.data-source-select-box').val()){
					$title = $widgetContainer.find('.data-source-select-box').val();
				}else{
					$title = '';
				}

				error = self.drawTacho($widgetContainer.find('.tacho_preview_canvas'),{
					title : $title,
					min : $min,
					max : $max,
					warn : $warn,
					crit : $crit,
					current : $current,
					unit : $unit,
					id : 1
				});
				if(!error){
					$widgetContainer.find('.tacho_preview_canvas').css('display','block');
				}else{

				}
			}else{
				$widgetContainer.find('.tacho_preview_canvas').css('display','none');
			}

		});

		$(document).on('change','.tacho-select-box', function(e){
			var $selectBox = $(this),
				$widgetContainer = $(this).parents('.tacho-body'),
				current_id = self.getWidgetId($widgetContainer),
				$tachoSvg = $widgetContainer.find('.tacho');

			$widgetContainer.find('.dataSourceValues').css('display','none');
			$widgetContainer.find('.tacho_preview').css('display','none');
			$widgetContainer.find('.tacho2Warning').css('display','none');

			if(parseInt($selectBox.val()) !== 0){
				$.ajax({
					url: "/admin/dashboard/getServicePerfData/" + encodeURIComponent($selectBox.val())+".json",
					type: "POST",
					dataType: "json",
					error: function(){},
					success: function(){},
					complete: function(response){
						self.lastResponse = response;
						if(response.responseJSON !== false){
							var countJsonEntries = self.countJson(response.responseJSON);
							$widgetContainer.find('.tacho_save').prop('disabled',false);
							$widgetContainer.find('.tachoWarning').detach();
							if(countJsonEntries > 1){
								$widgetContainer.find('.data-source-select-box').html('');
								$widgetContainer.find('.data-source-select-box').append($('<option/>', {
										value: 0,
										text : ''
									}));
								$.each(response.responseJSON, function (index, value) {
									$widgetContainer.find('.data-source-select-box').append($('<option/>', {
										value: index,
										text : index
									}));

									$widgetContainer.find('.data-source-select-box').trigger('chosen:updated');
								});

								$widgetContainer.find('.data-source-select').css('display','block');
								$widgetContainer.find("[for=tachometerDataSourceSelect]").css('display','block');
								$widgetContainer.find("[for=tachometerDataSourceSelect]").next().css('display','block');
							}
							else{
								$widgetContainer.find('.data-source-select').html();
								$widgetContainer.find('.data-source-select').css('display','none');
								$widgetContainer.find('.dataSourceValues').css('display','none');

								var res = response.responseJSON,
									allKeys = Object.keys(res);

								$widgetContainer.find('.min').val(res[allKeys[0]].min);
								$widgetContainer.find('.max').val(res[allKeys[0]].max);
								$widgetContainer.find('.warn').val(res[allKeys[0]].warn);
								$widgetContainer.find('.crit').val(res[allKeys[0]].crit);
								$widgetContainer.find('.current').val(res[allKeys[0]].current);
								$widgetContainer.find('.unit').val(res[allKeys[0]].unit);
								$widgetContainer.find('.dataSourceValues').css('display','block');
								$widgetContainer.find('.tacho_preview').css('display','block');
							}
						}else{
							$widgetContainer.find('.data-source-select').append($('<div/>',{
								class : 'tachoWarning',
								style : 'padding-left:17px; color:#FF0000; padding-top: 60px;',
								text : 'No performance data for chosen service! Please select a different one.'
							}));
							//$widgetContainer.find('.data-source-select').html('No performance data for chosen service. Please select a different one.');
							$widgetContainer.find('.data-source-select').css('display','block');
							$widgetContainer.find("[for=tachometerDataSourceSelect]").css('display','none');
							$widgetContainer.find("[for=tachometerDataSourceSelect]").next().css('display','none');
							$widgetContainer.find('.tacho_save').prop('disabled',true);
						}

					}
				});
			}else{
				$widgetContainer.find('.data-source-select').css('display','none');
			}

		});

		var tachoIds = $('.tacho-body').each(function(i, elem){
			var current_id = self.getWidgetId($(elem)),
				$widgetContainer = $(elem).parents('.grid-stack-item'),
				$form = $(elem).find('form');

			if(allWidgetParameters){
				if(typeof(allWidgetParameters[7]) !== "undefined"){
					if(typeof(allWidgetParameters[7][current_id].serviceGone) === 'undefined'){
						$form.css('display','none');

						$form.next().html(allWidgetParameters[7][current_id].host_name+' | '+allWidgetParameters[7][current_id].service_name+' <i class="fa fa-cog "></i>');
						$form.next().css('display','block');

						self.drawTacho($(elem).find('.tacho'),{
							title : allWidgetParameters[7][current_id].data_source,
							min : allWidgetParameters[7][current_id].min,
							max : allWidgetParameters[7][current_id].max,
							warn : allWidgetParameters[7][current_id].warn,
							crit : allWidgetParameters[7][current_id].crit,
							current : allWidgetParameters[7][current_id].current,
							unit : allWidgetParameters[7][current_id].unit,
							id : current_id
						});
						$(elem).find('.tacho canvas').css('display','block');

						//Refresh Current of selected Service perfdata
						var checkInterval = allWidgetParameters[7][current_id].check_interval;

						checkInterval = checkInterval*1000;
						var intervalId = setInterval(function() {
							self.createUpdateInterval(
								elem,
								allWidgetParameters[7][current_id].service_id,
								allWidgetParameters[7][current_id].data_source,
								allWidgetParameters[7][current_id].min,
								allWidgetParameters[7][current_id].max,
								allWidgetParameters[7][current_id].warn,
								allWidgetParameters[7][current_id].crit,
								allWidgetParameters[7][current_id].unit
							);
						}, checkInterval);
						self.intervalIds[current_id] = intervalId;
					}else{
						$widgetContainer.find('.selectTachoService').append($('<div/>',{
							class : 'tacho2Warning',
							style : 'color:#FF0000; padding-top: 10px;',
							text : allWidgetParameters[7][current_id].serviceGone
						}));
					}
				}
			}
		});
	},

	/**
	 * @param {HTMLElement} elem Container with class "tacho-body".
	 */
	createUpdateInterval: function(elem, serviceId, dataSource, min, max, warn, crit, unit){
		var self = this;
		$.ajax({
			url: "/admin/dashboard/getServicePerfData/" + encodeURIComponent(serviceId)+".json",
			type: "POST",
			dataType: "json",
			error: function(){},
			success: function(){},
			complete: function(response){
				var my_current,
					my_title,
					key;
				if(response.responseJSON[dataSource]){
					my_current = response.responseJSON[dataSource].current;
					my_title = dataSource;
				}else{
					for(key in response.responseJSON){
						if(response.responseJSON.hasOwnProperty(key)) {
							my_current = response.responseJSON[key].current;
							my_title = key;
						}
					}
				}
				self.drawTacho($(elem).find('.tacho'),{
					title : my_title,
					min : min,
					max : max,
					warn : warn,
					crit : crit,
					current : my_current,
					unit : unit,
					id : self.getWidgetId($(elem))
				});
				$(elem).find('.tacho canvas').css('display','block');
			}
		});
	},

	countJson: function(obj) {
		return Object.keys(obj).length;
	},

	getData: function(f){
		var data = {
			'serviceId' : f.find('select.tacho-select-box').val(),
			'dataSource' : f.find('select.data-source-select-box').val(),
			'min' : f.find('.min').val(),
			'max' : f.find('.max').val(),
			'warn' : f.find('.warn').val(),
			'crit' : f.find('.crit').val(),
		};
		return data;
	},

	/**
	 * @param {jQuery} $elem
	 * @return {number}
	 */
	getWidgetId: function($elem){
		var currentId = $elem.parents('.grid-stack-item').attr('widget-id');
		if(currentId === null){
			currentId = 0;
		}
		return currentId;
	},


	drawTacho:function($svgContainerId, opt){

		var error = false,
			max = parseInt(opt.max, 10),
			crit = parseInt(opt.crit, 10);

		if( (isNaN(max) || isNaN(crit)) || (!isNaN(max) && !isNaN(crit) && max <= crit) ){
			$svgContainerId.parent().find('input.max').css({
				'border':'1px solid #a94442',
				'color':'#a94442'
			});
			$svgContainerId.parent().find('input.max').parent().prev().css('color','#a94442');
			$('.tacho-error-message-max').html('');
			$('<div class=\"tacho-error-message-max\">Value of \'Maximum\' has to be bigger then \'Critical\'.</div>').insertAfter($svgContainerId.parent().find('input.max').parent().parent());
			error = true;

			return(error);
		}
		if(!opt.min){
			$svgContainerId.parent().find('input.min').css({
				'border':'1px solid #a94442',
				'color':'#a94442'
			});
			$svgContainerId.parent().find('input.min').parent().prev().css('color','#a94442');
			$('.tacho-error-message-min').html('');
			$('<div class=\"tacho-error-message-min\">\'Minimum\' can\'t be empty.</div>').insertAfter($svgContainerId.parent().find('input.min').parent().parent());
			error = true;
			return(error);
		}
		var ary = opt.crit.toString().split('.');
		if(ary[0]){
			vorkomma = ary[0].length;
		}

		if(ary[1]){
			nachkomma = ary[1].length;
		}else{
			nachkomma = 3;
		}

		$svgContainerId.css('background-color','');
		$svgContainerId.find('canvas').attr('id', 'canvas'+opt.id);

		if(!error){
			$svgContainerId.parent().find('input.max').css({
				'border':'1px solid #a9a9a9',
				'color':'#333'
			});
			$svgContainerId.parent().find('input.max').parent().prev().css('color','#333');
			$svgContainerId.parent().find('.tacho-error-message-max').css('display','none');

			$svgContainerId.parent().find('input.min').css({
				'border':'1px solid #a9a9a9',
				'color':'#333'
			});
			$svgContainerId.parent().find('input.min').parent().prev().css('color','#333');
			$svgContainerId.parent().find('.tacho-error-message-min').css('display','none');
		}

		var myHighlights = [
			{ from : opt.min, to : opt.warn, color : '#449D44' },
			{ from : opt.warn, to : opt.crit, color : '#DF8F1D' },
			{ from : opt.crit, to : opt.max, color : '#C9302C' }
		]

		//HDD workload
		if(parseInt(opt.warn, 10) > parseInt(opt.crit, 10)){
			myHighlights = [
				{ from : opt.min, to : opt.crit, color : '#C9302C' },
				{ from : opt.crit, to : opt.warn, color : '#DF8F1D' },
				{ from : opt.warn, to : opt.max, color : '#449D44' }
			]
		}

		var gauge = new Gauge({
			renderTo : 'canvas'+opt.id,

			height : 200,
			minValue : opt.min,
			maxValue : opt.max,
			units : opt.unit,
			strokeTicks: true,
			title : opt.title,
			glow : true,
			valueFormat : { int : vorkomma, dec : nachkomma },
			majorTicksFormat : {dec : ((opt.max-opt.min)<10)?1:0 },

			highlights  : myHighlights,
			colors : {
				needle : { start : 'black', end : '#333' },
				plate : 'white',
				title : '#333',
				units : '#333',
				majorTicks : '#333',
				minorTicks : '#333',
				numbers : '#333'
			},
			animation : {
				delay : 25,
				duration: 700,
				fn : 'cycle'
			}
		});

		gauge.onready = function() {
			gauge.setValue( opt.current);
		};
		gauge.draw();
	}

})
