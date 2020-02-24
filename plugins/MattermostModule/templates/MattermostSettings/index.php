<?php
/**
 * // Copyright (C) <2017>  <it-novum GmbH>
 * //
 * // This file is dual licensed
 * //
 * // 1.
 * //    This program is free software: you can redistribute it and/or modify
 * //    it under the terms of the GNU General Public License as published by
 * //    the Free Software Foundation, version 3 of the License.
 * //
 * //    This program is distributed in the hope that it will be useful,
 * //    but WITHOUT ANY WARRANTY; without even the implied warranty of
 * //    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * //    GNU General Public License for more details.
 * //
 * //    You should have received a copy of the GNU General Public License
 * //    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * //
 * // 2.
 * //    If you purchased an openITCOCKPIT Enterprise Edition you can use this file
 * //    under the terms of the openITCOCKPIT Enterprise Edition license agreement.
 * //    License agreement and license key will be shipped with the order
 * //    confirmation.
 */
?>

<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-code fa-fw "></i>
            <?php echo __('Mattermost Module'); ?>
            <span>>
                <?php echo __('Configuration'); ?>
            </span>
            <div class="third_level"> <?php echo __('Overview'); ?></div>
        </h1>
    </div>
</div>



<div class="jarviswidget">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2 class="hidden-mobile hidden-tablet"><?php echo __('Edit configuration'); ?></h2>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal">
                <div class="row">
                    <div class="form-group required" ng-class="{'has-error': errors.webhook_url}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Webhook URL'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                class="form-control"
                                type="text"
                                placeholder="https://mattermost.example.org/hooks/1nmqus1wsfr988e81sr8whqrte"
                                ng-model="post.webhook_url">
                            <div ng-repeat="error in errors.webhook_url">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block">
                                <?= __('Mattermost Webhook URL.'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col col-md-2 control-label" for="enableTwoWay">
                            <?php echo __('Enable Two-way integration'); ?>
                        </label>


                        <div class="col-xs-10 smart-form">
                            <label class="checkbox small-checkbox-label no-required">
                                <input type="checkbox" name="checkbox"
                                       id="enableTwoWay"
                                       ng-model="post.two_way">
                                <i class="checkbox-primary"></i>
                            </label>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.apikey}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('openITCOCKPIT API Key'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                ng-disabled="!post.two_way"
                                class="form-control"
                                type="text"
                                placeholder="6d132de3bfb3fa8ea5793fa3b2400a690ed72c5b0b9cfad8a0fd4f20caf4444c56057d4554eea978e5da72a7668d37ad3a50812905d958f5f47a58f6e2c5a2cff1c1ac11cdeb483504f4b80de2814c4d"
                                ng-model="post.apikey">
                            <div ng-repeat="error in errors.apikey">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block">
                                <a href="javascript:void(0);" data-toggle="modal" data-target="#ApiKeyOverviewModal">
                                    <?= __('API Key') ?>
                                </a>
                                <?= __('used by Mattermost for authentication against openITCOCKPIT.'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col col-md-2 control-label" for="use_proxy">
                            <?php echo __('Use Proxy'); ?>
                        </label>


                        <div class="col-xs-10 smart-form">
                            <label class="checkbox small-checkbox-label no-required">
                                <input type="checkbox" name="checkbox"
                                       id="use_proxy"
                                       ng-model="post.use_proxy">
                                <i class="checkbox-primary"></i>
                            </label>
                            <div class="help-block">
                                <?php
                                if ($this->Acl->hasPermission('index', 'proxy', '')):
                                    echo __('Determine if the <a ui-sref="ProxyIndex">configured proxy</a> shoud be used.');
                                else:
                                    echo __('Determine if the configured proxy shoud be used.');
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 margin-top-10">
                        <div class="well formactions ">
                            <div class="pull-right">
                                <input class="btn btn-primary" type="submit" value="<?= __('Save') ?>">&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php echo $this->element('apikey_help'); ?>

