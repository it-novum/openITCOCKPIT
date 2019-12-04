/*
 * LOAD SCRIPTS
 * Usage:
 * Define function = myPrettyCode ()...
 * loadScript("js/my_lovely_script.js", myPrettyCode);
 */

var ignore_key_elms = [".pace, .page-wrapper, .shortcut-menu, .js-modal-messenger, .js-modal-settings, script"]
var container = $('#js-page-content');
var bread_crumb = $('.page-breadcrumb');

var loadScript = function(scriptName, callback) {

	if (!jsArray[scriptName]) {
		var promise = jQuery.Deferred();

		// adding the script tag to the head as suggested before
		var body = document.getElementsByTagName('body')[0],
			script = document.createElement('script');
		script.type = 'text/javascript';
		script.src = scriptName;

		// then bind the event to the callback function
		// there are several events for cross browser compatibility
		script.onload = function () {
			promise.resolve();
		};

		// fire the loading
		body.appendChild(script);

		// clear DOM reference
		//body = null;
		//script = null;

		jsArray[scriptName] = promise.promise();

	} else {
		console.log("This script was already loaded %c: " + scriptName);
	}

	jsArray[scriptName].then(function () {
		if (typeof callback === 'function')
			callback();
	});
}

/* ~ END: LOAD SCRIPTS */

/*
 * APP AJAX REQUEST SETUP
 * Description: Executes and fetches all ajax requests also
 * updates naivgation elements to active
 */

// fire this on page load if nav exists
if ($('#js-nav-menu').length) {
	checkURL();
}

$(document).on('click', '#js-nav-menu a[href!="#"]', function (e) {
	e.preventDefault();
	var $this = $(e.currentTarget);

	// if parent is not active then get hash, or else page is assumed to be loaded
	if (!$this.parent().hasClass("active") && !$this.attr('target')) {

		// update window with hash
		// you could also do here:  thisDevice === "mobile" - and save a little more memory

		if (myapp_config.root_.hasClass('mobile-view-activated')) {
			myapp_config.root_.removeClass('hidden-menu');
			$('html').removeClass("hidden-menu-mobile-lock");
			window.setTimeout(function () {
				if (window.location.search) {
					window.location.href =
						window.location.href.replace(window.location.search, '')
						.replace(window.location.hash, '') + '#' + $this.attr('href');
				} else {
					window.location.hash = $this.attr('href');
				}
			}, 150);
			// it may not need this delay...
		} else {
			if (window.location.search) {
				window.location.href =
					window.location.href.replace(window.location.search, '')
					.replace(window.location.hash, '') + '#' + $this.attr('href');
			} else {
				window.location.hash = $this.attr('href');
			}
		}

		// clear DOM reference
		// $this = null;
	}

});

// fire links with targets on different window
$(document).on('click', '#js-nav-menu a[target="_blank"]', function (e) {
	e.preventDefault();
	var $this = $(e.currentTarget);

	window.open($this.attr('href'));
});

// fire links with targets on same window
$(document).on('click', '#js-nav-menu a[target="_top"]', function (e) {
	e.preventDefault();
	var $this = $(e.currentTarget);

	window.location = ($this.attr('href'));
});

// all links with hash tags are ignored
$(document).on('click', '#js-nav-menu a[href="#"]', function (e) {
	e.preventDefault();
});

// DO on hash change
$(window).on('hashchange', function () {
	checkURL();
});

/*
 * CHECK TO SEE IF URL EXISTS
 */
function checkURL() {

	//get the url by removing the hash
	//var url = location.hash.replace(/^#/, '');
	var url = location.href.split('#').splice(1).join('#');
	//BEGIN: IE11 Work Around
	if (!url) {

		try {
			var documentUrl = window.document.URL;
			if (documentUrl) {
				if (documentUrl.indexOf('#', 0) > 0 && documentUrl.indexOf('#', 0) < (documentUrl.length + 1)) {
					url = documentUrl.substring(documentUrl.indexOf('#', 0) + 1);

				}

			}

		} catch (err) {}
	}
	//END: IE11 Work Around


	// Do this if url exists (for page refresh, etc...)
	if (url) {
		// remove all active class
		$('#js-nav-menu li.active').removeClass("active");
		// match the url and add the active class
		$('#js-nav-menu li:has(a[href="' + url + '"])').addClass("active");
		var title = ($('nav a[href="' + url + '"]').attr('title'));

		// change page title from global var
		document.title = (title || document.title);

		// debugState
		console.log("Page title: %c " + document.title);


		// parse url to jquery
		loadURL(url + location.search, container);

	} else {

		// grab the first URL from nav
		var $this = $('#js-nav-menu > li:first-child > a[href!="#"]');

		//update hash
		window.location.hash = $this.attr('href');

		//clear dom reference
		$this = null;

	}

}
/*
 * LOAD AJAX PAGES
 */
function loadURL(url, container) {

	// debugState
	console.log("Loading URL: %c" + url);


	$.ajax({
		type: "GET",
		url: url,
		dataType: 'html',
		cache: true, // (warning: setting it to false will cause a timestamp and will call the request twice)
		beforeSend: function () {

			// destroy all widget instances
			if ($.navAsAjax && (container[0] == $("#js-page-content")[0]) && enableJarvisWidgets && $("#widget-grid")[0]) {

				$("#widget-grid").jarvisWidgets('destroy');
				// debugState
				console.log("✔ JarvisWidgets destroyed");

			}
			// end destroy all widgets 

			// cluster destroy: destroy other instances that could be on the page 
			// this runs a script in the current loaded page before fetching the new page
			if ((container[0] == $("#js-page-content")[0])) {

				/*
				 * The following elements should be removed, if they have been created:
				 *
				 *	colorList
				 *	icon
				 *	picker
				 *	inline
				 *	And unbind events from elements:
				 *	
				 *	icon
				 *	picker
				 *	inline
				 *	especially $(document).on('mousedown')
				 *	It will be much easier to add namespace to plugin events and then unbind using selected namespace.
				 *	
				 *	See also:
				 *	
				 *	http://f6design.com/journal/2012/05/06/a-jquery-plugin-boilerplate/
				 *	http://keith-wood.name/pluginFramework.html
				 */

				// this function is below the pagefunction for all pages that has instances

				if (typeof pagedestroy == 'function') {

					try {
						pagedestroy();

						console.log("✔ Pagedestroy()");

					} catch (err) {
						pagedestroy = undefined;

						console.log("! Pagedestroy() Catch Error");

					}

				}


			}
			// end cluster destroy

			// empty container and var to start garbage collection (frees memory)
			pagefunction = null;
			container.removeData().html("");

			// place cog
			container.html('<h1 class="ajax-loading-animation"><i class="fa fa-cog fa-spin"></i> Loading...</h1>');

			// Only draw breadcrumb if it is main content material
			if (container[0] == $("#js-page-content")[0]) {

				// clear everything else except these key DOM elements
				// we do this because sometime plugins will leave dynamic elements behind
				$('body').find('> *').filter(':not(' + ignore_key_elms + ')').empty().remove();

				// draw breadcrumb
				//drawBreadCrumb();

				// scroll up
				$("html").animate({
					scrollTop: 0
				}, "fast");
			}
			// end if
		},
		success: function (data) {

			// dump data to container
			container.css({
				opacity: '0.0'
			}).html(data).delay(50).animate({
				opacity: '1.0'
			}, 300);

			// clear data var
			data = null;
			container = null;
		},
		error: function (xhr, status, thrownError, error) {
			container.html('<h4 class="ajax-loading-error"><i class="fa fa-warning text-warning"></i> Error requesting <span class="text-danger">' + url + '</span>: ' + xhr.status + ' <span style="text-transform: capitalize;">' + thrownError + '</span></h4>');
		},
		async: true
	});

}
/*
 * UPDATE BREADCRUMB
 */
function drawBreadCrumb(opt_breadCrumbs) {
	var a = $("nav li.active > a"),
		b = a.length;

	bread_crumb.empty(),
		bread_crumb.append($("<li>Home</li>")), a.each(function () {
			bread_crumb.append($("<li></li>").html($.trim($(this).clone().children(".badge").remove().end().text()))), --b || (document.title = bread_crumb.find("li:last-child").text())
		});

	// Push breadcrumb manually -> drawBreadCrumb(["Users", "John Doe"]);
	// Credits: Philip Whitt | philip.whitt@sbcglobal.net
	if (opt_breadCrumbs != undefined) {
		$.each(opt_breadCrumbs, function (index, value) {
			bread_crumb.append($("<li></li>").html(value));
			document.title = bread_crumb.find("li:last-child").text();
		});
	}
}
/* ~ END: APP AJAX REQUEST SETUP */

/*
 * PAGE SETUP
 * Description: fire certain scripts that run through the page
 * to check for form elements, tooltip activation, popovers, etc...
 */
function pageSetUp() {

	if (thisDevice === "desktop") {
		// is desktop

		// activate tooltips
		$("[rel=tooltip], [data-rel=tooltip]").tooltip();

		// activate popovers
		$("[rel=popover], [data-rel=popover]").popover();

		// activate popovers with hover states
		$("[rel=popover-hover], [data-rel=popover-hover]").popover({
			trigger: "hover"
		});

		// setup widgets
		setup_widgets_desktop();

		// activate inline charts
		runAllCharts();

		// run form elements
		runAllForms();

	} else {

		// is mobile

		// activate popovers
		$("[rel=popover], [data-rel=popover]").popover();

		// activate popovers with hover states
		$("[rel=popover-hover], [data-rel=popover-hover]").popover({
			trigger: "hover"
		});

		// activate inline charts
		runAllCharts();

		// setup widgets
		setup_widgets_mobile();

		// run form elements
		runAllForms();

	}

}
/* ~ END: PAGE SETUP */