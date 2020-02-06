<div id="angularacknowledgeHostModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-user"></i>
                    <?php echo __('Acknowledge host status'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group required">
                            <label class="control-label" for="ackHostmethod">
                                <?php echo __('Select method'); ?>
                            </label>
                            <select
                                id="ackHostmethod"
                                data-placeholder="<?php echo __('Please choose'); ?>"
                                class="form-control"
                                chosen="{}"
                                ng-model="hostAckType">
                                <option value="hostOnly"><?php echo __('Individual hosts'); ?></option>
                                <option value="hostAndServices"><?php echo __('Hosts including services'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 margin-top-10">
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
                            <?php echo __('Sticky acknowledgements will be stay until the host is back in state Up'); ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 margin-top-10" ng-show="doHostAck">
                    <h4><?php echo __('Executing command'); ?></h4>
                </div>
                <div class="col-lg-12 margin-top-10" ng-show="doHostAck">
                    <div class="progress progress-striped active">
                        <div class="progress-bar bg-primary" style="width: {{percentage}}%"></div>
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
