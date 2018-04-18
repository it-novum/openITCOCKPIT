<div id="angularCreateApiKeyModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><h4>
                        <i class="fa fa-key"></i>
                        <?php echo __('Create API key'); ?>
                    </h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <section class="smart-form" ng-class="{'has-error': errors.description}">
                        <div class="required">
                            <label class="label"><?php echo __('Description'); ?></label>
                        </div>
                        <label class="input">
                            <input type="text" size="255" ng-model="post.Apikey.description">
                        </label>
                        <div ng-repeat="error in errors.description">
                            <div class="help-block text-danger">{{ error }}</div>
                        </div>
                    </section>

                    <section class="smart-form">
                        <label class="label"><?php echo __('API key (read-only)'); ?></label>
                        <label class="input">
                            <input type="text" readonly ng-model="post.Apikey.apikey" class="disabled">
                        </label>
                    </section>
                </div>

                <div class="row padding-top-10" ng-show="newApiKey">
                    <div class="col-xs-12 no-padding">
                        <span class="bold">
                            <code>curl</code> <?php echo __('example'); ?>:
                        </span>
                    </div>
                    <div>
                        <pre>curl -H \
"Authorization: X-OITC-API {{post.Apikey.apikey}}" \
"https://<?php echo h($_SERVER['SERVER_ADDR']); ?>/hosts/index.json?angular=true"</pre>
                    </div>
                    <div class="col-xs-12 no-padding">
                        <?php echo __('For self-signed certificates, add'); ?><code>-k</code>.
                    </div>
                </div>

                <div class="row padding-top-10" ng-show="newApiKey">
                    <div class="col-xs-12 no-padding">
                        <span class="bold">
                            <code>curl</code> <?php echo __('example with JSON processor'); ?>:
                        </span>
                    </div>
                    <div>
                        <pre>curl -k -s -H \
"Authorization: X-OITC-API {{post.Apikey.apikey}}" \
"https://<?php echo h($_SERVER['SERVER_ADDR']); ?>/hosts/index.json?angular=true" |jq .</pre>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary pull-left" ng-click="getNewApiKey()">
                    <i class="fa fa-refresh"></i>
                    <?php echo __('Generate new key'); ?>
                </button>
                <button type="button" class="btn btn-primary" ng-click="saveApiKey()">
                    <?php echo __('Save'); ?>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
