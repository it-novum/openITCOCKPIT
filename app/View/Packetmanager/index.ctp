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

use itnovum\openITCOCKPIT\Core\RepositoryChecker;
use itnovum\openITCOCKPIT\Core\System\Health\LsbRelease;

/** @var RepositoryChecker $RepositoryChecker */
/** @var LsbRelease $LsbRelease */

//Variablen die später dann aus der DB kommen müssen
$plugin_version = '1.5';
?>

<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark"><i
                    class="fa fa-cloud-download fa-fw "></i> <?php echo __('Package manager'); ?></h1>
    </div>
</div>

<?php echo $this->element('repository_checker'); ?>

<div id="error_msg"></div>
<?php if (isset($data) && version_compare($data->version, $openITCVersion) > 0): ?>
    <div class="alert alert-info alert-block">
        <a href="#" data-dismiss="alert" class="close">×</a>
        <h5 class="alert-heading"><i class="fa fa-fire"></i> <?php echo __('New Version!'); ?></h5>
        <?php echo __('A new version of'); ?> <?php echo $systemname; ?> <?php echo __('is available. You should update soon...'); ?>
        <p class="text-align-right">
            <a class="btn btn-sm btn-primary" href="javascript:void(0);" data-toggle="modal"
               data-target="#updateProcess"><strong><?php echo __('How to update?'); ?></strong></a>
            <a class="btn btn-sm btn-default" href="javascript:void(0);" data-toggle="modal"
               data-target="#newReleaseChangelog"><strong><?php echo __('What\'s new?'); ?></strong></a>
        </p>
    </div>
<?php endif; ?>

<?php if ($LsbRelease->getCodename() === 'trusty'): ?>
    <div class="alert alert-danger alert-block">
        <a class="close" data-dismiss="alert" href="#">×</a>
        <h4 class="alert-heading">
            <i class="fa fa-warning"></i>
            <?php echo __('Ubuntu Trusty 14.04 end of life!'); ?>
        </h4>
        <?php echo __('Official end of life of Ubuntu Trusty scheduled for April 2019.'); ?>
        <?php echo __('Therefore openITCOCKPIT 3.5 will be the last release for Ubuntu Trusty. Please update to Ubuntu Xenial to receive further updates.'); ?>
        <br />
        <?php echo __('Need help updating your system? Please don\'t hesitate to contact our enterprise support %s.', '<a class="txt-color-darken" href="mailto:support@itsm.it-novum.com">support@itsm.it-novum.com</a>'); ?>
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
        <br />
        <?php echo __('Need help updating your system? Please don\'t hesitate to contact our enterprise support %s.', '<a class="txt-color-darken" href="mailto:support@itsm.it-novum.com">support@itsm.it-novum.com</a>'); ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <input type="text" class="margin-bottom-10 padding-left-5" style="width: 100%; height: 35px;"
               placeholder="<?php echo __('Search for tags'); ?>" id="tagSearch"/>
    </div>

    <?php if (isset($data)): ?>
        <?php foreach ($data->modules as $module): ?>
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                <div class="jarviswidget">
                    <header>
                        <h2><strong><?php echo $module->Module->name; ?></strong></h2>
                        <?php if ($module->Module->enterprise): ?>
                            <div class="widget-toolbar" role="menu">
                                <div class="label label-danger">
                                    <i class="fa fa-star"></i> <?php echo __('Enterprise'); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($module->Module->check_for_update): ?>
                            <?php if (version_compare($module->Module->version, $plugin_version) > 0): ?>
                                <div class="widget-toolbar" role="menu">
                                    <div class="label label-primary">
                                        <i class="fa fa-fire"></i> <?php echo __('Update available'); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </header>
                    <div>
                        <div class="widget-body">
                            <p><?php echo h($module->Module->description); ?></p>
                            <div class="well formactions">
                                <strong><?php echo __('Plugin information:'); ?></strong>
                                <div style="padding-top: 5px; padding-left: 15px;">
                                    <table>
                                        <tr>
                                            <td><i class="fa fa-user"></i></td>
                                            <td>&nbsp;<?php echo __('Author:'); ?></td>
                                            <td>&nbsp;<?php echo h($module->Module->author); ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fa fa-list"></i></td>
                                            <td>&nbsp;<?php echo __('License:'); ?></td>
                                            <td>&nbsp;<?php echo h($module->Module->licence); ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fa fa-tags"></i></td>
                                            <td>&nbsp;<?php echo __('Tags:'); ?></td>
                                            <td>&nbsp;<span class="tags"><?php echo h($module->Module->tags); ?></span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <br/>
                            <div class="well formactions">
                                <div class="pull-right">
                                    <?php if (in_array($module->Module->name, $installedModules)): ?>
                                        <button class="uninstall btn btn-sm btn-danger"
                                                type="button"
                                                data-package-name="<?php echo h($module->Module->name); ?>"
                                                data-package-enterprise="<?php echo (int)$module->Module->enterprise; ?>"
                                                data-package-apt-name="<?php echo h(base64_encode(str_replace('openitcockpit-module-', '', $module->Module->apt_name))); ?>">
                                            <i class="fa fa-trash-o"></i> <?php echo __('Uninstall'); ?>
                                        </button>
                                    <?php else:
                                        /*if(version_compare($module->Module->requires, $openITCVersion) > 0): ?>
                                            <!-- Die installierte openITCOCKPIT Version ist zu alt -->
                                            <button class="btn btn-sm btn-default" disabled="disabled" type="button">
                                                <i class="fa fa-fire"></i> <?php echo __('Requires an'); ?> <?php echo $systemname; ?> <?php echo __('update'); ?>
                                            </button> 
                                        <?php else: ?> */ ?>
                                        <!-- Die installierte openITCOCKPIT Version ist ok -->
                                        <button class="install btn btn-sm btn-success"
                                                type="button"
                                                data-package-url="<?php echo h($module->Module->url); ?>"
                                                data-package-name="<?php echo h($module->Module->name); ?>"
                                                data-package-enterprise="<?php echo (int)$module->Module->enterprise; ?>"
                                                data-package-apt-name="<?php echo h(base64_encode(str_replace('openitcockpit-module-', '', $module->Module->apt_name))); ?>">
                                            <i class="fa fa-fire"></i> <?php echo __('Install'); ?>
                                        </button>
                                        <?php //endif;?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well formactions">
            <div class="pull-right">
                <a class="btn btn-sm btn-default" href="javascript:void(0);" data-toggle="modal"
                   data-target="#newReleaseChangelog"><strong><?php echo __('What\'s new?'); ?></strong></a>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="newReleaseChangelog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><?php echo __('Changelog'); ?></h4>
            </div>
            <div class="modal-body">

                <div class="">
                    <?php foreach ($data->changelog as $changelog): ?>
                        <h4 class="text-primary"><?php echo h($changelog->Changelog->version); ?></h4>
                        <div class="padding-top-5"></div>
                        <p class="padding-left-10"><?php echo nl2br($changelog->Changelog->changes); ?></p>
                        <hr/>
                    <?php endforeach; ?>
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

<div class="modal fade" id="package-manager-log" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><i
                            class="fa fa-list-alt"></i> <?php echo __('Package installation log'); ?></h4>
            </div>
            <div class="modal-body">
                <textarea class="form-control" disabled="disabled"
                          style="width: 100%; height: 250px; resize:none;"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-reload" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="updateProcess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel"><i
                            class="fa fa-rocket"></i> <?php echo __('Update instructions'); ?></h4>
            </div>
            <div class="modal-body">
                <?php echo __('openITCOCKPIT update process is handled by the package manager of your distribution.'); ?>
                <br/>
                <?php echo __('Please folow the instructions to update your version of openITCOCKPIT'); ?>
                <div class="padding-top-10"></div>
                <ol>
                    <li>
                        <?php echo __('Create a ssh session with your server'); ?>
                        <br/>
                        <code>yourPC$ ssh root@<?php echo $_SERVER['SERVER_ADDR']; ?></code>
                    </li>
                    <li>
                        <?php echo __('Update your package manager'); ?>
                        <br/>
                        <code>apt-get update</code>
                    </li>
                    <li>
                        <?php echo __('Update packages'); ?>
                        <br/>
                        <code>apt-get dist-upgrade</code>
                    </li>
                </ol>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?php echo __('Close'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
