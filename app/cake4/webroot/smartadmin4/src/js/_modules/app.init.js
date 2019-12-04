 /*!
 * jQuery SmartAdmin v4.0.0
 *
 * Copyright 2019, 2020 SmartAdmin WebApp
 * Released under Marketplace License (see your license details for usage)
 *
 * Publish Date: 2019-01-01T17:42Z
 */
var initApp = (function(app) {

	/**
	 * List filter 
	 * DOC: searches list items, it could be UL or DIV elements
	 * usage: initApp.listFilter($('.list'), $('#intput-id'));
	 *        inside the .list you will need to insert 'data-filter-tags' inside <a>
	 * @param  list
	 * @param  input 
	 * @param  anchor
	 * @return 
	 */
	app.listFilter = function (list, input, anchor) {

		/* add class to filter hide/show */
		if (anchor) {
			$(anchor).addClass('js-list-filter');
		} else {
			$(list).addClass('js-list-filter');
		}

		/* on change keyboard */
		$(input).change( function () {

			var filter = $(this).val().toLowerCase(),
				listPrev = $(list).next().filter('.js-filter-message');
	
			/* when user types more than 1 letter start search filter */
			if(filter.length > 1) {

				/* this finds all data-filter-tags in a list that contain the input val,
				   hiding the ones not containing the input while showing the ones that do */
				
				/* (1) hide all that does not match */   
				$(list).find($("[data-filter-tags]:not([data-filter-tags*='" + filter + "'])"))
					.parentsUntil(list).removeClass('js-filter-show')
					.addClass('js-filter-hide');

				/* (2) hide all that does match */	
				$(list).find($("[data-filter-tags*='" + filter + "']"))
					.parentsUntil(list).removeClass('js-filter-hide')
					.addClass('js-filter-show');

				/* if element exists then print results */	
				if (listPrev){	
					listPrev.text("showing " + $(list).find('li.js-filter-show').length + " from " + $(list).find('[data-filter-tags]').length + " total");
				}

			} else {

				/* when filter length is blank reset the classes */
				$(list).find('[data-filter-tags]').parentsUntil(list).removeClass('js-filter-hide js-filter-show');

				/* if element exists reset print results */
				if (listPrev){
					listPrev.text("");
				}
			} 

			return false;

		}).keyup( $.debounce( myapp_config.filterDelay, function (e) {

			/* fire the above change event after every letter is typed with a delay of 250ms */
			$(this).change();

			/*if(e.keyCode == 13) {
				console.log( $(list).find(".filter-show:not(.filter-hide) > a") );
			}*/

		}));
	};

	/**
	 * Load scripts using lazyload method 
	 * usage: initApp.loadScript("js/my_lovely_script.js", myFunction);
	 * @param  {[type]}   scriptName
	 * @param  {Function} callback  
	 * @return {[type]}             
	 */
	app.loadScript = function (scriptName, callback) {

		if (!myapp_config.jsArray[scriptName]) {	
			var promise = jQuery.Deferred();

			/* adding the script tag to the head as suggested before */
			var body = document.getElementsByTagName('body')[0],
				script = document.createElement('script');
				script.type = 'text/javascript';
				script.src = scriptName;

			/* then bind the event to the callback function
			   there are several events for cross browser compatibility */
			script.onload = function() {
				promise.resolve();
			};

			/* fire the loading */
			body.appendChild(script);
			myapp_config.jsArray[scriptName] = promise.promise();
		}	

		else if (myapp_config.debugState)
			console.log("This script was already loaded: " + scriptName);

		myapp_config.jsArray[scriptName].then(function () {
			if(typeof callback === 'function') {
				callback();
			}
		});
	};

	/**
	 * Javascript Animation for save settings 
	 * @return
	 **/
	app.saveSettings = function () {

		/* if saveSettings function exists */		
		if (typeof saveSettings !== 'undefined' && $.isFunction(saveSettings) && myapp_config.storeLocally) {

			/* call accessIndicator animation */
			initApp.accessIndicator();

			/* call saveSettings function from myapp_config.root_ (HTML) */
			saveSettings();

			if (myapp_config.debugState)
				console.log('Theme settings: ' + '\n' +localStorage.getItem('themeSettings'));

		} else {
			console.log("save function does not exist");
		}
		
	};

	/**
	 * Reset settings 
	 * DOC: removes all classes from root_ then saves
	 * @return {[type]}
	 **/
	app.resetSettings = function () {

		/* remove all setting classes nav|header|mod|display */
		myapp_config.root_.removeClass (function (index, className) {
			return (className.match (/(^|\s)(nav-|header-|mod-|display-)\S+/g) || []).join(' ');
		});

		/* detach custom css skin */
		$(myapp_config.mythemeAnchor).attr('href', "");

		/* check non-conflicting plugins */
		initApp.checkNavigationOrientation();

		/* save settings if "storeLocally == true" */
		initApp.saveSettings();

		if (myapp_config.debugState)
			console.log("App reset successful");
	};

	/**
	 * Factory Reset 
	 * DOC: Resets all of localstorage
	 * @return {[type]}
	 **/
	app.factoryReset = function () {

		//backdrop sound
		initApp.playSound('media/sound', 'messagebox');
		//hide settings modal to bootstrap avoid modal bug
		$('.js-modal-settings').modal('hide');

		if (typeof bootbox  != 'undefined') {

			bootbox.confirm({
				title: "<i class='fal fa-exclamation-triangle text-warning mr-2'></i> You are about to reset all of your localStorage settings",
				message: "<span><strong>Warning:</strong> This action is not reversable. You will lose all your layout settings.</span>",
				centerVertical: true,
				swapButtonOrder: true,
				buttons: {
					confirm: {
						label: 'Factory Reset',
						className: 'btn-warning shadow-0'
					},
					cancel: {
						label: 'Cancel',
						className: 'btn-success'
					}
				},
				className: "modal-alert",
				closeButton: false,
				callback: function (result) {
					if (result == true) {
						//close panel 
						localStorage.clear();
						initApp.resetSettings();
						location.reload();
					}
				}
			});

		} else {

			if (confirm( 'You are about to reset all of your localStorage to null state. Do you wish to continue?' )) {
				localStorage.clear();
				initApp.resetSettings();
				location.reload();
			}

		}				

		//e.preventDefault();

		if (myapp_config.debugState)
			console.log("App reset successful");
	};

	/**
	 * Access Indicator
	 * DOC: spinning icon that appears whenever you
	 * access localstorage or change settings
	 * @return {[type]}
	 **/
	app.accessIndicator = function () {

		myapp_config.root_.addClass('saving').delay(600).queue(function(){
			$(this).removeClass('saving').dequeue();
			return true;
		});

	};

	/*
	 * usage: initApp.pushSettings("className1 className2")
	 * save settings to localstorage: initApp.pushSettings("className1 className2", true)
	 * DOC: pushSettings will also auto save to localStorage if "storeLocally == true" 
	 * we will use this "pushSettings" when loading settings from a database
	 * @param  {[type]} DB_string   
	 * @param  {[type]} saveToLocal
	 * @return {[type]}            
	 */
	app.pushSettings = function (DB_string, saveToLocal) {

		/* clear localstorage variable 'themeSettings' */
		if (saveToLocal != false)
		localStorage.setItem("themeSettings", "");

		/* replace classes from <body> with fetched DB string */
		myapp_config.root_.addClass(DB_string); //ommited .removeClass()

		/* destroy or enable slimscroll */
		initApp.checkNavigationOrientation();

		/* save settings if "storeLocally == true" && "saveToLocal is true" */
		if (saveToLocal != false)
		initApp.saveSettings();

		/* return string */
		return DB_string;
	};

	/*
	 * usage: var DB_string = initApp.getSettings();
	 * we will use this "getSettings" when storing settings to a database
	 * @return {[type]}
	 */
	app.getSettings = function () {

		return myapp_config.root_.attr('class').split(/[^\w-]+/).filter(function(item) {
			return /^(nav|header|mod|display)-/i.test(item);
		}).join(' ');
	};

	/*
	 * Play Sounds
	 * usage: initApp.playSound(path, sound);
	 * @param  {[string]} path 
	 * @param  {[string]} sound 
	 */
	app.playSound = function(path, sound) {
		var audioElement = document.createElement('audio');
		if (navigator.userAgent.match('Firefox/'))
			audioElement.setAttribute('src', path + "/" + sound + '.ogg');
		else
			audioElement.setAttribute('src', path + "/" + sound + '.mp3');

		//$.get();// <-- ??
		audioElement.addEventListener("load", function () {
			audioElement.play();
		}, true);

		audioElement.pause();
		audioElement.play();
	}

	/*
	 * Checks and sets active settings selections
	 * DOC: ?
	 */
	/*app.indicateSelections = function () {

		var classNames = initApp.getSettings()
			.split(' ')
			.map(function(c) {
				return '[data-class="' +  c + '"].js-indicateSelections';
			})
			.join(',');

		$('[data-class].active.js-indicateSelections').removeClass('active');
		$(classNames).addClass('active');

		if (myapp_config.debugState)
			console.log(classNames);
	}*/

	/**
	 * detect browser type
	 * DOC: detect if browser supports webkit CSS
	 * @return {[type]}
	 **/
	app.detectBrowserType = function () {

		/* safari, chrome or IE detect */	
		if(myapp_config.isChrome){

			myapp_config.root_.addClass('chrome webkit');
			return 'chrome webkit';

		} else if (myapp_config.isWebkit) {

			myapp_config.root_.addClass('webkit');
			return 'webkit';

		} else if (myapp_config.isIE) {

			myapp_config.root_.addClass('ie');
			return 'ie';
		}

	};

	/**
	 * Add device type
	 * DOC: Detect if mobile or desktop
	 **/		
	app.addDeviceType = function() {
		
		if (!myapp_config.isMobile) {

			/* desktop */	
			myapp_config.root_.addClass('desktop');
			myapp_config.thisDevice = 'desktop';

		} else {

			/* mobile */
			myapp_config.root_.addClass('mobile');
			myapp_config.thisDevice = 'mobile';
			 
		}

		return myapp_config.thisDevice;
		
	};

	/**
	 * Fix logo position on .header-function-fixed & .nav-function-hidden
	 * DOC: Counters browser bug for fixed position and overflow:hidden for the logo (firefox/IE/Safari)
	 *      Will not fire for webkit devices or Chrome as its not needed
	 * @return {[type]}     
	 **/
	 app.windowScrollEvents = function () {
		if ( myapp_config.root_.is('.nav-function-hidden.header-function-fixed:not(.nav-function-top)') &&  myapp_config.thisDevice === 'desktop') {
			myapp_config.root_logo.css({
				'top': $(window).scrollTop()
			});
		} else if ( myapp_config.root_.is('.header-function-fixed:not(.nav-function-top):not(.nav-function-hidden)') &&  myapp_config.thisDevice === 'desktop') {
			myapp_config.root_logo.attr("style", "");
		}
	 };

	/**
	 * checkNavigationOrientation by checking layout conditions
	 * DOC: sometimes settings can trigger certain plugins; so we check this condition and activate accordingly
	 * E.g: the fixed navigation activates custom scroll plugin for the navigation, but this only happens when
	 *		it detects desktop browser and destroys the plugin when navigation is on top or if its not fixed.
	 * @return {[type]}		
	 **/
	 app.checkNavigationOrientation = function() {

		/**
		 * DOC: add the plugin with the following rules: fixed navigation is selected, top navigation is not active, minify nav is not active, 
		 * and the device is desktop. We do not need to activate the plugin when loading from a mobile phone as it is not needed for touch screens.
		 **/
		switch ( true ) {

			case ( myapp_config.root_.hasClass('nav-function-fixed') && !myapp_config.root_.is('.nav-function-top, .nav-function-minify, .mod-main-boxed') && myapp_config.thisDevice === 'desktop' ):

				/* start slimscroll on nav */
				if ( typeof $.fn.slimScroll !== 'undefined' ) {
					myapp_config.navAnchor.slimScroll({
						height: '100%',
						color: '#fff',
						size: '4px',
						distance: '4px',
						railOpacity: 0.4,
						wheelStep: 10
					});

					if ( document.getElementById(myapp_config.navHorizontalWrapperId) ) {
						myapp_config.navHooks.menuSlider('destroy');
						
						if (myapp_config.debugState)
						console.log("----top controls destroyed");
					}

					if (myapp_config.debugState)
					console.log("slimScroll created");

				} else {
					console.log("$.fn.slimScroll...NOT FOUND");
				}	

				break;			

			case ( myapp_config.navAnchor.parent().hasClass('slimScrollDiv') && myapp_config.thisDevice === 'desktop' && typeof $.fn.slimScroll !== 'undefined' ):

				/* destroy the plugin if it is in violation of rules above */
				myapp_config.navAnchor.slimScroll({ destroy: true });
				myapp_config.navAnchor.attr('style', '');

				/* clear event listners (IE bug) */
				events = jQuery._data( myapp_config.navAnchor[0], "events" );

				if (events) 
					jQuery._removeData( myapp_config.navAnchor[0], "events" );

				if (myapp_config.debugState)
					console.log("slimScroll destroyed");

				break;					

		}

		switch ( true ) {


			/* fires when user switches to nav-function-top on desktop view */
			case ( $.fn.menuSlider && myapp_config.root_.hasClass('nav-function-top') && $("#js-nav-menu-wrapper").length == false && !myapp_config.root_.hasClass('mobile-view-activated') ):

				/* build horizontal navigation */
				myapp_config.navHooks.menuSlider({
					element: myapp_config.navHooks,
					wrapperId: myapp_config.navHorizontalWrapperId
				});

				/* build horizontal nav */
				if (myapp_config.debugState)
				console.log("----top controls created -- case 1");

				break;

			/* fires when user resizes screen to mobile size or app is loaded on mobile resolution */
			case ( myapp_config.root_.hasClass('nav-function-top') && $("#js-nav-menu-wrapper").length == true && myapp_config.root_.hasClass('mobile-view-activated') ):

				/* destroy horizontal nav */
				myapp_config.navHooks.menuSlider('destroy');

				/* build horizontal nav */
				if (myapp_config.debugState)
				console.log("----top controls destroyed -- case 2");

				break;	

			/* fires when users switch off nav-function-top class */
			case ( !myapp_config.root_.hasClass('nav-function-top') && $("#js-nav-menu-wrapper").length == true ):

				/* destroy horizontal nav */
				myapp_config.navHooks.menuSlider('destroy');

				/* build horizontal nav */
				if (myapp_config.debugState)
				console.log("----top controls destroyed -- case 3");				

				break;	

		}

	};
	
	/**
	 * Activate Nav
	 * DOC: activation should not take place if top navigation is on
	 * @param  {[type]} id 
	 * @return {[type]} 
	 **/
	app.buildNavigation = function(id) {
		
		/**
		 * build nav
		 * app.navigation.js
		 **/
		if ($.fn.navigation) {

			$(id).navigation({ 

				accordion : myapp_config.navAccordion,
				speed : myapp_config.navSpeed,
				closedSign : '<em class="' + myapp_config.navClosedSign + '"></em>',
				openedSign : '<em class="' + myapp_config.navOpenedSign + '"></em>',
				initClass: myapp_config.navInitalized

			});

			return (id);
		} else {

			if (myapp_config.debugState)
			console.log( "WARN: navigation plugin missing" );

		}
	};

	/**
	 * Destroy Nav
	 * @param  {[type]} id 
	 * @return {[type]} 
	 **/
	app.destroyNavigation = function(id) {
		
		/**
		 * destroy nav
		 * app.navigation.js
		 **/
		if ($.fn.navigation) {

			$(id).navigationDestroy(); 

			return (id);
		} else {

			if (myapp_config.debugState)
			console.log( "WARN: navigation plugin missing" );

		}
	};

	/**
	 * App Forms
	 * DOC: detects if input is selected or blured
	 * @param  {[type]} parentClass
	 * @param  {[type]} focusClass
	 * @param  {[type]} disabledClass
	 * @return {[type]} 
	 **/
	app.appForms = function(parentClass,focusClass,disabledClass){

		/* go through each .form-control */
		/*$('.form-control').each(function () {
			checkLength(this);
		});*/

		/* if input has 'some value' add class .has-length to .form-group */
		/*function checkLength(e) {
			if (e.value.length > 0 ) {
				$(e).parents(parentClass).addClass(focusClass);
				if($(e).is('[readonly]') || $(e).is('[disabled]')) {
					$(e).parents(parentClass).addClass(disabledClass);
				}
			} else {
				$(e).parents(parentClass).removeClass(focusClass);
				if($(e).is('[readonly]') || $(e).is('[disabled]')) {
					$(e).parents(parentClass).removeClass(disabledClass);
				}
			}
		}*/

		function setClass(e, parentClass, focusClass) {
			$(e).parents(parentClass).addClass(focusClass);
		}

		function deleteClass(e, parentClass, focusClass) {
			/*if(e.value.length) {

			} else {*/
				$(e).parents(parentClass).removeClass(focusClass);
			/*}*/
		}

		$(parentClass).each(function () {
			var input = $(this).find('.form-control');
			input.on('focus', function(){
				setClass(this, parentClass, focusClass);
			});
			input.on('blur', function(){
				deleteClass(this, parentClass, focusClass);
			});
		});
	}; 
	
	/**
	 * Mobile Check Activate
	 * DOC: check on window resize if screen width is less than [value]
	 * @return {int}
	 */
	app.mobileCheckActivation = function(){
		
		if ( window.innerWidth < myapp_config.mobileResolutionTrigger ) {

			myapp_config.root_.addClass('mobile-view-activated');
			myapp_config.mobileMenuTrigger = true;

		} else {

			myapp_config.root_.removeClass('mobile-view-activated');
			myapp_config.mobileMenuTrigger = false;

		}

		if (myapp_config.debugState)
			console.log( "mobileCheckActivation on " + $(window).width() + " | activated: " + myapp_config.mobileMenuTrigger);

		return myapp_config.mobileMenuTrigger;
	}; 

	/**
	 *  Toggle visibility
	 * 	DOC: show and hide content with a button action
	 *  Usage: onclick="initApp.toggleVisibility('foo');"
	 *  @param  {[type]} id 
	 *  @return {[type]}
	 **/
	app.toggleVisibility = function (id) {
		var e = document.getElementById(id);
		if (e.style.display == 'block')
			e.style.display = 'none';
		else
			e.style.display = 'block';
	};

	/**
	 * Miscelaneous DOM ready functions
	 * DOC: start jQuery(document).ready calls
	 * @return {[type]}
	 **/
	app.domReadyMisc = function() {

		/* Add file name path to input files */
		$('.custom-file input').change(function (e) {
			var files = [];
			for (var i = 0; i < $(this)[0].files.length; i++) {
				files.push($(this)[0].files[i].name);
			}
			$(this).next('.custom-file-label').html(files.join(', '));
		});

		/* Give modal backdrop an extra class to make it customizable */
		$('.modal-backdrop-transparent').on('show.bs.modal', function (e) {
			setTimeout(function(){
				$('.modal-backdrop').addClass('modal-backdrop-transparent');
			});
		});
		
		/* Add app date to js-get-date */
		if ( myapp_config.appDateHook.length ) {
			var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
				day = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
				now = new Date(),
				formatted = day[now.getDay()] + ', ' +  
							months[now.getMonth()] + ' ' +  
							now.getDate() + ', ' +
							now.getFullYear();
			myapp_config.appDateHook.text(formatted);				
		}

		/* Check conflicting classes to build/destroy slimscroll */
		initApp.checkNavigationOrientation();

		/* Activate the last tab clicked using localStorage */
		var lastTab = localStorage.getItem('lastTab');
		 
		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			localStorage.setItem('lastTab', $(this).attr('href'));
		});
		
		if (lastTab) {
			$('[href="' + lastTab + '"]').tab('show');
		} 

		/**
		 * all options:
		 * --------------
			width: '300px',
			height: '500px',
			size: '10px',
			position: 'left',
			color: '#ffcc00',
			alwaysVisible: true,
			distance: '20px',
			start: $('#child_image_element'),
			railVisible: true,
			railColor: '#222',
			railOpacity: 0.3,
			wheelStep: 10,
			allowPageScroll: false,
			disableFadeOut: false
		 **/
		if ( typeof $.fn.slimScroll !== 'undefined' && myapp_config.thisDevice === 'desktop') {

			$('.custom-scroll:not(.disable-slimscroll) >:first-child').slimscroll({
				height: $(this).data('scrollHeight') || '100%',
				size: $(this).data('scrollSize') || '4px',
				position: $(this).data('scrollPosition') || 'right',
				color: $(this).data('scrollColor') || 'rgba(0,0,0,0.6)',
				alwaysVisible: $(this).data('scrollAlwaysVisible') || false,
				distance: $(this).data('scrollDistance') || '4px',
				railVisible: $(this).data('scrollRailVisible') || false,
				railColor: $(this).data('scrollRailColor') || '#fafafa',
				allowPageScroll: false,
				disableFadeOut: false
			});

			if (myapp_config.debugState)
				console.log("%c✔ SlimScroll plugin active", "color: #148f32");	

		} else {
			console.log("WARN! $.fn.slimScroll not loaded or user is on desktop");
			myapp_config.root_.addClass("no-slimscroll");
		}

		/**
		 * Activate listFilters
		 * usage: <input id="inputID" data-listfilter="listFilter" />
		 **/
		if( typeof initApp.listFilter !== 'undefined' && $.isFunction(initApp.listFilter) && $('[data-listfilter]').length ) {


			var inputID = $('[data-listfilter]').attr('id'),
				listFilter = $('[data-listfilter]').attr("data-listfilter");

			/* initApp.listFilter($('.list'), $('#intput-id')); */
			initApp.listFilter(listFilter, '#' + inputID);
		}

		/**
		 * Start bootstrap tooltips
		 **/
		if( typeof($.fn.tooltip) !== 'undefined' && $('[data-toggle="tooltip"]').length ){
			$('[data-toggle="tooltip"]').tooltip(); /*{html: true}*/
		} else {
			console.log("OOPS! bs.tooltip is not loaded");
		}

		/**
		 * Start bootstrap popovers
		 **/
		if( typeof($.fn.popover) !== 'undefined' && $('[data-toggle="popover"]').length ){

			/* BS4 sanatize */
			var myDefaultWhiteList = $.fn.tooltip.Constructor.Default.whiteList

			/* init popover */
			/* data-sanitize="false" was not working so had to add this globally */
			/* DOC: https://getbootstrap.com/docs/4.3/getting-started/javascript/#sanitizer */
			$('[data-toggle="popover"]').popover({sanitize:false}); /*{trigger: "focus"}*/

		} /*else {
			console.log("OOPS! bs.popover is not loaded");
			console.log("this")
		}*/

		/* 
		 * Disable popper.js's forced hardware accelaration styles
		 */
		if( typeof($.fn.dropdown) !== 'undefined'){ 
			Popper.Defaults.modifiers.computeStyle.gpuAcceleration = false;
		} else {
			console.log("OOPS! bs.popover is not loaded");
		} 

		/**
		 * Dropdowns will not close on click
		 * doc: close dropdowns on click outside hit area
		 **/
		$(document).on('click', '.dropdown-menu:not(.js-auto-close)', function (e) {
			e.stopPropagation();
		});

		/**
		 * Waves effect (plugin has issues with IE9)
		 * DOC: http://fian.my.id/Waves/#start 
		 **/
		if (window.Waves && myapp_config.rippleEffect) {

			Waves.attach('.nav-menu:not(.js-waves-off) a, .btn:not(.js-waves-off):not(.btn-switch), .js-waves-on', ['waves-themed']);
			Waves.init();

			if (myapp_config.debugState)
				console.log("%c✔ Waves plugin active", "color: #148f32");	
			
		} else {
			if (myapp_config.debugState)
				console.log("%c✘ Waves plugin inactive! ", "color: #fd3995");

		}

		/**
		 * Action buttons
		 **/		
		myapp_config.root_
			.on('click touchend', '[data-action]', function(e) {

				var actiontype = $(this).data('action');

				switch ( true ) {

					/**
					 * toggle trigger
					 * Usage 1 (body): <a href="#" data-action="toggle" data-class="add-this-class-to-body">...</a>
					 * Usage 2 (target): <a href="#" data-action="toggle" data-class="add-this-class-to-target" data-target="target">...</a>
					 **/
					case ( actiontype === 'toggle' ):

						var target = $(this).attr('data-target') || myapp_config.root_,
							dataClass = $(this).attr('data-class'),
							inputFocus = $(this).attr('data-focus');

						/* remove previous background image if alternate is selected */
						if ( dataClass.indexOf('mod-bg-') !== -1 ) {
							$(target).removeClass (function (index, css) {
								return (css.match (/(^|\s)mod-bg-\S+/g) || []).join(' ');
							});
						}

						/* trigger class change */
						$(target).toggleClass( dataClass );

						/* this allows us to add active class for dropdown toggle components */
						if ( $(this).hasClass('dropdown-item') ) {
							$(this).toggleClass('active');
						}

						/* focus input if available 
						   FAQ: We had to put a delay timer to slow it down for chrome
						*/
						if(inputFocus != undefined) {
							setTimeout(function(){ $('#' + inputFocus).focus(); }, 200);
						}

						/* save settings */
						if ( typeof classHolder != 'undefined' || classHolder != null ) {

							/* NOTE: saveSettings function is located right after <body> tag */
							initApp.checkNavigationOrientation();
							initApp.saveSettings();
						}

						break;

					/**
					 * toggle swap trigger
					 * Usage (target): <a href="#" data-action="toggle-swap" data-class=".add-this-class-to-target .another-class" data-target="#id">...</a>
					 **/
					case ( actiontype === 'toggle-swap' ):

						var target = $(this).attr('data-target'),
							dataClass = $(this).attr('data-class');

						/* trigger class change */
						$(target).removeClass().addClass( dataClass );

						break;

					/**
					 * panel 'collapse' trigger
					 **/
					case ( actiontype === 'panel-collapse' ):

						var selectedPanel = $(this).closest('.panel');

						selectedPanel.children('.panel-container').collapse('toggle')
						.on('show.bs.collapse', function() {
							selectedPanel.removeClass("panel-collapsed");

							if (myapp_config.debugState)
								console.log( "panel id:" + selectedPanel.attr('id') + " | action: uncollapsed" );

						}).on('hidden.bs.collapse', function(){
							selectedPanel.addClass("panel-collapsed");

							if (myapp_config.debugState)
								console.log( "panel id:" + selectedPanel.attr('id') + " | action: collapsed" );

						});
				
						/* return ID of panel */
						//return selectedPanel.attr('id');

						break;

					/**
					 * panel 'fullscreen' trigger
					 **/
					case ( actiontype === 'panel-fullscreen' ):

						var selectedPanel = $(this).closest('.panel');

						selectedPanel.toggleClass('panel-fullscreen');
						myapp_config.root_.toggleClass('panel-fullscreen');

						if (myapp_config.debugState)
						console.log( "panel id:" + selectedPanel.attr('id') + " | action: fullscreen" );

						/* return ID of panel */
						//return selectedPanel.attr('id');

						break;

					/**
					 * panel 'close' trigger
					 **/
					case ( actiontype === 'panel-close' ):

						var selectedPanel = $(this).closest('.panel');

						var killPanel = function (){

							selectedPanel.fadeOut(500,function(){
								/* remove panel */
								$(this).remove();

								if (myapp_config.debugState)
								console.log( "panel id:" + selectedPanel.attr('id') + " | action: removed" );
							});

						};

						if (typeof bootbox  != 'undefined') {

							initApp.playSound('media/sound', 'messagebox')

							bootbox.confirm({
								title: "<i class='fal fa-times-circle text-danger mr-2'></i> Do you wish to delete panel <span class='fw-500'>&nbsp;'" +selectedPanel.children('.panel-hdr').children('h2').text().trim()+ "'&nbsp;</span>?",
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
										killPanel();
									}
								}
							});

						} else {
							
							if (confirm( 'Do you wish to delete panel ' + selectedPanel.children('.panel-hdr').children('h2').text().trim() + '?' )) {
								killPanel();
							}

						}

						break;

					/**
					 * update header css, 'theme-update' trigger
					 * eg:  data-action = "theme-update" 
					 *      data-theme = "css/cust-theme-1.css"
					 **/
					case ( actiontype === 'theme-update' ):

						if ( $(myapp_config.mythemeAnchor).length) {
							$(myapp_config.mythemeAnchor).attr('href', $(this).attr('data-theme') );
						} else {
							var mytheme = $("<link>", {id: myapp_config.mythemeAnchor.replace('#', ''), "rel": "stylesheet", "href" : $(this).attr('data-theme') });
							$('head').append(mytheme);
						}

						if ( $(this).attr('data-themesave') != undefined ) {

							initApp.saveSettings();
						}

						break;

					/**
					 * theme 'app-reset' trigger
					 **/
					case ( actiontype === 'app-reset' ):

						initApp.resetSettings();

						break;

					/**
					 * theme 'factory-reset' trigger
					 **/
					case ( actiontype === 'factory-reset' ):

						initApp.factoryReset();

						break;	

					/**
					 * app print
					 * starts print priview for browser
					 **/
					case ( actiontype === 'app-print' ):

						window.print();

						break;

					/**
					 * ondemand
					 * load onDemand scripts
					 **/
					case ( actiontype === 'app-loadscript' ):

						var loadurl = $(this).attr('data-loadurl'),
							loadfunction = $(this).attr('data-loadfunction');

						initApp.loadScript(loadurl,loadfunction);

						break;	

					/**
					 * app language selection
					 * lazyloads i18n plugin and activates selected language
					 **/
					case ( actiontype === 'lang' ):

						var applang = $(this).attr('data-lang').toString();

						if (!$.i18n) {
						//jQuery.getScript('http://url/to/the/script');

							initApp.loadScript("js/i18n/i18n.js", 

								function activateLang () {
									
									$.i18n.init({
										resGetPath: 'media/data/__lng__.json',
										load: 'unspecific',
										fallbackLng: false,
										lng: applang
									}, function (t){
										$('[data-i18n]').i18n();
									});								
									
								}
							);

						} else {

							i18n.setLng(applang, function(){
								$('[data-i18n]').i18n();
								$('[data-lang]').removeClass('active');
								$(this).addClass('active');
							});

						}

						break;	

					/**
					 * app 'fullscreen' trigger
					 **/
					case ( actiontype === 'app-fullscreen' ):

						/* NOTE: this may not work for all browsers if the browser security does not permit it 
						   IE issues: http://stackoverflow.com/questions/33732805/fullscreen-not-working-in-ie */

						if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement ) {

							if (document.documentElement.requestFullscreen) {
								/* Standard browsers */
								document.documentElement.requestFullscreen();
							} else if (document.documentElement.msRequestFullscreen) {
								/* Internet Explorer */
								document.documentElement.msRequestFullscreen();
							} else if (document.documentElement.mozRequestFullScreen) {
								/* Firefox */
								document.documentElement.mozRequestFullScreen();
							} else if (document.documentElement.webkitRequestFullscreen) {
								/* Chrome */
								document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
							}

							if (myapp_config.debugState)
								console.log( "app fullscreen toggle active" );

						} else {

							if (document.exitFullscreen) {
								document.exitFullscreen();
							} else if (document.msExitFullscreen) {
								document.msExitFullscreen();
							} else if (document.mozCancelFullScreen) {
								document.mozCancelFullScreen();
							} else if (document.webkitExitFullscreen) {
								document.webkitExitFullscreen();
							}

							if (myapp_config.debugState)
								console.log("%capp fullscreen toggle inactive! ", "color: #ed1c24");
						}

						break; 
				
					/**
					 * app 'playsound' trigger
					 * usage: data-action="playsound" data-soundpath="media/sound/" data-soundfile="filename" (no file extensions)
					 **/
					case ( actiontype === 'playsound' ):

						var path = $(this).attr('data-soundpath') || "media/sound/",
							sound = $(this).attr('data-soundfile');

						initApp.playSound(path,sound);

						break;

				}

				/* hide tooltip if any present */
				$(this).tooltip('hide');

				if (myapp_config.debugState)
					console.log("data-action clicked: " + actiontype);

				/* stop default link action */				
				e.stopPropagation(); 
				e.preventDefault();		
		}); 

		/**
		 * Windows mobile 8 fix ~
		 * DOC: bootstrap related
		 **/
		if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
			var msViewportStyle = document.createElement('style');
			msViewportStyle.appendChild(
				document.createTextNode(
					'@-ms-viewport{width:auto!important}'
				)
			);
			document.head.appendChild(msViewportStyle)
		};

		/**
		 * Display APP version
		 * DOC: only show this if debug state tree
		 **/
		 if (myapp_config.debugState)
			console.log("%c✔ Finished app.init() v" + myapp_config.VERSION + '\n' + "---------------------------", "color: #148f32");	
	};

	return app;
	
})({});

/*
	"Night is a bag that bursts with the golden dust of dawn..."

	Oh wow, you actually opened this file and read it all the way though! Congrats! 
	Please do drop me a line at @myplaneticket :)

*/