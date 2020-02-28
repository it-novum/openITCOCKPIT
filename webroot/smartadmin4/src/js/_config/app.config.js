//--------------------------------------------------------------------------
// HEADSUP!
// Please be sure to re-run gulp again if you do not see the config changes
//--------------------------------------------------------------------------
var myapp_config = {
	/*
	APP VERSION
	*/
	VERSION: '4.0.2',
	/*
	SAVE INSTANCE REFERENCE
	Save a reference to the global object (window in the browser)
	*/
	root_: $('body'), // used for core app reference
	root_logo: $('.page-sidebar > .page-logo'), // used for core app reference
	/*
	DELAY VAR FOR FIRING REPEATED EVENTS (eg., scroll & resize events)
	Lowering the variable makes faster response time but taxing on the CPU
	Reference: http://benalman.com/code/projects/jquery-throttle-debounce/examples/throttle/
	*/
	throttleDelay: 450, // for window.scrolling & window.resizing
	filterDelay: 150,   // for keyup.functions 
	/*
	DETECT MOBILE DEVICES
	Description: Detects mobile device - if any of the listed device is 
	detected a class is inserted to $.root_ and the variable thisDevice 
	is decleard. (so far this is covering most hand held devices)
	*/
	thisDevice: null, // desktop or mobile
	isMobile: (/iphone|ipad|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase())), //popular device types available on the market
	mobileMenuTrigger: null, // used by pagescrolling and appHeight script, do not change!
	mobileResolutionTrigger: 992, //the resolution when the mobile activation fires
	/*
	DETECT IF WEBKIT
	Description: this variable is used to fire the custom scroll plugin. 
	If it is a non-webkit it will fire the plugin.
	*/
	isWebkit: ((!!window.chrome && !!window.chrome.webstore) === true || Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0 === true),
	/*
	DETECT CHROME
	Description: this variable is used to fire the custom CSS hacks
	*/
	isChrome: (/chrom(e|ium)/.test(navigator.userAgent.toLowerCase())),
	/*
	DETECT IE (it only detects the newer versions of IE)
	Description: this variable is used to fire the custom CSS hacks
	*/
	isIE: ( (window.navigator.userAgent.indexOf('Trident/') ) > 0 === true ),
	/*
	DEBUGGING MODE
	debugState = true; will spit all debuging message inside browser console.
	*/
	debugState: true, // outputs debug information on browser console
	/*
	Turn on ripple effect for buttons and touch events
	Dependency: 
	*/
	rippleEffect: true, // material design effect that appears on all buttons
	/*
	Primary theme anchor point ID
	This anchor is created dynamically and CSS is loaded as an override theme
	*/
	mythemeAnchor: '#mytheme',
	/*
	Primary menu anchor point #js-primary-nav
	This is the root anchor point where the menu script will begin its build
	*/
	navAnchor: $('#js-primary-nav'), //changing this may implicate slimscroll plugin target
	navHooks: $('#js-nav-menu'), //changing this may implicate CSS targets
	navAccordion: true, //nav item when one is expanded the other closes
	navInitalized: 'js-nav-built', //nav finished class 
	navFilterInput: $('#nav_filter_input'), //changing this may implicate CSS targets
	navHorizontalWrapperId: 'js-nav-menu-wrapper',
	/*
	The rate at which the menu expands revealing child elements on click
	Lower rate reels faster expansion of nav childs
	*/
	navSpeed: 500, //ms
	/*
	Color profile reference hook (needed for getting CSS value for theme colors in charts and various graphs)
	*/
	mythemeColorProfileID: $('#js-color-profile'),
	/*
	Nav close and open signs
	This uses the fontawesome css class
	*/
	navClosedSign: 'fal fa-angle-down',
	navOpenedSign: 'fal fa-angle-up',
	/*
	App date ID
	found inside the breadcrumb unit, displays current date to the app on pageload
	*/
	appDateHook: $('.js-get-date'),
	/*
	* SaveSettings to localStorage
	* DOC: to store settings to a DB instead of LocalStorage see below:
	*    initApp.pushSettings("className1 className2") //sets value
	*    var DB_string = initApp.getSettings(); //returns setting string
	*/
	storeLocally: true,
	/*
	* Used with initApp.loadScripts
	* DOC: Please leave it blank
	*/
	jsArray : []
};
