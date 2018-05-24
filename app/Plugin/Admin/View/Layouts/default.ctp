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

$bodyClass = '';
if ($sideMenuClosed) {
    $bodyClass = 'minified';
}

App::uses('Folder', 'Utility');
$appScripts = [];
if (ENVIRONMENT === Environments::PRODUCTION) {
    $compressedAngularControllers = WWW_ROOT . 'js' . DS . 'compressed_angular_controllers.js';
    $compressedAngularDrectives = WWW_ROOT . 'js' . DS . 'compressed_angular_directives.js';
    $compressedAngularServices = WWW_ROOT . 'js' . DS . 'compressed_angular_services.js';
    if (file_exists($compressedAngularControllers) && file_exists($compressedAngularDrectives) && file_exists($compressedAngularServices)) {
        $appScripts[] = $compressedAngularServices;
        $appScripts[] = $compressedAngularDrectives;
        $appScripts[] = $compressedAngularControllers;
    }
} else {
    App::uses('Folder', 'Utility');
    $ScriptsFolder = new Folder(WWW_ROOT . 'js' . DS . 'scripts' . DS);
    $appScripts = $ScriptsFolder->findRecursive('.*\.js');
}
?>
<!DOCTYPE html>
<html lang="en" ng-app="openITCOCKPIT">
<head>
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <?php echo $this->Html->charset(); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script data-pace-options='{ "ajax": false }' src='/smartadmin/js/plugin/pace/pace.min.js'></script>
    <title>
        <?php echo $title_for_layout; ?> - <?php echo Configure::read('general.site_name') ?>
    </title>
    <?php
    echo $this->Html->meta('icon');
    echo $this->element('assets');

    printf('<script src="%s"></script>', '/vendor/angular/angular.min.js');
    printf('<script src="%s"></script>', '/js/scripts/ng.app.js');

    foreach ($appScripts as $appScript):
        printf('<script src="/%s"></script>', str_replace(WWW_ROOT, '', $appScript));
    endforeach;
    ?>
</head>
<body class="<?= $bodyClass ?>">

<?php echo $this->element('Admin.layout/header') ?>
<?php echo $this->element('Admin.layout/sidebar') ?>
<div id="uglyDropdownMenuHack"></div>
<div id="main" role="main" ng-controller="LayoutController">
    <div id="ribbon" class="hidden-mobile hidden-tablet">
        <span class="ribbon-button-alignment"></span>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo $this->webroot; ?>"><i class="fa fa-home"></i> <?php echo __('Home'); ?></a>
            </li>
            <li>
                <?php echo $this->Html->link($title_for_layout, ['action' => 'index'], ['icon' => 'fa fa-cube']); ?>
            </li>
        </ol>

        <?php if ($loggedIn && $this->Auth->user('showstatsinmenu')): ?>
            <menustats></menustats>
        <?php endif; ?>

        <div class="pull-right">
            <span id="global_ajax_loader"><i class="fa fa-refresh fa-spin"></i> <?php echo __('Loading data'); ?></span>
        </div>

    </div>

    <div id="content" style="opacity: 1;">
        <div class="controller <?php echo $this->name ?>_<?php echo $this->action ?>">
            <?php echo $this->Flash->render(); ?>
            <?php echo $this->Flash->render('auth'); ?>
            <?php echo $content_for_layout; ?>
            <?php echo $this->element('Admin.sql_dump'); ?>
        </div>
    </div>
</div>
<div id="scroll-top-container">
    <i class="fa fa-arrow-up fa-2x" title="<?php echo __('Scroll back to top'); ?>"></i>
</div>
</body>
</html>
