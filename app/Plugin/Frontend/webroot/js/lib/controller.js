Frontend.Controller = Class.extend({
	/**
	 * Controller vars set server-side with Frontend->setJson()
	 *
	 * @var obj
	 */
	_frontendData: {},
	/**
	 * String list of the used components
	 *
	 * @var Array
	 */
	components: [],
	baseComponents: [],
	/**
	 * Holds a reference to the parentController, if set.
	 *
	 * @return Controller
	 */
	parentController: null,
	name: null,
	action: null,
	/**
	 * Class constructor
	 *
	 * @param 	obj 	vars	The controller vars made available by setJson()
	 * @param	Controller	parentController	(optional) Parent controller instance.
	 * @return	void 
	 */
	init: function(frontendData, parentController) {
		this.parentController = parentController;
		this._frontendData = frontendData;
		this.name = this._frontendData.params.controller;
		this.action = this._frontendData.params.action;
		this.__initComponents();
		this._init();
	},
	/**
	 * Returns the contents of a view var, otherwise null
	 *
	 * @param string	key 	The var name
	 * @return mixed
	 */
	getVar: function(key) {
		if(typeof this._frontendData.jsonData[ key ] != 'undefined') {
			return this._frontendData.jsonData[ key ];
		}
		return null;
	},
	/**
	 * Startup callback - can be implemented by sub controllers
	 *
	 * @return void 
	 */
	_init: function() {},
	/**
	 * Initializes the configured components
	 *
	 * @return void 
	 */
	__initComponents: function() {
		for(var i in this.baseComponents) {
			this.components.push(this.baseComponents[ i ]);
		}
		for(var i in this.components) {
			var name = this.components[i] + 'Component';
			if(typeof window['App']['Components'][ name ] == "function") {
				this[ this.components[i] ] = new window['App']['Components'][ name ](this);
				this[ this.components[i] ].startup();
			}
			else if(typeof this.components[i] == 'string') {
				console.error("Component %s not found", this.components[i]);
			}
		}
	},
	/**
	 * Makes an AJAX request to the server and returns the results.
	 *
	 * @param mixed		url		Either a string url or a Router-compatible url object
	 * @param Object	data	(optional)	POST data
	 * @param Function	responseCallback	The function which will receive the response 
	 * @return void 
	 */
	request: function(url, data, responseCallback) {
		App.Main.request(url, data, responseCallback);
	},
	isAjax: function() {
		return this.getVar('isAjax') === true;
	}
});