/**
 * Implements the Publish-Subscribe pattern. Used by Frontend.App 
 * to ease events communication between components.
 */
Frontend.PublishSubscribeBroker = Class.extend({
	/**
	 * Holds the topics and their subscribers
	 *
	 * @var obj 
	 */
	_topics: {},
	/**
	 * Simple sequence for subscription IDs
	 *
	 * @var int
	 */
	_subscriptionIdCounter: 0,
	/**
	 * Subscribe to a topic
	 *
	 * @param 	string		topic 	The topic identifier, e.g. "selectors.selectorChanged"
	 * @param 	Function	handler Function will be called when the topic is published
	 * @param 	obj			scope	If given, handler will be applied with this scope
	 * @return	obj			Object containing the topic and the subscription id. This object
	 * 						must be given to unsubscribe()
	 */
	subscribe: function(topic, handler, scope) {
		if(typeof this._topics[ topic ] == 'undefined') {
			this._topics[ topic ] = new Array;
		}
		if(scope != undefined) {
			handler = hitch(handler, scope);
		}
		var subscriptionId = ++this._subscriptionIdCounter;
		this._topics[ topic ][ subscriptionId ] = handler;
		return {
			topic:	topic,
			id:		subscriptionId
		};
	},
	/**
	 * Unsubscribe from a topic.
	 *
	 * @param obj	subscriptionHandle	Object containing the topic and the subscription id
	 * @return void 
	 */
	unsubscribe: function(subscriptionHandle) {
		var topic = subscriptionHandle.topic;
		var subscriptionId = subscriptionHandle.id;
		delete this._topics[ topic ][ subscriptionId ];
	},
	/**
	 * Publish a topic.
	 *
	 * @param {string} 	topic	The topic identifier
	 * @param {mixed} 	data	This data will be given the subscription handler as function param 
	 * @return void 
	 */
	publish: function(topic, data) {
		if(typeof this._topics[ topic ] == 'undefined') {
			this._topics[ topic ] = [];
		}
		for(var i in this._topics[ topic ]) {
			this._topics[ topic ][i](data);
		}
	}
});