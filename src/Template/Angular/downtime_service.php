<div id="angularServiceDowntimeModal" class="modal z-index-2500" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-clock-o"></i>
                    <?php echo __('Set planned maintenance times'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="control-label">
                                <?php echo __('Comment'); ?>
                            </label>
                            <input
                                id="downtimeComment"
                                class="form-control"
                                placeholder="<?php echo __('In progress'); ?>"
                                type="text"
                                ng-model="downtimeModal.comment"
                                ng-init="downtimeModal.comment='<?php echo __('In progress'); ?>'">

                            <div ng-repeat="error in errors.Downtime.comment"
                                 class="col-md-offset-2 col-xs-12 col-md-10">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row margin-top-10">
                    <!-- from -->
                    <div class="col col-lg-6">
                        <div class="form-group">
                            <label class="control-label">
                                <?php echo __('From'); ?>:
                            </label>
                            <div class="col col-xs-5">
                                <input class="form-control"
                                       ng-model="downtimeModal.from_date"
                                       ng-init="downtimeModal.from_date='<?php echo date('d.m.Y'); ?>'"
                                       type="text">
                            </div>
                            <div class="col col-xs-5">
                                <input class="form-control"
                                       ng-model="downtimeModal.from_time"
                                       ng-init="downtimeModal.from_time='<?php echo date('H:i'); ?>'"
                                       type="text">
                            </div>
                            <div ng-repeat="error in errors.Downtime.from_date"
                                 class="col-md-offset-2 col-xs-12 col-md-10">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div ng-repeat="error in errors.Downtime.from_time"
                                 class="col-md-offset-2 col-xs-12 col-md-10">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                    </div>

                    <!-- to -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="control-label">
                                <?php echo __('To'); ?>:
                            </label>
                            <div class="col col-xs-5">
                                <input class="form-control"
                                       ng-model="downtimeModal.to_date"
                                       ng-init="downtimeModal.to_date='<?php echo date('d.m.Y', time() + 60 * 15); ?>'"
                                       type="text">
                            </div>
                            <div class="col col-xs-5">
                                <input class="form-control"
                                       ng-model="downtimeModal.to_time"
                                       ng-init="downtimeModal.to_time='<?php echo date('H:i', time() + 60 * 15); ?>'"
                                       type="text">
                            </div>
                            <div ng-repeat="error in errors.Downtime.to_date"
                                 class="col-md-offset-2 col-xs-12 col-md-10">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div ng-repeat="error in errors.Downtime.to_time"
                                 class="col-md-offset-2 col-xs-12 col-md-10">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xs-12 margin-top-10" ng-show="doDowntime">
                        <h4><?php echo __('Executing command'); ?></h4>
                    </div>
                    <div class="col-lg-12 margin-top-10" ng-show="doDowntime">
                        <div class="progress progress-striped active">
                            <div class="progress-bar bg-primary" style="width: {{percentage}}%"></div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" ng-click="doServiceDowntime()">
                        <?php echo __('Save'); ?>
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?php echo __('Close'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
