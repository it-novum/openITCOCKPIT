<?php
// Copyright (C) <2015-present>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//     This program is free software: you can redistribute it and/or modify
//     it under the terms of the GNU General Public License as published by
//     the Free Software Foundation, version 3 of the License.
//
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 2.
//     If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//     under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//     License agreement and license key will be shipped with the order
//     confirmation.

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

/**
 * @var \App\View\AppView $this
 */

use itnovum\openITCOCKPIT\Core\LoginBackgrounds;

$Logo = new \itnovum\openITCOCKPIT\Core\Views\Logo();
$LoginBackgrounds = new LoginBackgrounds();
$images = $LoginBackgrounds->getImages();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- font awesome 4 is usd by the checkbox fa-check -->
    <link rel="stylesheet" type="text/css" href="/node_modules/font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="/node_modules/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/css/coreui/coreui.min.css">


    <link rel="stylesheet" type="text/css" href="/css/login/adminator.min.css">
    <link rel="stylesheet" type="text/css" href="/css/login/login.css">

    <title><?= __('openITCOCKPIT - Backend API') ?></title>

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

        <?php if($Logo->isCustomLoginBackground()): ?>
        .login-screen-vnc {
            background-image: url('<?= h($Logo->getCustomLoginBackgroundHtml()) ?>');
        }

        <?php endif; ?>

    </style>

</head>
<body class="app">

<?php
$description = '';
if ($images['description'] !== ''):
    $description = ' - ' . $images['description'];
endif;
?>


<div class="peers ai-s fxw-nw h-100vh" style="display:flex; justify-content: center">
    <div class="login-screen-vnc"></div>
    <?= $this->fetch('content') ?>
</div>

</body>
</html>
