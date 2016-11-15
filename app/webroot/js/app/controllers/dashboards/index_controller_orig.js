'use strict';
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

App.Controllers.DashboardZZZZZZIndexController = Frontend.AppController.extend({
	$gridstack: null,
	tabId: null,
	allTabs: null,
	allWidgetParameters: null,
	tabRotationInterval: null,
	tabIntervalId: null,
	sourceHasChanged: null,

	/**
	 * @type {Array}
	 */
	components: [
		'Rrd',
		'Utils',
		'WidgetTrafficLight',
		'WidgetTacho',
		'WidgetServiceStatusList',
		'WidgetHostStatusList',
		'WidgetMap',
		'Ajaxloader',
		'WidgetBrowser',
		'WidgetNotice',
		'WidgetGraphgenerator',
		'Overlay',
		'BootstrapModal',
		'Uuid',

	],



	/**
	 * @constructor
	 * @return {void}
	 */
	_initialize: function(){
		this.Ajaxloader.setup();
		this.tabId = this.getVar('tabId');
		this.allTabs = this.getVar('tabs');

		if(this.getVar('MapModule')){
			this.components.push('Gadget');
			this.components.push('Line');
			this.__initComponents();
		}

		this.tabRotationInterval = this.getVar('tabRotationInterval');
		this.allWidgetParameters = this.getVar('allWidgetParameters');
		this.sourceHasChanged = this.getVar('sourceHasChanged');
		this.manualUpdate = this.getVar('manualUpdate');
		this.alwaysUpdate = this.getVar('alwaysUpdate');

		var self = this;

		this.tabRotationInterval = this.tabRotationInterval*1000;

		//Change order of tabs
		$(function() {
			var tabs = [];
			$( "#widget-tab-1" ).sortable({
				update: function() {
					$(this).find('.dashboardTab').each(function(i, elem){
						if($(elem).hasClass('active')){
							tabs[i] = {
								'id' : $(elem).data('id'),
								'name' : trim($(elem).find('span.text').text()),
								'position' : i,
							};
						}else{
							tabs[i] = {
								'id' : $(elem).data('id'),
								'name' : trim($(elem).first('a').text()),
								'position' : i,
							};
						}
					});
					$.ajax({
						url: "/admin/dashboard/saveTabOrder/.json",
						type: "POST",
						cache: false,
						dataType: "json",
						data: {data: tabs},
						error: function(){},
						success: function(){},
						complete: function(response){

						}
					});
				},
				placeholder: 'tabTargetDestination'
			});
		});

		var nextTab = function(){
			var nextId,
				url = "/admin/dashboard/index/",
				$activeTab = $('[data-id='+self.tabId+']');

			if($activeTab.next().data('id') === null){
				nextId = $activeTab.parent().children().first().data('id');
			}else{
				nextId = $activeTab.next().data('id');
			}

			url = url+nextId
			$(location).attr('href',url);
		};

		if(this.tabRotationInterval > 0){
			this.tabRotationInterval = this.tabRotationInterval - 2000;
			var intervalId = setInterval(function() {
				$('.rotateTabs').find('.fa-refresh').addClass('fa-spin');
				setTimeout(function(){
					nextTab();
				},2000);
			}, this.tabRotationInterval);
			self.tabIntervalId = intervalId;
		}

		if(parseInt(this.sourceHasChanged) === 1 && parseInt(this.alwaysUpdate) === 0){
			bootbox.dialog({
				title: '<i class="fa fa-exclamation"></i> Update available for this tab.',
				message: '<h4>Do you want to run a update?</h4>' +
						'<div class="checkbox"><label><input type="checkbox" id=neverAsk>&nbsp;Do not ask again for this tab</label></div>',
				buttons: {
					yes: {
						label: 'Yes',
						className: "btn-sm btn-success",
						callback: function() {
							//deleteAllWidgetsFromTab and reclone them from source
							$.ajax({
								url: "/admin/dashboard/deleteAllWidgetsFromTab/"+self.tabId+".json",
								type: "POST",
								cache: false,
								dataType: "json",
								data: {data:  $('#neverAsk').prop('checked')},
								error: function(){},
								success: function(){},
								complete: function(response){
									if(response.responseJSON === 'true'){
										$.ajax({
											url: "/admin/dashboard/setTabRefresh/"+self.tabId+"/"+2+".json",
											type: "POST",
											dataType: "json",
											error: function(){},
											success: function(){},
											complete: function(response){
												location.reload();
											}.bind(self)
										});
									}
								}.bind(self)
							});

						}
					},
					no: {
						label: "No",
						className: "btn-sm",
						callback: function() {
							if($('#neverAsk').prop('checked')){
								$.ajax({
									url: "/admin/dashboard/setTabRefresh/"+self.tabId+".json",
									type: "POST",
									cache: false,
									dataType: "json",
									error: function(){},
									success: function(){},
									complete: function(response){

									}.bind(self)
								});
							}
						}
					}
				}
			});
		}
		//Alwayys Update
		if(parseInt(this.sourceHasChanged) === 1 && parseInt(this.alwaysUpdate) === 1){
			$.ajax({
				url: "/admin/dashboard/deleteAllWidgetsFromTab/"+self.tabId+".json",
				type: "POST",
				cache: false,
				dataType: "json",
				error: function(){},
				success: function(){},
				complete: function(response){
					location.reload();
				}.bind(self)
			});
		}

		if(parseInt(this.manualUpdate) === 1){
			$('a.refreshTab').css('display','block');
		}

		$('a.refreshTab').on('click', function(){
			$.ajax({
				url: "/admin/dashboard/deleteAllWidgetsFromTab/"+self.tabId+".json",
				type: "POST",
				cache: false,
				dataType: "json",
				error: function(){},
				success: function(){},
				complete: function(response){
					location.reload();
				}.bind(self)
			});
		});

		$(document).on('click', '.dashboardTab', function(){
			if(!$(this).hasClass('dropdown-toggle')){
				var tabId = $(this).data('id'),
					url = "/admin/dashboard/index/"+tabId;

				$(location).attr('href',url);
			}

		});

		$('.newTabContainer').on('click', function(){
			if(!$(this).find('.btn-group').hasClass('open')){
				if(self.tabIntervalId){
					clearInterval(self.tabIntervalId);
				}
			}else{
				if(self.tabRotationInterval > 0){
					var intervalId = setInterval(function() {
						nextTab();
					}, self.tabRotationInterval);
					self.tabIntervalId = intervalId;
				}
			}
		});

		$('.newTabsList').find('li.addNewTab, input').on('click', function(){
			$(this).parents('.btn-group').removeClass('open');
		});

		$('.addNewTab').on('click', function(){
			var position = parseInt($('li.dashboardTab').last().attr('position')),
				newPos = position+1;

			if(self.tabIntervalId){
				clearInterval(self.tabIntervalId);
			}
			bootbox.prompt({
				title: 'Name Tab',
				callback: function(result){
					if(!result){
						if(self.tabRotationInterval > 0){
							var intervalId = setInterval(function() {
								nextTab();
							}, self.tabRotationInterval);
							self.tabIntervalId = intervalId;
						}
						return;
					}
					result = trim(result);
					if(result === ''){
						return;
					}
					$.ajax({
						url: "/admin/dashboard/addTab/"+result+"/"+newPos+".json",
						type: "POST",
						cache: false,
						dataType: "json",
						error: function(){},
						success: function(){},
						complete: function(response){
							var $newTab = '<li data-id="'+response.responseJSON['DashboardTab'].id+'" data-name="'+result+'" position="'+newPos+'"><a href="/admin/Dashboard/index/'+response.responseJSON['DashboardTab'].id+'" class="dashboardTab">'+result+'</a></li>';
							$('li.dashboardTab').last().after($newTab);
							if(this.tabRotationInterval > 0){
								var intervalId = setInterval(function() {
									nextTab();
								}, this.tabRotationInterval);
								self.tabIntervalId = intervalId;
							}
						}.bind(self)
					});
				}
			});
		});

		$('.sharedTabSave').on('click', function(e){
			var position = parseInt($('li.dashboardTab').last().attr('position')),
				newPos = position+1,
				name = trim($('select.selectSharedTab option:selected').text()),
				sourceTabId = $('select.selectSharedTab').val();

			e.preventDefault();
			$.ajax({
				url: "/admin/dashboard/addSharedTab/"+name+"/"+newPos+"/"+sourceTabId+".json",
				type: "POST",
				cache: false,
				dataType: "json",
				error: function(){},
				success: function(){},
				complete: function(response){
					var $newTab = '<li data-id="'+response.responseJSON['DashboardTab'].id+'" data-name="'+name+'" position="'+newPos+'"><a href="/admin/Dashboard/index/'+response.responseJSON['DashboardTab'].id+'" class="dashboardTab">'+name+'</a></li>';
					$('li.dashboardTab').last().after($newTab);
					if(this.tabRotationInterval > 0){
						var intervalId = setInterval(function() {
							nextTab();
						}, this.tabRotationInterval);
						self.tabIntervalId = intervalId;
					}
				}.bind(self)
			});
		});


		$('a.renameTab').on('click', function(){
			var $tab = $(this).parents('li.dropdown-toggle'),
				id = $tab.data('id'),
				name = $tab.data('name');

			if(self.tabIntervalId){
				clearInterval(self.tabIntervalId);
			}

			bootbox.prompt({
				title: 'Rename Tab',
				value: name,
				callback: function(result){
					if(!result){
						if(self.tabRotationInterval > 0){
							var intervalId = setInterval(function() {
								nextTab();
							}, self.tabRotationInterval);
							self.tabIntervalId = intervalId;
						}
						return;
					}
					result = trim(result);
					if(result === '' || result === name){
						return;
					}

					$tab.data('name', result);
					$tab.find('.text').text(result);

					$.ajax({
						url: "/admin/dashboard/renameTab/"+id+"/"+result+".json",
						type: "POST",
						cache: false,
						dataType: "json",
						error: function(){},
						success: function(){},
						complete: function(response){
							if(this.tabRotationInterval > 0){
								var intervalId = setInterval(function() {
									nextTab();
								}, this.tabRotationInterval);
								self.tabIntervalId = intervalId;
							}
						}.bind(self)
					});
				}
			});
		});

		$(document).on('click', '.data-widget-colorbutton', function(e){
			e.stopPropagation();
		});

		$('.newTabsList').on('click', function(e){
			e.stopPropagation();
		});

		$('a.shareTab').on('click', function(){
			shareTab('start', this);
		});

		$('a.stopShareTab').on('click', function(){
			shareTab('stop', this);
		});

		var shareTab = function(what, obj){
			var self2 = obj,
				$self = $(self2),
				$tab = $self.parents('li.dropdown-toggle'),
				id = $tab.data('id'),
				name = $tab.data('name'),
				what = what;

			if(self.tabIntervalId){
				clearInterval(self.tabIntervalId);
			}

			$self.css('display','none');

			if(what === 'start'){
				var share = 1,
					message = 'Start sharing Tab \"'+name+'\" ?',
					newLink = '<a class="stopShareTab" href="javascript:void(0)"><i class="fa fa-share-alt"></i> Stop sharing</a>';

				$self.after(newLink);
				$self.parents().find('.stopShareTab').bind("click", function(){
					shareTab('stop', this);
				});
			}else{
				var share = 0,
					message = 'Stop sharing Tab \"'+name+'\" ?',
					newLink = '<a class="shareTab" href="javascript:void(0)"><i class="fa fa-share-alt"></i> Start sharing</a>';

				$self.before(newLink);
				$self.parents().find('.shareTab').bind("click", function(){
					shareTab('start', this);
				});
			}

			bootbox.confirm({
				message: message,
				callback: function(result){
					if(!result){
						if(self.tabRotationInterval > 0){
							var intervalId = setInterval(function() {
								nextTab();
							}, self.tabRotationInterval);
							self.tabIntervalId = intervalId;
						}
						return;
					}
					result = trim(result);
					if(result === '' || result === name){
						return;
					}

					$.ajax({
						url: "/admin/dashboard/shareTab/"+id+"/"+name+"/"+share+".json",
						type: "POST",
						cache: false,
						dataType: "json",
						error: function(){},
						success: function(){},
						complete: function(response){
							if(this.tabRotationInterval > 0){
								var intervalId = setInterval(function() {
									nextTab();
								}, this.tabRotationInterval);
								self.tabIntervalId = intervalId;
							}
						}.bind(self)
					});
				}
			});
		}

		$('a.deleteTab').on('click', function(){
			var $activeTab = $(this).parents('li.dropdown-toggle'),
				activeTabId = $activeTab.data('id'),
				activeTabName = $activeTab.data('name');

			if(self.tabIntervalId){
				clearInterval(self.tabIntervalId);
			}

			bootbox.confirm('Do you really want to delete the tab "<b>' + activeTabName + '</b>" ?',function(result){
				if(!result){
					if(self.tabRotationInterval > 0){
						var intervalId = setInterval(function() {
							nextTab();
						}, self.tabRotationInterval);
						self.tabIntervalId = intervalId;
					}
					return;
				}
				$.ajax({
					url: "/admin/dashboard/deleteTab/"+activeTabId+".json",
					type: "POST",
					cache: false,
					dataType: "json",
					error: function(){},
					success: function(){},
					complete: function(response){
						document.location.pathname = '/admin/Dashboard/index';
					}
				});
			});
		});
		this.lang = [];
		this.lang[1] = 'minutes';
		this.lang[2] = 'seconds';
		this.lang[3] = 'and';

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

		// Initialize the right value for the hidden input field.
		var $hostNotificationIntervalField = $('#HostNotificationinterval');

		var $slider = $('.rotateTabs input.slider');
		$slider.slider({ tooltip: 'hide' });
		$slider.slider('on', 'slide', onSlideStop);
		$slider.slider('on', 'slideStop', onSlideStop);



		if(this.allWidgetParameters){
			this.allWidgetParameters['tabRotation'] = {
				tabIntervalId: self.tabIntervalId,
				tabRotationInterval: self.tabRotationInterval,
			}
			this.allWidgetParameters['nextTab'] = {
				nextTab: nextTab,
			}
		}

		//console.log(this.allWidgetParameters);

		$slider.on('slideStop', function(){
			$.ajax({
				url: "/admin/dashboard/saveTabRotationTime/"+$(this).val()+".json",
				type: "POST",
				cache: false,
				dataType: "json",
				error: function(){},
				success: function(){},
				complete: function(response){

				}
			});

			this.tabRotationInterval = $(this).val()*1000;
			if(this.tabRotationInterval > 0){
				this.tabRotationInterval = this.tabRotationInterval - 2000;
				if(self.tabIntervalId){
					clearInterval(self.tabIntervalId);
					var intervalId = setInterval(function() {
						$('.rotateTabs').find('.fa-refresh').addClass('fa-spin');
						setTimeout(function(){
							nextTab();
						},2000);
					}, this.tabRotationInterval);
					self.tabIntervalId = intervalId;
				}else{
					var intervalId = setInterval(function() {
						$('.rotateTabs').find('.fa-refresh').addClass('fa-spin');
						setTimeout(function(){
							nextTab();
						},2000);
					}, this.tabRotationInterval);
					self.tabIntervalId = intervalId;
				}
			}else{
				$('.rotateTabs').find('.fa-refresh').removeClass('fa-spin');
				if(self.tabIntervalId){
					clearInterval(self.tabIntervalId);
				}
			}

		});

		var onChangeSliderInput = function(){
			var $this = $(this);
			$('#' + $this.attr('slider-for'))
				.slider('setValue', parseInt($this.val(), 10))
				.val($this.val())
				.attr('value', $this.val())
				.trigger('change');
			$hostNotificationIntervalField.trigger('change');
			var min = parseInt($this.val() / 60, 10);
			var sec = parseInt($this.val() % 60, 10);
			$($this.attr('human')).html(min + " " + self.lang[1] + " " + self.lang[3] + " " + sec + " " + self.lang[2]);
		};
		$('.slider-input').on('change.slider', onChangeSliderInput);
		$('.slider-input').on('keyup', onChangeSliderInput);

		this.$gridstack = $('.grid-stack');
		var options = {
			cell_height: 10,
			cell_width: 10,
			 draggable: {
				handle: '.jarviswidget header[role="heading"]',
			},
			always_show_resize_handle: true,
			vertical_margin: 10
		};
		this.$gridstack.gridstack(options);

		this.$gridstack.on('dragstop', function (event, ui) {
			var element = event.target;
			self.saveWidgetPositionsAndSizes($(element).attr('widget-id'));
		});

		this.$gridstack.on('resizestop', function (event, ui) {
			var grid = self;
			var element = event.target;
			self.saveWidgetPositionsAndSizes($(element).attr('widget-id'));
		});
		$(document).on('click', '.color-bar-picker', function(e){
			var $selectedItem = $(this),
				chosenColor = $selectedItem.attr('data-color'),
				$header = $selectedItem.parents('header'),
				$widget = $(this).parents('.grid-stack-item');
			$header.css('background-color',chosenColor);
			self.editWidgetHeader($widget,'editColor');
		});
		$(document).on('click', '.jarviswidget-edit-btn', function(){
			var $widget = $(this).parents('.grid-stack-item');
			self.editWidgetHeader($widget,'editTitle');

		});
		$(document).on('click', '.jarviswidget-delete-btn', function(){
			var $widget = $(this).parents('.grid-stack-item'),
				title = $(this).parents('.grid-stack-item').find('header h2').text(),
				grid = self.$gridstack.data('gridstack');

			bootbox.confirm('Do you really want to delete the Widget "<b>' + title + '</b>" ?',function(result){
				if(!result){
					return;
				}
				$.ajax({
					url: "/admin/dashboard/deleteWidget/"+$widget.attr('widget-id')+".json",
					type: "POST",
					cache: false,
					dataType: "json",
					error: function(){},
					success: function(){},
					complete: function(response){

					}
				});
				grid.remove_widget($widget);
			});

		});

		$(document).on('click', '.toggleDetailsForPiechart', function(){
			if($(this).find('i').hasClass('fa-angle-down')){
				$(this).find('i').removeClass('fa-angle-down');
				$(this).find('i').addClass('fa-angle-up');
				$(this).parents('.widget-body').find('.detailsForPiechart').fadeIn();
			}else{
				$(this).find('i').removeClass('fa-angle-up');
				$(this).find('i').addClass('fa-angle-down');
				$(this).parents('.widget-body').find('.detailsForPiechart').fadeOut();
			}

		});

		$('.addWidget').on('click', function(){
			var grid = self.$gridstack.data('gridstack'),
				type = $(this).attr('widget-type');

			//console.log(type, self.tabId);
			if(type !== 'standard'){
				$.ajax({
					url: "/admin/dashboard/addWidget/"+type+"/"+self.tabId,
					type: "POST",
					cache: false,
					dataType: "json",
					error: function(){},
					success: function(){},
					complete: function(response){
						var $newWidget = $(response.responseText),
							width = $newWidget.attr('data-gs-width'),
							height = $newWidget.attr('data-gs-height');
						grid.add_widget($.parseHTML(response.responseText),0,0,width,height,false);
						self.saveWidgetPositionsAndSizes($newWidget.attr('widget-id'));
						$('.chosen').chosen({
							placeholder_text_single: 'Please choose',
							placeholder_text_multiple: 'Please choose',
							allow_single_deselect: true,
							search_contains: true,
							enable_split_word_search: true,
							width: '100%'
						});

						if(parseInt(type) === 6 || parseInt(type) === 7 || parseInt(type) >= 9){
							if(self.tabIntervalId){
								clearInterval(self.tabIntervalId);
							}
						}
					}.bind(self)
				});
			}else{
				$.ajax({
					url: "/admin/dashboard/deleteAllWidgetsFromTab/"+self.tabId,
					type: "POST",
					cache: false,
					dataType: "json",
					error: function(){},
					success: function(){},
					complete: function(response){
						grid.remove_all();
						$.ajax({
							url: "/admin/dashboard/addDefaultWidgets/"+self.tabId,
							type: "POST",
							dataType: "json",
							error: function(){},
							success: function(){},
							complete: function(response){
								location.reload();
							}
						});
					}.bind(self)
				});
			}

		})

		var saveWidgetData = function(postData){
			$.ajax({
				url: "/admin/dashboard/saveWidget.json",
				type: "POST",
				cache: false,
				data: {data: postData},
				dataType: "json",
				error: function(){},
				success: function(){},
				complete: function(response){
					//self.Ajaxloader.hide();
				}
			});
		};

		var loadWidgetData = function(id, callback){
			$.ajax({
				url: "/admin/dashboard/loadWidgetConfiguration/"+id+".json",
				type: "POST",
				cache: false,
				dataType: "json",
				error: function(){},
				success: function(data){
					callback(data);
				},
				complete: function(response){
					//self.Ajaxloader.hide();
				}
			});
		};

		var fetchGraphData = function(id){
			$.ajax({
				url: "/admin/dashboard/fetchGraphData/"+id+".json",
				type: "POST",
				cache: false,
				dataType: "json",
				error: function(){},
				success: function(){},
				complete: function(response){
					//self.Ajaxloader.hide();
				}
			});
		};

		var myOverlay = this.Overlay,
			myRrd = this.Rrd,
			myAjaxloader = this.Ajaxloader,
			myBootstrapModal = this.BootstrapModal,
			myTime = this.Time,
			myUuid = this.Uuid,
			myGadget = this.Gadget,
			myLine = this.Line;

		self.WidgetTrafficLight.setup({
			saveWidgetData: saveWidgetData,
			loadWidgetData: loadWidgetData,
		}, this.allWidgetParameters);

		self.WidgetTacho.setup({
			saveWidgetData: saveWidgetData,
			loadWidgetData: loadWidgetData,
		}, this.allWidgetParameters);

		self.WidgetServiceStatusList.setup({
			saveWidgetData: saveWidgetData,
			loadWidgetData: loadWidgetData,
		}, this.allWidgetParameters);

		self.WidgetHostStatusList.setup({
			saveWidgetData: saveWidgetData,
			loadWidgetData: loadWidgetData,
		}, this.allWidgetParameters);

		//console.log(this.allWidgetParameters);
		self.WidgetMap.setup({
			saveWidgetData: saveWidgetData,
			loadWidgetData: loadWidgetData,
			Uuid: myUuid,
			Gadget: myGadget,
			Line: myLine,
		}, this.allWidgetParameters);

		self.WidgetBrowser.setup({
			saveWidgetData: saveWidgetData,
			loadWidgetData: loadWidgetData,
		}, this.allWidgetParameters);

		self.WidgetNotice.setup({
			saveWidgetData: saveWidgetData,
			loadWidgetData: loadWidgetData,
		}, this.allWidgetParameters);

		self.WidgetGraphgenerator.setup({
			saveWidgetData: saveWidgetData,
			loadWidgetData: loadWidgetData,
			Overlay: myOverlay,
			Rrd: myRrd,
			BootstrapModal: myBootstrapModal,
			Ajaxloader: myAjaxloader,
			//fetchGraphData: fetchGraphData,
			Time: myTime,
		}, this.allWidgetParameters);

	},

	saveWidgetPositionsAndSizes: function (idOfDraggedWidget, setStandard){
		var postData = [],
			setStandard = setStandard || 0;
		$('.grid-stack-item').each(function(i, elem){
			var $elem = $(elem),
				current_id = $elem.attr('widget-id'),
				row = $elem.attr('data-gs-x'),
				col = $elem.attr('data-gs-y'),
				width = $elem.attr('data-gs-width'),
				height = $elem.attr('data-gs-height');

			if(idOfDraggedWidget != current_id && typeof current_id !== "undefined"){
				var widget = {
					'id' : current_id,
					'col' : col,
					'row' : row,
					'width' : width,
					'height' : height,
				};
				postData.push(widget);
			}
			if(typeof current_id === "undefined" && setStandard === 1){
				var widget = {
					'id' : idOfDraggedWidget,
					'col' : 24,
					'row' : 5,
					'width' : 5,
					'height' : 13,
				};
				postData.push(widget);
			}
			if(typeof current_id === "undefined" && setStandard === 0){
				var widget = {
					'id' : idOfDraggedWidget,
					'col' : col,
					'row' : row,
					'width' : width,
					'height' : height,
				};
				postData.push(widget);
			}

		});

		$.ajax({
			url: "/admin/dashboard/saveWidgetPositionsAndSizes.json",
			type: "POST",
			cache: false,
			data: {data: postData},
			dataType: "json",
			error: function(){},
			success: function(){},
			complete: function(response){

			}
		});
	},

	editWidgetHeader: function($widget, option){
		var self = this,
			$widgetHead = $widget.find('header.ui-draggable-handle').children('h2'),
			color = $widget.find('header.ui-draggable-handle').css('background-color'),
			WidgetId = $widget.attr('widget-id');

		if(this.tabIntervalId){
			clearInterval(this.tabIntervalId);
		}

		if(option === 'editTitle'){
			bootbox.prompt({
				title: 'Rename Widget',
				value: $widgetHead.text(),
				callback: function(result){
					if(!result){
						if(this.tabRotationInterval > 0){
							var intervalId = setInterval(function() {
								self.nextTab();
							}, this.tabRotationInterval);
							this.tabIntervalId = intervalId;
						}
						return;
					}
					result = trim(result);
					if(result.length === '' || result === name){
						return;
					}

					$widgetHead.text(result);

					var widget = {
						'id' : WidgetId,
						'title' : result,
					};
					$.ajax({
						url: "/admin/dashboard/saveWidget.json",
						type: "POST",
						cache: false,
						data: {data: widget},
						dataType: "json",
						error: function(){},
						success: function(){},
						complete: function(response){

						}
					});
					if(this.tabRotationInterval > 0){
						var intervalId = setInterval(function() {
							self.nextTab();
						}, this.tabRotationInterval);
						this.tabIntervalId = intervalId;
					}
				}.bind(this)
			});
		}
		if(option === 'editColor'){
			var widget = {
				'id' : WidgetId,
				'color' : color,
			};
			$.ajax({
				url: "/admin/dashboard/saveWidget.json",
				type: "POST",
				cache: false,
				data: {data: widget},
				dataType: "json",
				error: function(){},
				success: function(){},
				complete: function(response){
				}
			});
			if(this.tabRotationInterval > 0){
				var intervalId = setInterval(function() {
					self.nextTab();
				}, this.tabRotationInterval);
				this.tabIntervalId = intervalId;
			}
		}
	}
});
