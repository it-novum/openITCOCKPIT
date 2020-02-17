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

/**
 * @var \App\View\AppView $this
 * @var string $systemname
 * @var \itnovum\openITCOCKPIT\Core\RepositoryChecker $RepositoryChecker
 * @var \itnovum\openITCOCKPIT\Core\System\Health\LsbRelease $LsbRelease
 */

?>
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item">
        <a ui-sref="DashboardsIndex">
            <i class="fa fa-home"></i> <?php echo __('Home'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <a ui-sref="PackageManagerIndex">
            <i class="fas fa-cubes"></i> <?php echo __('Package manager'); ?>
        </a>
    </li>
    <li class="breadcrumb-item">
        <i class="fa fa-list"></i> <?php echo __('Overview'); ?>
    </li>
</ol>


<?php echo $this->element('repository_checker'); ?>

<?php if ($LsbRelease->getCodename() === 'trusty'): ?>
    <div class="alert alert-danger alert-block">
        <a class="close" data-dismiss="alert" href="#">×</a>
        <h4 class="alert-heading">
            <i class="fa fa-warning"></i>
            <?php echo __('Ubuntu Trusty 14.04 end of life!'); ?>
        </h4>
        <?php echo __('Official end of life of Ubuntu Trusty scheduled for April 2019.'); ?>
        <?php echo __('Therefore openITCOCKPIT 3.5 will be the last release for Ubuntu Trusty. Please update to Ubuntu Xenial to receive further updates.'); ?>
        <br/>
        <?php echo __('Need help updating your system? Please don\'t hesitate to contact our enterprise support {0}.', '<a class="txt-color-darken" href="mailto:support@itsm.it-novum.com">support@itsm.it-novum.com</a>'); ?>
    </div>
<?php endif; ?>

<?php if ($LsbRelease->getCodename() === 'jessie'): ?>
    <div class="alert alert-danger alert-block">
        <a class="close" data-dismiss="alert" href="#">×</a>
        <h4 class="alert-heading">
            <i class="fa fa-warning"></i>
            <?php echo __('Debian Jessie 8 end of life!'); ?>
        </h4>
        <?php echo __('Debian Jessie is not supported by the Debian security team anymore!'); ?>
        <?php echo __('Therefore openITCOCKPIT 3.5 will be the last release for Debian Jessie. Please update to Debian Stretch to receive further updates.'); ?>
        <br/>
        <?php echo __('Need help updating your system? Please don\'t hesitate to contact our enterprise support {0}.', '<a class="txt-color-darken" href="mailto:support@itsm.it-novum.com">support@itsm.it-novum.com</a>'); ?>
    </div>
<?php endif; ?>


<div class="row">
    <div class="col-xl-12">
        <div id="panel-1" class="panel">
            <div class="panel-container show">
                <div class="panel-content">
                    <div class="col-lg-12" ng-show="error">
                        <div class="alert alert-danger alert-block">
                            <a href="javascript:void(0);" data-dismiss="alert" class="close">×</a>
                            <h5 class="alert-heading"><i
                                    class="fa fa-exclamation-triangle"></i> <?php echo __('Connection error.'); ?>
                            </h5>
                            <p>
                                {{error_msg}}
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-md-6 col-lg-4" ng-show="!newVersion">
                            <div class="card">
                                <div class="card-header">
                                    <div>
                                        <h4>
                                            <?= \Spatie\Emoji\Emoji::partyingFace(); ?>
                                            <?= __('Your system is on the latest version!'); ?>
                                        </h4>
                                        <div class="float-right italic unknown">
                                            <?= h(OPENITCOCKPIT_VERSION); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body" style="min-height: 200px;">
                                    <div class="text">

                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-lg-12 padding-right-0">
                                            <div class="float-right">
                                                <a href="javascript:void(0);"
                                                   class="btn btn-labeled btn-default"
                                                   data-toggle="modal"
                                                   data-target="#changelogModal">
                                                    <span class="btn-label">
                                                        <i class="fa fa-code-fork"></i>
                                                    </span>
                                                    <?= __('Changelog'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6 col-lg-4" ng-show="newVersion">
                            <div class="card">
                                <div class="card-header">
                                    <div>
                                        <h4>
                                            <?= \Spatie\Emoji\Emoji::partyPopper() ?>
                                            <?= __('New version available!'); ?>
                                        </h4>
                                        <div class="pull-right italic unknown">
                                            {{changelog[0].Changelog.version}}
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body" style="min-height: 150px;">
                                    <div class="text">
                                        <?= __('Please update your {0} installation to the latest version to get new features and latest security fixes.', h($systemname)) ?>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-lg-12 padding-right-0">

                                            <div class="float-right">

                                                <a href="https://openitcockpit.io/2018/01/02/how-to-update-openitcockpit/"
                                                   target="_blank"
                                                   class="btn btn-labeled btn-success">
                                                    <span class="btn-label">
                                                        <i class="fa fa-rocket"></i>
                                                    </span>
                                                    <?= __('How to Update'); ?>
                                                </a>

                                                <a href="javascript:void(0);"
                                                   class="btn btn-labeled btn-default"
                                                   data-toggle="modal"
                                                   data-target="#changelogModal">
                                                    <span class="btn-label">
                                                        <i class="fa fa-code-fork"></i>
                                                    </span>
                                                    <?= __('Changelog'); ?>
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-xs-12 col-md-6 col-lg-4" ng-repeat="module in modules">
                            <div class="card">
                                <div class="card-header">
                                    <h4>
                                        {{module.Module.name}}
                                    </h4>
                                    <div class="float-right italic unknown">
                                        {{module.Module.author}}
                                    </div>
                                    <div class="italic unknown" ng-show="module.Module.enterprise">
                                        <?= __('Enterprise') ?>
                                    </div>
                                </div>
                                <div class="card-body packagemanagerCardBody">
                                    <div class="text">
                                        {{module.Module.description}}
                                    </div>

                                    <div class="padding-top-10">
                                        <i class="fa fa-key"></i>
                                        {{module.Module.license}}
                                    </div>

                                    <div>
                                        <i class="fa fa-tags"></i>
                                        <span class="badge badge-secondary margin-right-5"
                                              ng-repeat="tag in module.Module.tags">
                                            {{tag}}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-lg-12 padding-right-0">
                                            <button
                                                type="button"
                                                title="<?= __('This module is already installed') ?>"
                                                ng-show="installedModules[module.Module.apt_name]"
                                                class="btn btn-labeled btn-success float-right">
                                            <span class="btn-label">
                                                <i class="fa fa-check"></i>
                                            </span>
                                                <?= __('Installed'); ?>
                                            </button>
                                        </div>

                                        <div class="col-lg-12 padding-right-0">
                                            <button
                                                type="button"
                                                ng-hide="installedModules[module.Module.apt_name]"
                                                class="btn btn-labeled btn-primary pull-right"
                                                ng-click="installPackage(module.Module.apt_name)">
                                            <span class="btn-label">
                                                <input type="checkbox"
                                                       ng-model="modulesToCheckboxesInstall[module.Module.apt_name]">
                                            </span>
                                                <?= __('Install'); ?>
                                            </button>
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
</div>

<!-- Install packages modal -->
<div id="changelogModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-code-fork"></i>
                    <?php echo __('Changelog'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container mt-5 mb-5">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>
                                <?= __('Latest version') ?>
                                {{changelog[0].Changelog.version}}
                            </h4>
                            <ul class="timeline">
                                <li ng-repeat="record in changelog">
                                    <span class="text-primary">{{record.Changelog.version}}</span>
                                    <p>
                                        {{record.Changelog.changes | trustAsHtml}}
                                    </p>
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



<!-- Changelog modal -->
<div class="modal fade" id="changelogModal" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">
                    <i class="fa fa-code-fork"></i>
                    <?php echo __('Changelog'); ?>
                </h4>
            </div>
            <div class="modal-body">
                <div class="container mt-5 mb-5">
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <h4>
                                <?= __('Latest version') ?>
                                {{changelog[0].Changelog.version}}
                            </h4>
                            <ul class="timeline">
                                <li ng-repeat="record in changelog">
                                    <span class="text-primary">{{record.Changelog.version}}</span>
                                    <p>
                                        {{record.Changelog.changes | trustAsHtml}}
                                    </p>
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

<!-- Install packages modal -->
<div id="installPackageModal" class="modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-code-fork"></i>
                    <?php echo __('Install packages'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <?= __('To install the selected packages, please execute the following command on your {0} system.', $systemname) ?>
                    </div>
                </div>

                <div class="row padding-top-5">
                    <div class="col-lg-12">
                        <div class="bg-color-black txt-color-white code-font padding-7">
                            sudo apt-get install <span ng-bind-html="getCliCommand() | trustAsHtml"></span>
                            \ <br>
                            && echo "#########################################" \ <br>
                            && echo "<?= __('Installation done. Please reload your {0} web interface.', $systemname) ?>"
                        </div>
                    </div>
                </div>

                <div class="row padding-top-5">
                    <div class="col-lg-12">
                        <?= __('To install two or more packages at once, close this window and select the next module you like to install. All selected modules will be added to the installation command.') ?>
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
