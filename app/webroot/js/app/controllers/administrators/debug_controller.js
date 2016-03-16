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

App.Controllers.AdministratorsDebugController = Frontend.AppController.extend({

	components: ['WebsocketSudo'],

	argumentNames: null,

	_initialize: function() {
		if(this.getVar('renderGraph') === true){
			$('#loadGraph').css('height', '300px');
			$('.graph_legend').show();
			//console.log(this.getVar('graphData'));
			var line1 = [];
			var line5 = [];
			var line15 = [];
			
			var data = this.getVar('graphData');
			for(var timestamp in data[1]){
				line1.push([timestamp, data[1][timestamp]]);
			}
			
			for(var timestamp in data[5]){
				line5.push([timestamp, data[5][timestamp]]);
			}
			
			for(var timestamp in data[15]){
				line15.push([timestamp, data[15][timestamp]]);
			}
			
			var options = {
				xaxis: { mode: "time"},

				series : {
					lines : {
						show : true,
						lineWidth : 1,
						fill : false,
						fillColor : {
							colors : [{
								opacity : 0.1
							}, {
								opacity : 0.15
							}]
						}
					},
					points: { show: false},
					shadowSize : 0
				},

				grid : {
					hoverable : false,
					clickable : false,
					tickColor : '#efefef',
					borderWidth : 0,
					borderColor : '#efefef',
				},
				tooltip : false,
				colors : ['#6595B4', '#7E9D3A', '#E24913'],

			};

			var plot = $.plot($("#loadGraph"), [line1, line5, line15], options);
		}

		this.WebsocketSudo.setup(this.getVar('websocket_url'), this.getVar('akey'));
		this.WebsocketSudo._errorCallback = function(){
			$('#error_msg').html('<div class="alert alert-danger alert-block"><a href="#" data-dismiss="alert" class="close">×</a><h5 class="alert-heading"><i class="fa fa-warning"></i> Error</h5>Could not connect to SudoWebsocket Server</div>');
		}
		this.WebsocketSudo.connect();
	}
	
});