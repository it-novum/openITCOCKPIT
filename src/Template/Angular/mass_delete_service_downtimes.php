<div id="angularMassDeleteServiceDowntimes" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-color-danger txt-color-white">
                <h5 class="modal-title">
                    <?php echo __('Attention!'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <?php echo __('Do you really want to cancel the selected service downtimes?'); ?>
                    </div>

                    <div class="col-lg-12 margin-top-10" ng-show="isDeleting">
                        <h4><?php echo __('Canceling...'); ?></h4>
                    </div>
                    <div class="col-lg-12 margin-top-10" ng-show="isDeleting">
                        <div class="progress progress-striped active">
                            <div class="progress-bar bg-primary" style="width: {{percentage}}%"></div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" ng-click="doDeleteServiceDowntime()">
                    <?php echo __('Cancel downtime'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
