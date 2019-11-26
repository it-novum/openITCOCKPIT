<div id="angularHostDowntimeModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <h4>
                        <i class="fa fa-clock-o"></i>
                        <?php echo __('Set planned maintenance times'); ?>
                    </h4>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-xs-12">
                        <section class="smart-form">
                            <label class="label"><?php echo __('Maintenance period for'); ?></label>
                            <label class="select">
                                <select ng-model="downtimeModal.hostDowntimeType">
                                    <option value="0"><?php echo __('Individual hosts'); ?></option>
                                    <option value="1"><?php echo __('Hosts including services'); ?></option>
                                    <option value="2"><?php echo __('Hosts and dependent Hosts (triggered)'); ?></option>
                                    <option value="3"><?php echo __('Hosts and dependent Hosts (non-triggered)'); ?></option>
                                </select><i> </i></label>
                        </section>
                    </div>
                </div>

                <br/>

                <div class="row">
                    <!-- comment -->
                    <div class="form-group">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Comment'); ?>:
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    placeholder="<?php echo __('In progress'); ?>"
                                    type="text"
                                    ng-model="downtimeModal.comment"
                                    ng-init="downtimeModal.comment='<?php echo __('In progress'); ?>'"
                        </div>
                    </div>
                    <div ng-repeat="error in errors.downtimeModal.comment" class="col-md-offset-2 col-xs-12 col-md-10">
                        <div class="help-block text-danger-important">{{ error }}</div>
                    </div>
                </div>
                <br/>

                <div class="row">
                    <!-- from -->
                    <div class="form-group">
                        <label class="col col-md-2 control-label">
                            <?php echo __('From'); ?>:
                        </label>
                        <div class="col col-xs-5" style="padding-right: 0px;">
                            <input class="form-control"
                                   ng-model="downtimeModal.from_date"
                                   ng-init="downtimeModal.from_date='<?php echo date('d.m.Y'); ?>'"
                                   type="text">
                        </div>
                        <div class="col col-xs-5" style="padding-left: 0px;">
                            <input class="form-control"
                                   ng-model="downtimeModal.from_time"
                                   ng-init="downtimeModal.from_time='<?php echo date('H:i'); ?>'"
                                   type="text">
                        </div>
                        <div ng-repeat="error in errors.Downtime.from_date" class="col-md-offset-2 col-xs-12 col-md-10">
                            <div class="help-block text-danger-important">{{ error }}</div>
                        </div>
                        <div ng-repeat="error in errors.Downtime.from_time" class="col-md-offset-2 col-xs-12 col-md-10">
                            <div class="help-block text-danger-important">{{ error }}</div>
                        </div>
                    </div>

                    <!-- to -->
                    <div class="form-group">
                        <label class="col col-md-2 control-label">
                            <?php echo __('To'); ?>:
                        </label>
                        <div class="col col-xs-5" style="padding-right: 0px;">
                            <input class="form-control"
                                   ng-model="downtimeModal.to_date"
                                   ng-init="downtimeModal.to_date='<?php echo date('d.m.Y', time() + 60 * 15); ?>'"
                                   type="text">
                        </div>
                        <div class="col col-xs-5" style="padding-left: 0px;">
                            <input class="form-control"
                                   ng-model="downtimeModal.to_time"
                                   ng-init="downtimeModal.to_time='<?php echo date('H:i', time() + 60 * 15); ?>'"
                                   type="text">
                        </div>
                        <div ng-repeat="error in errors.Downtime.to_date" class="col-md-offset-2 col-xs-12 col-md-10">
                            <div class="help-block text-danger-important">{{ error }}</div>
                        </div>
                        <div ng-repeat="error in errors.Downtime.to_time" class="col-md-offset-2 col-xs-12 col-md-10">
                            <div class="help-block text-danger-important">{{ error }}</div>
                        </div>
                    </div>


                    <div class="col-xs-12 margin-top-10" ng-show="doDowntime">
                        <h4><?php echo __('Executing command'); ?></h4>
                    </div>
                    <div class="col-xs-12 margin-top-10" ng-show="doDowntime">
                        <div class="progress progress-striped active">
                            <div class="progress-bar bg-primary" style="width: {{percentage}}%"></div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" ng-click="doHostDowntime()">
                    <?php echo __('Save'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
