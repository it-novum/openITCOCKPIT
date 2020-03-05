
<!-- Edit API key modal -->
<div id="angularEditApiKeyModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-key"></i>
                    <?php echo __('Edit API key'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group" ng-class="{'has-error': errors.description}">
                    <label class="control-label">
                        <?php echo __('Description'); ?>
                    </label>
                    <input
                        class="form-control"
                        type="text"
                        size="255"
                        ng-model="currentApiKey.description">
                    <div ng-repeat="error in errors.description">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>

                <div class="form-group" ng-class="{'has-error': errors.apikey}">
                    <label class="control-label">
                        <?php echo __('API key (read-only)'); ?>
                    </label>
                    <input
                        class="form-control disabled"
                        type="text"
                        readonly
                        ng-model="currentApiKey.apikey">
                    <div ng-repeat="error in errors.apikey">
                        <div class="help-block text-danger">{{ error }}</div>
                    </div>
                </div>

                <div class="row padding-top-10">
                    <div class="col-lg-12 padding-bottom-5">
                        <span class="bold">
                            <code>curl</code> <?php echo __('example'); ?>:
                        </span>
                    </div>
                    <div class="col-lg-12">
                        <pre>curl -H \
"Authorization: X-OITC-API {{currentApiKey.apikey}}" \
"https://<?php echo h($_SERVER['SERVER_ADDR']); ?>/hosts/index.json?angular=true"</pre>
                    </div>
                    <div class="col-lg-12">
                        <?php echo __('For self-signed certificates, add'); ?><code>-k</code>.
                    </div>
                </div>

                <div class="row padding-top-10" >
                    <div class="col-lg-12 padding-bottom-5">
                        <span class="bold">
                            <code>curl</code> <?php echo __('example with JSON processor'); ?>:
                        </span>
                    </div>
                    <div class="col-lg-12">
                        <pre>curl -k -s -H \
"Authorization: X-OITC-API {{currentApiKey.apikey}}" \
"https://<?php echo h($_SERVER['SERVER_ADDR']); ?>/hosts/index.json?angular=true" |jq .</pre>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" ng-click="deleteApiKey()">
                    <i class="fa fa-trash"></i>
                    <?php echo __('Delete'); ?>
                </button>

                <button type="button" class="btn btn-primary" ng-click="updateApiKey()">
                    <?php echo __('Save'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
