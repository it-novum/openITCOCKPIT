<div id="angularConfirmDeactivate" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning txt-color-white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('Attention!'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <?php echo __('Do you really want deactivate the selected object?'); ?>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-warning" ng-click="deactivate()">
                    <i class="fa fa-refresh fa-spin" ng-show="isDeactivating"></i>
                    <?php echo __('Disable'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Cancel'); ?>
                </button>
            </div>
        </div>

    </div>
</div>