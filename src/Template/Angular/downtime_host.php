<div id="angularHostDowntimeModal" class="modal" role="dialog">
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
                        <div class="form-group required">
                            <label class="control-label" for="downtimeForType">
                                <?php echo __('Maintenance period for'); ?>
                            </label>
                            <select
                                id="downtimeForType"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="{}"
                                ng-model="downtimeModal.hostDowntimeType">
                                <option value="0"><?php echo __('Individual hosts'); ?></option>
                                <option value="1"><?php echo __('Hosts including services'); ?></option>
                                <option value="2"><?php echo __('Hosts and dependent Hosts (triggered)'); ?></option>
                                <option
                                    value="3"><?php echo __('Hosts and dependent Hosts (non-triggered)'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row margin-top-10">
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
                                       type="date">
                            </div>
                            <div class="col col-xs-5">
                                <input class="form-control"
                                       ng-model="downtimeModal.from_time"
                                       ng-model-options="{timeSecondsFormat:'ss', timeStripZeroSeconds: true}"
                                       type="time">
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
                                       type="date">
                            </div>
                            <div class="col col-xs-5">
                                <input class="form-control"
                                       ng-model="downtimeModal.to_time"
                                       ng-model-options="{timeSecondsFormat:'ss', timeStripZeroSeconds: true}"
                                       type="time">
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
                    <div class="col-12 margin-top-10" ng-show="doDowntime">
                        <h4><?php echo __('Executing command'); ?></h4>
                    </div>
                    <div class="col-12 margin-top-10" ng-show="doDowntime">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped bg-primary" style="width: {{percentage}}%"></div>
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
</div>
