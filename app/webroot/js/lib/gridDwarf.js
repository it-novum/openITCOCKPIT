$.fn.gridDwarf = function(opt){
	'use strict';

	var GridDwarf,
		$selection = this;

	/**
	 * A grid system which utilizes gridster.js (gridner.net)
	 * @return {GridDwarf}
	 */
	GridDwarf = (function(){

		/**
		 * Initializes GridDwarf
		 */
		function GridDwarf(){
			var self = this;

			self.options = {
				dwarf: {
					height: 50,
					width: 50,
					minHeight: 50,
					minWidth: 300,
					maxGridsterPaneSize: 5000,
					prefix: 'widget_',
					afterInitialize: function(){}
				},
				widgets: {
					color: 'white',
					selector: {
						fullscreenContainer: '#jarviswidget-fullscreen-mode',
						fullscreenButton: '.jarviswidget-fullscreen-btn'
					}
				},
				uri: {
					grid: {
						save: '/admin/dashboard/saveGridPositions.json',
						load: '/admin/dashboard/loadGridPositions'
					},
					widget: {
						save: '/admin/dashboard/saveWidget.json',
						del: '/admin/dashboard/deleteWidget',
						load: '/admin/dashboard/loadWidgetConfiguration'
					},
					tabs: {
						saveTabs: '/admin/dashboard/saveTabs.json',
						saveTab: '/admin/dashboard/saveTab.json',
						del: '/admin/dashboard/deleteTab'
					}
				},
				tabs: {
					tabContainerId: '#dwarfTabs',
					addButton: '#dwarfTabs .addNewTab',
					deleteButton: '#dwarfTabs .deleteTab',
					renameButton: '#dwarfTabs .renameTab',
					dragDelay: 250,
					newTabDialogText: 'Tab name:',
					addWidgetDropdownMenu: '.add-widget-dropdown-menu',
					widgetText: 'Widget title:'
				}
			};

			self.$tabBar = $(self.options.tabs.tabContainerId);
			self.$addButton = $(self.options.tabs.addButton);
			self.$deleteButton = $(self.options.tabs.deleteButton);
			self.$renameButton = $(self.options.tabs.renameButton);
			self.$addWidgetDropDownMenu = $(self.options.tabs.addWidgetDropdownMenu);

			self.options.jarvis = {
				fullscreenClass: 'fa fa-expand | fa fa-compress'.split('|'),
				fullscreenDiff: 3,
				onFullscreen: function(){
					var $target = $(this).parents('li');
					if($target.css('position') === 'static'){
						$target.css('position', 'absolute'); //normal mode
						var newHeight = $target.height() - $target.find('header').height();
						$target.find('article div [role=content]').css('height', newHeight + 'px');
					}else{
						$target.css('position', 'static');
					}
				}
			};
			self.options.gridster = {
				widget_margins: [5, 5],
				widget_base_dimensions: [
					self.options.dwarf.width - 10,
					self.options.dwarf.height - 10
				],
				draggable: {
					handle: 'li > article > div > header, li > article > div > header > h2',
					stop: function(){
						self._saveGridPositions();
					}
				},
				//extra_cols: parseInt(self.options.dwarf.maxGridsterPaneSize / self.options.dwarf.width, 10),
				autogrow_cols: true,
				resize: {
					min_size: [
						parseInt(self.options.dwarf.minWidth / self.options.dwarf.width, 10),
						parseInt(self.options.dwarf.minHeight / self.options.dwarf.height, 10)
					],
					enabled: true,
					stop: function(e, ui, $widget){
						self.updateWidgetHeight($widget);
						self._saveGridPositions();
					}
				},
				serialize_params: function($widget, wgd){
					var id = $widget.data('id'),
						title = $widget.find('header > h2').text(),
						color = $widget.find('.jarviswidget').data('color');
					return {
						Widget: {
							col: wgd.col,
							row: wgd.row,
							size_x: wgd.size_x,
							size_y: wgd.size_y,
							id: id,
							title: title,
							color: color
						}
					};
				},
				avoid_overlapped_widgets: true,
				reposition: false
			};
			self.options = $.extend(true, {}, self.options, opt);

			self.gridster = $selection.gridster(self.options.gridster).data('gridster');

			self._widgetInit();
			self._tabInit();
			self._loadGridPositions();

			self.options.dwarf.afterInitialize(self);
		}

		GridDwarf.prototype._widgetInit = function(){
			var self = this;
			$('.jarviswidget-delete-btn').on('click', function(){
				var $widget = $(this).parents('.jarviswidget');
				self._deleteWidget($widget);
			});
			$('.jarviswidget-edit-btn').on('click', function(){
				var $widget = $(this).parents('.jarviswidget');
				self._editWidget($widget);
			});
			$('.color-bar-picker').on('click', function(){
				var val = $(this).data('color'),
					$widget = $(this).parents('.jarviswidget'),
					prefixClass = 'jarviswidget-color-';
				$widget.removeClass(prefixClass + $widget.data('color'));
				$widget.data('color', val);
				$widget.addClass(prefixClass + $widget.data('color'));
				self._saveGridPositions();
			});

			/**
			 * On click go to fullscreen mode.
			 **/
			$('.jarviswidget-fullscreen-btn').on('click', function(){
				var $widget = $(this).parents('.jarviswidget'),
					$widgetContent = $widget.children('div'),
					$fullscreen = $('#jarviswidget-fullscreen-mode');

				// Wrap the widget and go fullsize.
				if($fullscreen.length){
					self._deactivateFullscreenMode($widget, $widgetContent);
				}else{
					self._activateFullscreenMode($widget, $widgetContent, $fullscreen);
				}

				/**
				 * Run the callback function.
				 **/
				if (typeof self.options.jarvis.onFullscreen === 'function') {
					self.options.jarvis.onFullscreen.call(this, $widget);
				}
			});
		};

		GridDwarf.prototype._deactivateFullscreenMode = function($widget, $widgetContent){
			var self = this;

			// Remove class from the body.
			$('.nooverflow').removeClass('nooverflow');
			// Unwrap the widget, remove the height, set the right fullscreen button back, and show all other buttons.
			$widget.unwrap('<div>')
				.children('div')
					.removeAttr('style').end()
				.find('.jarviswidget-fullscreen-btn').children()
					.removeClass(self.options.jarvis.fullscreenClass[1])
					.addClass(self.options.jarvis.fullscreenClass[0])
					.parents('.jarviswidget-ctrls').children('a')
						.show();

			// Reset collapsed widgets.
			if($widgetContent.hasClass('jarviswidget-visible')){
				$widgetContent.hide()
					.removeClass('jarviswidget-visible');
			}
		};

		GridDwarf.prototype._activateFullscreenMode = function($widget, $widgetContent, $fullscreen){
			var self = this;

			$('body').addClass('nooverflow');
			$widget.wrap('<div id="jarviswidget-fullscreen-mode"/>')
				.parent().find('.jarviswidget-fullscreen-btn').children()
				.removeClass(self.options.jarvis.fullscreenClass[0])
				.addClass(self.options.jarvis.fullscreenClass[1])
				.parents('.jarviswidget-ctrls').children('a:not(.jarviswidget-fullscreen-btn)')
				.hide();

			// Show collapsed widgets.
			if($widgetContent.is(':hidden')){
				$widgetContent.show()
					.addClass('jarviswidget-visible');
			}

			$fullscreen = $('#jarviswidget-fullscreen-mode');
			if($fullscreen.length){
				var windowHeight = $(window).height();
				var headerHeight = $fullscreen.find('.jarviswidget').children('header').height();
				// Set the height to the right widget.
				$fullscreen.find('.jarviswidget').children('div')
					.height(windowHeight - headerHeight - 15);
			}
		};

		GridDwarf.prototype.saveCurrentTabPositions = function(){
			var self = this,
				tabs = [];

			self.$tabBar.children('li[data-id]').each(function(i){
				tabs.push({
					id: $(this).data('id'),
					position: i
				});
			});
			if(tabs.length === 0){
				return;
			}
			self._saveTabs({tabs: tabs});
		};

		GridDwarf.prototype._tabInit = function(){
			var self = this;

			self.$tabBar.sortable({
				items: '> li:not(.immobile)',
				stop: function(){
					self.saveCurrentTabPositions();
				},
				delay: self.options.tabs.dragDelay
			});

			self.$addWidgetDropDownMenu.find('a').on('click', function(){
				self._addWidget($(this));
			});
			self.$addButton.on('click', function(){
				self._addTab();
			});
			self.$deleteButton.on('click', function(){
				self._deleteTab();
			});
			self.$renameButton.on('click', function(){
				self._renameTab();
			});
		};

		/**
		 * Restores the position of the given widget data.
		 * @param {Object} widgetData Contains the size, position and ID of the widget.
		 */
		GridDwarf.prototype._moveWidgetIntoPosition = function(widgetData){
			var id = '#' + this.options.dwarf.prefix + widgetData.id,
				$widget = this.gridster.add_widget(
					id,
					Number(widgetData.size_x),
					Number(widgetData.size_y),
					Number(widgetData.col),
					Number(widgetData.row)
				);
			this.updateWidgetHeight($widget);
		};

		GridDwarf.prototype._renameTab = function(){
			var self = this,
				$tab = self.$tabBar.find('> .active'),
				id = $tab.data('id'),
				name = $tab.data('name');

			bootbox.prompt({
				title: 'Rename Tab',
				value: name,
				callback: function(result){
					if(!result){
						return;
					}
					result = trim(result);
					if(result === '' || result === name){
						return;
					}

					$tab.data('name', result);
					$tab.find('.text').text(result);

					self._saveTab({
						tab: {
							name: result,
							id: id
						}
					});
				}
			});
		};

		/**
		 * Set widget container to the size it holds in the grid.
		 * @param {jQuery} $widget Contains a widget wich was resized or loaded
		 */
		GridDwarf.prototype.updateWidgetHeight = function($widget){
			var self = this,
				newHeight = $widget.height() - $widget.find('header').height(),
				contentHeight = $widget.find('article div [role=content]').css('height', '').height();
			if(newHeight > contentHeight){
				$widget.find('article div [role=content]').css('height', newHeight + 'px');
			}else{
				var newY = parseInt($widget.children('article').height() / self.options.dwarf.height, 10) + 1;
				newHeight = newY * self.options.dwarf.height - $widget.find('header').height() - 10;
				self.gridster.resize_widget($widget, null, newY, function(){
					$widget.find('article div [role=content]').css('height', newHeight + 'px');
				});
			}
		};

		GridDwarf.prototype._addTab = function(){
			var self = this;
			bootbox.prompt({
				title: self.options.tabs.newTabDialogText,
				callback: function(name){
					if(!name){
						return;
					}
					name = trim(name);
					if(name === ''){
						return;
					}

					self._saveTab({
						tab: {
							name: name,
							position: self.$tabBar.children('li[data-id]').length
						}
					}, function(){
						document.location.reload();
					});
				}
			});
		};

		GridDwarf.prototype._saveTab = function(positionData, onSuccess){
			onSuccess = typeof onSuccess === 'function' ? onSuccess : function(){
			};
			$.ajax(this.options.uri.tabs.saveTab, {
				method: 'post',
				data: positionData,
				success: function(data){
					onSuccess(data);
				}
			});
		};

		GridDwarf.prototype._saveTabs = function(positionData, onSuccess){
			onSuccess = typeof onSuccess === 'function' ? onSuccess : function(){
			};
			$.ajax(this.options.uri.tabs.saveTabs, {
				method: 'post',
				data: positionData,
				success: function(data){
					onSuccess(data);
				}
			});
		};

		GridDwarf.prototype._deleteTab = function(){
			var self = this,
				$activeTab = self.$tabBar.find('> .active'),
				activeTabId = $activeTab.data('id'),
				activeTabName = $activeTab.data('name');

			bootbox.confirm('Do you really want to delete the tab ' + activeTabName + ' ?', function(result){
				if(!result){
					return;
				}
				$.ajax(self.options.uri.tabs.del + '/' + activeTabId + '.json', {
					method: 'post',
					complete: function(){
						document.location.pathname = '/admin/Dashboard/index';
					}
				});
			});
		};

		GridDwarf.prototype.saveWidget = function(data, onComplete){
			$.ajax(this.options.uri.widget.save, {
				method: 'post',
				complete: onComplete,
				data: data
			});
		};

		GridDwarf.prototype._addWidget = function($button){
			var self = this,
				name = $button.text();

			self.saveWidget({
				Widget: {
					type_id: $button.data('widget_type'),
					title: name,
					color: self.options.widgets.color,
					dashboard_tab_id: self.$tabBar.find('> .active').data('id'),
					size_x: parseInt(self.options.dwarf.minWidth / self.options.dwarf.width, 10),
					size_y: parseInt(self.options.dwarf.minHeight / self.options.dwarf.height, 10)
				}
			}, function(){
				document.location.reload();
			});
		};

		GridDwarf.prototype.loadWidget = function(id, onSuccess){
			var self = this;

			$.ajax(self.options.uri.widget.load + '/' + id + '.json', {
				success: function(data){
					onSuccess(data);
				}
			});
		};

		GridDwarf.prototype._editWidget = function($widget){
			var self = this,
				$widgetHead = $widget.children('header').children('h2');

			bootbox.prompt({
				title: 'Rename Widget',
				value: $widgetHead.text(),
				callback: function(result){
					if(!result){
						return;
					}
					result = trim(result);
					if(result.length === '' || result === name){
						return;
					}

					$widgetHead.text(result);
					self._saveGridPositions();
				}
			});
		};

		GridDwarf.prototype._deleteWidget = function($widget){
			var self = this,
				widTitle = $widget.children('header').children('h2').text();

			bootbox.confirm('Do you really want to delete the widget ' + widTitle + ' ?', function(result){
				if(result){
					var id = $widget.data('id');
					self.gridster.remove_widget($('#widget_' + id));
					$.ajax(self.options.uri.widget.del + '/' + id + '.json', {
						method: 'post'
					});
				}
			});
		};

		/**
		 * Saves grid position and size for each id
		 */
		GridDwarf.prototype._saveGridPositions = function(){
			var self = this;

			$.ajax(this.options.uri.grid.save, {
				method: 'post',
				data: {
					gridDwarf: self.gridster.serialize()
				}
			});
		};

		/**
		 * Loads positions and id of widgets via ajax. The widgets are restored if possible.
		 */
		GridDwarf.prototype._loadGridPositions = function(){
			var self = this,
				tabId = self.$tabBar.find('> .active').data('id');

			$.ajax(self.options.uri.grid.load + '/' + tabId + '.json', {
				success: function(data){
					self.gridster.remove_all_widgets();
					data.gridDwarf.forEach(function(element){
						self._moveWidgetIntoPosition(element.Widget);
					});
				}
			});
		};

		return GridDwarf;
	})();

	return new GridDwarf(opt);
};
