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
?>
<?php $timezones = CakeTime::listTimezones(); ?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-home fa-fw "></i>
            <?php echo __('Profile'); ?>
            <span>>
                <?php echo __('Edit'); ?>
            </span>
        </h1>
    </div>
</div>
<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon"> <i class="fa fa-user"></i> </span>
        <h2><?php echo __('Change profile'); ?></h2>
    </header>
    <div>
        <div class="widget-body">
            <form ng-submit="submit();" class="form-horizontal">

                <div class="row">

                    <div class="form-group required" ng-class="{'has-error': errors.firstname}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('First name'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.User.firstname">
                            <div ng-repeat="error in errors.firstname">
                                <div class="help-block text-danger">{{ error }}</div>
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
                                    ng-model="post.User.lastname">
                            <div ng-repeat="error in errors.lastname">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div ng-if="isLdapAuth" class="form-group required" ng-class="{'has-error': errors.samaccountname}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Username'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.User.samaccountname">
                            <div ng-repeat="error in errors.email">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.email}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Email Address'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input
                                    class="form-control"
                                    type="text"
                                    ng-model="post.User.email"
                                    ng-readonly="isLdapAuth">
                            <div ng-repeat="error in errors.email">
                                <div class="help-block text-danger">{{ error }}</div>
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
                            <?php echo __('Show status stats in menu'); ?>
                        </label>
                        <div class="col-xs-10 smart-form">
                            <label class="checkbox small-checkbox-label no-required">
                                <input type="checkbox" name="checkbox"
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
                                <input type="checkbox" name="checkbox"
                                       id="userRecursiveBrowser"
                                       ng-model="post.User.recursive_browser">
                                <i class="checkbox-primary"></i>
                            </label>
                        </div>
                    </div>

                    <div class="form-group required" ng-class="{'has-error': errors.paginatorlength}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Listelement Length'); ?>
                        </label>
                        <div class="col col-xs-10">
                            <input class="form-control"
                                   type="number"
                                   ng-model="post.User.paginatorlength">
                            <div>
                                <div class="help-block text-muted"><?php echo __('This field defines the length of every list in the openITCOCKPIT System for your Profile. You can choose between 1 and 1000'); ?></div>
                            </div>
                            <div ng-repeat="error in errors.paginatorlength">
                                <div class="help-block text-danger">{{ error }}</div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group required" ng-class="{'has-error': errors.dateformat}">
                        <label class="col col-md-2 control-label">
                            <?php echo __('Date Format'); ?>
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
                                    ng-init="post.User.timezone = post.User.timezone || 'Europe/Berlin'"
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
                                <a ui-sref="UsersIndex" class="btn btn-default"><?php echo __('Cancel'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="jarviswidget" id="wid-id-0">
        <header>
            <span class="widget-icon"> <i class="fa fa-picture-o"></i> </span>
            <h2><?php echo __('Your picture'); ?></h2>
        </header>
        <div>
            <div class="widget-body">
                <?php
                if ($user['User']['image'] != null && $user['User']['image'] != ''):
                    if (file_exists(WWW_ROOT . 'userimages' . DS . $user['User']['image'])):
                        echo $this->html->image('/userimages' . DS . $user['User']['image'], ['height' => 70]);
                        echo ' <a class="txt-color-red" href="/profile/deleteImage"><i class="fa fa-trash-o"></i> ' . __('Delete my image') . '</a>';
                    else:
                        echo $this->html->image('/img/fallback_user.png', ['width' => 70, 'height' => 70]);
                        echo ' <span class="text-muted">' . __('You have no own image uploaded yet') . '</span>';
                    endif;
                else:
                    echo $this->html->image('/img/fallback_user.png', ['width' => 70, 'height' => 70]);
                    echo ' <span class="text-muted">' . __('You have no own image uploaded yet') . '</span>';
                endif;

                echo $this->Form->create('Picture', [
                    'enctype' => 'multipart/form-data',
                ]);

                echo $this->Form->input('Image', [
                    'type'   => 'file',
                    'accept' => 'image/png,image/jpeg,image/gif',
                    'style'  => 'padding: 0px;',
                    'help'   => __('Allowd image types are: .jpg, .png and .gif. Best image size is 120x120px'),
                    'label'  => __('Select image'),
                ]);
                ?>

                <br/><br/>
                <div class="padding-top-20"></div>
                <?php
                echo $this->Form->formActions(__('Upload image'), [
                    'cancelButton' => [
                        'title' => __('Cancel'),
                        'url'   => '/dashboards/',
                    ]
                ]); ?>
            </div>
        </div>
    </div>

    <div class="jarviswidget" id="wid-id-0">
        <header>
            <span class="widget-icon"> <i class="fa fa-unlock-alt"></i> </span>
            <h2><?php echo __('Change password'); ?></h2>
        </header>
        <div>
            <div class="widget-body">
                <?php
                if ($systemsettings['FRONTEND']['FRONTEND.AUTH_METHOD'] == 'ldap' && $user['User']['samaccountname'] !== null):
                    ?>
                    <div class="padding-top-20">
                        <br/>
                        <center class="text-info">
                            <i class="fa fa-info-circle"></i>
                            &nbsp;
                            <?php echo __('Due to LDAP authentication you need to change your password over the operating system or your LDAP account manager tool.'); ?>
                        </center>
                    </div>
                <?php
                else:
                    echo $this->Form->create('Password', [
                        'class' => 'form-horizontal',
                    ]);
                    echo $this->Form->input('current_password', [
                        'type'     => 'password',
                        'label'    => __('Current password'),
                        'required' => true,
                    ]);
                    ?>
                    <hr>
                    <?php
                    echo $this->Form->input('new_password', [
                        'type'     => 'password',
                        'label'    => __('New password'),
                        'required' => true,
                        'help'     => __('user_model.password_requirement_notice'),
                    ]);
                    echo $this->Form->input('new_password_repeat', [
                        'type'     => 'password',
                        'label'    => __('Retype password'),
                        'required' => true,
                    ]);

                    echo $this->Form->formActions('Change password', [
                        'cancelButton' => [
                            'title' => __('Cancel'),
                            'url'   => '/dashboards/',
                        ]
                    ]);
                endif;
                ?>
            </div>
        </div>
    </div>

    <div class="jarviswidget" id="wid-id-0">
        <header>
            <div class="widget-toolbar" role="menu">
                <button type="button" class="btn btn-xs btn-default" ng-click="load()">
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
                        <?php echo __('In some cases it is easier to send an API key via a HTTP Header.'); ?>
                    </div>
                </div>

                <div class="row">
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

    <create-apikey-directive></create-apikey-directive>
    <edit-apikey-directive></edit-apikey-directive>

