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

/*
                             _                _  _____
     /\                     | |              | |/ ____|
    /  \   _ __   __ _ _   _| | __ _ _ __    | | (___
   / /\ \ | '_ \ / _` | | | | |/ _` | '__|   | |\___ \
  / ____ \| | | | (_| | |_| | | (_| | | | |__| |____) |
 /_/    \_\_| |_|\__, |\__,_|_|\__,_|_|  \____/|_____/
   _____ _        __/ |_      _____
  / ____(_)      |___/| |    |  __ \                   /\
 | (___  _ _ __   __ _| | ___| |__) |_ _  __ _  ___   /  \   _ __  _ __
  \___ \| | '_ \ / _` | |/ _ \  ___/ _` |/ _` |/ _ \ / /\ \ | '_ \| '_ \
  ____) | | | | | (_| | |  __/ |  | (_| | (_| |  __// ____ \| |_) | |_) |
 |_____/|_|_| |_|\__, |_|\___|_|   \__,_|\__, |\___/_/    \_\ .__/| .__/
                  __/ |                   __/ |             | |   | |
  ____           |___/      _            |___/ _  _         |_|   |_|
 |  _ \            | |     | |                | || |
 | |_) | ___   ___ | |_ ___| |_ _ __ __ _ _ __| || |_
 |  _ < / _ \ / _ \| __/ __| __| '__/ _` | '_ \__   _|
 | |_) | (_) | (_) | |_\__ \ |_| | | (_| | |_) | | |
 |____/ \___/ \___/ \__|___/\__|_|  \__,_| .__/  |_|
                                         | |
                                         |_|
*/

$bodyClass = '';
if ($sideMenuClosed) {
    $bodyClass = 'minified';
}


$AngularAssets = new \itnovum\openITCOCKPIT\Core\AngularJS\AngularAssetsBootstrap4();
$scripts = $AngularAssets->getJsFiles();

App::uses('Folder', 'Utility');
$appScripts = [];
if (ENVIRONMENT === Environments::PRODUCTION) {
    $compressedAngularControllers = WWW_ROOT . 'js' . DS . 'compressed_angular_controllers.js';
    $compressedAngularDirectives = WWW_ROOT . 'js' . DS . 'compressed_angular_directives.js';
    $compressedAngularServices = WWW_ROOT . 'js' . DS . 'compressed_angular_services.js';
    $compressedAngularStates = WWW_ROOT . 'js' . DS . 'compressed_angular_states.js';
    if (file_exists($compressedAngularControllers) && file_exists($compressedAngularDirectives) && file_exists($compressedAngularServices)) {
        $appScripts[] = str_replace(WWW_ROOT, '', $compressedAngularServices);
        $appScripts[] = str_replace(WWW_ROOT, '', $compressedAngularDirectives);
        $appScripts[] = str_replace(WWW_ROOT, '', $compressedAngularControllers);
        if (file_exists($compressedAngularStates)) {
            $appScripts[] = str_replace(WWW_ROOT, '', $compressedAngularStates);
        }
    }
} else {
    $core = new Folder(WWW_ROOT . 'js' . DS . 'scripts');
    $uncompressedAngular = str_replace(WWW_ROOT, '', $core->findRecursive('.*\.js'));
    foreach (CakePlugin::loaded() as $pluginName) {
        $plugin = new Folder(OLD_APP . 'Plugin' . DS . $pluginName . DS . 'webroot' . DS . 'js' . DS . 'scripts');
        $filenames = str_replace(OLD_APP . 'Plugin' . DS . $pluginName . DS . 'webroot' . DS, '', $plugin->findRecursive('.*\.js'));
        if (!empty($filenames)) {
            $fullPath = [];
            foreach ($filenames as $filename) {
                $fullPath[] = $pluginName . DS . $filename;
            }
            $uncompressedAngular = array_merge($uncompressedAngular, $fullPath);
        }
    }
    $appScripts = array_merge($appScripts, $uncompressedAngular);
}

?>
<!DOCTYPE html>
<html lang="en" ng-app="openITCOCKPIT">
<head>
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <?php echo $this->Html->charset(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <script data-pace-options='{ "ajax": false }' src='/smartadmin/js/plugin/pace/pace.min.js'></script>
    <title>
        <?php echo $title_for_layout; ?> - <?php echo Configure::read('general.site_name') ?>
    </title>
    <?php

    $fileVersion = '?v' . time();
    if (ENVIRONMENT === Environments::PRODUCTION) {
        Configure::load('version');
        $fileVersion = '?v' . Configure::read('version');
    }


    echo $this->Html->meta('icon');
    echo $this->element('assets_css_bs4');

    foreach ($scripts as $script):
        printf('<script src="/%s%s"></script>%s', $script, $fileVersion, PHP_EOL);
    endforeach;

    foreach ($appScripts as $appScript):
        printf('<script src="/%s%s"></script>%s', $appScript, $fileVersion, PHP_EOL);
    endforeach;

    ?>
</head>
<body class="mod-bg-1">
<div class="page-wrapper">
    <div class="page-inner">
        <script>
            Dropzone.autoDiscover = false;
        </script>
        <!-- BEGIN Left Aside -->
        <aside class="page-sidebar">
            <div class="page-logo">
                   <span id="logo">
                        <div id="logo-image"></div>
                        <p id="logo-text"><?php echo $systemname; ?></p>
                    </span>
            </div>
            <!-- BEGIN PRIMARY NAVIGATION -->
            <nav id="js-primary-nav" class="primary-nav" role="navigation">
                <div class="nav-filter">
                    <div class="position-relative">
                        <input type="text" id="nav_filter_input" placeholder="Filter menu" class="form-control"
                               tabindex="0">
                        <a href="#" onclick="return false;" class="btn-primary btn-search-close js-waves-off"
                           data-action="toggle" data-class="list-filter-active" data-target=".page-sidebar">
                            <i class="fal fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="info-card">
                    <?php
                    if ($this->Auth->user('image') != null && $this->Auth->user('image') != ''):
                        if (file_exists(WWW_ROOT . 'userimages' . DS . $this->Auth->user('image'))):
                            $img = '/userimages' . DS . $this->Auth->user('image');
                        else:
                            $img = '/img/fallback_user.png';
                        endif;
                    else:
                        $img = '/img/fallback_user.png';
                    endif;
                    ?>
                    <img class="profile-image rounded-circle" alt="me" src="<?php echo $img; ?>">
                    <div class="info-card-text">
                        <a class="d-flex align-items-center text-white" ui-sref="ProfileEdit">
                            <span class="text-truncate text-truncate-sm d-inline-block">
                                <?php echo h($this->Auth->user('full_name')); ?>
                                <?php if ($hasRootPrivileges === true): ?>
                                    <span class="text-info pull-right" style="margin-top: 11px;">
                                        <i class="fa fa-lg fa-trophy"
                                           style="color:#FFD700; text-shadow: 0px 0px 9px rgba(255, 255, 0, 0.50)"
                                           id="userRootIcon"
                                           data-html="true"
                                           data-original-title="<?php echo __('Administrator privileges'); ?>"
                                           data-placement="right" rel="tooltip"></i>
                                    </span>
                                <?php endif; ?>
                          </span>
                        </a>
                    </div>
                    <img src="/smartadmin4/dist/img/card-backgrounds/cover-6-lg.png" class="cover" alt="cover">
                    <a href="#" onclick="return false;" class="pull-trigger-btn" data-action="toggle"
                       data-class="list-filter-active" data-target=".page-sidebar" data-focus="nav_filter_input">
                        <i class="fal fa-angle-down"></i>
                    </a>
                </div>
                <ul menu id="js-nav-menu" class="nav-menu"></ul>
                <div class="filter-message js-filter-message bg-success-600"></div>
            </nav>
            <!-- END PRIMARY NAVIGATION -->
            <!-- NAV FOOTER -->
            <div class="nav-footer shadow-top">
                <a href="#" onclick="return false;" data-action="toggle" data-class="nav-function-minify"
                   class="hidden-md-down">
                    <i class="ni ni-chevron-right"></i>
                    <i class="ni ni-chevron-right"></i>
                </a>
                <ul class="list-table m-auto nav-footer-buttons">
                    <li>
                        <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Chat logs">
                            <i class="fal fa-comments"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Support Chat">
                            <i class="fal fa-life-ring"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Make a call">
                            <i class="fal fa-phone"></i>
                        </a>
                    </li>
                </ul>
            </div> <!-- END NAV FOOTER -->
        </aside>
        <!-- END Left Aside -->
        <div class="page-content-wrapper">

            <!-- HEADER START -->
            <header id="header" class="page-header" role="banner">

                <div class="hidden-md-down dropdown-icon-menu position-relative">
                    <a href="#" class="header-btn btn js-waves-off" data-action="toggle"
                       data-class="nav-function-hidden" title="Hide Navigation">
                        <i class="ni ni-menu"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="#" class="btn js-waves-off" data-action="toggle" data-class="nav-function-minify"
                               title="Minify Navigation">
                                <i class="ni ni-minify-nav"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="btn js-waves-off" data-action="toggle" data-class="nav-function-fixed"
                               title="Lock Navigation">
                                <i class="ni ni-lock-nav"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="hidden-lg-up">
                    <a href="#" class="header-btn btn press-scale-down" data-action="toggle" data-class="mobile-nav-on">
                        <i class="ni ni-menu"></i>
                    </a>
                </div>
                <div class="search">
                    <form class="app-forms hidden-xs-down" role="search" action="page_search.html" autocomplete="off">
                        <input type="text" id="search-field" placeholder="Search for anything" class="form-control"
                               tabindex="1">
                        <a href="#" onclick="return false;" class="btn-danger btn-search-close js-waves-off d-none"
                           data-action="toggle" data-class="mobile-search-on">
                            <i class="fal fa-times"></i>
                        </a>
                    </form>
                </div>

                <div class="ml-auto d-flex">
                    <?php if ($loggedIn): ?>
                        <div class="header-icon">
                            <?php if ($this->Auth->user('showstatsinmenu')): ?>
                                <menustats></menustats>
                            <?php endif; ?>
                        </div>
                        <div class="header-icon">
                            <system-health></system-health>
                        </div>
                        <div class="header-icon">
                            <server-time></server-time>
                        </div>
                        <div>
                            <?php if ($exportRunningHeaderInfo === false): ?>
                                <a href="/exports/index" sudo-server-connect=""
                                   data-original-title="<?php echo __('Refresh monitoring configuration'); ?>"
                                   data-placement="left" rel="tooltip" data-container="body" class="header-icon">
                                    <i class="fa fa-retweet"></i>
                                </a>
                            <?php else: ?>
                                <a href="/exports/index" export-status=""
                                   data-original-title="<?php echo __('Refresh monitoring configuration'); ?>"
                                   data-placement="left" rel="tooltip" data-container="body" class="header-icon">
                                    <i class="fa fa-retweet" ng-hide="exportRunning"></i>
                                    <i class="fa fa-refresh fa-spin txt-color-red" ng-show="exportRunning"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div>
                            <a href="/login/logout" data-original-title="<?php echo __('Sign out'); ?>"
                               data-placement="left"
                               rel="tooltip" data-container="body" class="header-icon">
                                <i class="fa fa-sign-out"></i>
                            </a>
                        </div>
                        <div class="header-icon">
                            <version-check></version-check>
                        </div>
                        <div class="header-icon">
                            <push-notifications></push-notifications>
                        </div>
                    <?php endif; ?>
                </div>

            </header>
            <!-- HEADER END -->
            <!-- END Page Header -->
            <!-- BEGIN Page Content -->
            <!-- the #js-page-content id is needed for some plugins to initialize -->
            <main id="js-page-content" role="main" class="page-content" ng-controller="LayoutController">
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $this->webroot; ?>"><i
                                    class="fa fa-home"></i> <?php echo __('Home'); ?>
                        </a></li>
                    <li class="breadcrumb-item"><?php echo $this->Html->link($title_for_layout, ['action' => 'index'], ['icon' => 'fa fa-cube']); ?></li>
                </ol>

                <?php if ($loggedIn && $this->Auth->user('showstatsinmenu')): ?>
                    <menustats></menustats>
                <?php endif; ?>

                <div class="pull-right">
                        <span id="global_ajax_loader">
                            <i class="fa fa-refresh fa-spin"></i> <?php echo __('Loading data'); ?>
                        </span>
                </div>

                <div id="content" style="opacity: 1;">
                    <?php echo $this->Flash->render(); ?>
                    <?php echo $this->Flash->render('auth'); ?>

                    <div ui-view>
                        <?php
                        //Remove this line if ui-router is in use!!
                        echo $this->Flash->render();
                        echo $this->Flash->render('auth');
                        echo $content_for_layout;
                        ?>
                    </div>
                    <?php echo $this->element('Admin.sql_dump'); ?>
                </div>
            </main>
        </div>
    </div>
</div>

<!-- COMMENTING THIS OUT CAUSES rgb is undefined error from vendors.bundle.js -->
<!-- BEGIN Color profile -->
<!-- this area is hidden and will not be seen on screens or screen readers -->
<!-- we use this only for CSS color refernce for JS stuff -->
<p id="js-color-profile" class="d-none">
    <span class="color-primary-50"></span>
    <span class="color-primary-100"></span>
    <span class="color-primary-200"></span>
    <span class="color-primary-300"></span>
    <span class="color-primary-400"></span>
    <span class="color-primary-500"></span>
    <span class="color-primary-600"></span>
    <span class="color-primary-700"></span>
    <span class="color-primary-800"></span>
    <span class="color-primary-900"></span>
    <span class="color-info-50"></span>
    <span class="color-info-100"></span>
    <span class="color-info-200"></span>
    <span class="color-info-300"></span>
    <span class="color-info-400"></span>
    <span class="color-info-500"></span>
    <span class="color-info-600"></span>
    <span class="color-info-700"></span>
    <span class="color-info-800"></span>
    <span class="color-info-900"></span>
    <span class="color-danger-50"></span>
    <span class="color-danger-100"></span>
    <span class="color-danger-200"></span>
    <span class="color-danger-300"></span>
    <span class="color-danger-400"></span>
    <span class="color-danger-500"></span>
    <span class="color-danger-600"></span>
    <span class="color-danger-700"></span>
    <span class="color-danger-800"></span>
    <span class="color-danger-900"></span>
    <span class="color-warning-50"></span>
    <span class="color-warning-100"></span>
    <span class="color-warning-200"></span>
    <span class="color-warning-300"></span>
    <span class="color-warning-400"></span>
    <span class="color-warning-500"></span>
    <span class="color-warning-600"></span>
    <span class="color-warning-700"></span>
    <span class="color-warning-800"></span>
    <span class="color-warning-900"></span>
    <span class="color-success-50"></span>
    <span class="color-success-100"></span>
    <span class="color-success-200"></span>
    <span class="color-success-300"></span>
    <span class="color-success-400"></span>
    <span class="color-success-500"></span>
    <span class="color-success-600"></span>
    <span class="color-success-700"></span>
    <span class="color-success-800"></span>
    <span class="color-success-900"></span>
    <span class="color-fusion-50"></span>
    <span class="color-fusion-100"></span>
    <span class="color-fusion-200"></span>
    <span class="color-fusion-300"></span>
    <span class="color-fusion-400"></span>
    <span class="color-fusion-500"></span>
    <span class="color-fusion-600"></span>
    <span class="color-fusion-700"></span>
    <span class="color-fusion-800"></span>
    <span class="color-fusion-900"></span>
</p>
<!-- END Color profile -->
<!--
<div id="scroll-top-container">
    <i class="fa fa-arrow-up fa-2x" title="<?php echo __('Scroll back to top'); ?>"></i>
</div>
-->
<?php //printf('<script src="/%s"></script>', 'smartadmin/js/app.js'); ?>
<?php printf('<script src="/%s"></script>', 'smartadmin4/dist/js/vendors.bundle.js'); ?>
<?php printf('<script src="/%s"></script>', 'smartadmin4/dist/js/app.bundle.js'); ?>

</body>
</html>
