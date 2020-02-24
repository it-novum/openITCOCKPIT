<div id="angularEnableServiceNotificationsModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="far fa-envelope"></i>
                    <?php echo __('Enable service notifications'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="card">
                            <span class="hintmark">
                                 <?php echo __('Yes, I would like to temporarily <strong>enable</strong> notifications.'); ?>
                            </span>
                            <div class="padding-left-10 padding-top-10">
                                <span class="note hintmark_before">
                                      <?php echo __('This option is only temporary. It does not affect your configuration. This is an external command and only saved in the memory of your monitoring engine.'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row" ng-show="isEnableingServiceNotifications">
                    <div class="col-lg-12 margin-top-10">
                        <div class="progress progress-striped active">
                            <div class="progress-bar bg-primary" style="width: {{percentage}}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" ng-click="doEnableServiceNotifications()">
                    <?php echo __('Execute'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
