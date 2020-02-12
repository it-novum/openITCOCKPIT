<div id="angularSubmitServiceNotificationModal" class="modal" role="dialog">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-envelope"></i>
                    <?php echo __('Send custom service notification'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-prepend fa fa-pencil-alt"></i></span>
                    </div>
                    <input type="text"
                           class="form-control"
                           placeholder="<?php echo __('Comment'); ?>"
                           ng-model="sendServiceNotification.force">
                </div>

                <div class="custom-control custom-checkbox margin-top-20">
                    <input type="checkbox"
                           name="checkbox"
                           class="custom-control-input"
                           checked="checked"
                           ng-model="sendServiceNotification.force"
                           id="forceSend">
                    <label class="custom-control-label" for="forceSend">
                        <?php echo __('Force'); ?>
                    </label>
                    <div class="helptext">
                        <?php echo __('Time period and notifications disabled configuration will be ignored'); ?>
                    </div>
                </div>

                <div class="custom-control custom-checkbox margin-top-20">
                    <input type="checkbox"
                           name="checkbox"
                           class="custom-control-input"
                           checked="checked"
                           g-model="sendServiceNotification.broadcast"
                           id="forceSend">
                    <label class="custom-control-label" for="forceSend">
                        <?php echo __('Broadcast'); ?>
                    </label>
                    <div class="helptext">
                        <?php echo __('Notification will also be sent to escalation contacts'); ?>
                    </div>
                </div>


                <div class="col-lg-12 margin-top-10" ng-show="isSubmittingServiceNotification">
                    <h4><?php echo __('Executing command'); ?></h4>
                </div>
                <div class="col-lg-12 margin-top-10" ng-show="isSubmittingServiceNotification">
                    <div class="progress progress-striped active">
                        <div class="progress-bar bg-primary" style="width: {{percentage}}%"></div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" ng-click="doSubmitServiceNotification()">
                    <?php echo __('Save'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
