angular.module('openITCOCKPIT')
    .controller('UsergroupsAddController', function($scope, $http, SudoService, $state, NotyService, RedirectService){

        $scope.data = {
            createAnother: false
        };

        $scope.ctrlFilter = '';

        var clearForm = function(){
            $scope.post = {
                Usergroup: {
                    name: '',
                    description: '',
                    ldapgroups: {
                        _ids: []
                    }
                },
                Acos: {}
            };
        };
        clearForm();

        $scope.init = true;

        $scope.loadAcos = function(){
            $http.get("/usergroups/add.json?angular=true",
                {}
            ).then(function(result){
                $scope.acos = result.data.acos;

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

        $scope.submit = function(){
            $http.post("/usergroups/add.json?angular=true",
                $scope.post
            ).then(function(result){
                var url = $state.href('UsergroupsEdit', {id: result.data.usergroup.id});
                NotyService.genericSuccess({
                    message: '<u><a href="' + url + '" class="txt-color-white"> '
                        + $scope.successMessage.objectName
                        + '</a></u> ' + $scope.successMessage.message
                });


                if($scope.data.createAnother === false){
                    RedirectService.redirectWithFallback('UsergroupsIndex');
                }else{
                    clearForm();
                    $scope.errors = {};
                    NotyService.scrollTop();
                }

                console.log('Data saved successfully');
            }, function errorCallback(result){

                NotyService.genericError();

                if(result.data.hasOwnProperty('error')){
                    $scope.errors = result.data.error;
                }
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

        // Fire on page load
        $scope.loadAcos();
        $scope.loadLdapGroups();

    });
