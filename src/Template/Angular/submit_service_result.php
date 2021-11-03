<div id="angularSubmitServiceResultModal" class="modal z-index-2500" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-download"></i>
                    <?php echo __('Passive transfer of check result'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label class="control-label">
                        <?php echo __('Status'); ?>
                    </label>
                    <select
                        data-placeholder="<?php echo __('Please choose'); ?>"
                        class="form-control"
                        chosen="{}"
                        ng-model="passiveServiceState">
                        <option value="0"><?php echo __('Ok'); ?></option>
                        <option value="1"><?php echo __('Warning'); ?></option>
                        <option value="2"><?php echo __('Critical'); ?></option>
                        <option value="3"><?php echo __('Unknown'); ?></option>
                    </select>
                </div>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="icon-prepend fa fa-pencil-alt"></i></span>
                    </div>
                    <input type="text"
                           class="form-control"
                           placeholder="<?php echo __('Output'); ?>"
                           ng-model="passiveServiceResult.output">
                </div>

                <div class="custom-control custom-checkbox margin-top-20">
                    <input type="checkbox"
                           id="hardStateForce"
                           class="custom-control-input"
                           name="checkbox"
                           checked="checked"
                           ng-model="passiveServiceResult.hardStateForce"
                           ng-model-options="{debounce: 500}">
                    <label class="custom-control-label"
                           for="hardStateForce"><?php echo __('Force to hard state'); ?></label>
                </div>

                <div class="col-lg-12 margin-top-10" ng-show="isSubmittingServiceResult">
                    <h4><?php echo __('Executing command'); ?></h4>
                </div>
                <div class="col-lg-12 margin-top-10" ng-show="isSubmittingServiceResult">
                    <div class="progress progress-striped active">
                        <div class="progress-bar bg-primary" style="width: {{percentage}}%"></div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" ng-click="doSubmitServiceResult()">
                    <?php echo __('Save'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
