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
                                        <option value="<?php echo CT_TENANT; ?>"><?php echo __('Tenant'); ?></option>
                                    <?php endif; ?>
                                    <?php if ($this->Acl->hasPermission('add', 'locations')): ?>
                                        <option value="<?php echo CT_LOCATION; ?>"><?php echo __('Location'); ?></option>
                                    <?php endif; ?>
                                    <?php if ($this->Acl->hasPermission('add', 'containers')): ?>
                                        <option value="<?php echo CT_NODE; ?>"><?php echo __('Node'); ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" ng-class="{'has-error': errors.name}">
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
                                <div ng-repeat="error in errors.name">
                                    <div class="help-block font-xs text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <fieldset class="margin-top-10" ng-show="post.Container.containertype_id='3'">
                        <legend><?php echo __('Optional fields for location'); ?></legend>
                        <div class="row">
                            <label class="col-xs-12 control-label">
                                <?php echo __('Timezone'); ?>
                            </label>
                            <div class="col col-xs-12">
                                <select class="form-control"
                                        chosen="{}"
                                        ng-init="post.Location.timezone = post.Location.timezone || 'Europe/Berlin'"
                                        ng-model="post.string.timezone">
                                    <?php foreach ($timezones as $continent => $continentTimezons): ?>
                                        <optgroup label="<?php echo h($continent); ?>">
                                            <?php foreach ($continentTimezons as $timezoneKey => $timezoneName): ?>
                                                <option value="<?php echo h($timezoneKey); ?>"><?php echo h($timezoneName); ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach;; ?>
                                </select>
                                <div ng-repeat="error in errors.Configfile.timezone">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="row" ng-class="{'has-error': errors.name}">
                                <label class="col-xs-12 control-label">
                                    <?php echo __('Latitude'); ?>
                                </label>
                                <div class="col-xs-12">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-location-arrow"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo '50.5558095'; ?>"
                                                   ng-model="post.Container.name">
                                        </label>
                                        <div ng-repeat="error in errors.name">
                                            <div class="help-block font-xs text-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                </div>
                                <label class="col-xs-12 control-label">
                                    <?php echo __('Longitude'); ?>
                                </label>
                                <div class="col-xs-12">
                                    <div class="form-group smart-form">
                                        <label class="input"> <i class="icon-prepend fa fa-location-arrow"></i>
                                            <input type="text" class="input-sm"
                                                   placeholder="<?php echo '9.6808449'; ?>"
                                                   ng-model="post.Container.name">
                                        </label>
                                        <div ng-repeat="error in errors.name">
                                            <div class="help-block font-xs text-danger">{{ error }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <div class="pull-left" ng-repeat="error in errors.id">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                    <button type="submit" class="btn btn-primary" ng-click="save()">
                        <?php echo __('Save'); ?>
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?php echo __('Cancel'); ?>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>