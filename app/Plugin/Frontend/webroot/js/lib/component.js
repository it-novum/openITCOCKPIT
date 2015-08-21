Frontend.Component = Class.extend({
	/**
	 * Holds the parent controller
	 *
	 * @var Frontend.Controller
	 */
	Controller: null,

	/**
	 * Class constructor
	 *
	 * @return void 
	 */
	init: function(controller) {
		this.Controller = controller;
		this._init();
	},
	_init: function() {},
	/**
	 * Startup callback - for overriding
	 *
	 * @return void 
	 */
	startup: function() {}
});