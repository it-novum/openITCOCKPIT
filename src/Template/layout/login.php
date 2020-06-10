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
?>

<!DOCTYPE html>
<html ng-app="openITCOCKPITLogin">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- font awesome 4 is usd by the checkbox fa-check -->
    <link rel="stylesheet" type="text/css" href="/node_modules/font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="/node_modules/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="/node_modules/noty/lib/noty.css">

    <link rel="stylesheet" type="text/css" href="/css/login/adminator.min.css">
    <link rel="stylesheet" type="text/css" href="/css/login/login.css">

    <title><?= __('Sign In') ?></title>

    <!-- FAVICONS -->
    <link rel="shortcut icon" type="image/x-icon; charset=binary" href="/img/favicons/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicons/favicon-16x16.png">
    <link rel="manifest" href="/img/favicons/site.webmanifest">

    <style>
        .login-screen-vnc {
            background-image: url('/img/login/<?= h($images['images'][0]['image']) ?>');
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            -moz-background-size: cover;
            -webkit-background-size: cover;
            position: fixed;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
        }

        .login-screen > figure:nth-child(1) {
            background-image: url('/img/login/<?= h($images['images'][0]['image']) ?>');
        }

        .login-screen > figure:nth-child(2) {
            animation-delay: 15s;
            background-image: url('/img/login/<?= h($images['images'][1]['image']) ?>');
        }
    </style>

    <script>
        <?php if($disableAnimation): ?>
        var disableLoginAnimation = true;
        <?php else: ?>
        var disableLoginAnimation = false;
        <?php endif; ?>
    </script>

</head>
<body class="app">

<?php
$description = '';
if ($images['description'] !== ''):
    $description = ' - ' . $images['description'];
endif;
?>


<div class="peers ai-s fxw-nw h-100vh" ng-controller="LoginLayoutController">

    <?php if ($disableAnimation === false): ?>
        <div class="login-screen">
            <figure>
                <figcaption><?= h($images['images'][0]['credit']) ?><?= $description; ?></figcaption>
            </figure>
            <figure>
                <figcaption><?= h($images['images'][1]['credit']) ?><?= $description; ?></figcaption>
            </figure>
        </div>
    <?php else: ?>
        <div class="login-screen-vnc"></div>
    <?php endif; ?>


    <?= $this->fetch('content') ?>

</div>

<script src="/node_modules/jquery/dist/jquery.min.js"></script>
<script src="/node_modules/angular/angular.min.js"></script>
<script src="/node_modules/angular-ui-router/release/angular-ui-router.min.js"></script>
<script src="/node_modules/tsparticles/dist/tsparticles.js"></script>
<script src="/node_modules/noty/lib/noty.min.js"></script>

<script src="/js/login/ng.login-app.js"></script>
<script src="/js/login/LoginLayoutController.js"></script>
<script src="/js/login/UsersLoginController.js"></script>


</body>
</html>
