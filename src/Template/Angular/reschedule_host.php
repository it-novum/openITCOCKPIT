<div id="angularRescheduleHostModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-refresh"></i>
                    <?php echo __('Executing command'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group required">
                    <label class="control-label" for="reschedulingType">
                        <?php echo __('Select method'); ?>
                    </label>
                    <select
                        id="reschedulingType"
                        data-placeholder="<?php echo __('Please choose'); ?>"
                        class="form-control"
                        chosen="{}"
                        ng-model="hostReschedulingType">
                        <option value="hostOnly"><?php echo __('Only host'); ?></option>
                        <option value="hostAndServices"><?php echo __('Host and services'); ?></option>
                    </select>
                </div>

                <div class="row" ng-show="isReschedulingHosts">
                    <div class="col-lg-12 margin-top-10">
                        <div class="progress progress-striped active">
                            <div class="progress-bar bg-primary" style="width: {{percentage}}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" ng-click="doHostReschedule()">
                    <?php echo __('Execute'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
