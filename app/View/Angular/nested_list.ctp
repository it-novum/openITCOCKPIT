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
        <i class="fa fa-pencil-square-o"
           ng-if="container.Container.containertype_id == <?php echo CT_SERVICETEMPLATEGROUP; ?>"></i>

        <div class="nodes-container-name" title="{{ container.Container.name }}">
            <span class="ellipsis"">{{ container.Container.name }}</span>
        </div>

        <?php if ($this->Acl->hasPermission('edit', 'containers')): ?>
            <edit-node container="container" callback="callback"
                       ng-if="container.Container.allow_edit === true"></edit-node>
        <?php endif; ?>

        <?php if ($this->Acl->hasPermission('add', 'containers')): ?>
            <add-node container="container" callback="callback"
                      ng-if="container.Container.allow_edit === true"></add-node>
        <?php endif; ?>


        <i class="note pull-right" ng-if="((container.Container.rght-container.Container.lft)/2-0.5) == 0">empty</i>
        <span class="badge bg-color-blue txt-color-white pull-right"
              ng-if="((container.Container.rght-container.Container.lft)/2-0.5) > 0">{{ (container.Container.rght-container.Container.lft)/2-0.5 }}</span>

    </div>

    <ol class="dd-list">
        <nested-list container="container" ng-repeat="container in container.children"
                     callback="callback"></nested-list>
    </ol>

</li>
