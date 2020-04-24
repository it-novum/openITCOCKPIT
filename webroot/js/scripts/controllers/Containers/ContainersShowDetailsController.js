angular.module('openITCOCKPIT')
    .controller('ContainersShowDetailsController', function($scope, $http, $timeout, $stateParams){

        $scope.init = true;

        $scope.post = {
            Container: {
                id: null,
                tenant: null
            },
            backState: null
        };

        $scope.nodes = new vis.DataSet();
        $scope.edges = new vis.DataSet();

        $scope.post.Container.id = parseInt($stateParams.id, 10);
        if($stateParams.tenant){
            if(isNaN($stateParams.tenant)){
                $scope.post.backState = $stateParams.tenant;
            }else{
                $scope.post.Container.tenant = $stateParams.tenant;
            }
        }

        angular.element(document).ready(function(){
            $scope.container = document.getElementById('containermap');

            var offset = $($scope.container).offset();
            var height = (window.innerHeight - offset.top);
            $($($scope.container)).css({
                'height': height
            });
        });

        $scope.loadContainers = function(){
            $http.get("/containers/loadContainers.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.containers;
                $scope.init = false;
                $scope.loadContainerDetails();
            });
        };


        $scope.loadContainerDetails = function(){
            $http.get('/containers/showDetails/' + $scope.post.Container.id + '.json', {
                params: {
                    'angular': true
                }
            }).then(function(result){
                var nodesData = result.data.containerMap.nodes;
                var edgesData = result.data.containerMap.edges;
                $scope.nodesCount = nodesData.length;
                if(nodesData.length > 0){
                    //$('#statusmap-progress-icon').show();
                    $scope.loadVisMap(nodesData, edgesData);
                }else{
                    $scope.isEmpty = true;
                }
                $scope.mutex = false;
            }, function errorCallback(result){
                console.log('Invalid JSON');
            });
        };

        $scope.resetVis = function(){
            if(!$scope.init){
                $('#statusmap-progress-icon .progress:first').attr('data-progress', 0);
                $($scope.container).html('');
            }
        };

        $scope.loadVisMap = function(nodesData, edgesData){
            $scope.nodes.clear();
            $scope.edges.clear();
            var network = null;

            var colorUp = '#00C851';
            var colorDown = '#CC0000';
            var colorUnreachable = '#727b84';
            var colorNotMonitored = '#4285F4';

            var options = {
                clickToUse: false,
                groups: {
                    root: {
                        //f0ac
                        shape: 'ellipse',
                        color: colorNotMonitored, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf0ac',
                            color: colorNotMonitored //color for icon
                        },
                        margin: {
                            top: 10,
                            bottom: 20,
                            left: 5,
                            right: 5
                        }
                    },
                    tenant: {
                        shape: 'icon',
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf015'
                        }
                    },
                    location: {
                        shape: 'icon',
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf124'
                        }
                    },
                    node: {
                        shape: 'icon',
                        color: colorNotMonitored, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf0c1',
                            color: colorNotMonitored //color for icon
                        }
                    },
                    devicegroup: {
                        shape: 'icon',
                        color: colorNotMonitored, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf0c1',
                            color: colorNotMonitored //color for icon
                        }
                    },
                    contactgroup: {
                        shape: 'icon',
                        color: colorNotMonitored, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf0c1',
                            color: colorNotMonitored //color for icon
                        }
                    },
                    hostgroup: {
                        shape: 'icon',
                        color: colorNotMonitored, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf0c1',
                            color: colorNotMonitored //color for icon
                        }
                    },
                    servicegroup: {
                        shape: 'icon',
                        color: colorNotMonitored, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf0c1',
                            color: colorNotMonitored //color for icon
                        }
                    },
                    servicetemplategroup: {
                        shape: 'icon',
                        color: colorNotMonitored, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf0c1',
                            color: colorNotMonitored //color for icon
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
            };


            containerTree = new vis.Network($scope.container, data, options);
            containerTree.fit({
                locked: false,
                animation: {
                    duration: 500,
                    easingFunction: 'linear'
                }
            });
            containerTree.on('stabilizationProgress', function(params){
                var currentPercentage = Math.round(params.iterations / params.total * 100);
                $('#statusmap-progress-icon .progress:first').attr('data-progress', currentPercentage);
            });
            containerTree.once('stabilizationIterationsDone', function(){
                $('#statusmap-progress-icon').hide();
                containerTree.setOptions({physics: false});
            });

            containerTree.on('click', function(properties){
                if(properties.nodes.length === 0){
                    containerTree.fit({
                        locked: false,
                        animation: {
                            duration: 500,
                            easingFunction: 'linear'
                        }
                    });
                    return;
                }

                var nodeId = properties.nodes[0];
                if(nodeId === 0){
                    return false;
                }
                $scope.containSummaryObject = data.nodes.get(nodeId);
                $scope.$apply();
            });
        };

        $scope.$watch('post.Container.id', function(){
            if($scope.init){
                return;
            }
            if($scope.post.Container.id !== null){
                $scope.loadContainers();
            }
        }, true);

        //Fire on page load
        $scope.loadContainers();
    });
