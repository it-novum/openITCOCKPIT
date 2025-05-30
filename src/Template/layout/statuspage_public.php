<?php
// Copyright (C) <2015>  <it-novum GmbH>
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


use itnovum\openITCOCKPIT\Core\LoginBackgrounds;
use itnovum\openITCOCKPIT\Core\Views\Logo;

/**
 * @var \App\View\AppView $this
 */


/**
 * @var \App\View\AppView $this
 * @var int $id
 * @var string $systemname
 * @var array $statuspage
 */

$logo = new Logo();
$LoginBackgrounds = new LoginBackgrounds();
$images = $LoginBackgrounds->getImages();
?>

<!DOCTYPE html>
<html>
<head>

    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <?php echo $this->Html->charset(); ?>

    <!--
                                    ///
                          ///////////////////////
                      ///////////////////////////////
                    ///////////////////////////////////
                 /////////////////////////////////////////
                ///////////////////////////////////////////
               ////////////////   .////////////////////    ,
              //////////                  //////             .
             ////////         //////        /         ///////.
             ///////       ///////////.   /////// ////////////
            ///////       /////////////      /   /////////////
            ///////       /////////////*  /* /   /////////////
            ///////       /////////////   /*     /////////////
            ////////        //////////    /*       //////////
            //////////                     //
            /////////////               ////////
            /////////////////////////////////////////////
            ///////////////////////////////////////////
            ////////////////////////////////////////
            ////////////////////////////////////

                    Open Source Monitoring Solution

        Website: https://openitcockpit.io/
        GitHub: https://github.com/it-novum/openITCOCKPIT
    -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="theme-color" content="#4085c6">

    <link rel="stylesheet" type="text/css" href="/node_modules/font-awesome/css/font-awesome.min.css">


    <link rel="stylesheet" type="text/css" href="/node_modules/@fortawesome/fontawesome-free/css/all.min.css">
    <!--<link rel="stylesheet" type="text/css" href="/node_modules/bootstrap/dist/css/bootstrap.min.css">-->
    <!--<link rel="stylesheet" type="text/css" href="/dist/compressed_app.css">-->
    <!--<link rel="stylesheet" type="text/css" href="/smartadmin4/dist/css/themes/cust-theme-10.css">-->
    <!--<link rel="stylesheet" type="text/css" href="/css/public_statuspage/colors.css">-->
    <!--<link rel="stylesheet" type="text/css" href="/css/openitcockpit-utils.css">-->
    <!--<link rel="stylesheet" type="text/css" href="/css/openitcockpit.css">-->

    <!--<link rel="stylesheet" type="text/css" href="/css/statuspage.css">-->
    <link rel="stylesheet" type="text/css" href="/css/coreui/coreui.css">
    <!--<link rel="stylesheet" type="text/css" href="/css/openitcockpit-colors.css">-->
    <link rel="stylesheet" type="text/css" href="/css/public_statuspage/colors.css">
    <link rel="stylesheet" type="text/css" href="/css/public_statuspage/style.css">
    <!--<link rel="stylesheet" type="text/css" href="/css/statuspage.css">-->


    <title>
        <?= __('Statuspage') ?>
        -
        <?= h($statuspage['statuspage']['name']); ?>
    </title>

    <!-- FAVICONS -->
    <link rel="shortcut icon" type="image/x-icon; charset=binary" href="/img/favicons/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicons/favicon-16x16.png">
    <link rel="manifest" href="/img/favicons/site.webmanifest">

    <style>
        /*  body {
              background-color: transparent;
          }


          .bg-not-monitored {
              background-color: rgb(88, 86, 214) !important;
          }

          .border-not-monitored {
              background-color: rgb(88, 86, 214) !important;
          } */

        .login-screen-vnc {
            background-image: url('/img/login/<?= h($images['images'][0]['image']) ?>');
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            -moz-background-size: cover;
            -webkit-background-size: cover;
            position: relative;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
        }

        <?php if($logo->isCustomLoginBackground()): ?>
        .login-screen-vnc {
            background-image: url('<?= h($logo->getCustomLoginBackgroundHtml()) ?>');
        }

        <?php endif; ?>
    </style>

</head>

<body class="dark-mode-body">


<div>

    <main role="main">
        <?= $this->fetch('content') ?>
    </main>

</div>

</body>

</html>
