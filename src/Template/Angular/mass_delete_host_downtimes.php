<div id="angularMassDeleteHostDowntimes" class="modal" role="dialog">
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
                        <?php echo __('Do you really want to cancel the selected host downtimes?'); ?>
                    </div>

                    <div class="col-lg-12 padding-top-15">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox"
                                   id="includeServices"
                                   class="custom-control-input"
                                   name="checkbox"
                                   checked="checked"
                                   ng-model="includeServices">
                            <label class="custom-control-label" for="includeServices">
                                <?php echo __('Also cancel corresponding service downtimes.'); ?>
                            </label>
                        </div>
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
                <button type="button" class="btn btn-danger" ng-click="doDeleteHostDowntime()">
                    <?php echo __('Cancel downtime'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
