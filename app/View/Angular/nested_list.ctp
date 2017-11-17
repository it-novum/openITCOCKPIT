<li class="dd-item" data-id="{{ container.Container.id }}">

    <button data-action="collapse" type="button" ng-if="container.children.length">Collapse</button>
    <button data-action="expand" type="button" style="display: none;" ng-if="container.children.length">Expand</button>

    <div class="dd-handle" parent-id="{{ container.Container.parent_id }}"
         containertype-id="{{ container.Container.id }}">

        <i class="fa fa-globe" ng-if="container.Container.containertype_id == <?php echo CT_GLOBAL; ?>"></i>
        <i class="fa fa-home" ng-if="container.Container.containertype_id == <?php echo CT_TENANT; ?>"></i>
        <i class="fa fa-location-arrow" ng-if="container.Container.containertype_id == <?php echo CT_LOCATION; ?>"></i>
        <i class="fa fa-link" ng-if="container.Container.containertype_id == <?php echo CT_NODE; ?>"></i>
        <i class="fa fa-users" ng-if="container.Container.containertype_id == <?php echo CT_CONTACTGROUP; ?>"></i>
        <i class="fa fa-sitemap" ng-if="container.Container.containertype_id == <?php echo CT_HOSTGROUP; ?>"></i>
        <i class="fa fa-cogs" ng-if="container.Container.containertype_id == <?php echo CT_SERVICEGROUP; ?>"></i>
        <i class="fa fa-pencil-square-o" ng-if="container.Container.containertype_id == <?php echo CT_SERVICETEMPLATEGROUP; ?>"></i>

        {{ container.Container.name }}

        <a href="#" data-toggle="modal" data-target="#rename_{{ container.Container.id }}"
           class="txt-color-red padding-left-10 font-xs">
            <i class="fa fa-pencil"></i>
            Rename
        </a>
        <a href="#" ng-if="container.Container.parent_id != 1" data-toggle="modal"
           data-target="#delete_{{ container.Container.id }}" class="txt-color-red padding-left-10 font-xs">
            <i class="fa fa-trash-o"></i>
            Delete
        </a>
        <i class="note pull-right" ng-if="((container.Container.rght-container.Container.lft)/2-0.5) == 0">empty</i>
        <span class="badge bg-color-blue txt-color-white pull-right" ng-if="((container.Container.rght-container.Container.lft)/2-0.5) > 0">{{ (container.Container.rght-container.Container.lft)/2-0.5 }}</span>
    </div>

    <ol class="dd-list" nested-list="" container="child" ng-repeat="child in container.children" ng-if="container.children"></ol>
{{container_list}}
</li>


