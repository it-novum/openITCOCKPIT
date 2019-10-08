 /*!
 * jQuery SmartPanels v1.0.0
 *
 * Copyright 2019, 2020 SmartAdmin WebApp
 * Released under Marketplace License (see your license details for usage)
 *
 * Publish Date: 2018-01-01T17:42Z
 */

(function ($, window, document, undefined) {

	//"use strict"; 

	var pluginName = 'smartPanel';

	/**
	 * Check for touch support and set right click events.
	 **/
	/*var clickEvent = (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch ? 
		'clickEvent' : 'click') + '.' + pluginName;*/

	var clickEvent;

	if (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
		clickEvent = 'click tap';
	} else {
		clickEvent = 'click';
	}

	function Plugin(element, options) {
		/**
		 * Variables.
		 **/
		this.obj = $(element);
		this.o = $.extend({}, $.fn[pluginName].defaults, options);
		this.objId = this.obj.attr('id');
		this.panel = this.obj.find(this.o.panels);
		this.storage = {enabled: this.o.localStorage};
		this.initialized = false;
		this.init();
	}

	Plugin.prototype = {

		/**
		 * Function for the indicator image.
		 *
		 * @param:
		 **/
		_runPanelLoader: function (elm) {
			var self = this;

			if (self.o.localStorage === true) {
				elm.closest(self.o.panels)
				.find('.panel-saving')
				.stop(true, true)
				.fadeIn(100)
				.delay(600)
				.fadeOut(100);
			}
		},

		_loadKeys : function () {
			
			var self = this;
			var panel_url = self.o.pageKey || location.pathname;

			self.storage.keySettings = 'smartPanel_settings_' + panel_url + '_' + self.objId;
			self.storage.keyPosition = 'smartPanel_position_' + panel_url + '_' + self.objId;
		},
 
		/**
		 * Save all settings to the localStorage.
		 *
		 * @param:
		 **/
		_savePanelSettings: function () {

			var self = this;
			var storage = self.storage;

			self._loadKeys();

			var storeSettings = self.obj.find(self.o.panels)
				.map(function () {
					var storeSettingsStr = {};
					storeSettingsStr.id = $(this)
						.attr('id');
					storeSettingsStr.style = $(this)
						.attr('data-panel-attstyle');
					storeSettingsStr.locked = ($(this)
						.hasClass('panel-locked') ? 1 : 0);
					storeSettingsStr.collapsed = ($(this)
						.hasClass('panel-collapsed') ? 1 : 0);
					return storeSettingsStr;
				}).get();

			var storeSettingsObj = JSON.stringify({
				'panel': storeSettings
			});

			/* Place it in the storage(only if needed) */
			if (storage.enabled && storage.getKeySettings != storeSettingsObj) {
				localStorage.setItem(storage.keySettings, storeSettingsObj);
				storage.getKeySettings = storeSettingsObj;

				//if (myapp_config.debugState)
					//console.log("storeSettingsObj:" + storeSettingsObj)
			}

			/**
			 * Run the callback function.
			 **/
			
			if (typeof self.o.onSave == 'function') {
				self.o.onSave.call(this, null, storeSettingsObj, storage.keySettings);

				if (myapp_config.debugState)
					console.log("keySettings: " + storage.keySettings)
			}
		},

		/**
		 * Save positions to the localStorage.
		 *
		 * @param:
		 **/
		_savePanelPosition: function () {

			var self = this;
			var storage = self.storage;

			self._loadKeys();

			var mainArr = self.obj.find(self.o.grid + '.sortable-grid')
				.map(function () {
					var subArr = $(this)
						.children(self.o.panels)
						.map(function () {
							return {
								'id': $(this).attr('id')
							};
						}).get();
					return {
						'section': subArr
					};
				}).get();

			var storePositionObj = JSON.stringify({
				'grid': mainArr
			});

			/* Place it in the storage(only if needed) */
			if (storage.enabled && storage.getKeyPosition != storePositionObj) {
				localStorage.setItem(storage.keyPosition, storePositionObj);
				storage.getKeyPosition = storePositionObj
			}

			/**
			 * Run the callback function.
			 **/
			if (typeof self.o.onSave == 'function') {
				self.o.onSave.call(this, storePositionObj, storage.keyPosition);
			}
		},

		/**
		 * Code that we run at the start.
		 *
		 * @param:
		 **/
		init: function () {

			var self = this;
			
			if (self.initialized) return;

			self._initStorage(self.storage);

			/**
			 * Force users to use an id(it's needed for the local storage).
			 **/
			if (!$('#' + self.objId)
				.length) {

				//alert('Your panel ID is missing!');
               if (typeof bootbox  != 'undefined') {
               		bootbox.alert("Your panel ID is missing!");
               } else {
               		alert('Your panel ID is missing!');
               }

			}

			/**
			 * This will add an extra class that we use to store the
			 * panels in the right order.(savety)
			 **/

			$(self.o.grid)
				.each(function () {
					if ($(this)
						.find(self.o.panels)
						.length) {
						$(this)
							.addClass('sortable-grid');
					}
				});


			/**
			 * SET POSITION PANEL
			 **/

			/**
			 * Run if data is present.
			 **/
			if (self.storage.enabled && self.storage.getKeyPosition) {

				var jsonPosition = JSON.parse(self.storage.getKeyPosition);

				/**
				 * Loop the data, and put every panels on the right place.
				 **/
				for (var key in jsonPosition.grid) {
					var changeOrder = self.obj.find(self.o.grid + '.sortable-grid')
						.eq(key);
					for (var key2 in jsonPosition.grid[key].section) {
						changeOrder.append($('#' + jsonPosition.grid[key].section[key2].id));
					}
				}

			}

			/**
			 * SET SETTINGS PANEL
			 **/

			/**
			 * Run if data is present.
			 **/
			if (self.storage.enabled && self.storage.getKeySettings) {

				var jsonSettings = JSON.parse(self.storage.getKeySettings);

				if (myapp_config.debugState)
					console.log("Panel settings loaded: " + self.storage.getKeySettings)

				/**
				 * Loop the data and hide/show the panels and set the inputs in
				 * panel to checked(if hidden) and add an indicator class to the div.
				 * Loop all labels and update the panel titles.
				 **/
				for (var key in jsonSettings.panel) {
					var panelId = $('#' + jsonSettings.panel[key].id);

					/**
					 * Set a style(if present).
					 **/
					if (jsonSettings.panel[key].style) {
						panelId.attr('data-panel-attstyle', '' + jsonSettings.panel[key].style + '')
							.children('.panel-hdr')
							.removeClassPrefix('bg-')
							.addClass(jsonSettings.panel[key].style);
					}

					/**
					 * Toggle content panel.
					 **/
					if (jsonSettings.panel[key].collapsed == 1) {
						panelId.addClass('panel-collapsed')
							.children('.panel-container').addClass('collapse').removeClass('show');
					}

					/**
					 * Locked panel from sorting.
					 **/
					if (jsonSettings.panel[key].locked == 1) {
						panelId.addClass('panel-locked');
					}

				}
			}

			/**
			 * Format colors
			 **/

			if (self.o.panelColors && self.o.colorButton) {
				var formatedPanelColors = [];
				for (var key in self.o.panelColors) {
					formatedPanelColors.push('<a href="#" class="btn d-inline-block '+ self.o.panelColors[key] +' width-2 height-2 p-0 rounded-0 js-panel-color hover-effect-dot" data-panel-setstyle="'+ self.o.panelColors[key] +'" style="margin:1px;"></a>');
				}
			}


			/**
			 * LOOP ALL PANELS
			 **/            
			self.panel.each(function () {

				var tPanel = $(this),
					closeButton,
					fullscreenButton,
					collapseButton,
					lockedButton,
					refreshButton,
					colorButton,
					resetButton,
					customButton,
					thisHeader = $(this).children('.panel-hdr'),
					thisContainer = $(this).children('.panel-container');

				/**
				 * Dont double wrap(check).
				 **/
				if (!thisHeader.parent().attr('role')) {

					/**
					 * Adding a helper class to all sortable panels, this will be
					 * used to find the panels that are sortable, it will skip the panels
					 * that have the dataset 'panels-sortable="false"' set to false.
					 **/
					if (self.o.sortable === true && tPanel.data('panel-sortable') === undefined) {
						tPanel.addClass('panel-sortable');
					}

					/**
					* Add a close button to the panel header (if set to true)
					**/
					if (self.o.closeButton === true && tPanel.data('panel-close') === undefined) {
						closeButton = '<a href="#" class="btn btn-panel hover-effect-dot js-panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></a>';
					} else {
						closeButton = '';
					}

					/**
					* Add a fullscreen button to the panel header (if set to true).
					**/
					if (self.o.fullscreenButton === true && tPanel.data('panel-fullscreen') === undefined) {
						fullscreenButton = '<a href="#" class="btn btn-panel hover-effect-dot js-panel-fullscreen" data-toggle="tooltip" data-offset="0,10" data-original-title="Fullscreen"></a>';
					} else {
						fullscreenButton = '';
					}

					/**
					* Add a collapse button to the panel header (if set to true).
					**/
					if (self.o.collapseButton === true && tPanel.data('panel-collapsed') === undefined) {
						collapseButton = '<a href="#" class="btn btn-panel hover-effect-dot js-panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></a>'
					} else {
						collapseButton = '';
					}

					/**
					* Add a locked button to the panel header (if set to true).
					**/
					if (self.o.lockedButton === true && tPanel.data('panel-locked') === undefined) {
						lockedButton = '<a href="#" class="dropdown-item js-panel-locked"><span data-i18n="drpdwn.lockpanel">' + self.o.lockedButtonLabel + '</span></a>'
					} else {
						lockedButton = '';
					}

					/**
					* Add a refresh button to the panel header (if set to true).
					**/
					if (self.o.refreshButton === true && tPanel.data('panel-refresh') === undefined) {
						refreshButton = '<a href="#" class="dropdown-item js-panel-refresh"><span data-i18n="drpdwn.refreshpanel">' + self.o.refreshButtonLabel + '</span></a>';
						thisContainer.prepend(
							'<div class="loader"><i class="fal fa-spinner-third fa-spin-4x fs-xxl"></i></div>'
						);
						//append** conflicts with panel > container > content:last child, so changed to prepend
						
					} else {
						refreshButton = '';
					}

					/**
					* Add a color select button to the panel header (if set to true).
					**/
					if (self.o.colorButton === true && tPanel.data('panel-color') === undefined) {
						colorButton = ' <div class="dropdown-multilevel dropdown-multilevel-left">\
											<div class="dropdown-item">\
												<span data-i18n="drpdwn.panelcolor">' + self.o.colorButtonLabel + '</span>\
											</div>\
											<div class="dropdown-menu d-flex flex-wrap" style="min-width: 9.5rem; width: 9.5rem; padding: 0.5rem">' + formatedPanelColors.join(" ") + '</div>\
										</div>'
					} else {
						colorButton = '';
					}

					/**
					* Add a reset widget button to the panel header (if set to true).
					**/
					if (self.o.resetButton === true && tPanel.data('panel-reset') === undefined) {
						resetButton = '<div class="dropdown-divider m-0"></div><a href="#" class="dropdown-item js-panel-reset"><span data-i18n="drpdwn.resetpanel">' + self.o.resetButtonLabel + '</span></a>'
					} else {
						resetButton = '';
					}

					/**
					* Add a custom button to the panel header (if set to true).
					**/
					if (self.o.customButton === true && tPanel.data('panel-custombutton') === undefined) {
						customButton = '<a href="#" class="dropdown-item js-panel-custombutton pl-4"><span data-i18n="drpdwn.custombutton">' + self.o.customButtonLabel + '</span></a>'
					} else {
						customButton = '';
					}

					/**
					 * Append the image to the panel header.
					 **/
					thisHeader.append(
						'<div class="panel-saving mr-2" style="display:none"><i class="fal fa-spinner-third fa-spin-4x fs-xl"></i></div>'
					);

					/**
					 * Set the buttons order.
					 **/
					var formatButtons = self.o.buttonOrder
						.replace(/%close%/g, closeButton)
						.replace(/%fullscreen%/g, fullscreenButton)
						.replace(/%collapse%/g, collapseButton);

					/**
					 * Add a button wrapper to the header.
					 **/
					if (closeButton !== '' || fullscreenButton !== '' || collapseButton !== '') {
						thisHeader.append('<div class="panel-toolbar">' + formatButtons + '</div>');
					}

					/**
					 * Set the dropdown buttons order.
					 **/
					var formatDropdownButtons = self.o.buttonOrderDropdown
						.replace(/%locked%/g, lockedButton)
						.replace(/%color%/g, colorButton)
						.replace(/%refresh%/g, refreshButton)
						.replace(/%reset%/g, resetButton)
						.replace(/%custom%/g, customButton);

					/**
					 * Add a button wrapper to the header.
					 **/
					if (lockedButton !== '' || colorButton !== '' || refreshButton !== '' || resetButton !== '' || customButton !== '') {
						thisHeader.append('<div class="panel-toolbar"><a href="#" class="btn btn-toolbar-master" data-toggle="dropdown"><i class="fal fa-ellipsis-v"></i></a><div class="dropdown-menu dropdown-menu-animated dropdown-menu-right p-0">' + formatDropdownButtons + '</div></div>');
					}    

					/**
					 * Adding roles to some parts.
					 **/
					tPanel.attr('role', 'widget')
						.children('div')
						.attr('role', 'content')
						.prev('.panel-hdr')
						.attr('role', 'heading')
						.children('.panel-toolbar')
						.attr('role', 'menu');
				}
			});


			/**
			 * SORTABLE
			 **/
			/**
			 * jQuery UI soratble, this allows users to sort the panels.
			 * Notice that this part needs the jquery-ui core to work.
			 **/
			if (self.o.sortable === true && jQuery.ui) {
				var sortItem = self.obj.find(self.o.grid + '.sortable-grid')
					.not('[data-panel-excludegrid]');
				sortItem.sortable({
					items: sortItem.find(self.o.panels + '.panel-sortable'),
					connectWith: sortItem,
					placeholder: self.o.placeholderClass,
					cursor: 'move',
					//revert: true,
					opacity: self.o.opacity,
					delay: 0,
					revert: 350,
					cancel: '.btn-panel, .panel-fullscreen .panel-fullscreen, .mod-panel-disable .panel-sortable, .panel-locked.panel-sortable',
					zIndex: 10000,
					handle: self.o.dragHandle,
					forcePlaceholderSize: true,
					forceHelperSize: true,
					update: function (event, ui) {
						/* run pre-loader in the panel */
						self._runPanelLoader(ui.item.children());
						/* store the positions of the plugins */
						self._savePanelPosition();
						/**
						 * Run the callback function.
						 **/
						if (typeof self.o.onChange == 'function') {
							self.o.onChange.call(this, ui.item);
						}
					}
				}); //you can add  }).disableSelection() if you don't want text to be selected accidently.
			}

			/**
			 * CLICKEVENTS
			 **/
			self._clickEvents();


			/**
			 * DELETE LOCAL STORAGE KEYS
			 **/
			if (self.storage.enabled) {
			   
				// Delete the settings key.
				$(self.o.deleteSettingsKey)
					.on(clickEvent, this, function (e) {
						var cleared = confirm(self.o.settingsKeyLabel);
						if (cleared) {
							localStorage.removeItem(keySettings);
						}
						e.preventDefault();
					});

				// Delete the position key.
				$(self.o.deletePositionKey)
					.on(clickEvent, this, function (e) {
						var cleared = confirm(self.o.positionKeyLabel);
						if (cleared) {
							localStorage.removeItem(keyPosition);
						}
						e.preventDefault();
					});
			}

			initialized = true;
		},

		/**
		 * Initialize storage.
		 *
		 * @param:
		 **/
		_initStorage: function (storage) {

			/**
			 * LOCALSTORAGE CHECK
			 **/
			storage.enabled = storage.enabled && !! function () {
				var result, uid = +new Date();
				try {
					localStorage.setItem(uid, uid);
					result = localStorage.getItem(uid) == uid;
					localStorage.removeItem(uid);
					return result;
				} catch (e) {}
			}();

			this._loadKeys();

			if (storage.enabled) {

				storage.getKeySettings = localStorage.getItem(storage.keySettings);
				storage.getKeyPosition = localStorage.getItem(storage.keyPosition);
				
			} // end if

		},

		/**
		 * Register all click events.
		 *
		 * @param:
		 **/
		_clickEvents: function () {

			var self = this;
			var headers = self.panel.children('.panel-hdr');

			/**
			 * Allow users to toggle collapse.
			 **/
			headers.on(clickEvent, '.js-panel-collapse', function (e) {

				var tPanel = $(this),
					pPanel = tPanel.closest(self.o.panels);

				/**
				 * Close tooltip
				 **/
				if( typeof($.fn.tooltip) !== 'undefined' && $('[data-toggle="tooltip"]').length ){
					$(this).tooltip('hide');
				} else {
					console.log("bs.tooltip is not loaded");
				}   

				/**
				 * Run function for the indicator image.
				 **/
			   // pPanel.toggleClass("panel-collapsed");

				pPanel.children('.panel-container').collapse('toggle')
					.on('shown.bs.collapse', function() {
						pPanel.removeClass('panel-collapsed');
						self._savePanelSettings(); 
					 }).on('hidden.bs.collapse', function(){
						pPanel.addClass('panel-collapsed');
						self._savePanelSettings(); 
					});

				/*if (pPanel.hasClass('panel-collapsed')) {
					pPanel.removeClass('panel-collapsed')
						.children('.panel-container')
						.slideDown(400, function () {
							self._savePanelSettings(); 
						});
				} else {
					pPanel.addClass('panel-collapsed')
						.children('.panel-container')
						.slideUp(400, function () {
							self._savePanelSettings(); 
						});
				}*/

				/**
				 * Run function for the indicator image.
				 **/
				self._runPanelLoader(tPanel);


				/**
				 * Run the callback function.
				 **/
				if (typeof self.o.onCollapse == 'function') {
					self.o.onCollapse.call(this, pPanel);
				}

				/**
				 * Lets save the setings.
				 **/
			   // self._savePanelSettings();             
				
				e.preventDefault();
			});

			/**
			 * Allow users to toggle fullscreen.
			 **/
			headers.on(clickEvent, '.js-panel-fullscreen', function (e) {

				var tPanel = $(this),
					pPanel = tPanel.closest(self.o.panels);

				/**
				 * Close tooltip
				 **/
				if( typeof($.fn.tooltip) !== 'undefined' && $('[data-toggle="tooltip"]').length ){
					$(this).tooltip('hide');
				} else {
					console.log("bs.tooltip is not loaded");
				}   

				/**
				 * Run function for the indicator image.
				 **/
				pPanel.toggleClass("panel-fullscreen");
				myapp_config.root_.toggleClass('panel-fullscreen');

				/**
				 * Run function for the indicator image.
				 **/
				self._runPanelLoader(tPanel);


				/**
				 * Run the callback function.
				 **/
				if (typeof self.o.onFullscreen == 'function') {
					self.o.onFullscreen.call(this, pPanel);
				}

				e.preventDefault();
			});

			/**
			 * Allow users to close the panel.
			 **/
			headers.on(clickEvent, '.js-panel-close', function (e) {

				var tPanel = $(this),
					pPanel = tPanel.closest(self.o.panels),
					pTitle = pPanel.children('.panel-hdr').children('h2').text().trim();

				/**
				 * Close tooltip
				 **/
				if( typeof($.fn.tooltip) !== 'undefined' && $('[data-toggle="tooltip"]').length ){
					$(this).tooltip('hide');
				} else {
					console.log("bs.tooltip is not loaded");
				}   

				
				var killPanel = function (){

					/**
					 * Run function for the indicator image.
					 **/
					pPanel.fadeOut(500,function(){
						/* remove panel */
						$(this).remove();
						/**
						 * Run the callback function.
						 **/
						if (typeof self.o.onClosepanel == 'function') {
							self.o.onClosepanel.call(this, pPanel);
						}
					});  

					/**
					 * Run function for the indicator image.
					 **/
					self._runPanelLoader(tPanel);

				};


				//backdrop sound
				initApp.playSound('media/sound', 'messagebox')

				if (typeof bootbox  != 'undefined') {

					bootbox.confirm({
						title: "<i class='fal fa-times-circle text-danger mr-2'></i> Do you wish to delete panel <span class='fw-500'>&nbsp;'" +pTitle+"'&nbsp;</span>?",
						message: "<span><strong>Warning:</strong> This action cannot be undone!</span>",
						centerVertical: true,
						swapButtonOrder: true,
						buttons: {
							confirm: {
								label: 'Yes',
								className: 'btn-danger shadow-0'
							},
							cancel: {
								label: 'No',
								className: 'btn-default'
							}
						},
						className: "modal-alert",
						closeButton: false,
						callback: function (result) {
							if (result == true) {
								//close panel 
								killPanel();
							}
						}
					});

				} else {

					if (confirm( 'Do you wish to delete panel ' + pTitle + '?' )) {
						killPanel();
					}

				}				

				e.preventDefault();
			});

			/**
			 * Allow users to set widget style (color).
			 **/
			headers.on(clickEvent, '.js-panel-color', function (e) {

				var tPanel = $(this),
					pPanel = tPanel.closest(self.o.panels),
					selectedHdr = tPanel.closest('.panel-hdr'),
					val = tPanel.data('panel-setstyle');

				/**
				 * Run the callback function.
				 **/
				selectedHdr.removeClassPrefix('bg-')
					.addClass(val)
					.closest('.panel')
					.attr('data-panel-attstyle', '' + val + '');              

				/**
				 * Run the callback function.
				 **/
				if (typeof self.o.onColor == 'function') {
					self.o.onColor.call(this, pPanel);
				}

				/**
				 * Run function for the indicator image.
				 **/
				self._runPanelLoader(tPanel);

				/**
				 * Lets save the setings.
				 **/
				self._savePanelSettings();

				e.preventDefault();
			});

			/**
			 * Allow users to lock widget to grid - preventing draging.
			 **/
			headers.on(clickEvent, '.js-panel-locked', function (e) {

				var tPanel = $(this),
					pPanel = tPanel.closest(self.o.panels);

				/**
				 * Run function for the indicator image.
				 **/
				pPanel.toggleClass('panel-locked');

				/**
				 * Run function for the indicator image.
				 **/
				self._runPanelLoader(tPanel);


				/**
				 * Run the callback function.
				 **/
				if (typeof self.o.onLocked == 'function') {
					self.o.onLocked.call(this, pPanel);
				}

				/**
				 * Lets save the setings.
				 **/
				self._savePanelSettings();             
				
				e.preventDefault();
			});

			/**
			 * Allow users to toggle refresh widget content.
			 **/
			headers.on(clickEvent, '.js-panel-refresh', function (e) {

				var tPanel = $(this),
					pPanel = tPanel.closest(self.o.panels),
					//pContainer = pPanel.children('.panel-container'),
					dTimer = pPanel.attr('data-refresh-timer') || 1500;

				/**
				 * Run function for the indicator image.
				 **/
				pPanel.addClass('panel-refresh').children('.panel-container').addClass('enable-loader')
					.stop(true, true)
					.delay(dTimer).queue(function(){
						//pContainer.removeClass('enable-spinner').dequeue();
						pPanel.removeClass('panel-refresh').children('.panel-container').removeClass('enable-loader').dequeue();
						console.log(pPanel.attr('id') + " refresh complete");
					}); 


				/**
				 * Run the callback function.
				 **/
				if (typeof self.o.onRefresh == 'function') {
					self.o.onRefresh.call(this, pPanel);
				}       
				
				e.preventDefault();
			});

			 /**
			 * Allow users to toggle reset widget settings.
			 **/
			headers.on(clickEvent, '.js-panel-reset', function (e) {

				var tPanel = $(this),
					pPanel = tPanel.closest(self.o.panels),
					selectedHdr = tPanel.closest('.panel-hdr');

				/**
				 * Remove all setting classes.
				 **/
				selectedHdr.removeClassPrefix('bg-')
					.closest('.panel')
					.removeClass('panel-collapsed panel-fullscreen panel-locked')
					.attr('data-panel-attstyle', '')
					.children('.panel-container').collapse('show');
					  

				/**
				 * Run function for the indicator image.
				 **/
				self._runPanelLoader(tPanel);    

				/**
				 * Lets save the setings.
				 **/
				self._savePanelSettings(); 

				/**
				 * Run the callback function.
				 **/
				if (typeof self.o.onReset == 'function') {
					self.o.onReset.call(this, pPanel);
				}       
				
				e.preventDefault();
			});

			headers = null;

		},

		/**
		 * Destroy.
		 *
		 * @param:
		 **/
		destroy: function () {
			var self = this, 
				namespace = '.' + pluginName, 
				sortItem = self.obj.find(self.o.grid + '.sortable-grid').not('[data-panel-excludegrid]');
			self.panel.removeClass('panel-sortable');
			sortItem.sortable('destroy');
			self.panel.children('.panel-hdr').off(namespace);
			$(self.o.deletePositionKey).off(namespace);
			$(window).off(namespace);
			self.obj.removeData(pluginName);
			self.initialized = false;
		}
	};

	$.fn[pluginName] = function (option) {
		return this.each(function () {

			var $this = $(this),
				data = $this.data(pluginName);

			if (!data) {
				var options = typeof option == 'object' && option;
				$this.data(pluginName, (data = new Plugin(this, options)));
			}
			if (typeof option == 'string') {
				data[option]();
			}
		});
	};

	/**
	 * Default settings(dont change).
	 * You can globally override these options
	 * by using $.fn.pluginName.key = 'value';
	 **/

	$.fn[pluginName].defaults = {
		grid: '[class*="col-"]',
		panels: '.panel',
		placeholderClass: 'panel-placeholder',
		dragHandle: '> .panel-hdr > h2',
		localStorage: true,
		onChange: function () {},
		onSave: function () {},
		opacity: 1,
		deleteSettingsKey: '',
		settingsKeyLabel: 'Reset settings?',
		deletePositionKey: '',
		positionKeyLabel: 'Reset position?',
		sortable: true,
		buttonOrder: '%collapse% %fullscreen% %close%',
		buttonOrderDropdown: '%refresh% %locked% %color% %custom% %reset%',
		customButton: false,
		customButtonLabel: "Custom Label",
		onCustom: function () {},
		closeButton: true,
		onClosepanel: function() {
			if (myapp_config.debugState)
				console.log($(this).closest(".panel").attr('id') + " onClosepanel")
		},
		fullscreenButton: true,
		onFullscreen: function() {
			if (myapp_config.debugState)
				console.log($(this).closest(".panel").attr('id') + " onFullscreen")
		},
		collapseButton: true,
		onCollapse: function() {
			if (myapp_config.debugState)
				console.log($(this).closest(".panel").attr('id') + " onCollapse")
		},
		lockedButton: true,
		lockedButtonLabel: "Lock Position",
		onLocked: function() {
			if (myapp_config.debugState)
				console.log($(this).closest(".panel").attr('id') + " onLocked")
		},
		refreshButton: true,
		refreshButtonLabel: "Refresh Content",
		onRefresh: function() {
			if (myapp_config.debugState)
				console.log($(this).closest(".panel").attr('id') + " onRefresh")
		},
		colorButton: true,
		colorButtonLabel: "Panel Style",
		onColor: function() {
			if (myapp_config.debugState)
				console.log($(this).closest(".panel").attr('id') + " onColor")
		},
		panelColors: ['bg-primary-700 bg-success-gradient',
					  'bg-primary-500 bg-info-gradient',
					  'bg-primary-600 bg-primary-gradient',
					  'bg-info-600 bg-primray-gradient',                      
					  'bg-info-600 bg-info-gradient',
					  'bg-info-700 bg-success-gradient',
					  'bg-success-900 bg-info-gradient',
					  'bg-success-700 bg-primary-gradient', 
					  'bg-success-600 bg-success-gradient',                                 
					  'bg-danger-900 bg-info-gradient',
					  'bg-fusion-400 bg-fusion-gradient', 
					  'bg-faded'],
		resetButton: true,
		resetButtonLabel: "Reset Panel",
		onReset: function() {
			if (myapp_config.debugState)
				console.log( $(this).closest(".panel").attr('id') + " onReset callback" )
		}
	};

})(jQuery, window, document);