<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.
$timezones = \itnovum\openITCOCKPIT\Core\Timezone::listTimezones();
?>

<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-home fa-fw "></i>
            <?php echo __('Profile'); ?>
            <span>>
                <?php echo __('Overview'); ?>
            </span>
        </h1>
    </div>
</div>

<reload-required></reload-required>

<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-user"></i> </span>
        <h2><?php echo __('Profile information'); ?></h2>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submitUser();" class="form-horizontal">

                <div class="row">

                    <div class="form-group required" ng-class="{'has-error': errors.firstname}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('First name'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    ng-disabled="isLdapUser"
                                    type="text"
                                    ng-model="post.User.firstname">
                            <div ng-repeat="error in errors.firstname">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block text-info" ng-show="isLdapUser">
                                <?php echo __('Value imported from LDAP Server'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.lastname}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Last name'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-disabled="isLdapUser"
                                    ng-model="post.User.lastname">
                            <div ng-repeat="error in errors.lastname">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block text-info" ng-show="isLdapUser">
                                <?php echo __('Value imported from LDAP Server'); ?>
                            </div>
                        </div>
                    </div>

                    <div ng-show="isLdapUser" class="form-group required" ng-class="{'has-error': errors.samaccountname}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('SAM-Account-Name'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-disabled="isLdapUser"
                                    ng-model="post.User.samaccountname">
                            <div ng-repeat="error in errors.samaccountname">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block text-info">
                                <?php echo __('Username for the login'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.email}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Email address'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-disabled="isLdapUser"
                                    ng-model="post.User.email">
                            <div ng-repeat="error in errors.email">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                            <div class="help-block text-info" ng-show="isLdapUser">
                                <?php echo __('Value imported from LDAP Server'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.phone}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Phone Number'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.User.phone">
                            <div ng-repeat="error in errors.phone">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group" ng-class="{'has-error': errors.showstatsinmenu}">
                        <label class="col col-md-2 control-label" for="userShowstatsinmenu">
                            <?php echo __('Show status badges in menu'); ?>
                        </label>
                        <div class="col-xs-10 smart-form">
                            <label class="checkbox small-checkbox-label no-required">
                                <input type="checkbox"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       id="userShowstatsinmenu"
                                       ng-model="post.User.showstatsinmenu">
                                <i class="checkbox-primary"></i>
                            </label>
                        </div>
                    </div>

                    <div class="form-group" ng-class="{'has-error': errors.recursive_browser}">
                        <label class="col col-md-2 control-label" for="userRecursiveBrowser">
                            <?php echo __('Recursive Browser'); ?>
                        </label>
                        <div class="col-xs-10 smart-form">
                            <label class="checkbox small-checkbox-label no-required">
                                <input type="checkbox"
                                       ng-true-value="1"
                                       ng-false-value="0"
                                       id="userRecursiveBrowser"
                                       ng-model="post.User.recursive_browser">
                                <i class="checkbox-primary"></i>
                            </label>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.paginatorlength}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Length of lists'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input class="form-control"
                                   type="number"
                                   ng-model="post.User.paginatorlength">
                            <div>
                                <div class="help-block text-muted">
                                    <?php echo __('This value defines how many records will load per list. You can choose between 1 and 1000'); ?>
                                </div>
                            </div>
                            <div ng-repeat="error in errors.paginatorlength">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group required" ng-class="{'has-error': errors.dateformat}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Date format'); ?>
                        </label>
                        <div class="col col-xs-10">

                            <select
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="dateformats"
                                    ng-options="dateformat.key as dateformat.value for dateformat in dateformats"
                                    ng-model="post.User.dateformat">
                            </select>
                            <div ng-repeat="error in errors.User.dateformat">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group required" ng-class="{'has-error': errors.timezone}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Timezone'); ?>
                        </label>
                        <div class="col col-xs-10">

                            <select
                                    data-placeholder="<?php echo __('Please choose'); ?>"
                                    class="form-control"
                                    chosen="{}"
                                    ng-model="post.User.timezone">
                                <?php foreach ($timezones as $continent => $continentTimezones): ?>
                                    <optgroup label="<?php echo h($continent); ?>">
                                        <?php foreach ($continentTimezones as $timezoneKey => $timezoneName): ?>
                                            <option value="<?php echo h($timezoneKey); ?>"><?php echo h($timezoneName); ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                            <div ng-repeat="error in errors.User.timezone">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                        <div class="helpText text-muted col-md-offset-2 col-md-6">
                            <br/>
                            <?php echo __('Server timezone is:'); ?>
                            <strong>
                                <?php echo h(date_default_timezone_get()); ?>
                            </strong>
                            <?php echo __('Current server time:'); ?>
                            <strong>
                                <?php echo date('d.m.Y H:i:s'); ?>
                            </strong>
                        </div>
                    </div>

                    <div class="col-xs-12 margin-top-10 margin-bottom-10">
                        <div class="well formactions ">
                            <div class="pull-right">
                                <input class="btn btn-primary" type="submit"
                                       value="<?php echo __('Update Profile'); ?>">
                                <a ui-sref="ProfileEdit" class="btn btn-default"><?php echo __('Cancel'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-picture-o"></i> </span>
        <h2><?php echo __('Profile picture'); ?></h2>
    </header>
    <div>
        <div class="widget-body">
            <!-- Iconset Upload dropzone -->
            <div class="row">

                <div class="form-group">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Current image'); ?>
                    </label>
                    <div class="col col-xs-10">
                        <img ng-if="post.User.image == null" ng-src="/img/fallback_user.png" alt="fallback_profile_img"
                             width="70" height="70">
                        <span ng-if="post.User.image == null"
                              class="text-muted"> <?php echo __('You have no own image uploaded yet'); ?></span>
                        <img ng-if="post.User.image != null" ng-src="/userimages/{{post.User.image}}" alt="profile_img"
                             width="70" height="70">
                        <a ng-if="post.User.image != null" class="txt-color-red"
                           href="javascript:void(0);"
                           class="txt-color-red"
                           ng-click="deleteUserImage()">
                            <i class="fa fa-trash-o"></i> <?php echo __('Delete my image'); ?></a>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col col-md-2 control-label">
                        <?php echo __('Select image'); ?>
                    </label>
                    <div class="col col-xs-10 no-padding padding-top-10">
                        <div class="col-xs-12 text-info">
                            <i class="fa fa-info-circle"></i>
                            <?php echo __('Max allowed file size: '); ?>
                            {{ maxUploadLimit.string }}
                        </div>
                        <div class="col-xs-12">
                            <div class="profileImg-dropzone dropzone dropzoneStyle"
                                 action="/profile/upload_profile_icon.json?angular=true">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="jarviswidget">
    <header>
        <span class="widget-icon"> <i class="fa fa-unlock-alt"></i> </span>
        <h2><?php echo __('Change password'); ?></h2>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submitPassword();" class="form-horizontal">

                <div class="row" ng-show="isLdapUser">
                    <div class="col-xs-12 text-center text-info" ng-show="apikeys.length === 0">
                        <i class="fa fa-info-circle"></i>
                        <?php echo __('LDAP users need to change their password through the operating system or an LDAP account manager tool.'); ?>
                    </div>
                </div>

                <div class="row" ng-hide="isLdapUser">
                    <div class="form-group required"
                         ng-class="{'has-error': errors.current_password}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Current Password'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    autocomplete="new-password"
                                    type="password"
                                    ng-model="post.Password.current_password">
                            <div ng-repeat="error in errors.current_password">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group required" ng-class="{'has-error': errors.password}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('New Password'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="password"
                                    autocomplete="new-password"
                                    ng-model="post.Password.password">
                            <div ng-repeat="error in errors.password">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required"
                         ng-class="{'has-error': errors.confirm_password}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Confirm new password'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="password"
                                    autocomplete="new-password"
                                    ng-model="post.Password.confirm_password">
                            <div ng-repeat="error in errors.confirm_password">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 margin-top-10 margin-bottom-10">
                        <div class="well formactions ">
                            <div class="pull-right">
                                <input class="btn btn-primary" type="submit"
                                       value="<?php echo __('Change Password'); ?>">
                                <a ui-sref="ProfileEdit" class="btn btn-default"><?php echo __('Cancel'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="jarviswidget">
    <header>
        <div class="widget-toolbar" role="menu">
            <button type="button" class="btn btn-xs btn-default" ng-click="loadApiKey()">
                <i class="fa fa-refresh"></i>
                <?php echo __('Refresh'); ?>
            </button>

            <button type="button" class="btn btn-xs btn-success" ng-click="createApiKey()">
                <i class="fa fa-key"></i>
                <?php echo __('Create new API key'); ?>
            </button>
        </div>

        <span class="widget-icon"> <i class="fa fa-key"></i> </span>
        <h2><?php echo __('API keys'); ?></h2>

    </header>
    <div>
        <div class="widget-body">

            <div class="row">
                <div class="col-xs-12 text-center text-info" ng-show="apikeys.length === 0">
                    <i class="fa fa-info-circle"></i>
                    <?php echo __('No API keys created yet. You can still use the api using your username and password.'); ?>
                    <br />
                    <b><?php echo __('It\'s recommended to create a own API key for every external application.'); ?></b>
                </div>
            </div>

            <div class="row" ng-show="apikeys.length > 0">
                <div class="col-xs-12 col-md-1 bold">
                    <?php echo __('ID'); ?>
                </div>
                <div class="col-xs-12 col-md-9 bold">
                    <?php echo __('Description'); ?>
                </div>
                <div class="col-xs-12 col-md-2 bold">
                    <?php echo __('Show'); ?>
                </div>
            </div>
            <div class="row" ng-repeat="apikey in apikeys">
                <div class="col-xs-12 col-md-1">
                    {{apikey.id}}
                </div>
                <div class="col-xs-12 col-md-9">
                    {{apikey.description}}
                </div>
                <div class="col-xs-12 col-md-2">
                    <button class="btn btn-primary btn-xs btn-block" ng-click="editApiKey(apikey.id)">
                        <i class="fa fa-eye"></i>
                        <?php echo __('Show'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<edit-apikey-directive></edit-apikey-directive>


<div id="angularCreateApiKeyModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
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
