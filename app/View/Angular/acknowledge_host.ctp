<div id="angularacknowledgeHostModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><h4>
                        <i class="fa fa-user"></i>
                        <?php echo __('Acknowledge host status'); ?>
                    </h4>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-xs-12">
                        <div class="form-group smart-form">
                            <?php echo __('Select method'); ?>
                            <label class="select">
                                <select ng-model="hostAckType">
                                    <option value="hostOnly"><?php echo __('Individual hosts'); ?></option>
                                    <option value="hostAndServices"><?php echo __('Hosts including services'); ?></option>
                                </select> <i></i>
                            </label>
                        </div>
                    </div>
                </div>
                <br/>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form" ng-class="{'has-error': ack.error}">
                            <label class="input"> <i class="icon-prepend fa fa-pencil"></i>
                                <input type="text" class="input-sm"
                                       placeholder="<?php echo __('Comment'); ?>"
                                       ng-model="ack.comment">
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
                                       ng-model="ack.sticky">
                                <i class="checkbox-primary"></i>
                                <?php echo __('Sticky'); ?>
                            </label>
                            <div class="helptext">
                                <?php echo __('Sticky acknowledgements will be stay until the host is back in state Up'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 margin-top-10" ng-show="doHostAck">
                        <h4><?php echo __('Executing command'); ?></h4>
                    </div>
                    <div class="col-xs-12 margin-top-10" ng-show="doHostAck">
                        <div class="progress progress-striped active">
                            <div class="progress-bar bg-primary" style="width: {{percentage}}%"></div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" ng-click="doAcknowledgeHost()">
                    <?php echo __('Save'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
