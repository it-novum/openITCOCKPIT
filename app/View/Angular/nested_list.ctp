<ol class="dd-list" id="nestable">

    <li class="dd-item" data-id="1" ng-repeat="container in containers">
        <button data-action="collapse" type="button" ng-if="container.children.length">Collapse</button>
        <button data-action="expand" type="button" style="display: none;" ng-if="container.children.length">Expand</button>
        <div class="dd-handle">
            <i class="fa fa-home" ng-if="container.Container.parent_id == 1"></i>
            <i class="fa fa-link" ng-if="container.Container.parent_id != 1"></i>
            {{ container.Container.name }}
            <a href="#" ng-if="container.Container.parent_id != 1" data-toggle="modal" data-target="#delete_location_8" class="txt-color-red padding-left-10 font-xs"><i class="fa fa-trash-o"></i>Delete</a>
            <i class="note pull-right" ng-if="!container.children.length">empty</i>
            <span class="badge bg-color-blue txt-color-white pull-right" ng-if="container.children.length">{{ container.children.length }}</span>
        </div>
        <div class="dd dd-nodrag" nested-list="" containers="container.children" ng-if="container.children"></div>
    </li>

</ol>
