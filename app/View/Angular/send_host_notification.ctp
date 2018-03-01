<div id="angularSubmitHostNotificationModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><h4>
                        <i class="fa fa-envelope"></i>
                        <?php echo __('Send custom host notification'); ?>
                    </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form">
                            <label class="input"> <i class="icon-prepend fa fa-pencil"></i>
                                <input type="text" class="input-sm"
                                       placeholder="<?php echo __('Comment'); ?>"
                                       ng-model="sendHostNotification.comment">
                            </label>
                        </div>
                    </div>
                </div>

                <br/>

                <div class="row">
                    <div class="col-xs-12 ">
                        <div class="form-group smart-form">
                            <label class="checkbox small-checkbox-label">
                                <input type="checkbox" name="checkbox" checked="checked"
                                       ng-model="sendHostNotification.force">
                                <i class="checkbox-primary"></i>
                                <?php echo __('Force'); ?>
                            </label>
                            <div class="helptext">
                                <?php echo __('Time period and notifications disabled configuration will be ignored'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="padding-top-10 margin-bottom-10"><!-- spacer --></div>
                    </div>

                    <div class="col-xs-12 ">
                        <div class="form-group smart-form">
                            <label class="checkbox small-checkbox-label">
                                <input type="checkbox" name="checkbox" checked="checked"
                                       ng-model="sendHostNotification.broadcast">
                                <i class="checkbox-primary"></i>
                                <?php echo __('Broadcast'); ?>
                            </label>
                            <div class="helptext">
                                <?php echo __('Notification will also be sent to escalation contacts'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 margin-top-10" ng-show="isSubmittingHostNotification">
                        <h4><?php echo __('Executing command'); ?></h4>
                    </div>
                    <div class="col-xs-12 margin-top-10" ng-show="isSubmittingHostNotification">
                        <div class="progress progress-striped active">
                            <div class="progress-bar bg-primary" style="width: {{percentage}}%"></div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" ng-click="doSubmitHostNotification()">
                    <?php echo __('Save'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
