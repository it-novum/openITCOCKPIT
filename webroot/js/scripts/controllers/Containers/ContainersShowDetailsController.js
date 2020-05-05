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

        $scope.tabName = 'Containers';

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

        document.addEventListener("fullscreenchange", function(){
            if(document.fullscreenElement === null){
                $scope.fullscreen = false;
            }

        }, false);

        $scope.toggleFullscreenMode = function(){
            var elem = document.getElementById('containermap');
            if($scope.fullscreen === true){
                if(document.exitFullscreen){
                    document.exitFullscreen();
                }else if(document.webkitExitFullscreen){
                    document.webkitExitFullscreen();
                }else if(document.mozCancelFullScreen){
                    document.mozCancelFullScreen();
                }else if(document.msExitFullscreen){
                    document.msExitFullscreen();
                }
            }else{
                if(elem.requestFullscreen){
                    elem.requestFullscreen();
                }else if(elem.mozRequestFullScreen){
                    elem.mozRequestFullScreen();
                }else if(elem.webkitRequestFullscreen){
                    elem.webkitRequestFullscreen();
                }else if(elem.msRequestFullscreen){
                    elem.msRequestFullscreen();
                }
            }
        };


        angular.element(document).ready(function(){
            if($scope.tabName === 'ContainersMap'){
                $scope.container = document.getElementById('containermap');
                var offset = $($scope.container).offset();
                var height = (window.innerHeight - offset.top - 70);
                $($($scope.container)).css({
                    'height': height
                });
            }
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
                    'angular': true,
                    'asTree': ($scope.tabName === 'ContainersMap') ? true : false
                }
            }).then(function(result){
                if($scope.tabName === 'ContainersMap'){
                    $scope.lastMapContainerId = $scope.post.Container.id;
                    var nodesData = result.data.containerMap.nodes;
                    var edgesData = result.data.containerMap.edges;
                    var cluster = result.data.containerMap.cluster;

                    $scope.nodesCount = nodesData.length;
                    if(nodesData.length > 0){
                        $('#visProgressbarLoader').show(); //AngularJS is to slow
                        $scope.loadVisMap(nodesData, edgesData, cluster);
                    }else{
                        $scope.isEmpty = true;
                    }
                    $scope.mutex = false;
                }else{
                    $scope.lastTreeContainerId = $scope.post.Container.id;
                    $scope.containersWithChilds = result.data.containersWithChilds;
                    if($scope.containersWithChilds.length > 0){
                        $scope.isEmpty = false;
                    }
                }


            }, function errorCallback(result){
                console.log('Invalid JSON');
            });
        };

        $scope.resetVis = function(){
            if(!$scope.init){
                $('#visProgressbarLoader .progress-bar:first').css('width', '0%');
                $($scope.container).html('');
            }
        };

        $scope.loadVisMap = function(nodesData, edgesData, cluster){
            $scope.nodes.clear();
            $scope.edges.clear();


            // create a network
            var container = document.getElementById('containermap');
            $scope.nodes.add(nodesData);
            $scope.edges.add(edgesData);
            var data = {
                nodes: nodesData,
                edges: edgesData
            };

            var colorUp = '#00C851';
            var colorDown = '#CC0000';
            var colorUnreachable = '#727b84';
            var colorNotMonitored = '#4285F4';

            var options = {
                groups: {
                    root: {
                        shape: 'ellipse',
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf0ac',
                            color: colorNotMonitored, //color for icon,

                        },
                        margin: {
                            top: 10,
                            bottom: 20,
                            left: 5,
                            right: 5
                        },
                        color: {
                            border: 'black',
                            background: 'white',
                            highlight: {
                                border: 'yellow',
                                background: 'orange'
                            }
                        },
                        fontColor: 'red'
                    },
                    tenant: {
                        shape: 'icon',
                        color: '#ff4444',
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf015',
                            size: 35,
                            color: '#ff4444'
                        }
                    },
                    location: {
                        shape: 'icon',
                        color: '#ff8800', // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf124',
                            size: 35,
                            color: '#ff8800',
                        }
                    },
                    node: {
                        shape: 'icon',
                        color: '#00695c', // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf0c1',
                            size: 35,
                            color: '#00695c'
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

                    hostgroups: {
                        shape: 'dot',
                        color: '#00e676',
                        size: 15,
                        icon: {
                            code: '\uf233',
                            color: '#ffffff',
                            size: 5
                        }
                    },

                    hosts: {
                        shape: 'dot',
                        color: '#007bff',
                        size: 15,
                        icon: {
                            code: '\uf108',
                            color: '#ffffff',
                            size: 5
                        }
                    },
                    hosttemplates: {
                        shape: 'dot',
                        color: '#8bc34a',
                        size: 15,
                        icon: {
                            code: '\uf044',
                            color: '#ffffff',
                            size: 5
                        }
                    },
                    hostescalations: {
                        shape: 'dot',
                        color: '#304ffe',
                        size: 15,
                        icon: {
                            code: '\uf1e2',
                            color: '#ffffff',
                            size: 5
                        }
                    },
                    hostdependencies: {
                        shape: 'dot',
                        color: '#66bb6a',
                        size: 15,
                        icon: {
                            code: '\uf0e8',
                            color: '#ffffff',
                            size: 5
                        }
                    },
                    servicegroups: {
                        shape: 'dot',
                        color: '#f4511e',
                        size: 15,
                        icon: {
                            code: '\uf085',
                            color: '#ffffff',
                            size: 5
                        }
                    },
                    servicetemplategroups: {
                        shape: 'dot',
                        color: '#1c2a48',
                        size: 15,
                        icon: {
                            code: '\uf0c5',
                            color: '#ffffff',
                            size: 5
                        }
                    },
                    servicetemplates: {
                        shape: 'dot',
                        color: '#009688',
                        size: 15,
                        icon: {
                            code: '\uf044',
                            color: '#ffffff',
                            size: 5
                        }
                    },
                    serviceescalations: {
                        shape: 'dot',
                        color: '#45526e',
                        size: 15,
                        icon: {
                            code: '\uf1e2',
                            color: '#ffffff',
                            size: 5
                        }
                    },
                    servicedependencies: {
                        shape: 'dot',
                        color: '#0091ea',
                        size: 15,
                        icon: {
                            code: '\uf0e8',
                            color: '#ffffff',
                            size: 5
                        }
                    },
                    contacts: {
                        shape: 'dot',
                        color: '#9933CC',
                        size: 15,
                        icon: {
                            code: '\uf2bd',
                            color: '#ffffff',
                            size: 5
                        }
                    },
                    contactgroups: {
                        shape: 'dot',
                        color: '#b388ff',
                        size: 15,
                        icon: {
                            code: '\uf0c0',
                            color: '#ffffff',
                            size: 5
                        }
                    },
                    timeperiods: {
                        shape: 'dot',
                        color: '#3f51b5',
                        size: 15,
                        icon: {
                            code: '\uf017',
                            color: '#ffffff',
                            size: 5
                        }
                    },
                    maps: {
                        shape: 'dot',
                        color: '#f50057',
                        size: 15,
                        icon: {
                            code: '\uf041',
                            color: '#ffffff',
                            size: 5
                        }
                    },
                    instantreports: {
                        shape: 'dot',
                        color: '#0099CC',
                        size: 15,
                        icon: {
                            code: '\uf1c5',
                            color: '#ffffff',
                            size: 5
                        }
                    },
                    autoreports: {
                        shape: 'dot',
                        color: '#ab47bc',
                        size: 15,
                        icon: {
                            code: '\uf1c5',
                            color: '#ffffff',
                            size: 5
                        }
                    },
                    satellites: {
                        shape: 'dot',
                        color: '#01579b',
                        size: 15,
                        icon: {
                            code: '\uf0c2',
                            color: '#ffffff',
                            size: 5,
                            weight: 'bold'
                        }
                    },
                },
                physics: {
                    barnesHut: {
                        gravitationalConstant: -2000,
                        centralGravity: 0.3,
                        springLength: 95,
                        springConstant: 0.04,
                        damping: 0.09
                    },
                    maxVelocity: 146,
                    solver: 'barnesHut',
                    timestep: 0.35,
                    stabilization: {
                        enabled: true,
                        iterations: 2000,
                        updateInterval: 25
                    }
                    /*forceAtlas2Based: {
                        gravitationalConstant: -138,
                        centralGravity: 0.02,
                        springLength: 100
                    },
                    minVelocity: 0.75,
                    solver: "forceAtlas2Based",*/
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
            var network = new vis.Network(container, data, options);


            network.fit({
                locked: false,
                animation: {
                    duration: 500,
                    easingFunction: 'linear'
                }
            });
            network.on('stabilizationProgress', function(params){
                var currentPercentage = Math.round(params.iterations / params.total * 100);
                $('#visProgressbarLoader .progress-bar:first').css('width', currentPercentage + '%');
            });
            network.once('stabilizationIterationsDone', function(){
                $('#visProgressbarLoader').hide(); //AngularJS is to slow
                network.setOptions({physics: false});

                setTimeout(function(){
                    network.setOptions({physics: true});
                }, 250);
            });

            network.on("selectNode", function(params){
                if(params.nodes.length === 1){
                    if(network.isCluster(params.nodes[0]) == true){
                        // The method .cluster() creates a cluster and .openCluster() releases the clustered nodes and
                        // edges from the cluster and then disposes of it. There's no way to close it because it no longer exists.
                        // Source: https://github.com/visjs/vis-network/issues/354#issuecomment-574260404
                        network.openCluster(params.nodes[0]);
                    }else{
                        //Was a cluster and want to get closed down?
                        for(var index in nodesData){
                            if(nodesData[index].hasOwnProperty('createCluster') && nodesData[index].id === params.nodes[0]){

                                var node = nodesData[index];

                                //Lookup cluster configuration
                                var clusterLabel = 'ERR';
                                for(var k in cluster){
                                    if(cluster[k].name === node.createCluster){
                                        clusterLabel = "" + cluster[k].size;
                                    }
                                }

                                //Recluster
                                var clusterOptions = {
                                    joinCondition: function(nodeOptions){
                                        //console.log(node);
                                        return nodeOptions.cid === node.createCluster;
                                    },
                                    clusterNodeProperties: {
                                        label: clusterLabel,
                                        color: node.color || '#97c2fc'
                                    }
                                };
                                network.clustering.cluster(clusterOptions);
                            }
                        }
                    }
                }
            });

            //Create all visJS clusters on page load
            for(var index in cluster){
                var clusterOptions = {
                    joinCondition: function(nodeOptions){
                        return nodeOptions.cid === cluster[index].name;
                    },
                    clusterNodeProperties: {
                        label: "" + cluster[index].size //cast to string because of reasons
                    }
                };
                network.clustering.cluster(clusterOptions);
            }

        };

        $scope.$watch('post.Container.id', function(){
            if($scope.init){
                return;
            }
            if($scope.post.Container.id !== null){
                $scope.loadContainers();
            }
        }, true);

        $scope.$watch('tabName', function(){
            if($scope.init){
                return;
            }
            if($scope.tabName === 'ContainersMap'){
                if($scope.lastMapContainerId !== $scope.post.Container.id){
                    $scope.loadContainerDetails();
                }
                $scope.container = document.getElementById('containermap');
                var offset = $($scope.container).offset();
                var height = (window.innerHeight - offset.top - 70);
                $($($scope.container)).css({
                    'height': height
                });

            }else{
                if($scope.lastTreeContainerId !== $scope.post.Container.id){
                    $scope.loadContainerDetails();
                }
            }
        }, true);

        //Fire on page load
        $scope.loadContainers();
    });
