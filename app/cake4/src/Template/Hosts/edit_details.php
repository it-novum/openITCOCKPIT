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
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-pencil-square-o fa-fw "></i>
            <?php echo __('Monitoring'); ?>
            <span>>
                <?php echo __('Edit host details'); ?>
            </span>
        </h1>
    </div>
</div>
<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-pencil-square-o"></i> </span>
        <h2 class="hidden-mobile hidden-tablet">
            <?php echo __('Edit host detail'); ?>
        </h2>
        <div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'hosts')): ?>
                <a class="btn btn-default" ui-sref="HostsIndex">
                    <i class="fa fa-arrow-left"></i>
                    <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <form class="form-horizontal">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="jarviswidget">
                            <header>
                                <span class="widget-icon">
                                    <i class="fa fa-magic"></i>
                                </span>
                                <h2><?php echo __('Basic configuration'); ?></h2>
                            </header>
                            <div>
                                <div class="widget-body">
                                    <div class="row">
                                        <div>
                                            <div class="form-group">
                                                <label class="col-xs-12 col-lg-2 control-label">
                                                    <button class="btn btn-xs width-25"
                                                            ng-class="{ 'btn-primary': !post.editSharedContainers, 'btn-success': post.editSharedContainers }"
                                                            ng-click="post.editSharedContainers = !post.editSharedContainers"
                                                            name="post.editSharedContainers"
                                                            title="<?php echo __('Unlock for edit'); ?>">
                                                        <i class="fa fa-lock fa-lock"
                                                           ng-class="{ 'fa-lock': !post.editSharedContainers, 'fa-unlock': post.editSharedContainers }"></i>
                                                    </button>
                                                    <?php echo __('Shared containers'); ?>
                                                </label>
                                                <div class="col-xs-12 col-lg-10"
                                                     ng-class="{ 'not-edit-area': !post.editSharedContainers}">
                                                    <select
                                                        id="SharedContainers"
                                                        data-placeholder="<?php echo __('Please choose'); ?>"
                                                        class="form-control"
                                                        multiple
                                                        chosen="sharingContainers"
                                                        ng-disabled="!post.editSharedContainers"
                                                        ng-options="container.key as container.value for container in sharingContainers"
                                                        ng-model="post.Host.hosts_to_containers_sharing._ids">
                                                    </select>
                                                    <div class="help-block">
                                                        <div class="form-group">
                                                            <div class="col-xs-12 col-lg-10 smart-form">
                                                                <label
                                                                    class="checkbox small-checkbox-label display-inline margin-right-5 padding-top-0">
                                                                    <input type="checkbox" name="checkbox"
                                                                           id="keepExistingSharedContainers"
                                                                           ng-true-value="1"
                                                                           ng-false-value="0"
                                                                           ng-disabled="!editSharedContainers"
                                                                           ng-model="post.keepSharedContainers">
                                                                    <i class="checkbox-primary disabled"></i>
                                                                    <?php echo __('Keep existing'); ?>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <button class="btn btn-xs width-25"
                                                        ng-class="{ 'btn-primary': !editDescription, 'btn-success': editDescription }"
                                                        ng-click="editDescription = !editDescription"
                                                        title="<?php echo __('Unlock for edit'); ?>">
                                                    <i class="fa fa-lock fa-lock"
                                                       ng-class="{ 'fa-lock': !editDescription, 'fa-unlock': editDescription }"></i>
                                                </button>
                                                <?php echo __('Description'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10"
                                                 ng-class="{ 'not-edit-area': !editDescription}">
                                                <input
                                                    class="form-control"
                                                    type="text"
                                                    ng-disabled="!editDescription"
                                                    ng-model="post.Host.description">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <button class="btn btn-xs width-25"
                                                        name="editTags"
                                                        ng-class="{ 'btn-primary': !editTags, 'btn-success': editTags }"
                                                        ng-click="editTags = !editTags"
                                                        title="<?php echo __('Unlock for edit'); ?>">
                                                    <i class="fa fa-lock fa-lock"
                                                       ng-class="{ 'fa-lock': !editTags, 'fa-unlock': editTags }"></i>
                                                </button>
                                                <?php echo __('Tags'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10"
                                                 ng-class="{ 'not-edit-area': !editTags}">
                                                <input class="form-control tagsinput"
                                                       type="text"
                                                       ng-disabled="!editTags"
                                                       ng-model="post.Host.tags">
                                                <div class="help-block">
                                                    <?php echo __('Press return to separate tags'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-xs-12 col-lg-2 control-label">
                                                <button class="btn btn-xs width-25"
                                                        name="editPriority"
                                                        ng-class="{ 'btn-primary': !editPriority, 'btn-success': editPriority }"
                                                        ng-click="editPriority = !editPriority"
                                                        title="<?php echo __('Unlock for edit'); ?>">
                                                    <i class="fa fa-lock fa-lock"
                                                       ng-class="{ 'fa-lock': !editPriority, 'fa-unlock': editPriority }"></i>
                                                </button>
                                                <?php echo __('Priority'); ?>
                                            </label>
                                            <div class="col-xs-12 col-lg-10"
                                                 ng-class="{ 'not-edit-area': !editPriority}">
                                                <priority-directive priority="post.Host.priority"
                                                                    callback="setPriority"></priority-directive>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="jarviswidget">
                            <header>
                                <span class="widget-icon">
                                    <i class="fa fa-terminal"></i>
                                </span>
                                <h2><?php echo __('Check configuration'); ?></h2>
                            </header>
                            <div>
                                <div class="widget-body">
                                    <div class="form-group">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <button class="btn btn-xs width-25"
                                                    name="editCheckInterval"
                                                    ng-class="{ 'btn-primary': !editCheckInterval, 'btn-success': editCheckInterval }"
                                                    ng-click="editCheckInterval = !editCheckInterval"
                                                    title="<?php echo __('Unlock for edit'); ?>">
                                                <i class="fa fa-lock fa-lock"
                                                   ng-class="{ 'fa-lock': !editCheckInterval, 'fa-unlock': editCheckInterval }"></i>
                                            </button>
                                            <?php echo __('Check interval'); ?>
                                        </label>
                                        <div ng-class="{ 'not-edit-area': !editCheckInterval}">
                                            <interval-input-directive
                                                interval="post.Host.check_interval"></interval-input-directive>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <button class="btn btn-xs width-25"
                                                    name="editRetryInterval"
                                                    ng-class="{ 'btn-primary': !editRetryInterval, 'btn-success': editRetryInterval }"
                                                    ng-click="editRetryInterval = !editRetryInterval"
                                                    title="<?php echo __('Unlock for edit'); ?>">
                                                <i class="fa fa-lock fa-lock"
                                                   ng-class="{ 'fa-lock': !editRetryInterval, 'fa-unlock': editRetryInterval }"></i>
                                            </button>
                                            <?php echo __('Retry interval'); ?>
                                        </label>
                                        <div ng-class="{ 'not-edit-area': !editRetryInterval}">
                                            <interval-input-directive
                                                interval="post.Host.retry_interval"></interval-input-directive>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <button class="btn btn-xs width-25"
                                                    name="editMaxNumberOfCheckAttempts"
                                                    ng-class="{ 'btn-primary': !editMaxNumberOfCheckAttempts, 'btn-success': editMaxNumberOfCheckAttempts }"
                                                    ng-click="editMaxNumberOfCheckAttempts = !editMaxNumberOfCheckAttempts"
                                                    title="<?php echo __('Unlock for edit'); ?>">
                                                <i class="fa fa-lock fa-lock"
                                                   ng-class="{ 'fa-lock': !editMaxNumberOfCheckAttempts, 'fa-unlock': editMaxNumberOfCheckAttempts }"></i>
                                            </button>
                                            <?php echo __('Max. number of check attempts'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-7"
                                             ng-class="{ 'not-edit-area': !editMaxNumberOfCheckAttempts}">
                                            <div class="btn-group">
                                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                                    <button
                                                        type="button"
                                                        class="btn btn-default"
                                                        ng-click="post.Host.max_check_attempts = <?php echo h($i) ?>"
                                                        ng-class="{'active': post.Host.max_check_attempts == <?php echo h($i); ?>}">
                                                        <?php echo h($i); ?>
                                                    </button>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-lg-3">
                                            <input
                                                class="form-control"
                                                type="number"
                                                min="0"
                                                ng-disabled="!editMaxNumberOfCheckAttempts"
                                                ng-model="post.Host.max_check_attempts">
                                        </div>
                                        <div class="col-xs-12 col-lg-offset-2 col-lg-12"
                                             ng-show="post.Host.check_interval && post.Host.max_check_attempts && post.Host.retry_interval">
                                            <div class="help-block">
                                                <?php echo __('Number of failed attempts before the host will switch into hard state.'); ?>
                                            </div>
                                            <div class="help-block">
                                                <?php echo __('Worst case time delay until notification command gets executed after state hits a non ok state: '); ?>
                                                <human-time-directive
                                                    seconds="(post.Host.check_interval + (post.Host.max_check_attempts -1) * post.Host.retry_interval)"></human-time-directive>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="jarviswidget">
                            <header>
                                <span class="widget-icon">
                                    <i class="fa fa-envelope-open-o"></i>
                                </span>
                                <h2><?php echo __('Notification configuration'); ?></h2>
                            </header>
                            <div>
                                <div class="widget-body">
                                    <div class="form-group">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <button class="btn btn-xs width-25"
                                                    name="editNotificationInterval"
                                                    ng-class="{ 'btn-primary': !editNotificationInterval, 'btn-success': editNotificationInterval }"
                                                    ng-click="editNotificationInterval = !editNotificationInterval"
                                                    title="<?php echo __('Unlock for edit'); ?>">
                                                <i class="fa fa-lock fa-lock"
                                                   ng-class="{ 'fa-lock': !editNotificationInterval, 'fa-unlock': editNotificationInterval }"></i>
                                            </button>
                                            <?php echo __('Notification interval'); ?>
                                        </label>
                                        <div ng-class="{ 'not-edit-area': !editNotificationInterval}">
                                            <interval-input-directive
                                                interval="post.Host.notification_interval"></interval-input-directive>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <button class="btn btn-xs width-25"
                                                    name="editContacts"
                                                    ng-class="{ 'btn-primary': !editContacts, 'btn-success': editContacts }"
                                                    ng-click="editContacts = !editContacts"
                                                    title="<?php echo __('Unlock for edit'); ?>">
                                                <i class="fa fa-lock fa-lock"
                                                   ng-class="{ 'fa-lock': !editContacts, 'fa-unlock': editContacts }"></i>
                                            </button>
                                            <?php echo __('Contacts'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10"
                                             ng-class="{ 'not-edit-area': !editContacts}">
                                            <select
                                                id="ContactsPeriodSelect"
                                                data-placeholder="<?php echo __('Please choose'); ?>"
                                                class="form-control"
                                                chosen="contacts"
                                                ng-disabled="!editContacts"
                                                multiple
                                                ng-options="contact.key as contact.value for contact in contacts"
                                                ng-model="post.Host.contacts._ids">
                                            </select>
                                            <div class="help-block">
                                                <div class="form-group">
                                                    <div class="col-xs-12 col-lg-10 smart-form">
                                                        <label
                                                            class="checkbox small-checkbox-label display-inline margin-right-5 padding-top-0">
                                                            <input type="checkbox" name="checkbox"
                                                                   id="keepExistingContacts"
                                                                   ng-true-value="1"
                                                                   ng-false-value="0"
                                                                   ng-disabled="!editContacts"
                                                                   ng-model="post.keepContacts">
                                                            <i class="checkbox-primary disabled"></i>
                                                            <?php echo __('Keep existing'); ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <button class="btn btn-xs width-25"
                                                    name="editContactgroups"
                                                    ng-class="{ 'btn-primary': !editContactgroups, 'btn-success': editContactgroups }"
                                                    ng-click="editContactgroups = !editContactgroups"
                                                    title="<?php echo __('Unlock for edit'); ?>">
                                                <i class="fa fa-lock fa-lock"
                                                   ng-class="{ 'fa-lock': !editContactgroups, 'fa-unlock': editContactgroups }"></i>
                                            </button>
                                            <?php echo __('Contact groups'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10"
                                             ng-class="{ 'not-edit-area': !editContactgroups}">
                                            <select
                                                id="ContactgroupsSelect"
                                                data-placeholder="<?php echo __('Please choose'); ?>"
                                                class="form-control"
                                                chosen="contactgroups"
                                                ng-disabled="!editContactgroups"
                                                multiple
                                                ng-options="contactgroup.key as contactgroup.value for contactgroup in contactgroups"
                                                ng-model="post.Host.contactgroups._ids">
                                            </select>
                                            <div class="help-block">
                                                <div class="form-group">
                                                    <div class="col-xs-12 col-lg-10 smart-form">
                                                        <label
                                                            class="checkbox small-checkbox-label display-inline margin-right-5 padding-top-0">
                                                            <input type="checkbox" name="checkbox"
                                                                   id="keepExistingContactgroups"
                                                                   ng-true-value="1"
                                                                   ng-false-value="0"
                                                                   ng-disabled="!editContactgroups"
                                                                   ng-model="post.keepContactgroups">
                                                            <i class="checkbox-primary disabled"></i>
                                                            <?php echo __('Keep existing'); ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                        <div class="jarviswidget">
                            <header>
                                <span class="widget-icon">
                                    <i class="fa fa-wrench"></i>
                                </span>
                                <h2><?php echo __('Misc. configuration'); ?></h2>
                            </header>
                            <div>
                                <div class="widget-body">
                                    <div class="form-group">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <button class="btn btn-xs width-25"
                                                    name="editHostUrl"
                                                    ng-class="{ 'btn-primary': !editHostUrl, 'btn-success': editHostUrl }"
                                                    ng-click="editHostUrl = !editHostUrl"
                                                    title="<?php echo __('Unlock for edit'); ?>">
                                                <i class="fa fa-lock fa-lock"
                                                   ng-class="{ 'fa-lock': !editHostUrl, 'fa-unlock': editHostUrl }"></i>
                                            </button>
                                            <?php echo __('Host URL'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10"
                                             ng-class="{ 'not-edit-area': !editHostUrl}">
                                            <input
                                                class="form-control"
                                                placeholder="https://issues.example.org?host=$HOSTNAME$"
                                                ng-disabled="!editHostUrl"
                                                type="text"
                                                ng-model="post.Host.host_url">
                                            <div class="help-block">
                                                <?php echo __('The macros $HOSTID$, $HOSTNAME$, $HOSTDISPLAYNAME$ and $HOSTADDRESS$ will be replaced'); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" ng-class="{'has-error': errors.notes}">
                                        <label class="col-xs-12 col-lg-2 control-label">
                                            <button class="btn btn-xs width-25"
                                                    name="editNotes"
                                                    ng-class="{ 'btn-primary': !editNotes, 'btn-success': editNotes }"
                                                    ng-click="editNotes = !editNotes"
                                                    title="<?php echo __('Unlock for edit'); ?>">
                                                <i class="fa fa-lock fa-lock"
                                                   ng-class="{ 'fa-lock': !editNotes, 'fa-unlock': editNotes }"></i>
                                            </button>
                                            <?php echo __('Notes'); ?>
                                        </label>
                                        <div class="col-xs-12 col-lg-10"
                                             ng-class="{ 'not-edit-area': !editNotes}">
                                            <input
                                                class="form-control"
                                                ng-disabled="!editNotes"
                                                type="text"
                                                ng-model="post.Host.notes">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 margin-top-10 margin-bottom-10">
                        <div class="well formactions ">
                            <div class="pull-right">
                                <button class="btn btn-primary" ng-click="editDetails()">
                                    <?php echo __('Save details'); ?>
                                </button>
                                <?php if ($this->Acl->hasPermission('index', 'hosts')): ?>
                                    <a ui-sref="HostsIndex" class="btn btn-default"><?php echo __('Cancel'); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
