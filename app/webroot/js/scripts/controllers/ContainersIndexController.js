angular.module('openITCOCKPIT').filter('to_trusted', ['$sce', function($sce){
    return function(text) {
        return $sce.trustAsHtml(text);
    };
}]);

angular.module('openITCOCKPIT')
    .controller('ContainersIndexController', function($scope, $http){


        $scope.init = true;
        $scope.selectedTenant = null;
        $scope.selectedTenantForNode = null;
        $scope.newNode_name = null;
        $scope.newNode_parent = null;
        $scope.nested_list_counter = 0;
        $scope.errors = null;
        $scope.delete_url = '/containers/delete.json';
        $scope.delete_id = null;
        $scope.tree = null;
        $scope.consts = {
            'CT_GLOBAL'               : '1',
            'CT_TENANT'               : '2',
            'CT_LOCATION'             : '3',
            'CT_DEVICEGROUP'          : '4',
            'CT_NODE'                 : '5',
            'CT_CONTACTGROUP'         : '6',
            'CT_HOSTGROUP'            : '7',
            'CT_SERVICEGROUP'         : '8',
            'CT_SERVICETEMPLATEGROUP' : '9'
        };

        $scope.getContainerIdIcon = function(containerId){
            var html = '';
            switch(containerId) {
                case $scope.consts['CT_GLOBAL']:
                    html += '<i class="fa fa-globe"></i>';
                    break;
                case $scope.consts['CT_TENANT']:
                    html += '<i class="fa fa-home"></i>';
                    break;
                case $scope.consts['CT_LOCATION']:
                    html += '<i class="fa fa-location-arrow"></i>';
                    break;
                case $scope.consts['CT_NODE']:
                    html += '<i class="fa fa-link"></i>';
                    break;
                case $scope.consts['CT_CONTACTGROUP']:
                    html += '<i class="fa fa-users"></i>';
                    break;
                case $scope.consts['CT_HOSTGROUP']:
                    html += '<i class="fa fa-sitemap"></i>';
                    break;
                case $scope.consts['CT_SERVICEGROUP']:
                    html += '<i class="fa fa-cogs"></i>';
                    break;
                case $scope.consts['CT_SERVICETEMPLATEGROUP']:
                    html += '<i class="fa fa-pencil-square-o"></i>';
                    break;
            }
            return html+" ";
        };

        $scope.fetchContainerChildren = function(children, options){
            //console.log(children);
            var html = '<ol class="'+options['ol_class']+'">';
            for (var si = 0, len = children.length; si < len; si++) {
                var child = children[si];
                var i = 0;
                i = (child.Container.rght-child.Container.lft)/2-0.5;
                html += '<li class="'+options['child_li_class']+'" data-id="'+child.Container.id+'">';
                html += '<div class="'+options['handle_class']+'"' +
                    'parent-id="'+child.Container.parent_id+'"' +
                    'containertype-id="'+child.Container.containertype_id+'">'+
                    $scope.getContainerIdIcon(child.Container.containertype_id)+child.Container.name;

                if(child.Container.containertype_id == $scope.consts['CT_NODE']){
                    html += '<a href="#" data-toggle="modal" ' +
                        'data-target="#delete_location_'+child.Container.id+'"' +
                        'class="txt-color-red padding-left-10 font-xs"><i class="fa fa-trash-o"></i>Delete</a>';
                }
                if(child.children.length > 0){  //original: sizeof(child.children)
                    html += '<span class="badge bg-color-blueLight txt-color-white pull-right">'+i+'</span>';
                } else {
                    html += '<i class="note pull-right">empty</i>';
                }
                html += '</div>';

                //Now we need to check of the children (of the parent) has some childrens
                if(typeof child.children != "undefined" && child.children != null && child.children.length > 0){
                    html += $scope.fetchContainerChildren(child.children, options);
                }

                html += '</li>';
            }
            html += '</ol>';
            //original: for create delete modals //now: nothing because of refactoring into angular
            return html;
        };

        $scope.loadTree = function(nest){
            console.log(nest);

            var options = {
                'id'             : 'nestable',
                'wrapper_class'  : 'dd',
                'ol_class'       : 'dd-list',
                'child_li_class' : 'dd-item',
                'handle_class'   : 'dd-handle'
            };

            var html = '<div class="'+options['wrapper_class']+' dd-nodrag">';
            html += '<ol class="'+options['ol_class']+'" id="'+options['id']+'">';

            if(typeof nest[0].children != "undefined" && nest[0].children != null && nest[0].children.length > 0){
                html += '<button data-action="collapse" type="button" >Collapse</button>';
                html += '<button data-action="expand" type="button" style="display: none;" >Expand</button>';
            }

            for (var si = 0, len = nest.length; si < len; si++) {
                var parent=nest[si];
                var i = 0;
                i = (parent.Container.rght-parent.Container.lft)/2-0.5;
                //console.log(i);
                html += '<li class="'+options['child_li_class']+'" data-id="'+parent.Container.id+'">';
                html += '<div class="'+options['handle_class']+'"' +
                    'parent-id="'+parent.Container.parent_id+'"' +
                    'containertype-id="'+parent.Container.containertype_id+'">' +
                     /*<i class="fa fa-pencil edit-tenant" id="'+parent.Container.id+'"></i> + */
                    $scope.getContainerIdIcon(parent.Container.containertype_id)+parent.Container.name;

                if(parent.Container.containertype_id == $scope.consts['CT_NODE']){
                    html += '<button class="btn btn-xs btn-default pull-right" title="delete" data-action="remove">' +
                        '<i class="fa fa-trash txt-color-red" value="'+parent.Container.id+'"></i>' +
                        '</button>';
                }
                html += '<span class="badge bg-color-blue txt-color-white pull-right">'+i+'</span>';
                html += '</div>';

                //Now we need to check for childrens and append them
                if(typeof parent.children != "undefined" && parent.children != null && parent.children.length > 0){
                    html += $scope.fetchContainerChildren(parent.children, options);
                }

                html += '</li>';
            }

            html += '</ol></div>'; //replace later if ready in whole code
            $scope.tree = html;
            console.log(html);
        };

        $scope.$watch('delete_id',function(){
            if($scope.selectedTenant !== null){
                console.log("delc");
            }
        });


        $scope.getObjectForDelete = function(name){
            console.log("ggg");
            var object = {};
            object[1] = name;
            return object;
        };

        $scope.saveNewNode = function(){
            if($scope.newNode_name && $scope.newNode_parent){
                $http.post("/containers/add.json",
                    {
                        Container: {
                            parent_id: $scope.newNode_parent,
                            name: $scope.newNode_name,
                            containertype_id: '5'
                        },
                    }
                ).then(function(result){
                    //console.log(result);
                    $scope.newNode_name = null;
                    $scope.loadContainers();
                    $scope.loadContainerlist();
                }, function errorCallback(result){
                    if(result.data.hasOwnProperty('error')){
                        $scope.errors = result.data.error;
                    }
                });
            }
        };

        $scope.loadTenants = function(){
            $http.get("/tenants/index.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.tenants = result.data.all_tenants;
                $scope.init = false;
            });
        };

        $scope.loadContainers = function(){
            $http.get('/containers/byTenant/'+$scope.selectedTenant+'.json', {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.containers = result.data.nest;
                $scope.loadTree(result.data.nest);
            });
        };

        $scope.loadContainerlist = function(){
            $http.get('/containers/byTenantForSelect/'+$scope.selectedTenant+'.json').then(function(result){
                $scope.containerlist = result.data.paths;
            });
        };

        $scope.loadTenants();

        $scope.$watch('selectedTenant',function(){
            if($scope.selectedTenant !== null){
                $scope.loadContainers();
                $scope.loadContainerlist();
            }
        });

    });