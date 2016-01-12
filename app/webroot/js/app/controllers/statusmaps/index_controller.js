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

App.Controllers.StatusmapsIndexController = Frontend.AppController.extend({

	components: ['Ajaxloader'],

	_initialize: function() {

		this.Ajaxloader.setup();


		var height = $('.widget-body').offset().top;
		var height = parseInt(($(window).innerHeight() - height),10);
		$('#my_statusmap').css('height', height + 'px');

		sigma.classes.graph.addMethod('neighbors', function(nodeId) {
			var k,
				neighbors = {},
				index = this.allNeighborsIndex[nodeId] || {};

			for (k in index)
				neighbors[k] = this.nodesIndex[k];

			return neighbors;
		});

		jQuery.getJSON("/statusmaps/getHostsAndConnections.json", function(data){
			//For autocomplete
			var allHosts = data,
				$autocomplete_results = $("#autocomplete_results"),
				$my_node_id = $("#my_node_id"),
				$enterNode = $('#enter_node');

			$(document).on('click', 'a.autocomplete-items', function() {
				var $this = $(this);
				//console.log($this);
				//console.log($this.text());
				$enterNode.val($this.data('name'));
				$my_node_id.val($this.data('id'));
				$autocomplete_results.html('');
				$enterNode.focus();
			});

			$enterNode.on('keyup click',function(f) {
				//alert($(this).val());
				//console.log($("#enter_node").val());
				$autocomplete_results.html('');
				if(f.keyCode==13){
					return
				}else{
					allHosts.nodes.forEach(function(n){
						var regex = new RegExp($enterNode.val(), 'g');
						var matches = n.label.match(regex);
						if(matches){
							//console.log(n.label+' '+n.id);
							if(n.current_state == 0){
								color = '#449D44';
							}
							if(n.current_state == 1){
								color = '#C9302C';
							}
							if(n.current_state == 2){
								color = '#92A2A8';
							}
							$autocomplete_results.append($('<a/>', {
								'class': 'autocomplete-items',
								'html': n.label + "<br/>" + n.ip,
								'data': {
									'name': n.label,
									'id': n.id
								}
							}).css({
								'background-color': color,
								'display': 'block'
							}))
						}
					});
				}
			});
			//================
			var s = new sigma();
			s.addRenderer({
				type : 'canvas',
				container : 'my_statusmap'
			})
			s.settings({
				minArrowSize : 5,
				borderSize : 2,
				defaultNodeBorderColor : '#666666',
				sideMargin : 2,
				labelColor : 'node',
				defaultEdgeColor: "#BDD1D9",
				edgeColor: "default",
				//defaultNodeType: 'border',
				mouseWheelEnabled : true, //Mausrad Zoom
				batchEdgesDrawing : false, //Sollen Verbindungen während der Startanimation gezeigt werden
				animationsTime : 2000,
				doubleClickEnabled:false
			})

			var camera = s.camera;

			for (var i = 0; i < data.nodes.length; ++i){
				var elem = data.nodes[i];
				elem.x = Math.random();
				elem.y = Math.random();

				if(i == 0){
					elem.color = '#6e587a';
				}else{
					if(data.nodes[i].current_state == 0){
						elem.color = '#449D44';
					}
					if(data.nodes[i].current_state == 1){
						elem.color = '#C9302C';
					}
					if(data.nodes[i].current_state == 2){
						elem.color = '#92A2A8';
					}
				}

				//elem.type = 'rectangle'
				s.graph.addNode(elem);
			}

			for (i = 0; i < data.links.length; ++i){
				var link = data.links[i];
				link.type = 'arrow';
				s.graph.addEdge(link);
			}

			s.configForceAtlas2({
				gravity : 0.1,
				slowDown : 10
			});
			s.startForceAtlas2();


			// We first need to save the original colors of our
			// nodes and edges, like this:
			s.graph.nodes().forEach(function(n) {
				n.originalColor = n.color;
			});
			s.graph.edges().forEach(function(e) {
				e.originalColor = e.color;
			});

			// When a node is clicked, we check for each node
			// if it is a neighbor of the clicked one. If not,
			// we set its color as grey, and else, it takes its
			// original color.
			// We do the same for the edges, and we only keep
			// edges that have both extremities colored.
			s.bind('clickNode', function(e) {
				$("#autocomplete_results").html('');
				//console.log(e.data.node.id);
				var nodeId = e.data.node.id,
					toKeep = s.graph.neighbors(nodeId);
				toKeep[nodeId] = e.data.node;

				s.graph.nodes().forEach(function(n) {
					if (toKeep[n.id])
						n.color = n.originalColor;
					else
						n.color = '#eee';
				});

				s.graph.edges().forEach(function(e) {
					if (toKeep[e.source] && toKeep[e.target])
						e.color = e.originalColor;
					else
						e.color = '#eee';
				});


				// Since the data has been modified, we need to
				// call the refresh method to make the colors
				// update effective.
				s.refresh();
				// Zoom in - animation :

				sigma.misc.animation.camera(
					s.camera,{
						x: e.data.node[s.camera.readPrefix + 'x'],
						y: e.data.node[s.camera.readPrefix + 'y'],
						ratio: 0.3
					},{
						duration: s.settings('animationsTime')
					}
				);
				var $el = $('#divSmallBoxes').children();
				$el.hide(300,function(){
					$(this).remove();
				});
				setTimeout(function(){
					clicknode = e.data.node;
					//console.log(clicknode.id);
					//If clicked Node is rootnode (OpenITCOCKPIT) no Ajax request
					if(clicknode.id != 0){
						setTimeout(function(){
							var titleAndIconColor = 'rgb(90,90,90)';
							$.ajax({
								url: "/statusmaps/clickHostStatus/" + encodeURIComponent(clicknode.uuid),
								type: "POST",
								dataType: "html",
								//dataType: "json",
								error: function(){},
								success: function(){},
								complete: function(response){
									//console.log(response.responseText);
									$.smallBox({
										//class: 'statusmapInfoBox',
										title : 'Hoststatus',
										content : response.responseText,
										color : 'rgba(249, 249, 249, 1)',
										//timeout: 8000,
										icon : "fa fa-desktop"
									});
									$('.textoFoto').first('<span>').css({'color':titleAndIconColor});
									$('.textoFoto').css('color', titleAndIconColor);
									$('.foto').css({'color':titleAndIconColor});
									$('.SmallBox').addClass('statusmapInfoBox');
								}.bind(self)
							});
						}, 300);
					}
				 }, 2100);

			});
			$('#my_statusmap').dblclick(function(){
				s.graph.nodes().forEach(function(n) {
					n.color = n.originalColor;
				});
				s.graph.edges().forEach(function(e) {
					e.color = e.originalColor;
				});
				var $el = $('#divSmallBoxes').children();
				$el.hide(300,function(){
					$(this).remove();
				});
				// Same as in the previous event:
				s.refresh();
				sigma.misc.animation.camera(
					s.camera,{
						x: 0,
						y: 0,
						ratio: 1
					},{
						duration: s.settings('animationsTime')
					}
				);
			});

			//Nach Nodes suchen:
			var onSearchClick = function(){
				var my_node_id = $('#my_node_id').val();
				var searchFieldValue = $('#enter_node').val();
				var searchRegexp = new RegExp(searchFieldValue,'i');
				//console.log(my_node_id);
				if(my_node_id > 0){
					s.graph.nodes().forEach(function(e) {
						if(e.id == my_node_id){
							var fakeData = {
								node: e
							};
							// dispatch event
							s.dispatchEvent('clickNode', fakeData);
							//console.log(e);
						}
					});
				}else{
					//Gibt es mehr als ein zutreffendes Ergebniss (mehrmals selber Name)
					var allMatchesCount = $('#autocomplete_results').children().length;
					//console.log(allMatchesCount);
					if(allMatchesCount == 1){
						s.graph.nodes().forEach(function(e) {
							//console.log(e.label.match(searchRegexp).length);
							if(e.label.match(searchRegexp)){
								var fakeData = {
									node: e
								};
								// dispatch event
								s.dispatchEvent('clickNode', fakeData);
								//console.log(e);
							}
						});
					}else{
						var nodes = s.graph.nodes();
						for(var i = 0; i < nodes.length; i++){
							if(!nodes.hasOwnProperty(i)){
								continue;
							}
							var e = nodes[i];
							if(e.label.match(searchRegexp)){
								var fakeData = {
									node: e
								};
								// dispatch event

								s.dispatchEvent('clickNode', fakeData);
								//console.log(e);
								break;
							}
						}
					}

				}
				$('#my_node_id').val("");
			};

			$('#search_entered_node').on('click', onSearchClick);
			$('#enter_node').keypress(function(f){
				if(f.keyCode==13){
					onSearchClick();
				}
			});


		});
	}
});
