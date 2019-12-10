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
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-wrench fa-fw "></i>
            <?php echo __('System Settings'); ?>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
        <div class="alert alert-danger fade in">
            <button data-dismiss="alert" class="close">×</button>
            <i class="fa fa-exclamation "></i>
            <strong><?php echo __('Attention!'); ?></strong> <?php echo __("Do not change values, where you don't know what you are doing!"); ?>
        </div>
    </div>
</div>

<reload-required></reload-required>

<section id="widget-grid" class="">

    <div class="row">

        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon hidden-mobile"> <i class="fa fa-wrench"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('System settings'); ?> </h2>

                </header>
                <div>
                    <form ng-submit="submit();" class="form-horizontal">
                        <div class="widget-body no-padding">

                            <div class="mobile_table">
                                <table id="host_list" class="table table-striped table-hover table-bordered smart-form"
                                       style="">
                                    <thead>
                                    <tr>
                                        <th><?php echo __('Key'); ?></th>
                                        <th><?php echo __('Value'); ?></th>
                                        <th class="text-center"><?php echo __('Info'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody ng-repeat="(key, value) in systemsettings">
                                    <tr>
                                        <td class="service_table_host_header text-primary" colspan="3">
                                            <strong>{{ key }}</strong>
                                        </td>
                                    </tr>
                                    <tr ng-repeat="systemsetting in value">
                                        <td>
                                            {{systemsetting.exploded}}
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="col col-xs-12">
                                                    <div ng-switch="systemsetting.key">
                                                        <div ng-switch-when="MONITORING.HOST.INITSTATE">
                                                            <select class="form-control systemsetting-input"
                                                                    ng-model="systemsetting.value">
                                                                <option value="o"><?php echo __('Up'); ?></option>
                                                                <option value="d"><?php echo __('Down'); ?></option>
                                                                <option value="u"><?php echo __('Unreachable'); ?></option>
                                                            </select>
                                                        </div>
                                                        <div ng-switch-when="MONITORING.SERVICE.INITSTATE">
                                                            <select class="form-control systemsetting-input"
                                                                    ng-model="systemsetting.value">
                                                                <option value="o"><?php echo __('Ok'); ?></option>
                                                                <option value="w"><?php echo __('Warning'); ?></option>
                                                                <option value="c"><?php echo __('Critical'); ?></option>
                                                                <option value="u"><?php echo __('Unknown'); ?></option>
                                                            </select>
                                                        </div>
                                                        <div ng-switch-when="FRONTEND.SHOW_EXPORT_RUNNING">
                                                            <select class="form-control systemsetting-input"
                                                                    ng-model="systemsetting.value">
                                                                <option value="yes"><?php echo __('True'); ?></option>
                                                                <option value="no"><?php echo __('False'); ?></option>
                                                            </select>
                                                        </div>
                                                        <div ng-switch-when="FRONTEND.AUTH_METHOD">
                                                            <select class="form-control systemsetting-input"
                                                                    ng-model="systemsetting.value">
                                                                <option value="session"><?php echo __('PHP session'); ?></option>
                                                                <option value="ldap"><?php echo __('PHP LDAP'); ?></option>
                                                                <option value="sso"><?php echo __('SSO'); ?></option>
                                                            </select>
                                                        </div>
                                                        <div ng-switch-when="FRONTEND.LDAP.TYPE">
                                                            <select class="form-control systemsetting-input"
                                                                    ng-model="systemsetting.value">
                                                                <option value="adldap"><?php echo __('Active Directory LDAP'); ?></option>
                                                                <option value="openldap"><?php echo __('OpenLDAP'); ?></option>
                                                            </select>
                                                        </div>
                                                        <div ng-switch-when="FRONTEND.LDAP.PASSWORD|MONITORING.ACK_RECEIVER_PASSWORD|FRONTEND.SSO.CLIENT_SECRET"
                                                             ng-switch-when-separator="|">
                                                            <input type="password"
                                                                   ng-model="systemsetting.value"
                                                                   class="form-control systemsetting-input">
                                                        </div>
                                                        <div ng-switch-when="FRONTEND.LDAP.USE_TLS|MONITORING.SINGLE_INSTANCE_SYNC|MONITORING.HOST_CHECK_ACTIVE_DEFAULT|MONITORING.SERVICE_CHECK_ACTIVE_DEFAULT|FRONTEND.HIDDEN_USER_IN_CHANGELOG|FRONTEND.DISABLE_LOGIN_ANIMATION"
                                                             ng-switch-when-separator="|">
                                                            <select class="form-control systemsetting-input"
                                                                    ng-model="systemsetting.value">
                                                                <option value="0"><?php echo __('False'); ?></option>
                                                                <option value="1"><?php echo __('True'); ?></option>
                                                            </select>
                                                        </div>
                                                        <div ng-switch-when="FRONTEND.PRESELECTED_DOWNTIME_OPTION">
                                                            <select class="form-control systemsetting-input"
                                                                    ng-model="systemsetting.value">
                                                                <option value="0"><?php echo __('Individual host'); ?></option>
                                                                <option value="1"><?php echo __('Host including services'); ?></option>
                                                            </select>
                                                        </div>
                                                        <div ng-switch-when="ARCHIVE.AGE.SERVICECHECKS|ARCHIVE.AGE.HOSTCHECKS|ARCHIVE.AGE.STATEHISTORIES|ARCHIVE.AGE.NOTIFICATIONS|ARCHIVE.AGE.LOGENTRIES|ARCHIVE.AGE.CONTACTNOTIFICATIONS|ARCHIVE.AGE.CONTACTNOTIFICATIONMETHODS"
                                                             ng-switch-when-separator="|">
                                                            <select class="form-control systemsetting-input"
                                                                    ng-options="i as i for i in dropdownOptionSequence"
                                                                    ng-model="systemsetting.value" convert-to-number>
                                                            </select>
                                                        </div>
                                                        <div ng-switch-when="SYSTEM.ANONYMOUS_STATISTICS">
                                                            <input type="text"
                                                                   ng-value="getAnonymousStatisticsValue(systemsetting.value)"
                                                                   class="form-control systemsetting-input"
                                                                   disabled="disabled" readonly="readonly">
                                                            <?php if ($this->Acl->hasPermission('index', 'statistics')): ?>
                                                                <br/>
                                                                <a ui-sref="StatisticsIndex">
                                                                    <?php echo __('Click for more information.'); ?>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div ng-switch-default>
                                                            <input type="text"
                                                                   ng-model="systemsetting.value"
                                                                   class="form-control systemsetting-input">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0);"
                                               data-original-title="{{systemsetting.info}}"
                                               data-placement="left" rel="tooltip"
                                               data-container="body">
                                                <i class="padding-top-5 fa fa-info-circle fa-2x"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="well formactions ">
                                <div class="pull-right">
                                    <input class="btn btn-primary" type="submit" value="Save">&nbsp;
                                    <a ng-sref="SystemsettingsIndex" class="btn btn-default">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </article>
    </div>
</section>
