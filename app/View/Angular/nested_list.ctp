<ol class="dd-list" id="nestable">

    <li class="dd-item" data-id="8" ng-repeat="container in containers">
        <button data-action="collapse" type="button" ng-if="container.children.length">Collapse</button>
        <button data-action="expand" type="button" style="display: none;" ng-if="container.children.length">Expand</button>
        <div class="dd-handle">
            <i class="fa fa-home" ng-if="container.Container.parent_id == 1"></i>
            <i class="fa fa-link" ng-if="container.Container.parent_id != 1"></i>
            {{ container.Container.name }}
            <a href="#" data-toggle="modal" data-target="#rename_location_8" class="txt-color-red padding-left-10 font-xs">
                <i class="fa fa-pencil"></i>
                Rename
            </a>
            <a href="#" ng-if="container.Container.parent_id != 1" data-toggle="modal" data-target="#delete_location_8" class="txt-color-red padding-left-10 font-xs">
                <i class="fa fa-trash-o"></i>
                Delete
            </a>
            <i class="note pull-right" ng-if="!container.children.length">empty</i>
            <span class="badge bg-color-blue txt-color-white pull-right" ng-if="container.children.length">{{ container.children.length }}</span>
        </div>
        <div class="dd dd-nodrag" nested-list="" containers="container.children" ng-if="container.children"></div>
    </li>

</ol>


<div class="modal fade" id="delete_location_8" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    ×
                </button>
                <h4 class="modal-title" id="myModalLabel">Do you really want to delete this node and all related objects?</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    Make sure that you really do not use this node<br>
                    and any related objects like: <br>
                    <ul>
                        <li>Hosts</li>
                        <li>HostGroups</li>
                        <li>Users</li>
                        <li>Satellites</li>
                        <li>Locations</li>
                        <li>Services</li>
                        <li>ServiceGroups</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <form action="/containers/delete/8" name="post_5a0e95fbf4114934767368" id="post_5a0e95fbf4114934767368" style="display:none;" method="post"><input name="_method" value="POST" type="hidden"></form><a href="#" class="btn btn-danger" data-dismiss="modal" onclick="document.post_5a0e95fbf4114934767368.submit(); event.returnValue = false; return false;">Delete</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>