<div id="angularMassDeleteAcknowledgements" class="modal" role="dialog">
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
                        <?php echo __('Do you really want to delete the acknowledgements?'); ?>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 margin-top-10">
                            <ul>
                                <li ng-repeat="(id, object) in objects">
                                    {{ object.name }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-12 margin-top-10" ng-show="isDeleting">
                        <h4><?php echo __('Deleting...'); ?></h4>
                    </div>
                    <div class="col-12 margin-top-10" ng-show="isDeleting">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped bg-primary" style="width: {{percentage}}%"></div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" ng-click="doDeleteAcknowledgements()">
                    <?php echo __('Delete acknowledgement'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
