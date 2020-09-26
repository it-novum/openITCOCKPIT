<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fas fa-puzzle-piece"></i> <?php echo __('Telegram Module'); ?>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="TelegramSettingsIndex">
            <i class="fa fa-gears"></i> <?php echo __('Configuration'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-edit"></i> <?php echo __('Edit'); ?>
    </li>
</ol>

<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    <?php echo __('Telegram'); ?>
                    <span class="fw-300"><i><?php echo __('Configuration'); ?></i></span>
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-xs btn-default mr-1 shadow-0" data-toggle="modal" data-target="#howtoModal">
                        <i class="fas fa-question-circle"></i> <?php echo __('HowTo'); ?>
                    </button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form ng-submit="submit();" class="form-horizontal">
                        <div class="form-group required" ng-class="{'has-error':errors.webhook_url}">
                            <label class="control-label">
                                <?php echo __('Token'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                placeholder="123456789:YYKKKSNNIBBBBAAXXXXATCCC234567...."
                                ng-model="post.token">
                            <div ng-repeat="error in errors.token">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block">
                                <?= __('Telegram bot token'); ?>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error':errors.access_key}">
                            <label class="control-label">
                                <?php echo __('API access key'); ?>
                            </label>
                            <input
                                class="form-control"
                                type="text"
                                ng-model="post.access_key">
                            <div ng-repeat="error in errors.access_key">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block">
                                <?= __('Telegram api access key to authenticate as privileged user in the telegram chat against this api.'); ?>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.two_way}">
                            <div class="custom-control custom-checkbox  margin-bottom-10"
                                 ng-class="{'has-error': errors.two_way}">

                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="enableTwoWayWebook"
                                       ng-model="post.two_way">
                                <label class="custom-control-label" for="enableTwoWayWebook">
                                    <?php echo __('Enable two-way webhook integration'); ?>
                                </label>
                                <div class="help-block">
                                    <?php echo __('If this option is activated, this openITCOCKPIT instance must be accessible via the Internet.'); ?>
                                    <br>
                                    <?php echo __('By using the two-way webhook integration, interactions in Telegram are sent directly to this openITCOCKPIT instance. Otherwise these can only be queried every minute. Please read the documentation to make this setup successful.'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="panel-container show" ng-if="post.two_way">
                            <div class="panel-content">
                                <div class="form-group required"
                                     ng-class="{'has-error':errors.external_webhook_domain}">
                                    <label class="control-label">
                                        <?php echo __('External webhook domain'); ?>
                                    </label>
                                    <input
                                        class="form-control"
                                        type="text"
                                        placeholder="https://demo.openitcockpit.io"
                                        ng-model="post.external_webhook_domain">
                                    <div ng-repeat="error in errors.external_webhook_domain">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                    <div class="help-block">
                                        <?= __('Enter the externally accessible domain name of this openITCOCKPIT instance.'); ?>
                                        <br>
                                        <?= __('The webhook will be automatically set up for the bot based on the given bot token.'); ?>
                                    </div>
                                </div>

                                <div class="form-group required" ng-class="{'has-error':errors.webhook_api_key}">
                                    <label class="control-label">
                                        <?php echo __('Webhook api key'); ?>
                                    </label>
                                    <input
                                        class="form-control"
                                        type="text"
                                        placeholder=""
                                        ng-model="post.webhook_api_key">
                                    <div ng-repeat="error in errors.webhook_api_key">
                                        <div class="help-block text-danger">{{ error }}</div>
                                    </div>
                                    <div class="help-block">
                                        <a href="javascript:void(0);" data-toggle="modal"
                                           data-target="#ApiKeyOverviewModal">
                                            <?= __('API Key') ?>
                                        </a>
                                        <?= __('used by Telegram for authentication against openITCOCKPIT.'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" ng-class="{'has-error': errors.use_proxy}">
                            <div class="custom-control custom-checkbox  margin-bottom-10"
                                 ng-class="{'has-error': errors.use_proxy}">

                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="use_proxy"
                                       ng-model="post.use_proxy">
                                <label class="custom-control-label" for="use_proxy">
                                    <?php echo __('Use Proxy'); ?>
                                </label>
                                <div class="help-block">
                                    <?php
                                    if ($this->Acl->hasPermission('index', 'proxy', '')):
                                        echo __('Determine if the <a ui-sref="ProxyIndex">configured proxy</a> should be used.');
                                    else:
                                        echo __('Determine if the configured proxy should be used.');
                                    endif;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card margin-top-10">
                            <div class="card-body">
                                <div class="float-right">
                                    <input class="btn btn-primary" type="submit"
                                           value="<?= __('Save configuration') ?>">&nbsp;
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HowTo modal -->
<div id="howtoModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-question-circle"></i>
                    <?php echo __('Telegram module - HowTo'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h4>
                                <?= __('Configuration') ?>
                            </h4>
                            <ul>
                                <li>
                                    <?php echo sprintf(__('Open %s in Telegram.'), '<a href="https://t.me/botfather" target="_blank">BotFather</a>'); ?>
                                </li>
                                <li>
                                    <?php echo sprintf(__('Run %s'), '<code>/newbot</code>'); ?>
                                    <ul>
                                        <li><?= __('define e.g. openITCOCKPIT as name') ?></li>
                                        <li><?= __('define e.g. oitcTGBot as username') ?></li>
                                    </ul>
                                </li>
                                <li>
                                    <?php echo sprintf(__('Your new bot is now located at %s.'), '<a href="https://t.me/oitcTGBot" target="_blank">t.me/oitcTGBot</a>'); ?>
                                </li>
                                <li>
                                    <?= __('Copy the HTTP API token which was generated by the BotFather.') ?>
                                </li>
                                <li>
                                    <b><?= __('Keep your token secure and store it safely, it can be used by anyone to control your bot!') ?></b>
                                </li>
                                <li>
                                    <?= __('Open the openITCOCKPIT Telegram configuration, enter the copied bot token and save the configuration.') ?>
                                </li>
                                <li>
                                    <?= __("Add the 'host-notify-by-telegram' and 'service-notify-by-telegram' commands to your notification contact.") ?>
                                </li>
                            </ul>

                            <b><?= __('If your openITCOCKPIT is reachable from the Internet:') ?></b>
                            <br><br>
                            <ul>
                                <li>
                                    <?= __('Open the openITCOCKPIT Telegram configuration and check the optional field "Enable two-way webhook integration".') ?>
                                </li>
                                <li>
                                    <?= __('Make sure the configured "External webhook domain" is correct and openITCOCKPIT is reachable from the Internet using this domain.') ?>
                                </li>
                                <li>
                                    <?= __('Create a new openITCOCKPIT API Key for your user and insert it into the "Webhook api key" field.') ?>
                                </li>
                                <li>
                                    <?= __('Save the changes and try it out.') ?>
                                    <br>
                                    <?= __('openITCOCKPIT automatically set up the webhook for the bot with the given token.') ?>
                                    <br>
                                    <?= __('This connection can be removed by saving with an unchecked "Enable two-way webhook integration" field.') ?>
                                </li>
                            </ul>
                        </div>

                        <div class="col-12">
                            <h4>
                                <?= __('Usage') ?>
                            </h4>
                            <ul>
                                <li>
                                    <?= __('Notifications sent by the openITCOCKPIT telegram module can be received by any chat with a real telegram user or by channels.') ?>
                                </li>
                                <li>
                                    <?= __('With enabled two-way integration issues can be acknowledged by simply clicking the button for an action provided by the bot message.') ?>
                                </li>
                                <li>
                                    <?= __('If your openITCOCKPIT is reachable from the Internet and you configured the two-way webhook integration, interactions with the bot are processed within seconds.') ?>

                                </li>
                                <li>
                                    <?= __('If your openITCOCKPIT is not reachable from the Internet, the built-in basic two-way integration calls up interactions cached by Telegram and processes them every minute. (Therefore, interactions with the bot can take up to a minute to process!)') ?>
                                </li>
                                <li>
                                    <?php echo sprintf(__('Run %s in bot chat to get more information about how to control the bot.'), '<code>/help</code>'); ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<?php echo $this->element('apikey_help'); ?>
