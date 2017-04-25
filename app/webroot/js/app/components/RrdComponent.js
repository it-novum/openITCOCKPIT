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

App.Components.RrdComponent = Frontend.Component.extend({
	last_fetched_graph_data: {},
	last_given_service_rules: [],
	plot: null,
	$selector: null,
	host_names: {},
	service_names: {},
	timeout_id: 0,
	color: null,
	$ajax_loader: null,
	timezoneOffset: 0,
	isUpdate: false,

	setup: function(conf){
		var self = this;

		//self.Ajaxloader.setup();

		conf = conf || {};
		self.url = conf.url || '/';
		self.width = conf.width || '100%';
		self.height = conf.height || '500px';
		self.host_and_service_uuids = conf.host_and_service_uuids || {};
		self.selector = conf.selector || null;
		self.color = conf.color || ['#57889c'];
		self.units = []; // The units are stored here after the data was retrieved via fetchRrdData().
		//self.dateformat = conf.dataformat || '%y.%0m.%0d %H:%M:%S';
		self.dateformat = conf.dateformat || 'd.m.y H:i:s';
		self.displayTooltip = conf.displayTooltip || true;
		self.display_threshold_lines = conf.display_threshold_lines || false;
		self.error_callback = conf.error_callback || function(response, status){};
		self.$selector = $(self.selector);
		self.start = conf.start || null;
		self.end = conf.end || null;
		self.async = conf.async != null ? conf.async : true;
		self.timeout_in_ms = conf.timeout_in_ms || 200; // Timeout in ms for redrawing the plot if it's moved or zoomed.
		self.max_drawing_point_threshold = conf.max_drawing_point_threshold || 500; // Maximum drawing points.
		self.$ajax_loader = $('#global_ajax_loader');
		self.timezoneOffset = conf.timezoneOffset || self.timezoneOffset;
		self.update_plot = typeof conf.update_plot == 'function' ? conf.update_plot : function(event, plot, action){
			var axes = plot.getAxes(),
				min = axes.xaxis.min.toFixed(2),
				max = axes.xaxis.max.toFixed(2),
				time_range = { // Convert the timestamp from ms to seconds.
					start: parseInt(min),
					end: parseInt(max),
					isUpdate: true
				};

			// Clear current timeout
			if(self.timeout_id > 0){
				clearTimeout(self.timeout_id);
			}

			// Set new timeout
			self.timeout_id = setTimeout(function(){
				self.update(time_range);
			}, self.timeout_in_ms);
		};
		self.flot_options = conf.flot_options == null ? {} : conf.flot_options;
		// currently unused
		self.threshold_lines = [];
		self.threshold_values = [];

		self.dsNames = [];

	},

	/**
	 * Fetches the data by the given configuration.
	 *
	 * @param {Object} conf - An object which contains at least the start and end timestamps for the requested time period.
	 * @param {Function} on_success [fn] - The function which will be called after the data has been successfuly fetched.
	 */
	fetchRrdData: function(conf, on_success){
		var self = this;

		on_success = typeof on_success === 'function' ? on_success : function(){};
		conf = conf || {};
		self.start = conf.start || self.start;
		self.end = conf.end || self.end;

		var post_data = {'host_and_service_uuids': self.host_and_service_uuids};
		post_data.isUpdate = conf.isUpdate || self.isUpdate;
		if(self.start !== null && self.end !== null){
			post_data.start = self.start;
			post_data.end = self.end;
		}
		//console.log('fetchRrdData(): post_data', post_data);

		this.xhr = $.ajax({
			type: 'post',
			url: self.url,
			dataType: 'json', // Data type of the response
			data: JSON.stringify(post_data),
			contentType: 'application/json',
			cache: false,
			async: self.async,
			success: function(response, status){
				// Count items and abort when there are too many to render them.
				var max_length = 1000,
					rrd_data_length = 0,
					service_uuid,
					host_uuid,
					service,
					host,
					rule;

				for(host_uuid in response.rrd_data){
					host = response.rrd_data[host_uuid];
					for(service_uuid in host){
						service = host[service_uuid];
						for(rule in service.data){
							rrd_data_length += Object.keys(service.data[rule]).length;
						}
					}
				}
				var console_message = 'The length of all points is ' + rrd_data_length + ' !';
				if(typeof console.warn === 'function' && rrd_data_length > max_length){
					console.warn(console_message);
					// or abort??
				}else if(typeof console.info === 'function'){
					//console.info(console_message);
				}

				// Clear the last fetched graph data.
				self.last_fetched_graph_data = {};
				for(host_uuid in response.rrd_data){
					host = response.rrd_data[host_uuid];
					for(service_uuid in host){
						service = host[service_uuid];
						var ds;
						$.each(service.xml_data, function(i, xml_object){
							ds = service.xml_data[i].ds;
							var dataObj = service.data[xml_object.ds];

							if(typeof self.last_fetched_graph_data[host_uuid] !== 'object'){
								self.last_fetched_graph_data[host_uuid] = {};
							}
							if(typeof self.last_fetched_graph_data[host_uuid][service_uuid] !== 'object'){
								self.last_fetched_graph_data[host_uuid][service_uuid] = {};
							}
							self.last_fetched_graph_data[host_uuid][service_uuid][ds] = [];

							// What is this used for?
							if(typeof self.threshold_values[host_uuid] !== 'object'){
								self.threshold_values[host_uuid] = {};
							}
							if(typeof self.threshold_values[host_uuid][service_uuid] !== 'object'){
								self.threshold_values[host_uuid][service_uuid] = {};
							}

							self.threshold_values[host_uuid][service_uuid][ds] = [];
							self.threshold_values[host_uuid][service_uuid][ds]['warn'] = xml_object.warn;
							self.threshold_values[host_uuid][service_uuid][ds]['crit'] = xml_object.crit;

							self.threshold_values[host_uuid][service_uuid][ds]['label'] = service.xml_data[i].label;

							if(typeof self.units[host_uuid] !== 'object'){
								self.units[host_uuid] = {};
							}
							$.each(dataObj, function(timestamp, RRDValue){
								self.last_fetched_graph_data[host_uuid][service_uuid][ds].push([timestamp, RRDValue]);

								if(typeof self.units[host_uuid][service_uuid] !== 'object'){
									self.units[host_uuid][service_uuid] = {};
								}
								self.units[host_uuid][service_uuid][ds] = service.xml_data[i].unit;
							});
						});
						self.host_names[host_uuid] = service.hostname;
						self.service_names[service_uuid] = service.servicename;

						// Not necessary at this moment
						if(self.display_threshold_lines === true){
							self.addThreshold(host_uuid, service_uuid, ds);
						}
					}
				}
				//console.log('last_fetched_graph_data', self.last_fetched_graph_data);
				on_success(self.last_fetched_graph_data);
			},
			error: self.error_callback
		});
	},

    /**
     * Renders the given graph data. If 'graph_data' isn't given, it renders what was last fetched via fetchRrdData().
     *
     * @param {Array} graph_data [] - The data to be rendered.
     * @param {Function} on_success [fn] - The function which will be called after the graph has been rendered.
     */
    renderGraph: function(graph_data, on_success){
        var self = this;
        on_success = typeof on_success == 'function' ? on_success : function(){};

        if(!graph_data){
            graph_data = [];
            for(var host_uuid in self.last_fetched_graph_data){
                var services = self.last_fetched_graph_data[host_uuid];
                for(var service_uuid in services){
                    var current_data = services[service_uuid],
                        host_name = self.host_names[host_uuid],
                        service_name = self.service_names[service_uuid],
                        units = self.units[host_uuid][service_uuid];

                    for(var ds_number in current_data){
                        graph_data.push({
                            //label: host_name + '/' + service_name + '/' + ds_number,
                            label: host_name + '/' + service_name + '/' + self.threshold_values[host_uuid][service_uuid][ds_number]['label'],
                            data: current_data[ds_number],
                            unit: units[ds_number]
                        })
                    }
                }
            }
        }
        self.$selector.css({
            'width': self.width,
            'height': self.height
        });

        var color_amount = graph_data.length < 3 ? 3 : graph_data.length,
            color_generator = new ColorGenerator(),
            // TODO remove the options here and place it somewhere where it makes more sense to have them. like the setup function.
            options = {
                //colors: ['#ed1b24', '#f1592a', '#f1592a', '#f8931f', '#fef200', '#8cc540', '#21b24b', '#09b3cd', '#2e3094', '#262163', '#652d92', '#92278f'],
                colors: color_generator.generate(color_amount, 90, 120),
                legend: {
                    show:true,
                    noColumns: 3,
                    container: $(self.selector).parent().find('.graph_legend'), // container (as jQuery object) to put legend in, null means default on top of graph
                },
                grid: {
                    hoverable: true,
                    markings: self.threshold_lines,
                    borderWidth: {
                        top: 1,
                        right: 1,
                        bottom: 1,
                        left: 1
                    },
                    borderColor: {
                        top: '#CCCCCC'
                    }
                },
                tooltip: true,
                tooltipOpts: {
                    defaultTheme: false
                },
                xaxis: {
                    mode: 'time',
                    timeformat: '%d.%m.%y %H:%M:%S', // This is handled by a plugin, if it is used -> jquery.flot.time.js
                    tickFormatter: function(val, axis){
                        var fooJS = new Date((val + self.timezoneOffset) * 1000);
                        var fixTime = function(value){
                            if(value < 10){
                                return '0' + value;
                            }
                            return value;
                        };
                        return fixTime(fooJS.getUTCDate()) + '.' + fixTime(fooJS.getUTCMonth() + 1) + '.' + fooJS.getUTCFullYear() + ' ' + fixTime(fooJS.getUTCHours()) + ':' + fixTime(fooJS.getUTCMinutes());
                    }
                },
                lines: {
                    show: true,
                    lineWidth: 1,
                    fill: true,
                    steps: 0,
                    fillColor : {
                        colors : [{
                            opacity : 0.5
                        },
                        {
                            opacity : 0.3
                        }]
                    }
                },
                points: {
                    show: false,
                    radius: 1
                },
                series: {
                    show: true,
                    labelFormatter: function(label, series){
                        // series is the series object for the label
                        return '<a href="#' + label + '">' + label + '</a>';
                    },
                },
                selection: {
                    mode: "x"
                },
            };

        $.extend(true, options, self.flot_options);

        var $container = self.$selector,
            plot_actions = ['plotpan', 'plotzoom'];

        $.each(plot_actions, function(i, action){
            $container
                .off(action)
                .off('contextmenu'); // Removes all previously bound functions.
            $container
                .on(action, function(event, plot){
                    console.info('test');
                    self.update_plot(event, plot, action);
                })
                .on('contextmenu', function(event){
                    event.preventDefault();
                    self.plot.zoomOut();
                    return false;
                });
        });

        self.plot = $.plot($container, graph_data, options);

        on_success(); // Callback

        $('#graph').bind('plotselected', function(event, ranges){
            $.each(self.plot.getXAxes(), function(_, axis) {
                var opts = axis.options;
                opts.min = ranges.xaxis.from;
                opts.max = ranges.xaxis.to;
            });
            self.plot.setupGrid();
            self.plot.draw();
            self.plot.clearSelection();
        });

        if(self.displayTooltip){
            self.initTooltip();
        }
    },
    /**
     * Renders the given graph data. If 'graph_data' isn't given, it renders what was last fetched via fetchRrdData().
     *
     * @param {Array} graph_data [] - The data to be rendered.
     * @param {Function} on_success [fn] - The function which will be called after the graph has been rendered.
     */
    renderGraphForBrowser: function(graph_data, on_success){
        var self = this;
        on_success = typeof on_success == 'function' ? on_success : function(){};
        var thresholdWarnValue = null;
        var thresholdCriticalValue = null;
        if(!graph_data){
            graph_data = [];
            for(var host_uuid in self.last_fetched_graph_data){
                var services = self.last_fetched_graph_data[host_uuid];
                for(var service_uuid in services){
                    var current_data = services[service_uuid],
                        host_name = self.host_names[host_uuid],
                        service_name = self.service_names[service_uuid],
                        units = self.units[host_uuid][service_uuid];

                    for(var ds_number in current_data){
                        graph_data.push({
                            label: host_name + ' / ' + service_name + ' / ' + self.threshold_values[host_uuid][service_uuid][ds_number]['label'],
                            data: current_data[ds_number],
                            unit: units[ds_number]
                        });
                        if(self.threshold_values[host_uuid][service_uuid][ds_number]['warn']){
                            thresholdWarnValue = self.threshold_values[host_uuid][service_uuid][ds_number]['warn'];
                        }
                        if(self.threshold_values[host_uuid][service_uuid][ds_number]['crit']){
                            thresholdCriticalValue = self.threshold_values[host_uuid][service_uuid][ds_number]['crit'];
                        }
                    }
                }
            }
        }
        self.$selector.css({
            'width': self.width,
            'height': self.height
        });

        var thresholdArray = [];
        var invertedWarnAndCriticalValues = false; // if warn value is greater than critical value, for example Disk Size
        if((thresholdWarnValue !== null && thresholdCriticalValue !== null) && Number(thresholdWarnValue) > Number(thresholdCriticalValue)){
            invertedWarnAndCriticalValues = true;
        }
        var defaultColor = 'green';
        if(invertedWarnAndCriticalValues){
            if(thresholdWarnValue !== null){
                thresholdArray.push({below:thresholdWarnValue,color:'#FFFF00'});
            }
            if(thresholdCriticalValue !== null){
                thresholdArray.push({below:thresholdCriticalValue,color:'#FF0000'});
            }
        }else{
            defaultColor = '#FF0000';
            if(thresholdCriticalValue !== null){
                thresholdArray.push({below:thresholdCriticalValue,color:'#FFFF00'});
            }
            if(thresholdWarnValue !== null){
                thresholdArray.push({below:thresholdWarnValue,color:'green'});
            }
        }
        var options = {
            legend: {
                show: false,
            },
            grid: {
                hoverable: true,
                markings: self.threshold_lines,
                borderWidth: {
                    top: 1,
                    right: 1,
                    bottom: 1,
                    left: 1
                },
                borderColor: {
                    top: '#CCCCCC'
                }
            },
            tooltip: true,
            tooltipOpts: {
                defaultTheme: false
            },
            xaxis: {
                mode: 'time',
                timeformat: '%d.%m.%y %H:%M:%S', // This is handled by a plugin, if it is used -> jquery.flot.time.js
                tickFormatter: function(val, axis){
                    //return date("d.m.y H:i:s", val + self.timezoneOffset + (new Date().getTimezoneOffset() * 60)); // <-- this returns local time or other crap and will not work!!
                    var fooJS = new Date((val + self.timezoneOffset) * 1000);
                    var fixTime = function(value){
                        if(value < 10){
                            return '0' + value;
                        }

                        return value;
                    };
                    return fixTime(fooJS.getUTCDate()) + '.' + fixTime(fooJS.getUTCMonth() + 1) + '.' + fooJS.getUTCFullYear() + ' ' + fixTime(fooJS.getUTCHours()) + ':' + fixTime(fooJS.getUTCMinutes());
                }
            },
          /*  yaxis: {
                tickFormatter: function(val, axis){
                    console.log(val);
                    return '$' + val
                },
                max: 1200
            },*/
            lines: {
                show: true,
                lineWidth: 1,
                fill: true,
                fillColor : {
                    colors : [{
                        opacity : 0.4
                    },
                    {
                        opacity : 0.3
                    },
                    {
                        opacity : 0.9
                    }]
                }
            },
            points: {
                show: false,
                radius: 1
            },
            series: {
                color: defaultColor,
                threshold: thresholdArray,
            },
            selection: {
                mode: "x"
            },
        };


        $.extend(true, options, self.flot_options);

        var $container = self.$selector,
            plot_actions = ['plotpan', 'plotzoom'];

        $.each(plot_actions, function(i, action){
            $container
                .off(action)
                .off('contextmenu'); // Removes all previously bound functions.
            $container
                .on(action, function(event, plot){
                    self.update_plot(event, plot, action);
                })
                .on('contextmenu', function(event){
                    event.preventDefault();
                    //self.plot.zoomOut({amount:1.2,center: {left:0, top:450}});
                    self.plot.zoomOut();
                    return false;
                });
        });

        self.plot = $.plot($container, graph_data, options);
        on_success(); // Callback

        $('#graph').bind('plotselected', function(event, ranges){
            $.each(self.plot.getXAxes(), function(_, axis) {
                var opts = axis.options;
                opts.min = ranges.xaxis.from;
                opts.max = ranges.xaxis.to;
            });
            self.plot.setupGrid();
            self.plot.draw();
            self.plot.clearSelection();
        });



        if(self.displayTooltip){
            self.initTooltip();
        }
    },

    /**
     * Renders the given graph data. If 'graph_data' isn't given, it renders what was last fetched via fetchRrdData().
     *
     * @param {Array} graph_data [] - The data to be rendered.
     * @param {Function} on_success [fn] - The function which will be called after the graph has been rendered.
     */
    renderGraphForPopup: function(graph_data, on_success){
        var self = this;
        on_success = typeof on_success == 'function' ? on_success : function(){};

        if(!graph_data){
            graph_data = [];
            for(var host_uuid in self.last_fetched_graph_data){
                var services = self.last_fetched_graph_data[host_uuid];
                for(var service_uuid in services){
                    var current_data = services[service_uuid],
                        host_name = self.host_names[host_uuid],
                        service_name = self.service_names[service_uuid],
                        units = self.units[host_uuid][service_uuid];

                    for(var ds_number in current_data){
                        graph_data.push({
                            //label: host_name + '/' + service_name + '/' + ds_number,
                            label: host_name + '/' + service_name + '/' + self.threshold_values[host_uuid][service_uuid][ds_number]['label'],
                            data: current_data[ds_number],
                            unit: units[ds_number]
                        })
                    }
                }
            }
        }
        self.$selector.css({
            'width': self.width,
            'height': self.height
        });
        var color_amount = graph_data.length < 3 ? 3 : graph_data.length,
            color_generator = new ColorGenerator(),
            // TODO remove the options here and place it somewhere where it makes more sense to have them. like the setup function.
            options = {
                //colors: ['#ed1b24', '#f1592a', '#f1592a', '#f8931f', '#fef200', '#8cc540', '#21b24b', '#09b3cd', '#2e3094', '#262163', '#652d92', '#92278f'],
                colors: color_generator.generate(color_amount, 90, 90),
                legend: {
                    show: false,
                },
                grid: {
                    hoverable: true,
                    markings: self.threshold_lines,
                    borderWidth: {
                        top: 1,
                        right: 1,
                        bottom: 1,
                        left: 1
                    },
                    borderColor: {
                        top: '#CCCCCC'
                    }
                },
                tooltip: true,
                tooltipOpts: {
                    defaultTheme: false
                },
                xaxis: {
                    mode: 'time',
                    timeformat: '%d.%m.%y %H:%M:%S', // This is handled by a plugin, if it is used -> jquery.flot.time.js
                    //timezone: 'browser' // This was addec by a plugin -> jquery.flot.time.js
                    // mode: 'time',
                    //zoomRange: [0.1, 10],
                    //panRange: [-10, 10]
                    tickFormatter: function(val, axis){
                        //return date("d.m.y H:i:s", val + self.timezoneOffset + (new Date().getTimezoneOffset() * 60)); // <-- this returns local time or other crap and will not work!!
                        var fooJS = new Date((val + self.timezoneOffset) * 1000);
                        var fixTime = function(value){
                            if(value < 10){
                                return '0' + value;
                            }

                            return value;
                        };
                        return fixTime(fooJS.getUTCDate()) + '.' + fixTime(fooJS.getUTCMonth() + 1) + '.' + fooJS.getUTCFullYear() + ' ' + fixTime(fooJS.getUTCHours()) + ':' + fixTime(fooJS.getUTCMinutes());

                    }
                },
                yaxes: {
                    tickFormatter: function(val, axis){
                        return '$' + val
                    },
                    max: 1200
                    //zoomRange: [0.1, 10],
                    //panRange: [-10, 10]
                },
                series: {
                    lines: {
                        lineWidth: 1,
                        fill: true,
                        fillColor: {
                            colors: [{
                                opacity: 0.5
                            }, {
                                opacity: 0.2
                            }]
                        },
                        steps: false

                    }
                },
                points: {
                    show: false,
                    radius: 1
                },
            };

        $.extend(true, options, self.flot_options);

        var $container = self.$selector,
            plot_actions = ['plotpan', 'plotzoom'];

        $.each(plot_actions, function(i, action){
            $container
                .off(action)
                .off('contextmenu'); // Removes all previously bound functions.
            $container
                .on(action, function(event, plot){
                    self.update_plot(event, plot, action);
                })
                .on('contextmenu', function(event){
                    event.preventDefault();
                    self.plot.zoomOut();
                    return false;
                });
        });

        self.plot = $.plot($container, graph_data, options);

        on_success(); // Callback

        if(self.displayTooltip){
            self.initTooltip();
        }
    },

	/**
	 * This function updates the plot on pan or zoom.
	 *
	 * It differentiates between one data source and many data sources, chooses the right source automatically and
	 * updates the plot properly.
	 *
	 * @param {Object} config - The configuration. Contains the timerange only.
	 * @param {Function} before_fetch [fn] - A function that will be called just before the data is fetched.
	 * @param {Function} after_render [fn] - A function that will be called after the data has been fetched and rendered.
	 */
	update: function(config, before_fetch, after_render){
		var self = this;

		before_fetch = typeof before_fetch == 'function' ? before_fetch : function(){};
		after_render = typeof after_render == 'function' ? after_render : function(){};
		config = config || {};

		before_fetch();

		self.fetchRrdData(config, function(){
			var data = [],
				service_rules = self.last_given_service_rules,
				host_name,
				service_name,
				service_rule_name,
				ds_number,
				host_uuid,
				service_uuid;

			if(Object.keys(service_rules).length){
				// When the service rules have been used, use them again to update the plot.
				for(host_uuid in service_rules){
					for(service_uuid in service_rules[host_uuid]){
						for(ds_number in service_rules[host_uuid][service_uuid]){
							var service_rule = service_rules[host_uuid][service_uuid][ds_number];

							host_name = service_rule.host_name;
							service_name = service_rule.service_name;
							service_rule_name = service_rule.service_rule_name;

							var label = host_name + '/' + service_name + '/' + service_rule_name,
								tmp_data = self.last_fetched_graph_data[host_uuid][service_uuid][ds_number],
								unit = self.units[host_uuid][service_uuid][ds_number];

							data.push({
								label: label,
								data: tmp_data,
								unit: unit
							});
						}
					}
				}
			}else{
				// Otherwise, simply use what was retrieved from the last time when fetchRrdData() was used.
				for(host_uuid in self.last_fetched_graph_data){
					var services = self.last_fetched_graph_data[host_uuid];
					for(service_uuid in services){
						var current_data = services[service_uuid];

						host_name = self.host_names[host_uuid];
						service_name = self.service_names[service_uuid];

						for(ds_number in current_data){
							data.push({
								label: host_name + '/' + service_name + '/' + ds_number,
								data: current_data[ds_number],
								unit: self.units[host_uuid][service_uuid][ds_number]
							})
						}
					}
				}
			}

			self.plot.setData(data);
			self.plot.setupGrid();
			self.plot.draw();

			after_render();
		});
	},

	/**
	 * @param {Object} service_rules - The service rules.
	 * @param {Object} timerange - The time range with 'start' and 'end'.
	 * @param {Function} on_success - The callback function which will be called after the data has been
	 *                                succesfully fetched and drawn.
	 */
	drawServiceRules: function(service_rules, timerange, on_success){
		var self = this;

		on_success = typeof on_success == 'function' ? on_success : function(){};

		if(Object.keys(service_rules).length == 0){
			return;
		}

		self.last_given_service_rules = service_rules;
		self.fetchRrdData(timerange, function(){
			self.resetGraph();

			if(Object.keys(self.last_fetched_graph_data).length == 0){
				return; // No service rule is chosen for example.
			}

			var plot_graph_data = [];
			for(var host_uuid in service_rules){
				for(var service_uuid in service_rules[host_uuid]){
					for(var ds_number in service_rules[host_uuid][service_uuid]){
						var service_rule = service_rules[host_uuid][service_uuid][ds_number],
							host_name = service_rule.host_name,
							service_name = service_rule.service_name,
							service_rule_name = service_rule.service_rule_name,
							label = host_name + '/' + service_name + '/' + service_rule_name,
							temp_data = self.last_fetched_graph_data[host_uuid][service_uuid][ds_number],
							unit = self.units[host_uuid][service_uuid][ds_number];

						plot_graph_data.push({
							label: label,
							data: temp_data,
							unit: unit
						});
					}
				}
			}

			self.renderGraph(plot_graph_data, function(){
				//self.$ajax_loader.hide();
				on_success();
			});
			//self.Ajaxloader.hide();
		});
	},

	initTooltip: function(){
		var self = this,
			previousPoint = null,
			$graph_data_tooltip = $('#graph_data_tooltip');

		$graph_data_tooltip.css({
			position: 'absolute',
			display: 'none',
			//border: '1px solid #666',
			'border-top-left-radius': '5px',
			'border-top-right-radius': '0',
			'border-bottom-left-radius': '0',
			'border-bottom-right-radius': '5px',
			padding: '2px 4px',
			'background-color': '#f2f2f2',
			'border-radius': '5px',
			opacity: 0.9,
			'box-shadow': '2px 2px 3px #888',
			transition: 'all 1s'
		});

		$(this.selector).bind('plothover', function(event, pos, item){
			$('#x').text(pos.pageX.toFixed(2));
			$('#y').text(pos.pageY.toFixed(2));

			if(item){
				if(previousPoint != item.dataIndex){
					previousPoint = item.dataIndex;

					$('#graph_data_tooltip').hide();
					// Unused?
					//var x = item.datapoint[0].toFixed(2),
					//	y = item.datapoint[1].toFixed(2);

					var value = item.datapoint[1];
					if(!isNaN(value) && isFinite(value)){
						value = value.toFixed(4);
					}
					var tooltip_text = value;
					if(item.series['unit']){
						tooltip_text += ' ' + item.series.unit;
					}

					self.showTooltip(item.pageX, item.pageY, tooltip_text, item.datapoint[0]);
				}
			}else{
				$("#graph_data_tooltip").hide();
				previousPoint = null;
			}
		});
	},

	showTooltip: function(x, y, contents, timestamp){
		var self = this,
			//current_date = new Date(),
			//humanTime = date(self.dateformat, timestamp + self.timezoneOffset + (new Date().getTimezoneOffset() * 60)),
			$graph_data_tooltip = $('#graph_data_tooltip');

			var fooJS = new Date((timestamp + self.timezoneOffset) * 1000);
			var fixTime = function(value){
				if(value < 10){
					return '0' + value;
				}

				return value;
			};

			var humanTime = fixTime(fooJS.getUTCDate()) + '.' + fixTime(fooJS.getUTCMonth() + 1) + '.' + fooJS.getUTCFullYear() + ' ' + fixTime(fooJS.getUTCHours()) + ':' + fixTime(fooJS.getUTCMinutes());

		$graph_data_tooltip
			.html('<i class="fa fa-clock-o"></i> ' + humanTime + '<br /><strong>' + contents + '</strong>')
			.css({
				top: y,
				left: x + 10
			})
			.appendTo('body')
			.fadeIn(200);
	},

	addThreshold: function(host_uuid, service_uuid, ds){
		this.threshold_lines = [];
		this.ds = ds;
		if($.isNumeric(this.ds) && this.threshold_values[host_uuid][service_uuid][this.ds]['warn'] !== '' && this.threshold_values[host_uuid][service_uuid][this.ds]['crit'] !== ''){
			this.threshold_lines.push({
				color: '#FFFF00',
				yaxis: {
					from: this.threshold_values[host_uuid][service_uuid][this.ds]['warn'],
					to: this.threshold_values[host_uuid][service_uuid][this.ds]['warn']
				}
			});
			this.threshold_lines.push({
				color: '#FF0000',
				yaxis: {
					from: this.threshold_values[host_uuid][service_uuid][this.ds]['crit'],
					to: this.threshold_values[host_uuid][service_uuid][this.ds]['crit']
				}
			});
		}else if($.isArray(this.ds)){
			for(var ds_key in this.ds){
				var ds_value = this.ds[ds_key];

				this.threshold_lines.push({
					color: '#FFFF00',
					yaxis: {
						from: this.threshold_values[ds_value]['warn'],
						to: this.threshold_values[ds_value]['warn']
					}
				});
				this.threshold_lines.push({
					color: '#FF0000',
					yaxis: {
						from: this.threshold_values[ds_value]['crit'],
						to: this.threshold_values[ds_value]['crit']
					}
				});
			}
		}
	},

	removeThreshold: function(){
		this.threshold_lines = [];
	},

	/**
	 * Resets the graph.
	 */
	resetGraph: function(){
		if(this.$selector && this.$selector.length){
			this.$selector.html('');
			this.$selector.css({'width': '0px', 'height': '0px'});
		}
	},

	randomRgbColor: function(){
		var r = this.getRandomInt(0, 200);
		var g = this.getRandomInt(0, 200);
		var b = this.getRandomInt(0, 200);

		return 'rgb(' + r + ',' + g + ',' + b + ')';
	},

	randomHexColor: function(){
		var r = this.getRandomInt(0, 200).toString(16);
		var g = this.getRandomInt(0, 200).toString(16);
		var b = this.getRandomInt(0, 200).toString(16);

		return '#' + r + g + b;
	},

	getRandomInt: function(min, max){
		return Math.floor(Math.random() * (max - min)) + min;
	},

	getTimeoutId: function(){
		return self.timeout_id;
	},

	setTimeoutId: function(timeout_id){
		self.timeout_id = timeout_id;
	},

	bindPopup: function(conf){
		var conf = conf || {};

		this.Time = conf.Time || null;
		if(this.Time === null){
			return alert('ERROR: TimeComponent is missing!');
		}

		this.timezoneOffset = this.Time.timezoneOffset;

		this.windowHeight = $(window).innerHeight();
		var _this = this;
		$('.popupGraph').mouseover(function(e){
			var $this = $(this);
			var offset = $this.offset();
			_this.popupGraph($this.attr('host-uuid'), $this.attr('service-uuid'), offset);
		});

		$('.popupGraph').mouseleave(function(){
			$('#popupGraphContainer').hide();
			$('#popupGraphContainer').html('<div id="graph_legend"></div><div id="popupGraph"><center><br /><i class="fa fa-cog fa-4x fa-spin"></i><br /><br /></div></center>');

			//Kill the last ajax request, if the user jump over the icons
			this.xhr.abort();

		}.bind(this));

		//Create div for popup graph
		$('body').append('<div id="popupGraphContainer"><div id="graph_legend"></div><div id="popupGraph"><center><br /><i class="fa fa-cog fa-4x fa-spin"></i><br /><br /></div></center></div>');
	},

	popupGraph: function(hostUuid, serviceUuid, offset){
		var $popupGraphContainer = $('#popupGraphContainer');

		var margin = 15;

		var currentScrollPosition = $(window).scrollTop();


		if((offset.top - currentScrollPosition + margin + $popupGraphContainer.height()) > this.windowHeight){
			//Ther is no space in the window for the popup, we need to set it to an higher point
			$popupGraphContainer.css({
				'top': parseInt(offset.top - $popupGraphContainer.height() - margin + 10),
				'left': parseInt(offset.left + margin),
				'padding': '6px'
			});
		}else{
			//Default Popup
			$popupGraphContainer.css({
				'top': parseInt(offset.top + margin),
				'left': parseInt(offset.left + margin),
				'padding': '6px'
			});
		}


		var host_and_service_uuids = {};
		host_and_service_uuids[hostUuid] = [serviceUuid];

		/*this.setup({
			'url':'/Graphgenerators/fetchGraphData.json',
			'selector': '#popupGraphContainer',
			//'display_threshold_lines': false,
			'width': $popupGraphContainer.innerWidth(),
			'height': '300px',
		});*/
		this.url = '/Graphgenerators/fetchGraphData.json';
		this.host_and_service_uuids = host_and_service_uuids;
		this.selector = '#graph';
		//this.ds = $('#GraphgeneratorServicerule').val();
		this.display_threshold_lines = false;

		this.width = $popupGraphContainer.innerWidth();
		this.height = '250px';
		this.$selector = $('#popupGraph');
		this.threshold_values = [];
		this.units = [];

		this.dsNames = [];

		var current_time = parseInt(this.Time.getCurrentTimeWithOffset(0).getTime() / 1000, 10),
		time_period = { // Current timestamp.
			start: current_time - (60 * 60 * 2),
			end: current_time
		};


		this.fetchRrdData(time_period, function(){
			this.renderGraphForPopup();

			var margin = 15;

			var currentScrollPosition = $(window).scrollTop();

			if((offset.top - currentScrollPosition + margin + $popupGraphContainer.height()) > this.windowHeight){
				//Ther is no space in the window for the popup, we need to set it to an higher point
				$popupGraphContainer.css({
					'top': parseInt(offset.top - $popupGraphContainer.height() - margin + 10),
					'left': parseInt(offset.left + margin),
					'padding': '6px'
				});
			}else{
				//Default Popup
				$popupGraphContainer.css({
					'top': parseInt(offset.top + margin),
					'left': parseInt(offset.left + margin),
					'padding': '6px'
				});
			}
		}.bind(this));

		$popupGraphContainer.show();
	}
});
