Frontend.Router = Class.extend({
	webroot: '',
	controller: '',
	action: '',
	named: {},
	currentUrl: '',
	urlDefaults: {
		controller: null,
		action: null,
		pass: [],
		named: {},
		prefix: '',
		plugin: null
	},
	/**
	 * Class constructor
	 *
	 * @param	Object	the appData object
	 * @return 	void 
	 */
	init: function(appData) {
		this.webroot = appData.webroot;
		this.controller = appData.controller;
		this.action = appData.action;
		this.named = appData.named;
		this.currentUrl = appData.url;
	},
	/**
	 * Constructs an url based on the given parameters.
	 * 
	 * The method also accepts an object containing at least the controller 
	 * and action keys (see this.urlDefaults). Otherwise it takes
	 * the function arguments.
	 * 
	 * @param string	controller 	The controller name in lower case
	 * @param string	action 		The controller action
	 * @param Array		pass		An array containing the pass params (/arg1/arg2/)
	 * @param Object 	named		An object containing the named params, indexed by param name
	 * @return string				The generated URL
	 */
	url: function(controller, action, pass, named, get) {
		if(typeof controller == 'object') {
			var params = jQuery.extend({}, this.urlDefaults, controller);
			var controller = params.controller;
			var action = params.action;
			var pass = params.pass;
			var named = params.named;
			var prefix = params.prefix;
			var get = params.get;
			var plugin = params.plugin;
		}
		
		if(plugin) {
			plugin = plugin + '/';
		} else {
			plugin = '';
		}
		
		var url = this.webroot + prefix + plugin + controller + '/' + action + '/';

		if(pass instanceof Array) {
			for(var i in pass) {
				url += pass[ i ] + '/';
			}
		}
		if(typeof named == 'object') {
			for(var key in named) {
				url += key + ':' + named[ key ] + '/';
			}
		}
		
		if(typeof get == 'object') {
			url += '?' + http_build_query(get);
		}
		return url;
	},
	/**
	 * Returns the complete, current URL of the site.
	 *
	 * @return void 
	 */
	getCurrentUrl: function() {
		return this.webroot + '/' + this.url;
	}
});