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
 */

use App\Lib\Environments;
?>

<!DOCTYPE html>
<html ng-app="openITCOCKPITStatuspageFullscreen">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- font awesome 4 is used by the checkbox fa-check -->
    <link rel="stylesheet" type="text/css" href="/node_modules/font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="/node_modules/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="/node_modules/noty/lib/noty.css">
    <?php
    $cssFiles = [
        '/css/statuspage-timeline.css',
        '/smartadmin4/dist/css/app.bundle.css',
        '/smartadmin4/dist/css/themes/cust-theme-10.css',
        '/css/openitcockpit-colors.css',
        '/css/openitcockpit-utils.css',
    ];


    $fileVersion = '?v' . time();
    if (ENVIRONMENT === Environments::PRODUCTION):
        Configure::load('version');
        $fileVersion = '?v' . OPENITCOCKPIT_VERSION;
    endif;


    foreach ($cssFiles as $cssFile) {
        printf('<link rel="stylesheet" type="text/css" href="%s%s">%s', $cssFile, $fileVersion, PHP_EOL);
    }

    if (\Cake\Core\Plugin::isLoaded('DesignModule')) {
        //load custom design css file
        $customCss = PLUGIN . 'DesignModule' . DS . 'webroot' . DS . 'css' . DS . 'customStyle.css';
        if (file_exists($customCss)) {
            $customCss = 'design_module/css/customStyle.css';
            printf('<link rel="stylesheet" type="text/css" href="%s%s">%s', $customCss, $fileVersion, PHP_EOL);
        }
    }
    ?>
    <title><?= __('Fullscreen') ?></title>

    <!-- FAVICONS -->
    <link rel="shortcut icon" type="image/x-icon; charset=binary" href="/img/favicons/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicons/favicon-16x16.png">
    <link rel="manifest" href="/img/favicons/site.webmanifest">

</head>
<body class="app nav-function-top">
<div ng-controller="StatuspageFullscreenLayoutController">
    <?= $this->fetch('content') ?>
</div>


<script src="/node_modules/jquery/dist/jquery.min.js"></script>
<script src="/node_modules/angular/angular.min.js"></script>
<script src="/node_modules/angular-ui-router/release/angular-ui-router.min.js"></script>
<script src="/node_modules/tsparticles/tsparticles.min.js"></script>
<script src="/node_modules/noty/lib/noty.min.js"></script>

<script src="/js/statuspage/ng.statuspage-app.js"></script>
<script src="/js/statuspage/StatuspageFullscreenLayoutController.js"></script>
<script src="/js/statuspage/StatuspagesViewController.js"></script>

</body>
</html>
