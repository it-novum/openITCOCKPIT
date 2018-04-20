angular.module('openITCOCKPIT')
    .controller('StatusmapsIndexController', function($scope, $http, QueryStringService){

        /*** Filter Settings ***/
        $scope.filter = {
            Host: {
                name: QueryStringService.getValue('filter[Host.name]', ''),
                address:  QueryStringService.getValue('filter[Host.address]', ''),
                satellite_id: ['0']
            }
        };

        $scope.init = true;
        $scope.mutex = false;

        $scope.nodes = new vis.DataSet();
        $scope.edges = new vis.DataSet();

        $scope.container = document.getElementById('statusmap');

        var offset = $($scope.container).offset();
        var height = (window.innerHeight-offset.top);

        $($($scope.container)).css({
            'height': height
        });

        $scope.load = function(){
            $scope.mutex = true;
            var params = {
                'angular': true,
                'page': $scope.currentPage,
                'filter[Host.name]': $scope.filter.Host.name,
                'filter[Host.address]': $scope.filter.Host.address,
                'filter[Host.satellite_id][]': $scope.filter.Host.satellite_id
            };
            $http.get("/statusmaps/index.json", {
                params: params
            }).then(function(result){
                var nodesData = result.data.statusMap.nodes;
                var edgesData = result.data.statusMap.edges;
                $scope.init = false;
                if(nodesData.length > 0 && edgesData.length > 0){
                    $('#statusmap-progress-icon').show();
                    $scope.loadVisMap(nodesData, edgesData);
                }
                $scope.mutex = false;
            });
        };

        $scope.resetVis = function(){
            if(!$scope.init){
                $('#statusmap-progress-icon div:first').attr('data-progress', 0);
                $('#statusmap-progress-icon').show();
                $($scope.container).html('');
            }
        };

        $scope.loadVisMap = function(nodesData, edgesData){
            $scope.nodes.clear();
            $scope.edges.clear();
            var network = null;

            var colorUp = '#449D44';
            var colorDown = '#C9302C';
            var colorUnreachable = '#92A2A8';
            var colorNotMonitored = '#428bca';

/*
            var options = {
                clickToUse: false,
                groups: {
                    satellite:{
                        shape:'ellipse',
                        margin: {
                            top: 10,
                            bottom: 20,
                            left: 5,
                            right: 5
                        },
                    },
                    notMonitored: {
                        shape: 'icon',
                        color: colorNotMonitored, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf070',
                            color: colorNotMonitored //color for icon
                        }
                    },
                    disabled: {
                        shape: 'icon',
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf1e6'
                        }
                    },
                    hostUp: {
                        shape: 'icon',
                        color: colorUp, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf058',
                            color: colorUp
                        },
                    },
                    hostDown: {
                        shape: 'icon',
                        color: colorDown,
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf06a',
                            color: colorDown
                        }
                    },
                    hostUnreachable: {
                        shape: 'icon',
                        color: colorUnreachable,
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf059',
                            color: colorUnreachable
                        }
                    },
                    isInDowntimeUp: {
                        shape: 'icon',
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf011',
                            color: colorUp
                        }
                    },
                    isInDowntimeDown: {
                        shape: 'icon',
                        color: colorDown,
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf011',
                            color: colorDown
                        }
                    },
                    isInDowntimeUnreachable: {
                        shape: 'icon',
                        color: colorUnreachable,
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf011',
                            color: colorUnreachable
                        }
                    },
                    isAcknowledgedUp: {
                        shape: 'icon',
                        color: colorUp, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf007',
                            color: colorUp
                        }
                    },
                    isAcknowledgedDown: {
                        shape: 'icon',
                        color: colorDown,
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf007',
                            color: colorDown
                        }
                    },
                    isAcknowledgedUnreachable: {
                        shape: 'icon',
                        color: colorUnreachable,
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf007',
                            color: colorUnreachable
                        }
                    },
                    isAcknowledgedAndIsInDowntimeUp: {
                        shape: 'icon',
                        color: colorUp, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf0f0',
                            color: colorUp
                        }
                    },
                    isAcknowledgedAndIsInDowntimeDown: {
                        shape: 'icon',
                        color: colorDown,
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf0f0',
                            color: colorDown
                        }
                    },
                    isAcknowledgedAndIsInDowntimeUnreachable: {
                        shape: 'icon',
                        color: colorUnreachable,
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf0f0',
                            color: colorUnreachable
                        }
                    },
                },

                nodes: {
                    shapeProperties: {
                        interpolation: false    // 'true' for intensive zooming
                    },
                    shadow:{
                        enabled: true,
                        color: '#cccccc',
                        size:1,
                        x:1,
                        y:1
                    },
                    scaling: {
                        min: 10,
                        max: 30,
                    },
                    font: {
                        size: 20,
                    }
                },
                edges: {
                    width: 2,
                    smooth: {
                        enabled: true,
                        type: 'continuous'
                    }
                },

                interaction: {
                    hover: true,
                    dragNodes: false,
                    keyboard: {
                        enabled: false
                    },
                    hideEdgesOnDrag: true

                },
                physics: {
                    barnesHut: {
                        gravitationalConstant: -80000,
                        springConstant: 0.001,
                        springLength: 200
                    }
                },
                layout: {
                    randomSeed: 1000,
                    improvedLayout: false
                }
            };
*/

            var options = {
                groups: {
                    satellite:{
                        shape:'ellipse',
                        margin: {
                            top: 10,
                            bottom: 20,
                            left: 5,
                            right: 5
                        },
                    },
                    notMonitored: {
                        shape: 'icon',
                        color: colorNotMonitored, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf070',
                            color: colorNotMonitored //color for icon
                        }
                    },
                    disabled: {
                        shape: 'icon',
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf1e6'
                        }
                    },
                    hostUp: {
                        shape: 'icon',
                        color: colorUp, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf058',
                            color: colorUp
                        },
                    },
                    hostDown: {
                        shape: 'icon',
                        color: colorDown,
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf06a',
                            color: colorDown
                        }
                    },
                    hostUnreachable: {
                        shape: 'icon',
                        color: colorUnreachable,
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf059',
                            color: colorUnreachable
                        }
                    },
                    isInDowntimeUp: {
                        shape: 'icon',
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf011',
                            color: colorUp
                        }
                    },
                    isInDowntimeDown: {
                        shape: 'icon',
                        color: colorDown,
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf011',
                            color: colorDown
                        }
                    },
                    isInDowntimeUnreachable: {
                        shape: 'icon',
                        color: colorUnreachable,
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf011',
                            color: colorUnreachable
                        }
                    },
                    isAcknowledgedUp: {
                        shape: 'icon',
                        color: colorUp, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf007',
                            color: colorUp
                        }
                    },
                    isAcknowledgedDown: {
                        shape: 'icon',
                        color: colorDown,
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf007',
                            color: colorDown
                        }
                    },
                    isAcknowledgedUnreachable: {
                        shape: 'icon',
                        color: colorUnreachable,
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf007',
                            color: colorUnreachable
                        }
                    },
                    isAcknowledgedAndIsInDowntimeUp: {
                        shape: 'icon',
                        color: colorUp, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf0f0',
                            color: colorUp
                        }
                    },
                    isAcknowledgedAndIsInDowntimeDown: {
                        shape: 'icon',
                        color: colorDown,
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf0f0',
                            color: colorDown
                        }
                    },
                    isAcknowledgedAndIsInDowntimeUnreachable: {
                        shape: 'icon',
                        color: colorUnreachable,
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf0f0',
                            color: colorUnreachable
                        }
                    },
                },
                nodes: {
                    radiusMin: 5,
                    radiusMax: 50,
                    fontSize: 12,
                    fontFace: "Tahoma",
                    borderWidth: 0.5,
                },
                edges: {
                    width: 0.2,
                    inheritColor: "from",
                    style: "line",
                    widthSelectionMultiplier: 8
                },
                tooltip: {
                    delay: 200,
                    fontSize: 12,
                    color: {
                        background: "#fff"
                    }
                },
                smoothCurves: {
                    dynamic:false,
                    type: "continuous"
                },
                stabilize: false,
                physics: {
                    barnesHut: {
                        gravitationalConstant: -80000,
                        springConstant: 0.001,
                        springLength: 200
                    }
                },
                hideEdgesOnDrag: true
            };

            $scope.nodes.add(nodesData);
            $scope.edges.add(edgesData);

            var data = {
                nodes : $scope.nodes,
                edges : $scope.edges
            }

            var scaleFactor = 0.5;
            var defaultNodeSize = 250;
            var scaleForNodes = Math.round($scope.nodes.length / defaultNodeSize * scaleFactor);

            if(scaleForNodes > 1){
                $($scope.container).css({
                    'height': Math.round($($scope.container).height() * scaleForNodes)
                });
            }

            network = new vis.Network($scope.container, data, options);
            network.once('initRedraw', function() {
                network.moveTo({ position: {x:10, y:25}})
            });
            network.on('stabilizationProgress', function(params) {
                var currentPercentage = Math.round(params.iterations/params.total*100);
                $('#statusmap-progress-icon div:first').attr('data-progress', currentPercentage);
            });
            network.once('stabilizationIterationsDone', function() {
                $('#statusmap-progress-icon').hide();
                network.setOptions( { physics: false } );
            });

            network.on( 'click', function(properties) {
                if (properties.nodes.length === 0) {
                    network.fit({
                        locked: false,
                        animation: {
                            duration: 500,
                            easingFunction: 'linear'
                        }});
                    return;
                }
                var nodeId = properties.nodes[0];
                if(nodeId === 0){
                    return false;
                }
                network.focus(nodeId, {
                    scale: 1.5,
                    locked: true,
                    animation: {
                        duration: 1000,
                        easingFunction: 'linear'
                    }
                });
            });

            network.on('hoverNode', function(properties) {


            });
        };

        $scope.$watch('filter', function(){
            if($scope.mutex){
                return;
            }
            $scope.resetVis();
            $scope.load();
        }, true);
    });
