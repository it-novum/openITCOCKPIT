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

                $('#credits-container').css({
                    'width': $(window).width(),
                    'height': $(window).height()
                });
            }
        };


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
                var cluster = result.data.containerMap.cluster;

                $scope.nodesCount = nodesData.length;
                if(nodesData.length > 0){
                    $('#visProgressbarLoader').show(); //AngularJS is to slow
                    $scope.loadVisMap(nodesData, edgesData, cluster);
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
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf015',
                            size: 30,
                            color: 'red',
                            fontColor: 'red'
                        }
                    },
                    location: {
                        shape: 'icon',
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf124',
                            size: 30,
                            color: 'orange',
                            fontColor: 'orange'
                        }
                    },
                    node: {
                        shape: 'icon',
                        color: colorNotMonitored, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf0c1',
                            color: colorNotMonitored, //color for icon
                            size: 30,
                            color: 'purple',
                            fontColor: 'purple'
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
                    contactgroups: {
                        shape: 'icon',
                        color: colorNotMonitored, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf0c0',
                            color: colorNotMonitored //color for icon
                        }
                    },
                    hostgroup: {
                        shape: 'icon',
                        color: colorNotMonitored, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf233',
                            color: colorNotMonitored, //color for icon
                            size: 20
                        }
                    },
                    servicegroup: {
                        shape: 'icon',
                        color: colorNotMonitored, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf085',
                            color: colorNotMonitored, //color for icon
                            size: 20
                        }
                    },
                    servicetemplategroup: {
                        shape: 'icon',
                        color: colorNotMonitored, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf0c5',
                            color: colorNotMonitored, //color for icon
                            size: 20
                        }
                    },
                    hosts: {
                        shape: 'icon',
                        color: colorUp, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf108',
                            color: colorUp,
                            size: 15
                        }
                    },
                    hosttemplates: {
                        shape: 'icon',
                        color: colorUp, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf044',
                            color: colorUp,
                            size: 15
                        }
                    },
                    servicetemplates: {
                        shape: 'icon',
                        color: colorDown, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf044',
                            color: colorUp,
                            size: 15
                        }
                    },

                    contacts: {
                        shape: 'icon',
                        color: colorNotMonitored, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf2bd',
                            color: colorNotMonitored, //color for icon
                            size: 15
                        }
                    },
                    timeperiods: {
                        shape: 'icon',
                        color: colorUp, // color for edges
                        icon: {
                            face: 'FontAwesome',
                            code: '\uf017',
                            color: colorUp,
                            size: 15
                        }
                    }
                },
                physics: {
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
                                        color: node.color || 'red'
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

        //Fire on page load
        $scope.loadContainers();
    });
