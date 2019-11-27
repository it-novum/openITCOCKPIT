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
            <i class="fa fa-user fa-fw "></i>
            <?php echo __('Contacts'); ?>
            <span>>
                <?php echo __('Copy'); ?>
            </span>
        </h1>
    </div>
</div>


<div class="jarviswidget" id="wid-id-0">
    <header>
        <span class="widget-icon hidden-mobile hidden-tablet"> <i class="fa fa-copy"></i> </span>
        <h2 class="hidden-mobile hidden-tablet">
            <?php echo __('Copy contact/s'); ?>
        </h2>
        <div class="widget-toolbar hidden-mobile hidden-tablet" role="menu">
            <?php if ($this->Acl->hasPermission('index', 'contacts')): ?>
                <a back-button fallback-state='ContactsIndex' class="btn btn-default btn-xs">
                    <i class="glyphicon glyphicon-white glyphicon-arrow-left"></i> <?php echo __('Back to list'); ?>
                </a>
            <?php endif; ?>
        </div>
    </header>
    <div>
        <div class="widget-body">
            <div class="row form-horizontal" ng-repeat="sourceContact in sourceContacts">
                <div class="col-xs-12 col-md-9 col-lg-7">
                    <fieldset>
                        <legend>
                            <span class="text-info"><?php echo __('Source contact:'); ?></span>
                            {{sourceContact.Source.name}}
                        </legend>

                        <div class="form-group required" ng-class="{'has-error': sourceContact.Error.name}">
                            <label for="Contact{{$index}}Name" class="col col-md-2 control-label">
                                <?php echo('Contact name'); ?>
                            </label>
                            <div class="col col-xs-10 required">
                                <input
                                        class="form-control"
                                        type="text"
                                        ng-model="sourceContact.Contact.name"
                                        id="Contact{{$index}}Name">
                                <span class="help-block">
                                    <?php echo __('Name of the new contact'); ?>
                                </span>
                                <div ng-repeat="error in sourceContact.Error.name">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" ng-class="{'has-error': sourceContact.Error.description}">
                            <label for="Contact{{$index}}Description" class="col col-md-2 control-label">
                                <?php echo('Description'); ?>
                            </label>
                            <div class="col col-xs-10">
                                <input
                                        class="form-control"
                                        type="text"
                                        ng-model="sourceContact.Contact.description"
                                        id="Contact{{$index}}Description">
                                <div ng-repeat="error in sourceContact.Error.description">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': sourceContact.Error.email}">
                            <label for="Contact{{$index}}Email" class="col col-md-2 control-label">
                                <?php echo('Email'); ?>
                            </label>
                            <div class="col col-xs-10">
                                <input
                                        class="form-control"
                                        type="text"
                                        ng-model="sourceContact.Contact.email"
                                        id="Contact{{$index}}Email">
                                <div ng-repeat="error in sourceContact.Error.email">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group required" ng-class="{'has-error': sourceContact.Error.phone}">
                            <label for="Contact{{$index}}Phone" class="col col-md-2 control-label">
                                <?php echo('Phone'); ?>
                            </label>
                            <div class="col col-xs-10">
                                <input
                                        class="form-control"
                                        type="text"
                                        ng-model="sourceContact.Contact.phone"
                                        id="Contact{{$index}}Phone">
                                <div ng-repeat="error in sourceContact.Error.phone">
                                    <div class="help-block text-danger">{{ error }}</div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>

            <div class="well formactions ">
                <div class="pull-right">
                    <button class="btn btn-primary" ng-click="copy()">
                        <?php echo __('Copy'); ?>
                    </button>
                    <?php if ($this->Acl->hasPermission('index', 'Contacts')): ?>
                        <a back-button fallback-state='ContactsIndex' class="btn btn-default"><?php echo __('Cancel'); ?></a>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>
