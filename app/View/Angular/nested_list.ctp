<li class="dd-item" data-id="{{ container.Container.id }}">

    <button data-action="collapse" type="button" ng-if="container.children.length">Collapse</button>
    <button data-action="expand" type="button" style="display: none;" ng-if="container.children.length">Expand</button>

    <div class="dd-handle" parent-id="{{ container.Container.parent_id }}"
         containertype-id="{{ container.Container.id }}">

        <i class="fa fa-home" ng-if="container.Container.parent_id == 1"></i>
        <i class="fa fa-link" ng-if="container.Container.parent_id != 1"></i>
        {{ container.Container.name }}
        <a href="#" data-toggle="modal" data-target="#rename_location_{{ container.Container.id }}"
           class="txt-color-red padding-left-10 font-xs">
            <i class="fa fa-pencil"></i>
            Rename
        </a>
        <a href="#" ng-if="container.Container.parent_id != 1" data-toggle="modal"
           data-target="#delete_location_{{ container.Container.id }}" class="txt-color-red padding-left-10 font-xs">
            <i class="fa fa-trash-o"></i>
            Delete
        </a>
        <i class="note pull-right" ng-if="!container.children.length">empty</i>
        <span class="badge bg-color-blue txt-color-white pull-right" ng-if="container.children.length">{{ container.children.length }}</span>

    </div>


    <ol class="dd-list" nested-list="" container="child" ng-repeat="child in container.children" ng-if="container.children"></ol>

</li>

