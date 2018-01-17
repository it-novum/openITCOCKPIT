<?php if ($this->Acl->hasPermission('edit','containers')): ?>
    <a ng-if="container.Container.containertype_id == <?php echo CT_NODE; ?>"
       class="txt-color-red padding-left-10 font-xs pointer"
       ng-click="openModal()"
    >
        <i class="fa fa-pencil"></i>
        <?php echo __('Edit'); ?>
    </a>
<?php endif; ?>


<div id="angularEditNode-{{container.Container.id}}" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form onsubmit="return false;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo __('Edit Node'); ?></h4>
                </div>
                <div class="modal-body" ng-class="{'has-error': errors.name}">
                    <div class="row">
                        <div class="col-xs-2">
                            <label class="control-label">
                                <?php echo __('Edit node name: '); ?>
                            </label>
                        </div>
                        <div class="col-xs-10">
                            <input type="text"
                                   class="form-control"
                                   maxlength="255"
                                   required="required"
                                   placeholder="<?php echo __('Node name'); ?>"
                                   ng-model="post.Container.name"
                            >
                            <div ng-repeat="error in errors.name">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" ng-click="delete()" ng-class="{'has-error': errors.id}">
                        <i class="fa fa-refresh fa-spin" ng-show="isDeleting"></i>
                        <?php echo __('Delete'); ?>
                    </button>
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