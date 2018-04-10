<div id="angularEnableServiceFalpDetectionModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><h4>
                        <i class="fa fa-adjust"></i>
                        <?php echo __('Enable service flap detection'); ?>
                    </h4>
            </div>
            <div class="modal-body">

                <div class="row">

                    <div class="col-xs-12 text-center">
                        <div class="well">
                            <span class="hintmark">
                                <?php echo __('Yes, I would like to temporarily <strong>enable</strong> flap detection.'); ?>
                            </span>
                            <div class="padding-left-10 padding-top-10">
                                <span class="note hintmark_before">
                                    <?php echo __('This option is only temporary. It does not affect your configuration. This is an external command and only saved in the memory of your monitoring engine.'); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row" ng-show="isEnableingServiceFlapDetection">
                    <div class="col-xs-12 margin-top-10">
                        <div class="progress progress-striped active">
                            <div class="progress-bar bg-primary" style="width: {{percentage}}%"></div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" ng-click="doEnableServiceFlapDetection()">
                    <?php echo __('Execute'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
