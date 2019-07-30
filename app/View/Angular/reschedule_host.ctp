<div id="angularRescheduleHostModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-refresh"></i>
                    <?php echo __('Executing command'); ?>
                </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <section class="smart-form">
                        <label class="label"><?php echo __('Select method'); ?></label>
                        <label class="select">
                            <select ng-model="hostReschedulingType">
                                <option value="hostOnly"><?php echo __('Only host'); ?></option>
                                <option value="hostAndServices"><?php echo __('Host and services'); ?></option>
                            </select> <i></i> </label>
                    </section>
                </div>

                <div class="row" ng-show="isReschedulingHosts">
                    <div class="col-xs-12 margin-top-10">
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
