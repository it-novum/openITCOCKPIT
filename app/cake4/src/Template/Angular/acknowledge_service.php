<div id="angularacknowledgeServiceModal" class="modal" role="dialog">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-user"></i>
                    <?php echo __('Acknowledge service status'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="input-group" ng-class="{'has-error': ack.error}">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="icon-prepend fa fa-pencil-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="<?php echo __('Comment'); ?>"
                                   ng-model="ack.comment">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 margin-top-10">

                        <div class="custom-control custom-checkbox custom-control-down margin-bottom-10">
                            <input type="checkbox"
                                   name="checkbox"
                                   class="custom-control-input"
                                   checked="checked"
                                   ng-model="ack.sticky"
                                   id="ackSticky">
                            <label class="custom-control-label" for="ackSticky">
                                <?php echo __('Sticky'); ?>
                            </label>
                        </div>
                        <div class="helptext">
                            <?php echo __('Sticky acknowledgements will be stay until the service is back in state Ok'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 margin-top-10" ng-show="doAck">
                    <h4><?php echo __('Executing command'); ?></h4>
                </div>
                <div class="col-lg-12 margin-top-10" ng-show="doAck">
                    <div class="progress progress-striped active">
                        <div class="progress-bar bg-primary" style="width: {{percentage}}%"></div>
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




