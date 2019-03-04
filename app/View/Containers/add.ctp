<?php if ($this->Acl->hasPermission('add', 'containers')): ?>
    <a ng-if="container.Container.containertype_id == <?php echo CT_GLOBAL; ?> ||
    container.Container.containertype_id == <?php echo CT_NODE; ?> ||
    container.Container.containertype_id == <?php echo CT_TENANT; ?> ||
    container.Container.containertype_id == <?php echo CT_LOCATION; ?>"
       class="txt-color-green padding-left-10 font-xs pointer"
       ng-click="openModal()">
        <i class="fa fa-plus"></i>
        <?php echo __('Add'); ?>
    </a>
<?php endif; ?>

<?php if ($this->Acl->hasPermission('showDetails', 'containers')): ?>
    <a ng-if="container.Container.containertype_id == <?php echo CT_NODE; ?> ||
        container.Container.containertype_id == <?php echo CT_TENANT; ?> ||
        container.Container.containertype_id == <?php echo CT_LOCATION; ?>"
       class="text-info padding-left-10 font-xs pointer"
       ui-sref="ContainersShowDetails({id:container.Container.id})">
        <i class="fa fa-info"></i>
        <?php echo __('Show details'); ?>
    </a>
<?php endif;
$timezones = CakeTime::listTimezones();
?>
<div id="angularAddNode-{{container.Container.id}}" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form onsubmit="return false;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo __('Add new container'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('Select container type'); ?>
                            </label>
                            <div class="col-xs-12">
                                <select class="form-control" ng-init="post.Container.containertype_id='5'"
                                        ng-model="post.Container.containertype_id">
                                    <?php if ($this->Acl->hasPermission('add', 'tenants')): ?>
                                        <option value="<?php echo CT_TENANT; ?>"
                                                ng-show="container.Container.containertype_id == 1">
                                            <?php echo __('Tenant'); ?>
                                        </option>
                                    <?php endif; ?>
                                    <?php if ($this->Acl->hasPermission('add', 'locations')): ?>
                                        <option value="<?php echo CT_LOCATION; ?>">
                                            <?php echo __('Location'); ?>
                                        </option>
                                    <?php endif; ?>
                                    <?php if ($this->Acl->hasPermission('add', 'containers')): ?>
                                        <option value="<?php echo CT_NODE; ?>">
                                            <?php echo __('Node'); ?>
                                        </option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" ng-class="{'has-error': errors.Container.name || errors.name}">
                        <label class="col-xs-12 control-label">
                            <?php echo __('Name'); ?>
                        </label>
                        <div class="col-xs-12">
                            <div class="form-group smart-form">
                                <label class="input"> <i class="icon-prepend fa fa-folder-open"></i>
                                    <input type="text" class="input-sm"
                                           placeholder="<?php echo __('Container name'); ?>"
                                           ng-model="post.Container.name">
                                </label>
                                <div ng-repeat="error in errors.Container.name">
                                    <div class="help-block font-xs text-danger">{{ error }}</div>
                                </div>
                                <div ng-repeat="error in errors.name">
                                    <div class="help-block font-xs text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <fieldset class="margin-top-10" ng-show="post.Container.containertype_id==3">
                        <legend><?php echo __('Optional fields for location'); ?></legend>
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('Description'); ?>
                            </label>
                            <div class="col-xs-12">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-info"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('Description'); ?>"
                                               ng-model="post.Location.description">
                                    </label>
                                    <div ng-repeat="error in errors.name">
                                        <div class="help-block font-xs text-danger">{{ error }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('Timezone'); ?>
                            </label>
                            <div class="col col-xs-12">
                                <select class="form-control"
                                        chosen="{}"
                                        ng-init="post.Location.timezone = post.Location.timezone || 'Europe/Berlin'"
                                        ng-model="post.Location.timezone">
                                    <?php foreach ($timezones as $continent => $continentTimezons): ?>
                                        <optgroup label="<?php echo h($continent); ?>">
                                            <?php foreach ($continentTimezons as $timezoneKey => $timezoneName): ?>
                                                <option value="<?php echo h($timezoneKey); ?>"><?php echo h($timezoneName); ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach;; ?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="row" ng-class="{'has-error': errors.latitude}">
                                <label class="col-xs-12 control-label">
                                    <?php echo __('Latitude'); ?>
                                </label>
                                <div class="col-xs-12">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-map-marker"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo '50.5558095'; ?>"
                                                   ng-model="post.Location.latitude">
                                        </label>
                                        <div ng-repeat="error in errors.latitude">
                                            <div class="help-block font-xs text-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" ng-class="{'has-error': errors.longitude}">
                                <label class="col-xs-12 control-label">
                                    <?php echo __('Longitude'); ?>
                                </label>
                                <div class="col-xs-12">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-map-marker"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo '9.6808449'; ?>"
                                                   ng-model="post.Location.longitude">
                                        </label>
                                        <div ng-repeat="error in errors.longitude">
                                            <div class="help-block font-xs text-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="margin-top-10" ng-show="post.Container.containertype_id==2">
                        <legend><?php echo __('Optional fields for tenant'); ?></legend>
                        <div class="row padding-bottom-10">
                            <div class="form-group">
                                <label class="col col-xs-2 control-label" for="isActive">
                                    <?php echo __('Is active'); ?>
                                </label>
                                <div class="col-xs-10 smart-form">
                                    <label class="checkbox small-checkbox-label no-required">
                                        <input type="checkbox" name="checkbox"
                                               id="isActive"
                                               ng-true-value="1"
                                               ng-false-value="0"
                                               ng-model="post.Tenant.is_active">
                                        <i class="checkbox-primary"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('Description'); ?>
                            </label>
                            <div class="col-xs-12">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-info"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('Info text ...'); ?>"
                                               ng-model="post.Tenant.description">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('First name'); ?>
                            </label>
                            <div class="col-xs-12">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('John'); ?>"
                                               ng-model="post.Tenant.firstname">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('Last name'); ?>
                            </label>
                            <div class="col-xs-12">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('Doe'); ?>"
                                               ng-model="post.Tenant.lastname">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('Street'); ?>
                            </label>
                            <div class="col-xs-12">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-road"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('Any street'); ?>"
                                               ng-model="post.Tenant.street">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('Zip code'); ?>
                            </label>
                            <div class="col-xs-12">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-building-o"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('12345'); ?>"
                                               ng-model="post.Tenant.zipcode">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('City'); ?>
                            </label>
                            <div class="col-xs-12">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-building-o"></i>
                                        <input type="text" class="input-sm"
                                               placeholder="<?php echo __('Any city'); ?>"
                                               ng-model="post.Tenant.city">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row" ng-class="{'has-error': errors.max_users}">
                            <label class="col-xs-12 control-label">
                                <?php echo __('Max Users'); ?>
                                <span class="text-info font-xs">
                                    <?php echo __('(enter 0 for infinity)'); ?>
                                </span>
                            </label>
                            <div class="col-xs-12">
                                <div class="form-group smart-form">
                                    <label class="input"> <i class="icon-prepend fa fa-users"></i>
                                        <input type="number" class="input-sm" min="0"
                                               placeholder="<?php echo __('Maximum allowed number of users'); ?>"
                                               ng-model="post.Tenant.max_users">
                                    </label>
                                </div>
                                <div ng-repeat="error in errors.max_users">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <div class="pull-left" ng-repeat="error in errors.id">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                    <button type="submit" class="btn btn-primary" ng-click="saveNode()"
                            ng-show="post.Container.containertype_id==5">
                        <?php echo __('Create new node'); ?>
                    </button>
                    <button type="submit" class="btn btn-primary" ng-click="saveTenant()"
                            ng-show="post.Container.containertype_id==2">
                        <?php echo __('Create new node'); ?>
                    </button>
                    <button type="submit" class="btn btn-primary" ng-click="saveLocation()"
                            ng-show="post.Container.containertype_id==3">
                        <?php echo __('Create new node'); ?>
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?php echo __('Cancel'); ?>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>