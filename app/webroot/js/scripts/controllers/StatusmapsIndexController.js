angular.module('openITCOCKPIT')
    .controller('StatusmapsIndexController', function ($scope, $http, QueryStringService) {
        /*** Filter Settings ***/
        $scope.filter = {
            Host: {
                name: QueryStringService.getValue('filter[Host.name]', ''),
                address: QueryStringService.getValue('filter[Host.address]', ''),
                satellite_id: '4'
            },
            showAll: false
        };
        /*** Filter end ***/


        $scope.init = true;
        $scope.mutex = false;

        $scope.nodes = new vis.DataSet();
        $scope.edges = new vis.DataSet();

        $scope.nodesCount = 0;

        $scope.isEmpty = false;

        $scope.container = document.getElementById('statusmap');

        var offset = $($scope.container).offset();
        var height = (window.innerHeight - offset.top);

        $($($scope.container)).css({
            'height': height
        });

        $scope.load = function () {
            $scope.mutex = true;
            $scope.isEmpty = false;
            var params = {
                'angular': true,
                'filter[Host.name]': $scope.filter.Host.name,
                'filter[Host.address]': $scope.filter.Host.address,
                'filter[Host.satellite_id]': $scope.filter.Host.satellite_id,
                'showAll': $scope.filter.showAll
            };
            $http.get("/statusmaps/index.json", {
                params: params
            }).then(function (result) {
                var nodesData = result.data.statusMap.nodes;
                var edgesData = result.data.statusMap.edges;
                $scope.init = false;
                $scope.nodesCount = nodesData.length;
                if (nodesData.length > 0) {
                    $('#statusmap-progress-icon').show();
                    $scope.loadVisMap(nodesData, edgesData);
                } else {
                    $scope.isEmpty = true;
                }
                $scope.mutex = false;
            }, function errorCallback(result) {
                console.log('Invalid JSON');
            });
        };

        $scope.resetVis = function () {
            if (!$scope.init) {
                $('#statusmap-progress-icon div:first').attr('data-progress', 0);
                $($scope.container).html('');
            }
        };

        $scope.loadVisMap = function (nodesData, edgesData) {
            $scope.nodes.clear();
            $scope.edges.clear();
            var network = null;

            var colorUp = '#449D44';
            var colorDown = '#C9302C';
            var colorUnreachable = '#92A2A8';
            var colorNotMonitored = '#428bca';

            var options = {
                clickToUse: false,
                groups: {
                    satellite: {
                        shape: 'ellipse',
                        margin: {
                            top: 10,
                            bottom: 20,
                            left: 5,
                            right: 5
                        }
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
                        }
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
                    }
                },
                nodes: {
                    borderWidth: 0.5,
                },
                edges: {
                    width: 0.2,
                    smooth: {
                        enabled: false
                    }
                },
                physics: {
                    forceAtlas2Based: {
                        gravitationalConstant: -138,
                        centralGravity: 0.02,
                        springLength: 100
                    },
                    minVelocity: 0.75,
                    solver: "forceAtlas2Based",
                },
                interaction: {
                    hover: true,
                    dragNodes: false,
                    keyboard: {
                        enabled: false
                    },
                    hideEdgesOnDrag: true

                },
                layout: {
                    randomSeed: 1000,
                    improvedLayout: false
                }
            };

            $scope.nodes.add(nodesData);
            $scope.edges.add(edgesData);

            var data = {
                nodes: $scope.nodes,
                edges: $scope.edges
            }


            network = new vis.Network($scope.container, data, options);
            network.fit({
                locked: false,
                animation: {
                    duration: 500,
                    easingFunction: 'linear'
                }
            });
            network.on('stabilizationProgress', function (params) {
                var currentPercentage = Math.round(params.iterations / params.total * 100);
                $('#statusmap-progress-icon .progress:first').attr('data-progress', currentPercentage);
            });
            network.once('stabilizationIterationsDone', function () {
                $('#statusmap-progress-icon').hide();
                network.setOptions({physics: false});
            });

            network.on('click', function (properties) {
                if (properties.nodes.length === 0) {
                    network.fit({
                        locked: false,
                        animation: {
                            duration: 500,
                            easingFunction: 'linear'
                        }
                    });
                    return;
                }
                var nodeId = properties.nodes[0];
                if (nodeId === 0) {
                    return false;
                }
                network.focus(nodeId, {
                    scale: 1.5,
                    locked: false,
                    animation: {
                        duration: 1000,
                        easingFunction: 'linear'
                    }
                });
            });

            network.on('hoverNode', function (properties) {
                var node = data.nodes.get(properties.node);
                $http.get("/hosts/hoststatus/"+node.uuid+"/"+node.hostId, {
                    params: {
                        'angular': true
                    }
                }).then(function (results) {
                    /* enter your logic here */
                    console.log(results);
                    $.bigBox({
                        title: 'test',
                        content: results[0].data,
                     //   color: hostColor,
                        icon: 'fa  flash animated',
                        // timeout: 6000
                    });

                    return;
                    console.log(results[0].data.hoststatus);
                    console.log(results[1].data.serviceStateSummary);
                    $scope.showHostOverviewBox(
                        node.title,
                        results[0].data.hoststatus,
                        results[1].data.serviceStateSummary
                    );
                });
                /*
                $http.get("/hosts/hoststatus/"+node.uuid+".json", {
                    params: {
                        'angular': true
                    }
                }).then(function(result){
                    $scope.showHostOverviewBox(node.title, result.data.hoststatus);

                });
                */
                return;
            });
        };

        $scope.$watch('filter', function () {
            if ($scope.mutex) {
                return;
            }
            $scope.resetVis();
            $scope.load();
        }, true);

        $scope.showHostOverviewBox = function (title, hoststatus, serviceStateSummary) {
            var hostColor = '#428bca';
            var hostColors = [
                '#449D44',  //Up
                '#C9302C',  //Down
                '#92A2A8'  //Unreachable
            ];

            var statusIcon = $scope.getIconForHoststatus(hoststatus.Hoststatus);

            if (typeof hoststatus.Hoststatus.current_state !== 'undefined') {
                hostColor = hostColors[hoststatus.Hoststatus.current_state];
            }
            $.bigBox({
                title: title,
                content: '<div class="bg-color-white">This message will dissapear in 6 seconds!</div>',
                color: hostColor,
                icon: 'fa ' + statusIcon + ' flash animated',
                // timeout: 6000
            });
        };

        $scope.getIconForHoststatus = function (hoststatus) {
            var statusIcon = 'fa-check-circle';
            if (typeof hoststatus.current_state === 'undefined') {
                return 'fa-eye-slash';
            }
            if (hoststatus.current_state > 0) {
                statusIcon = 'fa-exclamation-circle';
            }
            if (hoststatus.scheduled_downtime_depth > 0) {
                statusIcon = 'fa-power-off';
            } else if (hoststatus.scheduled_downtime_depth > 0 && hoststatus.problem_has_been_acknowledged == 1) {
                statusIcon = 'fa-user-md';
            } else if (hoststatus.scheduled_downtime_depth > 0 && hoststatus.problem_has_been_acknowledged == 0) {
                statusIcon = 'fa-user';
            }
            return statusIcon;
        }
    });
