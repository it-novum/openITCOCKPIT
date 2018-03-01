<div id="angularSubmitHostResultModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><h4>
                        <i class="fa fa-download"></i>
                        <?php echo __('Passive transfer of check result'); ?>
                    </h4>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-xs-12">
                        <div class="form-group smart-form">
                            <?php echo __('Status'); ?>
                            <label class="select">
                                <select ng-model="passiveHostState">
                                    <option value="0"><?php echo __('Up'); ?></option>
                                    <option value="1"><?php echo __('Down'); ?></option>
                                    <option value="2"><?php echo __('Unreachable'); ?></option>
                                </select> <i></i>
                            </label>
                        </div>
                    </div>
                </div>
                <br/>

                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group smart-form">
                            <label class="input"> <i class="icon-prepend fa fa-pencil"></i>
                                <input type="text" class="input-sm"
                                       placeholder="<?php echo __('Output'); ?>"
                                       ng-model="passiveHostResult.output">
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
                                       ng-model="passiveHostResult.hardStateForce">
                                <i class="checkbox-primary"></i>
                                <?php echo __('Force to hard state'); ?>
                            </label>
                        </div>
                    </div>

                    <div class="col-xs-12 margin-top-10" ng-show="isSubmittingHostResult">
                        <h4><?php echo __('Executing command'); ?></h4>
                    </div>
                    <div class="col-xs-12 margin-top-10" ng-show="isSubmittingHostResult">
                        <div class="progress progress-striped active">
                            <div class="progress-bar bg-primary" style="width: {{percentage}}%"></div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" ng-click="doSubmitHostResult()">
                    <?php echo __('Save'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
