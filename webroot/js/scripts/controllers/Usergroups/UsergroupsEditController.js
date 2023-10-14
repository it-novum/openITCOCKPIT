angular.module('openITCOCKPIT')
    .controller('UsergroupsEditController', function($scope, $http, $state, $stateParams, NotyService, RedirectService){

        $scope.id = $stateParams.id;
        $scope.ctrlFilter = '';
        $scope.init = true;

        $scope.load = function(){
            $http.get("/usergroups/edit/" + $scope.id + ".json",
                {}
            ).then(function(result){
                $scope.post = {
                    Usergroup: result.data.usergroup,
                    Acos: {}
                };
                $scope.acos = result.data.acos;

                //Put all existing acos to $scope.post.Acos
                for(var aco in $scope.acos){
                    for(var controller in $scope.acos[aco].children){
                        for(var action in $scope.acos[aco].children[controller].children){
                            var acoId = $scope.acos[aco].children[controller].children[action].id;
                            $scope.post.Acos[acoId] = 0;
                        }
                    }
                }

                //Set permissions of current user group to $scope.post.Acos;
                for(var usergroupAco in result.data.usergroup.aro.acos){
                    var usergroupAcoId = result.data.usergroup.aro.acos[usergroupAco].id;

                    //Deny all by default
                    $scope.post.Acos[usergroupAcoId] = 0;

                    if(result.data.usergroup.aro.acos[usergroupAco]._joinData._create === "1"){
                        //Only enable what is enabled in the database
                        $scope.post.Acos[usergroupAcoId] = 1;
                    }
                }
                $scope.loadLdapGroups();
            });
        };


        $scope.submit = function(){
            $http.post("/usergroups/edit/" + $scope.id + ".json",
                $scope.post
            ).then(function(result){
                var url = $state.href('UsergroupsEdit', {id: $scope.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });

                RedirectService.redirectWithFallback('UsergroupsIndex');

                console.log('Data saved successfully');
            }, function errorCallback(result){

                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
            });
        };

        $scope.loadLdapGroups = function(searchString){
            $http.get("/usergroups/loadLdapgroupsForAngular.json", {
                params: {
                    'angular': true,
                    'filter[Ldapgroups.cn]': searchString,
                    'selected[]': $scope.post.Usergroup.ldapgroups._ids
                }
            }).then(function(result){
                $scope.isLdapAuth = result.data.isLdapAuth;
                $scope.ldapgroups = result.data.ldapgroups;
            });
        };

        $scope.tickAll = function(actionToTick){
            if(actionToTick === 'all'){
                for(var aco in $scope.acos){
                    for(var controller in $scope.acos[aco].children){
                        var isModule = $scope.acos[aco].children[controller].alias.substr(-6) === 'Module';

                        if(!isModule){
                            for(var action in $scope.acos[aco].children[controller].children){
                                var acoId = $scope.acos[aco].children[controller].children[action].id;
                                $scope.post.Acos[acoId] = 1;
                            }
                        }else{
                            for(var pluginController in $scope.acos[aco].children[controller].children){
                                for(var action in $scope.acos[aco].children[controller].children[pluginController].children){
                                    var acoId = $scope.acos[aco].children[controller].children[pluginController].children[action].id;
                                    $scope.post.Acos[acoId] = 1;
                                }
                            }
                        }
                    }
                }
            }else{

                for(var aco in $scope.acos){
                    for(var controller in $scope.acos[aco].children){
                        var isModule = $scope.acos[aco].children[controller].alias.substr(-6) === 'Module';

                        if(!isModule){
                            for(var action in $scope.acos[aco].children[controller].children){
                                var acoId = $scope.acos[aco].children[controller].children[action].id;
                                var actionName = $scope.acos[aco].children[controller].children[action].alias;

                                if(actionName === actionToTick){
                                    $scope.post.Acos[acoId] = 1;
                                }
                            }
                        }else{
                            for(var pluginController in $scope.acos[aco].children[controller].children){
                                for(var action in $scope.acos[aco].children[controller].children[pluginController].children){
                                    var actionName = $scope.acos[aco].children[controller].children[pluginController].children[action].alias;
                                    var acoId = $scope.acos[aco].children[controller].children[pluginController].children[action].id;
                                    if(actionName === actionToTick){
                                        $scope.post.Acos[acoId] = 1;
                                    }
                                }
                            }
                        }
                    }
                }
            }


        };

        $scope.untickAll = function(actionToUntick){
            if(actionToUntick === 'all'){
                for(var aco in $scope.acos){
                    for(var controller in $scope.acos[aco].children){
                        var isModule = $scope.acos[aco].children[controller].alias.substr(-6) === 'Module';

                        if(!isModule){
                            for(var action in $scope.acos[aco].children[controller].children){
                                var acoId = $scope.acos[aco].children[controller].children[action].id;
                                $scope.post.Acos[acoId] = 0;
                            }
                        }else{
                            for(var pluginController in $scope.acos[aco].children[controller].children){
                                for(var action in $scope.acos[aco].children[controller].children[pluginController].children){
                                    var acoId = $scope.acos[aco].children[controller].children[pluginController].children[action].id;
                                    $scope.post.Acos[acoId] = 0;
                                }
                            }
                        }
                    }
                }
            }else{

                for(var aco in $scope.acos){
                    for(var controller in $scope.acos[aco].children){
                        var isModule = $scope.acos[aco].children[controller].alias.substr(-6) === 'Module';

                        if(!isModule){
                            for(var action in $scope.acos[aco].children[controller].children){
                                var acoId = $scope.acos[aco].children[controller].children[action].id;
                                var actionName = $scope.acos[aco].children[controller].children[action].alias;

                                if(actionName === actionToUntick){
                                    $scope.post.Acos[acoId] = 0;
                                }
                            }
                        }else{
                            for(var pluginController in $scope.acos[aco].children[controller].children){
                                for(var action in $scope.acos[aco].children[controller].children[pluginController].children){
                                    var actionName = $scope.acos[aco].children[controller].children[pluginController].children[action].alias;
                                    var acoId = $scope.acos[aco].children[controller].children[pluginController].children[action].id;
                                    if(actionName === actionToUntick){
                                        $scope.post.Acos[acoId] = 0;
                                    }
                                }
                            }
                        }
                    }
                }
            }

        };

        //Reset form on page load
        $scope.load();
    });
