<div id="angularServiceDowntimeModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><h4>
                        <i class="fa fa-clock-o"></i>
                        <?php echo __('Set planned maintenance times'); ?>
                    </h4>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="form-group">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Comment'); ?>:
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    placeholder="<?php echo __('In progress'); ?>"
                                    type="text"
                                    ng-model="downtime.comment">
                        </div>
                    </div>
                    <!-- from -->
                    <div class="form-group">
                        <label class="col col-md-2 control-label">
                            <?php echo __('From'); ?>:
                        </label>
                        <div class="col col-xs-5" style="padding-right: 0px;">
                            <input class="form-control"
                                   ng-model="downtime.from_date"
                                   type="text">
                        </div>
                        <div class="col col-xs-5" style="padding-left: 0px;">
                            <input class="form-control"
                                   ng-model="downtime.from_time"
                                   type="text">
                        </div>
                    </div>

                    <!-- to -->
                    <div class="form-group">
                        <label class="col col-md-2 control-label">
                            <?php echo __('To'); ?>:
                        </label>
                        <div class="col col-xs-5" style="padding-right: 0px;">
                            <input value="20.11.2017" class="form-control"
                                   type="text">
                        </div>
                        <div class="col col-xs-5" style="padding-left: 0px;">
                            <input value="04:11" class="form-control"
                                   type="text">
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
                <button type="button" class="btn btn-success" ng-click="doAcknowledgeService()">
                    <?php echo __('Save'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
