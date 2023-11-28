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

use itnovum\openITCOCKPIT\Core\Views\Logo;

/**
 * @var \App\View\AppView $this
 */

$logo = new Logo();
?>

<!DOCTYPE html>
<html ng-app="openITCOCKPITStatuspagePublic">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" type="text/css" href="/node_modules/font-awesome/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="/node_modules/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/dist/compressed_app.css">
    <link rel="stylesheet" type="text/css" href="/smartadmin4/dist/css/themes/cust-theme-10.css">
    <link rel="stylesheet" type="text/css" href="/css/openitcockpit-utils.css">




    <title><?= __('Statuspage') ?></title>

    <!-- FAVICONS -->
    <link rel="shortcut icon" type="image/x-icon; charset=binary" href="/img/favicons/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicons/favicon-16x16.png">
    <link rel="manifest" href="/img/favicons/site.webmanifest">

    <style>

    </style>

    <script>

    </script>

</head>
<body>
<div class="container-fluid">
    <nav class="navbar" style="background-color: black;">
        <!-- Navbar content -->
        <a class="navbar-brand"><img src="<?= $logo->getHeaderLogoForHtml(); ?>" alt="SmartAdmin WebApp" aria-roledescription="logo"><span></span> openItCOCKPIT</span></a>
    </nav>
</div>
<div class="container-fluid">
<?= $this->fetch('content') ?>
</div>

<script src="/node_modules/jquery/dist/jquery.min.js"></script>


</body>
</html>
